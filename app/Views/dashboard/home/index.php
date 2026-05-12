<?= $this->extend('dashboard/templates/dashboard'); ?>
<?php /** @var string $headertitle */ ?>

<?= $this->section('css'); ?>
<style>
    .second-row-form { min-width: 15em; }
    .card-antrean { border-radius: 1.25rem; transition: all 0.3s ease; border: 1px solid rgba(0, 74, 140, 0.1); }
    .card-antrean:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0, 74, 140, 0.15) !important; }
    .text-bps { color: #004a8c !important; }
    .bg-bps-soft { background-color: #e6f0ff !important; }
    .badge-status { font-size: 0.7em; padding: 0.5em 0.8em; border-radius: 50rem; }
    
    @media (max-width: 767.98px) { .second-row-form { min-width: 0; } }
</style>
<?= $this->endSection(); ?>

<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <div class="flex-fill text-truncate">
        <div class="d-flex flex-column">
            <div class="fw-bold fs-6 lh-sm text-bps"><?= $headertitle; ?></div>
            <div class="fw-medium lh-sm opacity-75" style="font-size: 0.75em;"><span id="totalRecords">0</span> antrean ditemukan</div>
        </div>
    </div>
    <div id="loadingSpinner" class="px-2">
        <?= $this->include('spinner/spinner'); ?>
    </div>
    <a id="toggleFilter" class="fs-6 mx-2 text-bps" href="#" data-bs-toggle="tooltip" data-bs-title="Pencarian"><i class="fa-solid fa-magnifying-glass"></i></a>
    <a id="refreshButton" class="fs-6 mx-2 text-bps" href="#" data-bs-toggle="tooltip" data-bs-title="Segarkan"><i class="fa-solid fa-sync"></i></a>
</div>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<main class="main-content-inside">
    <div id="filterFields" class="sticky-top" style="z-index: 99; display: none;">
        <ul class="list-group shadow-sm rounded-0">
            <li class="list-group-item border-top-0 border-end-0 border-start-0 bg-bps-soft transparent-blur">
                <div class="no-fluid-content">
                    <div class="d-flex flex-column flex-lg-row gap-2">
                        <div class="input-group input-group-sm flex-grow-1">
                            <label class="input-group-text bg-white border-bps text-bps fw-bold">LAYANAN</label>
                            <select class="form-select" id="nama_jaminan">
                                <option value="UMUM" selected>UMUM (PST)</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm w-auto second-row-form">
                            <input type="date" id="tanggalFilter" class="form-control" value="<?= date('Y-m-d') ?>">
                            <button class="btn btn-primary bg-gradient" type="button" id="setTodayTglButton"><i class="fa-solid fa-calendar-day"></i></button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="px-3 mt-3">
        <div class="no-fluid-content">
            <div id="no-data-alert" class="text-center py-5" style="display: none;">
                <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-25"></i>
                <h4 class="text-muted">Tidak ada data antrean</h4>
            </div>

            <div id="antreanContainer" class="row row-cols-1 row-cols-lg-2 g-3">
                </div>

            <nav id="paginationNav" class="d-flex justify-content-center justify-content-lg-end mt-4 w-100">
                <ul class="pagination pagination-sm shadow-sm"></ul>
            </nav>
        </div>
    </div>

    <div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg text-center p-4" style="border-radius: 1.5rem;">
                <h4 class="fw-bold text-bps mb-3">Selesaikan Antrean?</h4>
                <p id="completeMessage" class="text-muted"></p>
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-primary btn-lg rounded-pill fw-bold" id="confirmCompleteBtn">Ya, Selesaikan</button>
                    <button type="button" class="btn btn-link text-decoration-none text-muted" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg text-center p-4" style="border-radius: 1.5rem;">
                <h4 class="fw-bold text-danger mb-3">Batalkan Antrean?</h4>
                <p id="cancelMessage" class="text-muted"></p>
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-danger btn-lg rounded-pill fw-bold" id="confirmCancelBtn">Ya, Batalkan</button>
                    <button type="button" class="btn btn-link text-decoration-none text-muted" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    let limit = 8;
    let currentPage = 1;
    let currentId = null;
    let googleVoice = null;

    function loadVoices() {
        const voices = speechSynthesis.getVoices();
        googleVoice = voices.find(v => v.lang === 'id-ID' && v.name.includes('Google'));
    }
    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = loadVoices;
    }

    async function fetchAntrean() {
        const params = {
            nama_jaminan: $('#nama_jaminan').val(),
            tanggal_antrean: $('#tanggalFilter').val(),
            limit: limit,
            offset: (currentPage - 1) * limit
        };

        $('#loadingSpinner').show();

        try {
            const response = await axios.get('<?= base_url('antrean/list_antrean') ?>', { params });
            const data = response.data;

            $('#antreanContainer').empty();
            $('#totalRecords').text(data.total);

            if (data.total === 0) {
                $('#no-data-alert').show();
                $('#paginationNav ul').empty();
            } else {
                $('#no-data-alert').hide();
                data.antrean.forEach(item => {
                    let statusBadge = '';
                    if (item.status === 'BELUM DIPANGGIL') statusBadge = 'bg-warning text-dark';
                    else if (item.status === 'SUDAH DIPANGGIL') statusBadge = 'bg-success text-white';
                    else statusBadge = 'bg-danger text-white';

                    const nomorHanyaAngka = item.nomor_antrean;

                    // LOGIKA PERBAIKAN TOMBOL
                    const isFinished = (item.status === 'SELESAI' || item.status === 'BATAL');
                    const isCalled = (item.status === 'SUDAH DIPANGGIL');

                    // 1. Tentukan Class Warna (Secondary = Abu-abu)
                    const classPanggil = isFinished ? 'btn-secondary text-white border-secondary' : 'btn-outline-primary';
                    const classSelesai = isFinished ? 'btn-secondary text-white border-secondary' : 'btn-primary';
                    const classBatal   = (isFinished || isCalled) ? 'btn-secondary text-white border-secondary' : 'btn-light';

                    // 2. Tentukan Kondisi Disabled
                    const disablePanggil = isFinished ? 'disabled' : '';
                    const disableSelesai = isFinished ? 'disabled' : '';
                    const disableBatal   = (isFinished || isCalled) ? 'disabled' : '';

                    const card = `
                        <div class="col">
                            <div class="card card-antrean h-100 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h2 class="display-5 fw-bold text-bps mb-0 date">${nomorHanyaAngka}</h2>
                                        <span class="badge badge-status ${statusBadge}">${item.status}</span>
                                    </div>
                                    <div class="small text-muted mb-3">
                                        <i class="fa-solid fa-tag me-1"></i> ${item.nama_jaminan} • 
                                        <i class="fa-solid fa-clock me-1"></i> ${item.tanggal_antrean.split(' ')[1]}
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col d-grid">
                                            <button class="btn ${classPanggil} btn-sm rounded-pill btn-call" 
                                                data-id="${item.id_antrean}" 
                                                data-num="${nomorHanyaAngka}"
                                                ${disablePanggil}> 
                                                <i class="fa-solid fa-volume-high me-1"></i> Panggil
                                            </button>
                                        </div>
                                        <div class="col d-grid">
                                            <button class="btn ${classSelesai} btn-sm rounded-pill btn-complete" 
                                                data-id="${item.id_antrean}" data-name="${nomorHanyaAngka}"
                                                ${disableSelesai}> Selesai
                                            </button>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn ${classBatal} btn-sm rounded-circle btn-cancel" 
                                                data-id="${item.id_antrean}" data-name="${nomorHanyaAngka}"
                                                ${disableBatal}> 
                                                <i class="fa-solid fa-xmark ${disableBatal ? 'text-white' : 'text-danger'}"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    $('#antreanContainer').append(card);
                });
                renderPagination(data.total);
            }
        } catch (error) {
            console.error(error);
        } finally {
            $('#loadingSpinner').hide();
        }
    }

    function renderPagination(total) {
        const totalPages = Math.ceil(total / limit);
        const ul = $('#paginationNav ul').empty();
        for (let i = 1; i <= totalPages; i++) {
            ul.append(`<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link date" href="#" onclick="changePage(${i})">${i}</a></li>`);
        }
    }

    window.changePage = (p) => { currentPage = p; fetchAntrean(); };

    $(document).on('click', '.btn-call', async function() {
        const id = $(this).data('id');
        const num = $(this).data('num');
        const btn = $(this);
        
        const numSpaced = num.split('').join(' ');
        const msg = `Nomor antrean, ${numSpaced}, silakan menuju, Petugas Layanan.`;

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        try {
            await axios.post(`<?= base_url('/antrean/cek_antrean') ?>/${id}`);
            
            const utter = new SpeechSynthesisUtterance(msg);
            utter.lang = 'id-ID';
            utterance.rate = 0.9;
            if (googleVoice) utter.voice = googleVoice;
            speechSynthesis.speak(utter);

            fetchAntrean();
        } catch (e) {
            alert("Gagal memanggil.");
        } finally {
            btn.prop('disabled', false).html('<i class="fa-solid fa-volume-high me-1"></i> Panggil');
        }
    });

    $(document).on('click', '.btn-complete', function() {
        currentId = $(this).data('id');
        $('#completeMessage').html(`Apakah antrean nomor <b>${$(this).data('name')}</b> telah selesai dilayani?`);
        $('#completeModal').modal('show');
    });

    $(document).on('click', '.btn-cancel', function() {
        currentId = $(this).data('id');
        $('#cancelMessage').html(`Batalkan antrean nomor <b>${$(this).data('name')}</b>?`);
        $('#cancelModal').modal('show');
    });

    $('#confirmCompleteBtn').click(async () => {
        await axios.post(`<?= base_url('/antrean/selesai_antrean') ?>/${currentId}`);
        $('#completeModal').modal('hide');
        fetchAntrean();
    });

    $('#confirmCancelBtn').click(async () => {
        await axios.post(`<?= base_url('/antrean/batal_antrean') ?>/${currentId}`);
        $('#cancelModal').modal('hide');
        fetchAntrean();
    });

    $(document).ready(() => {
        fetchAntrean();
        $('#refreshButton').click((e) => { e.preventDefault(); fetchAntrean(); });
        $('#toggleFilter').click((e) => { e.preventDefault(); $('#filterFields').slideToggle(); });
        $('#setTodayTglButton').click(() => { $('#tanggalFilter').val(new Date().toISOString().split('T')[0]); fetchAntrean(); });
        
        const socket = new WebSocket('<?= env('WS-URL-JS') ?>');
        socket.onmessage = (e) => { if (JSON.parse(e.data).update) fetchAntrean(); };
    });
</script>
<?= $this->endSection(); ?>