<!DOCTYPE html>

<html lang="id">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Display Antrean - BPS Kota Pekalongan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {

            --bps-blue: #004a8c;

            --bps-light-blue: #00a2e9;

            --dark-navy: #0f172a;

        }



        html,

        body {

            height: 100%;

            margin: 0;

            padding: 0;

            overflow: hidden;

            background: radial-gradient(circle at top right, #1e293b, #0f172a);

            color: #ffffff;

            font-family: 'Inter', sans-serif;

        }



        .tv-header {

            height: 12vh;

            background: rgba(15, 23, 42, 0.9);

            backdrop-filter: blur(10px);

            border-bottom: 0.5vh solid var(--bps-light-blue);

            padding: 0 3vw;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }



        .logo-img {

            height: 7vh;

        }



        .header-title {

            font-size: 3.5vh;

            font-weight: 800;

            margin: 0;

        }



        .header-sub {

            font-size: 2vh;

            color: var(--bps-light-blue);

            margin: 0;

        }



        .clock-box {

            background: var(--bps-blue);

            padding: 1vh 2vw;

            border-radius: 1.5vh;

            font-size: 4vh;

            font-weight: 800;

            box-shadow: inset 0 0 1vh rgba(0, 0, 0, 0.5);

        }



        .main-container {

            height: 80vh;

            padding: 3vh 3vw;

        }



        .main-display {

            background: linear-gradient(145deg, #004a8c, #002d5a);

            border-radius: 4vh;

            height: 100%;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            border: 0.3vh solid rgba(255, 255, 255, 0.1);

            box-shadow: 0 4vh 6vh rgba(0, 0, 0, 0.5);

        }



        .label-panggilan {

            font-size: 4vh;

            letter-spacing: 1vh;

            font-weight: 300;

            text-transform: uppercase;

            margin-bottom: -2vh;

        }



        .nomor-antrean {

            font-size: 35vh;

            font-weight: 900;

            line-height: 1;

            margin: 0;

            background: linear-gradient(to bottom, #ffffff 60%, #cbd5e1);

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;

        }



        .status-box {

            background: rgba(255, 255, 255, 0.1);

            padding: 2vh 5vw;

            border-radius: 10vh;

            border: 0.2vh solid rgba(255, 255, 255, 0.2);

        }



        .label-loket {

            font-size: 5vh;

            font-weight: 700;

        }



        .sidebar-history {

            background: rgba(30, 41, 59, 0.4);

            border-radius: 3vh;

            padding: 2vh;

            height: 100%;

            display: flex;

            flex-direction: column;

        }



        .history-title {

            font-size: 3vh;

            font-weight: bold;

            color: var(--bps-light-blue);

            text-align: center;

            margin-bottom: 2vh;

            border-bottom: 0.2vh solid rgba(255, 255, 255, 0.1);

        }



        .history-item {

            background: rgba(255, 255, 255, 0.05);

            border-left: 1vh solid var(--bps-light-blue);

            margin-bottom: 1.5vh;

            padding: 1.5vh 2vh;

            border-radius: 1.5vh;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }



        .history-number {

            font-size: 5vh;

            font-weight: 800;

        }



        .history-loket {

            font-size: 2vh;

            background: var(--bps-blue);

            padding: 0.5vh 1.5vh;

            border-radius: 1vh;

        }



        .footer-marquee {

            height: 8vh;

            background: var(--bps-blue);

            display: flex;

            align-items: center;

            border-top: 0.5vh solid var(--bps-light-blue);

        }



        .marquee-text {

            font-size: 3vh;

            font-weight: 600;

            color: #ffffff;

        }



        .text-yellow {

            color: #fbbf24;

        }
    </style>

</head>



<body>



    <header class="tv-header">

        <div class="d-flex align-items-center">

            <div class="bg-white p-2 rounded-3 me-3">

                <img src="<?= base_url('assets/images/logo_bps.png') ?>" class="logo-img" alt="Logo">

            </div>

            <div>

                <h1 class="header-title">BPS KOTA PEKALONGAN</h1>

                <p class="header-sub text-uppercase">Sistem Antrean Layanan Statistik Terpadu</p>

            </div>

        </div>

        <div class="clock-box" id="real-time-clock">00:00:00</div>

    </header>



    <main class="container-fluid main-container">

        <div class="row h-100 g-4">

            <div class="col-8">

                <div class="main-display">

                    <div class="label-panggilan">Panggilan Sekarang</div>

                    <div class="nomor-antrean" id="current-number">---</div>

                    <div class="status-box">

                        <div class="label-loket" id="status-text">MENUNGGU PETUGAS</div>

                    </div>

                </div>

            </div>



            <div class="col-4">

                <div class="sidebar-history">

                    <div class="history-title">RIWAYAT</div>

                    <div id="history-container">

                    </div>

                    <div class="mt-auto text-center opacity-50 p-3">

                        <p style="font-size: 2.2vh; font-style: italic;">"Menyediakan Data Statistik Berkualitas untuk Indonesia Maju"</p>

                    </div>

                </div>

            </div>

        </div>

    </main>



    <footer class="footer-marquee">

        <marquee scrollamount="12" class="marquee-text">

            Selamat Datang di PST BPS Kota Pekalongan — Jam Layanan: Senin s/d Jumat (08.00 - 15.30 WIB) — Mohon Antre dengan Tertib — Siapkan Kartu Identitas Anda.

        </marquee>

    </footer>



    <script src="http://127.0.0.1:3010/socket.io/socket.io.js"></script>



    <script>
        let lastId = null;



        if (typeof io !== 'undefined') {

            const socket = io('http://127.0.0.1:3010');



            socket.on('update', function() {

                console.log("Admin menekan panggil! Me-reset ID agar suara muncul kembali...");

                lastId = null; // Ini yang bikin nomor sama bisa dipanggil berkali-kali!

                checkUpdates();

            });

        } else {

            console.error("Library Socket.io tidak terbaca! Pastikan server node running.");

        }



        setInterval(() => {

            const now = new Date();

            document.getElementById('real-time-clock').innerText = now.toLocaleTimeString('id-ID', {

                hour: '2-digit',

                minute: '2-digit',

                second: '2-digit'

            }).replace(/\./g, ':');

        }, 1000);



        function checkUpdates() {

            fetch('<?= base_url('antrean/get_latest') ?>?' + new Date().getTime())

                .then(response => response.json())

                .then(data => {

                    if (data.current) {

                        if (data.current.id_antrean !== lastId || lastId === null) {

                            document.getElementById('current-number').innerText = data.current.nomor_antrean;

                            const namaPetugas = data.current.loket ? data.current.loket.toUpperCase() : "PETUGAS";

                            document.getElementById('status-text').innerHTML = `SILAKAN KE <span class="text-yellow">${namaPetugas}</span>`;



                            playVoice(data.current.nomor_antrean, namaPetugas);

                            lastId = data.current.id_antrean;

                        }

                    }



                    const historyContainer = document.getElementById('history-container');

                    let historyHtml = '';

                    if (data.history) {

                        data.history.slice(0, 4).forEach(item => {

                            historyHtml += `

                            <div class="history-item">

                                <div class="history-number">${item.nomor_antrean}</div>

                                <div class="history-loket">${item.loket ? item.loket.toUpperCase() : 'PETUGAS'}</div>

                            </div>`;

                        });

                    }

                    historyContainer.innerHTML = historyHtml;

                })

                .catch(err => console.error("Fetch Error:", err));

        }



        function playVoice(nomor, loket) {
            if (!('speechSynthesis' in window)) return;

            window.speechSynthesis.cancel();

            const voicedNumber = nomor.split('').join(' ');
            const text = `Nomor antrean, ${voicedNumber}, silakan menuju, ${loket}`;
            const utterance = new SpeechSynthesisUtterance(text);

            const setVoice = () => {
                const voices = window.speechSynthesis.getVoices();

                // Kumpulkan semua suara bahasa Indonesia
                const indoVoices = voices.filter(v => v.lang.includes('id-ID'));

                // Cari nama yang mengandung Google, Gadis, atau Female (dibuat huruf kecil semua agar pasti cocok)
                let femaleVoice = indoVoices.find(v =>
                    v.name.toLowerCase().includes('google') ||
                    v.name.toLowerCase().includes('gadis') ||
                    v.name.toLowerCase().includes('female')
                );

                // Kalau spesifik nama cewek nggak ketemu, tapi ada LEBIH DARI SATU suara Indo,
                // biasanya yang urutan kedua [1] adalah suara cewek.
                if (!femaleVoice && indoVoices.length > 1) {
                    femaleVoice = indoVoices[1];
                }

                // Tetapkan suara (prioritas cewek, kalau mentok baru apa aja yang ada)
                const finalVoice = femaleVoice || indoVoices[0];

                if (finalVoice) {
                    utterance.voice = finalVoice;
                    console.log("Menggunakan suara:", finalVoice.name); // Cek di F12 (Console) suara apa yang dipakai
                }

                utterance.lang = 'id-ID';
                utterance.rate = 0.85; // Kecepatan sedikit dipercepat
                utterance.pitch = 1.4; // Nada dinaikkan ke 1.4 agar Andika terdengar seperti suara CS/Perempuan
            };

            setVoice();

            setTimeout(() => {
                if (!utterance.voice) setVoice();
                window.speechSynthesis.speak(utterance);
            }, 250);
        }

        window.speechSynthesis.onvoiceschanged = () => {
            window.speechSynthesis.getVoices();
        };

        checkUpdates();
        setInterval(checkUpdates, 2000);

        document.body.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            }
            window.speechSynthesis.speak(new SpeechSynthesisUtterance(""));
        });
    </script>

</body>



</html>