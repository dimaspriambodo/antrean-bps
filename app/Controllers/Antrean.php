<?php

namespace App\Controllers;

use App\Models\AntreanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Antrean extends BaseController
{
    protected $AntreanModel;

    public function __construct()
    {
        $this->AntreanModel = new AntreanModel();
    }

    public function index()
    {
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Admisi') {
            $data = [
                'title' => 'Antrean - ' . $this->systemName,
                'headertitle' => 'Antrean',
                'agent' => $this->request->getUserAgent()
            ];
            return view('dashboard/antrean/index', $data);
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function list_antrean()
    {
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Admisi') {
            $nama_jaminan = $this->request->getGet('nama_jaminan');
            $tanggal_antrean = $this->request->getGet('tanggal_antrean');
            $limit = $this->request->getGet('limit');
            $offset = $this->request->getGet('offset');

            if (empty($tanggal_antrean)) {
                return $this->response->setJSON(['antrean' => [], 'total' => 0]);
            }

            $limit = $limit ? intval($limit) : 10;
            $offset = $offset ? intval($offset) : 0;

            $AntreanModel = $this->AntreanModel;
            if ($nama_jaminan) {
                $AntreanModel->like('nama_jaminan', $nama_jaminan);
            }
            if ($tanggal_antrean) {
                $AntreanModel->like('tanggal_antrean', $tanggal_antrean);
            }

            $total = $AntreanModel->countAllResults(false);
            $Pasien = $AntreanModel->orderBy('id_antrean', 'DESC')->findAll($limit, $offset);

            $startNumber = $offset + 1;
            $dataAntrean = array_map(function ($data, $index) use ($startNumber) {
                $data['number'] = $startNumber + $index;
                return $data;
            }, $Pasien, array_keys($Pasien));

            return $this->response->setJSON(['antrean' => $dataAntrean, 'total' => $total]);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Halaman tidak ditemukan']);
        }
    }

    public function cek_antrean($id)
    {
        if (session()->get('role') == 'Admisi' || session()->get('role') == 'Admin') {
            $antrean = $this->AntreanModel->find($id);
            if ($antrean) {
                $dataUpdate = [
                    'loket'  => 'Petugas Pelayanan',
                    'status' => 'SUDAH DIPANGGIL',
                    // Update waktu agar terbaca sebagai antrean terbaru saat dipanggil ulang
                    'tanggal_antrean' => date('Y-m-d H:i:s')
                ];
                $this->AntreanModel->update($id, $dataUpdate);
                $this->notify_clients();

                return $this->response->setJSON($antrean);
            }
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }
        return $this->response->setStatusCode(401)->setJSON(['message' => 'Harap login']);
    }

    public function selesai_antrean($id)
    {
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Admisi') {
            $data = [
                'loket'  => 'Petugas Pelayanan',
                'status' => 'SELESAI' 
            ];
            if ($this->AntreanModel->update($id, $data)) {
                $this->notify_clients();
                return $this->response->setJSON(['success' => true]);
            }
            return $this->response->setStatusCode(500)->setJSON(['success' => false]);
        }
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Halaman tidak ditemukan']);
    }

    public function batal_antrean($id)
    {
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Admisi') {
            $data = ['loket' => session()->get('fullname'), 'status' => 'BATAL'];
            if ($this->AntreanModel->update($id, $data)) {
                $this->notify_clients();
                return $this->response->setJSON(['success' => true]);
            }
        }
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Halaman tidak ditemukan']);
    }

    public function notify_clients()
    {
        try {
            $client = \Config\Services::curlrequest();
            $client->post('http://127.0.0.1:3010/notify', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => ['action' => 'update'],
                'timeout' => 2
            ]);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'WebSocket Notify Error: ' . $e->getMessage());
            return false;
        }
    }

    public function display()
    {
        return view('dashboard/antrean/display_tv');
    }

    public function get_latest()
    {
        $model = new \App\Models\AntreanModel();
        
        // Mengambil antrean yang baru saja dipanggil berdasarkan timestamp terbaru
        $current = $model->where('status', 'SUDAH DIPANGGIL')
            ->orderBy('tanggal_antrean', 'DESC')
            ->first();

        $history = [];
        if ($current) {
            $history = $model->whereIn('status', ['SUDAH DIPANGGIL', 'SELESAI'])
                ->where('id_antrean !=', $current['id_antrean'])
                ->orderBy('tanggal_antrean', 'DESC')
                ->findAll(4);
        }
        return $this->response->setJSON(['current' => $current, 'history' => $history]);
    }
}