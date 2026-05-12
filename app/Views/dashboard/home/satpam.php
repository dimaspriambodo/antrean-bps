<?php
$db = db_connect();
?>
<?= $this->extend('dashboard/templates/dashboard'); ?>

<?= $this->section('css'); ?>
<style>
    .main-content-inside {
        margin-left: 0px;
    }

    /* Card Antrean Modern */
    .card-antrean {
        border: none;
        border-radius: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .card-antrean:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 74, 140, 0.15) !important;
    }

    /* Warna Khas BPS */
    .text-bps-dark {
        color: #004a8c !important;
    }

    .bg-bps-soft {
        background-color: #e6f0ff !important;
    }

    .bg-bps-gradient {
        background: linear-gradient(135deg, #004a8c 0%, #00a2e9 100%) !important;
    }

    /* Modal & Table Styling */
    .modal-content {
        border-radius: 1.5rem;
        border: none;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #004a8c;
        border-bottom: 2px solid #e6f0ff;
    }

    .btn-apply {
        padding: 1.25rem;
        font-size: 1.25rem;
        letter-spacing: 0.5px;
    }

    .ratio-onecol {
        --bs-aspect-ratio: 33%;
    }

    @media (max-width: 991.98px) {
        .ratio-onecol {
            --bs-aspect-ratio: 75%;
        }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <div class="flex-fill text-truncate">
        <div class="d-flex flex-column">
            <div class="fw-bold fs-6 lh-sm text-bps-dark" id="tanggal"></div>
            <div class="fw-medium lh-sm date opacity-75" id="waktu" style="font-size: 0.85em;"></div>
        </div>
    </div>
    <div id="loadingSpinner" class="px-2">
        <?= $this->include('spinner/spinner'); ?>
    </div>
</div>
<div style="min-width: 1px; max-width: 1px;"></div>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<main class="main-content-inside px-3">
    <div class="no-fluid-content">
        <!-- HEADER IDENTITAS -->
        <div class="text-center pt-4 pb-2">
            <div class="mb-3">
                <span class="d-flex justify-content-center align-items-center gap-3">
                    <img src="<?= base_url('/assets/images/logo_bps.png'); ?>" alt="BPS KOTA PEKALONGAN" height="60px">
                    <div class="text-start d-none d-lg-block">
                        <h4 class="fw-bold mb-0 text-bps-dark">BADAN PUSAT STATISTIK</h4>
                        <h5 class="fw-medium mb-0 text-bps-dark opacity-75">KOTA PEKALONGAN</h5>
                    </div>
                </span>
            </div>
            <h6><em>Penyedia Data Statistik Berkualitas untuk Indonesia Maju</em></h6>
            <div class="my-4">
                <h4 class="fw-bold text-bps-dark">Selamat Datang</h4>
                <p class="text-muted">Silakan klik tombol di bawah untuk mendapatkan nomor antrean PST</p>
            </div>
        </div>

        <!-- TOMBOL UTAMA -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-lg-5">
                <div class="card card-antrean shadow-sm border-0">
                    <div class="card-body text-center py-5 bg-white">
                        <div style="font-size: 80pt;" class="text-bps-dark mb-3">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h3 class="fw-bold text-bps-dark mb-1">LAYANAN UTAMA</h3>
                        <p class="text-muted small px-4">Klik tombol di bawah untuk ambil antrean</p>
                    </div>
                    <div class="card-footer p-4 bg-white border-0">
                        <div class="d-grid">
                            <button type="button" class="btn btn-lg bg-bps-gradient text-white rounded-4 btn-apply py-3 fw-bold shadow-sm" data-name="UMUM">
                                <i class="fa-solid fa-ticket-alt me-2"></i> Buat Antrean
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOMBOL RIWAYAT -->
        <div class="d-grid gap-2 mb-5">
            <button type="button" class="btn btn-bps-soft text-bps-dark bg-gradient rounded-4 fw-medium py-2" id="list_antrean_btn" data-bs-toggle="modal" data-bs-target="#listAntreanModal">Lihat Nomor Antrean Sebelumnya</button>
        </div>
    </div>

    <!-- MODAL LIST ANTREAN -->
    <div class="modal fade" id="listAntreanModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="listAntreanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-lg-down modal-dialog-centered modal-dialog-scrollable ">
            <div id="rajaldiv" enctype="multipart/form-data" class="modal-content bg-body-tertiary shadow-lg transparent-blur">
                <div class="modal-header justify-content-between pt-3 pb-3 bg-bps-soft">
                    <div class="d-flex flex-row gap-2 me-2 w-100">
                        <select class="form-select form-select-sm w-auto rounded-3" id="length-menu">
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <div class="input-group input-group-sm flex-grow-1">
                            <input type="date" class="form-control form-control-sm rounded-start-3" id="externalSearch">
                            <button class="btn btn-danger btn-sm bg-gradient " type="button" id="clearTglButton" data-bs-toggle="tooltip" data-bs-title="Bersihkan Tanggal"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                    <button id="listAntreanCloseBtn" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <table id="tabel" class="table table-sm table-hover m-0 p-0" style="width:100%; font-size: 0.85rem;">
                        <thead>
                            <tr class="align-middle">
                                <th>No.</th>
                                <th>Tindakan</th>
                                <th>Jaminan</th>
                                <th>Nomor Antrean</th>
                                <th>Waktu</th>
                                <th>Satpam</th>
                            </tr>
                        </thead>
                        <tbody class="align-top"></tbody>
                    </table>
                </div>
                <div class="modal-footer pt-2 pb-2 d-flex justify-content-between">
                    <div id="loading" class="small fw-medium text-bps-dark"></div>
                    <button id="refreshButton" type="button" class="btn btn-primary btn-sm bg-gradient rounded-3"><i class="fa-solid fa-arrows-rotate"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PRINT -->
    <div class="modal modal-sheet p-4 py-md-5 fade" id="printModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-body-tertiary rounded-5 shadow-lg transparent-blur border-0">
                <div class="modal-body p-5 text-center">
                    <div class="text-success mb-3" style="font-size: 50pt;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <p class="mb-1 text-muted fw-medium">NOMOR ANTREAN ANDA ADALAH:</p>
                    <h1 class="display-1 fw-bold text-bps-dark mb-0" id="antrean"></h1>
                    <p class="mb-3 badge bg-bps-soft text-bps-dark px-3 py-2 rounded-pill">Jaminan: <span id="nama_jaminan"></span></p>
                    <p class="small text-muted mb-4">Tanggal: <span id="tanggal_antrean"></span></p>

                    <div class="alert bg-light border-0 small mb-4">
                        <i class="fa-solid fa-print me-2"></i>Nomor antrean dicetak otomatis. Silakan tunggu di ruang layanan.
                    </div>

                    <iframe id="print_frame" style="display: none;"></iframe>
                    <div class="row gy-2">
                        <div class="d-grid">
                            <button type="button" class="btn btn-lg bg-bps-gradient text-white fs-6 mb-0 rounded-4 py-3 fw-bold" id="cetak-btn">Cetak Ulang Nomor Antrean</button>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-lg btn-link link-muted text-decoration-none fs-6 mb-0 rounded-4" id="closeModalBtn" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    $(document).ready(function() {
        $('#loadingSpinner').hide();
    })
</script>
<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script>
    let countdownTimer = null;

    dayjs.extend(dayjs_plugin_localizedFormat);
    dayjs.locale('id');

    function updateDateTime() {
        const now = dayjs();
        $('#tanggal').text(now.format('dddd, D MMMM YYYY'));
        $('#waktu').text(now.format('HH.mm.ss'));
    }

    $(document).ready(async function() {
        var table = $('#tabel').DataTable({
            "oLanguage": {
                "sDecimal": ",",
                "sEmptyTable": 'Silakan pilih tanggal untuk melihat daftar antrean',
                "sInfo": "Menampilkan _START_ hingga _END_ dari _TOTAL_ antrean",
                "sInfoEmpty": "Menampilkan 0 hingga 0 dari 0 antrean",
                "sInfoFiltered": "(di-filter dari _MAX_ antrean)",
                "sThousands": ".",
                "sLengthMenu": "Tampilkan _MENU_ antrean",
                "sLoadingRecords": "Memuat...",
                "sSearch": "Cari:",
                "sZeroRecords": "Antrean tidak ditemukan",
                "oPaginate": {
                    "sFirst": '<i class="fa-solid fa-angles-left"></i>',
                    "sLast": '<i class="fa-solid fa-angles-right"></i>',
                    "sPrevious": '<i class="fa-solid fa-angle-left"></i>',
                    "sNext": '<i class="fa-solid fa-angle-right"></i>'
                }
            },
            'dom': "<'row'<'col-md-12'tr>>" + "<'d-flex justify-content-center align-items-center'<'text-md-center text-lg-start'><'d-md-flex justify-content-md-center d-lg-block'p>>",
            'initComplete': function(settings, json) {
                $("#tabel").wrap("<div class='card shadow-sm mb-3 overflow-auto position-relative datatables-height'></div>");
            },
            "drawCallback": function() {
                $(".pagination").addClass("pagination-sm");
                var pageInfo = this.api().page.info();
                $('#loading').html(`${pageInfo.recordsDisplay} antrean`);
            },
            "searching": false,
            'pageLength': 25,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('/home/list_antrean') ?>",
                "type": "POST",
                "data": function(d) {
                    d.search = {
                        "value": $('#externalSearch').val()
                    };
                },
                beforeSend: function() {
                    $('#loading').html(`Memuat...`);
                }
            },
            columns: [{
                    data: 'no',
                    render: (d) => `<span class="date d-block text-center">${d}</span>`
                },
                {
                    data: null,
                    render: (row) => `<div class="d-grid"><button class="btn btn-outline-primary btn-sm rounded-pill cetak-btn" data-id="${row.id_antrean}"><i class="fa-solid fa-print"></i></button></div>`
                },
                {
                    data: 'nama_jaminan'
                },
                {
                    data: 'kode_antrean',
                    render: (d, t, row) => `<span class="date fw-bold text-bps-dark">${d}-${row.nomor_antrean}</span>`
                },
                {
                    data: 'tanggal_antrean',
                    render: (d) => `<span class="date">${d}</span>`
                },
                {
                    data: 'satpam'
                }
            ],
            "order": [
                [3, 'desc']
            ],
            "columnDefs": [{
                "target": [1],
                "orderable": false
            }]
        });

        $('#externalSearch').on('input', () => table.search($('#externalSearch').val()).draw());
        $('#length-menu').on('change', function() {
            table.page.len($(this).val()).draw();
        });
        $('#refreshButton').on('click', () => table.ajax.reload(null, false));
        $('#clearTglButton').on('click', () => {
            $('#externalSearch').val('');
            table.ajax.reload(null, false);
        });

        function cetakAntrean(id) {
            const $btn = $('#cetak-btn');
            const $closeBtn = $('#closeModalBtn');
            const $iframe = $('#print_frame');

            if (countdownTimer !== null) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }

            $closeBtn.prop('disabled', true).text('Tutup');
            $btn.prop('disabled', true).html(`Mencetak...`);
            $iframe.attr('src', `<?= base_url("home/cetak_antrean") ?>/${id}`);

            $iframe.off('load').on('load', function() {
                try {
                    this.contentWindow.focus();
                    this.contentWindow.print();
                    let countdown = 5;
                    $closeBtn.text(`Menutup dalam ${countdown} detik`);
                    countdownTimer = setInterval(() => {
                        countdown--;
                        if (countdown > 0) $closeBtn.text(`Menutup dalam ${countdown} detik`);
                        else {
                            clearInterval(countdownTimer);
                            $('#printModal').modal('hide');
                            $closeBtn.text('Tutup');
                        }
                    }, 1000);
                } catch (e) {
                    alert('Cetak gagal');
                } finally {
                    $btn.prop('disabled', false).html('Cetak Ulang Nomor Antrean');
                    $closeBtn.prop('disabled', false);
                }
            });
        }

        $('#cetak-btn').on('click', function() {
            cetakAntrean($(this).data('id'));
        });
        $(document).on('click', '.cetak-btn', function() {
            cetakAntrean($(this).data('id'));
        });

        $('.btn-apply').on('click', async function(e) {
            e.preventDefault();
            const jaminan = $(this).data('name');
            const $btn = $(this);
            $btn.prop('disabled', true).html(`Tunggu...`);

            try {
                const response = await axios.post(`<?= base_url('/home/buat_antrean') ?>?jaminan=${jaminan}`);
                const data = response.data.data;
                $('#antrean').text(data.antrean);
                $('#nama_jaminan').text(data.nama_jaminan);
                $('#tanggal_antrean').text(data.tanggal_antrean);
                $('#cetak-btn').attr('data-id', data.id_antrean);
                $('#printModal').modal('show');
                cetakAntrean(data.id_antrean);
            } catch (error) {
                alert('Gagal');
            } finally {
                $btn.prop('disabled', false).html(`Buat Antrean`);
            }
        });

        $('#printModal').on('hidden.bs.modal', function() {
            if (countdownTimer !== null) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }
        });

        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
</script>
<?= $this->endSection(); ?>