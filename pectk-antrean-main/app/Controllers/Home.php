<?php

namespace App\Controllers;

use App\Models\AntreanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Home extends BaseController
{
    protected $AntreanModel;

    public function __construct()
    {
        $this->AntreanModel = new AntreanModel();
    }

    public function index()
    {
        // 1. PROTEKSI SESSION (Arahkan ke '/' sesuai Routes.php kamu)
        if (!session()->get('role')) {
            return redirect()->to(base_url('/'));
        }

        $db = db_connect();
        $agent = $this->request->getUserAgent();

        // 2. LOGIKA GREETINGS
        $hour = (int)date('H');
        if ($hour >= 5 && $hour < 11) $txtGreeting = 'Selamat Pagi';
        elseif ($hour >= 11 && $hour < 16) $txtGreeting = 'Selamat Siang';
        elseif ($hour >= 16 && $hour < 19) $txtGreeting = 'Selamat Sore';
        else $txtGreeting = 'Selamat Malam';

        // 3. DATA DASAR UNTUK VIEW
        $data = [
            'title'       => 'Beranda - BPS Kota Pekalongan',
            'headertitle' => 'Beranda',
            'agent'       => $agent,
            'txtgreeting' => $txtGreeting,
        ];

        // 4. DATA UNTUK ADMIN/ADMISI/SATPAM (Sesuai pengecekan di View)
        $antrean = $db->table('antrean');
        $user = $db->table('user');
        $user_sessions = $db->table('user_sessions');

        // Statistik Antrean untuk Grafik
        $data['antreanpiegraph'] = $antrean->select('nama_jaminan, COUNT(*) AS total_antrean')->groupBy('nama_jaminan')->get();
        $data['antreangraph']    = $antrean->select('DATE_FORMAT(tanggal_antrean, "%Y-%m") AS bulan, COUNT(*) AS total_antrean')->groupBy('bulan')->get();

        // Inisialisasi variabel Chart.js agar tidak undefined
        $data['labels_antreankode']   = json_encode([]);
        $data['datasets_antreankode'] = json_encode([]);

        // Statistik Pengguna (Card Atas)
        $data['total_user']           = $user->countAllResults();
        $data['total_user_inactive']  = $user->where('active', 0)->countAllResults();
        $data['total_user_active']    = $user->where('active', 1)->countAllResults();

        // Statistik Sesi (Card Bawah)
        $currentDateTime = date('Y-m-d H:i:s');
        $data['total_sessions']         = $user_sessions->where('session_token !=', (string)session()->get('session_token'))->countAllResults();
        $data['total_sessions_expired'] = $user_sessions->where('expires_at <', $currentDateTime)->countAllResults();
        $data['total_sessions_active']  = $user_sessions->where('expires_at >=', $currentDateTime)->countAllResults();

        // Cek Role untuk menentukan View
        if (session()->get('role') == "Satpam") {
            return view('dashboard/home/satpam', $data);
        } else {
            return view('dashboard/home/index', $data);
        }
    }

    public function list_antrean()
    {
        if (session()->get('role') == 'Satpam') {
            $request = $this->request->getPost();
            $search  = $request['search']['value'] ?? '';
            $start   = $request['start'] ?? 0;
            $length  = $request['length'] ?? 10;
            $draw    = $request['draw'] ?? 1;

            $totalRecords = $this->AntreanModel->where('status', 'BELUM DIPANGGIL')->countAllResults(true);

            $builder = $this->AntreanModel->where('status', 'BELUM DIPANGGIL');
            if (!empty($search)) {
                $builder->like('tanggal_antrean', $search);
            }

            $users = $builder->orderBy('id_antrean', 'DESC')->findAll($length, $start);

            foreach ($users as $index => &$item) {
                $item['no'] = $start + $index + 1;
            }

            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => count($users),
                'data'            => $users
            ]);
        }
        return $this->response->setStatusCode(404);
    }

    public function cetak_antrean($id)
    {
        if (session()->get('role') !== 'Satpam') throw PageNotFoundException::forPageNotFound();

        $antrean = $this->AntreanModel->find($id);
        if (!$antrean) throw PageNotFoundException::forPageNotFound();

        $filename = 'Antrean_BPS_Pekalongan_' . $antrean['kode_antrean'] . '-' . $antrean['nomor_antrean'] . '.pdf';

        $data = [
            'antrean' => $antrean,
            'title'   => 'Struk Antrean BPS Kota Pekalongan'
        ];

        $html = view('dashboard/home/struk', $data);
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post(env('PDF_URL'), [
                'headers' => ['Content-Type' => 'application/json'],
                'json'    => [
                    'html'     => $html,
                    'filename' => $filename,
                    'paper'    => ['width' => '45mm', 'height' => '150mm']
                ],
                'timeout' => 15
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['success']) && $result['success']) {
                $path = "C:/Users/mafif/Downloads/pectk-antrean-main/pectk-antrean-main/writable/temp/" . $result['file'];
                if (!is_file($path)) return $this->response->setStatusCode(500);

                $pdfContent = file_get_contents($path);
                @unlink($path);

                return $this->response
                    ->setHeader('Content-Type', 'application/pdf')
                    ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->setBody($pdfContent);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500);
        }
    }

    public function buat_antrean()
    {
        if (session()->get('role') != 'Satpam') return $this->response->setStatusCode(403);

        $db = db_connect();
        $lastQueue = $db->table('antrean')
            ->select('RIGHT(nomor_antrean, 3) AS last_number')
            ->where('kode_antrean', 'U')
            ->where('tanggal_antrean >=', date('Y-m-d 00:00:00'))
            ->orderBy('nomor_antrean', 'DESC')
            ->limit(1)->get()->getRow();

        $nextNum = $lastQueue ? intval($lastQueue->last_number) + 1 : 1;
        $nomor   = str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $data = [
            'nama_jaminan'  => 'UMUM',
            'kode_antrean'  => 'U',
            'nomor_antrean' => $nomor,
            'satpam'        => session()->get('fullname'),
            'status'        => 'BELUM DIPANGGIL'
        ];

        $this->AntreanModel->insert($data);
        $insertedId = $this->AntreanModel->insertID();
        $this->notify_clients();

        return $this->response->setJSON([
            'success' => true,
            'data'    => [
                'id_antrean'      => $insertedId,
                'antrean'         => $nomor,
                'nama_jaminan'    => 'UMUM',
                'tanggal_antrean' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    public function notify_clients()
    {
        try {
            $client = \Config\Services::curlrequest();

            // PASTIIN PAKAI UNDERSCORE (_) SESUAI .ENV KAMU
            $url = env('WS_URL_PHP');

            // Tambahkan pengecekan agar tidak error null lagi
            if (empty($url)) {
                log_message('error', 'WS_URL_PHP tidak terbaca di .env');
                return;
            }

            $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json'    => ['action' => 'update'],
                'timeout' => 2
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Notification server offline: ' . $e->getMessage());
        }
    }
}
