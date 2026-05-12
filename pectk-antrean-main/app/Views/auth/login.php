<?php

/** 
 * @var array $users 
 * @var string $title
 * @var string $systemName
 */
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link rel="manifest" href="<?= base_url(); ?>/manifest.json">
    <meta name="theme-color" content="#d1e7dd" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#051b11" media="(prefers-color-scheme: dark)">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Favicon -->
    <link href="<?= base_url(); ?>logo_bps.png" rel="icon" type="image/png" />
    <link href="<?= base_url(); ?>logo_bps.png" rel="apple-touch-icon" />
    <!-- Akhir dari Favicon -->
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/heroes/">
    <?= $this->include('main-css/index'); ?>
    <link href="<?= base_url(); ?>assets_public/css/JawiDubai.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fonts/IosevkaHwpMono/IosevkaHwpMono.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fontawesome/css/all.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        (() => {
            'use strict'

            const getStoredTheme = () => localStorage.getItem('theme');
            const setStoredTheme = theme => localStorage.setItem('theme', theme);

            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme) {
                    return storedTheme;
                }

                return 'auto';
            };

            const setTheme = theme => {
                let themeColor = '';
                let isDarkMode = theme === 'auto' ? window.matchMedia('(prefers-color-scheme: dark)').matches : theme === 'dark';

                if (isDarkMode) {
                    $('html').attr('data-bs-theme', 'dark');
                    themeColor = '#051b11';
                } else {
                    $('html').attr('data-bs-theme', theme);
                    themeColor = '#d1e7dd';
                }
                $('meta[name="theme-color"]').attr('content', themeColor);

                const colorSettings = {
                    color: isDarkMode ? "#FFFFFF" : "#000000",
                    borderColor: isDarkMode ? "rgba(255,255,255,0.1)" : "rgba(0,0,0,0.1)",
                    backgroundColor: isDarkMode ? "rgba(255,255,0,0.1)" : "rgba(0,255,0,0.1)",
                    lineBorderColor: isDarkMode ? "rgba(255,255,0,0.4)" : "rgba(0,255,0,0.4)",
                    gridColor: isDarkMode ? "rgba(255,255,255,0.2)" : "rgba(0,0,0,0.2)"
                };

                if (typeof chartInstances !== 'undefined') {
                    chartInstances.forEach(chart => {
                        if (chart.options.scales) {
                            if (chart.options.scales.x) {
                                if (chart.options.scales.x.ticks) {
                                    chart.options.scales.x.ticks.color = colorSettings.color;
                                }
                                if (chart.options.scales.x.title) {
                                    chart.options.scales.x.title.color = colorSettings.color;
                                }
                                if (chart.options.scales.x.grid) {
                                    chart.options.scales.x.grid.color = colorSettings.gridColor;
                                }
                            }

                            if (chart.options.scales.y) {
                                if (chart.options.scales.y.ticks) {
                                    chart.options.scales.y.ticks.color = colorSettings.color;
                                }
                                if (chart.options.scales.y.title) {
                                    chart.options.scales.y.title.color = colorSettings.color;
                                }
                                if (chart.options.scales.y.grid) {
                                    chart.options.scales.y.grid.color = colorSettings.gridColor;
                                }
                            }
                        }

                        if (chart.options.elements && chart.options.elements.line) {
                            chart.options.elements.line.borderColor = colorSettings.lineBorderColor;
                        }

                        if ((chart.config.type === 'doughnut' || chart.config.type === 'pie') && chart.options.plugins && chart.options.plugins.legend) {
                            chart.options.plugins.legend.labels.color = colorSettings.color;
                        }

                        chart.update();
                    });
                }
            };

            setTheme(getPreferredTheme());

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitcher = $('#bd-theme');

                if (!themeSwitcher.length) {
                    return;
                }

                const themeSwitcherText = $('#bd-theme-text');
                const activeThemeIcon = $('.theme-icon-active use');
                const btnToActive = $(`[data-bs-theme-value="${theme}"]`);

                $('[data-bs-theme-value]').removeClass('active').attr('aria-pressed', 'false');
                btnToActive.addClass('active').attr('aria-pressed', 'true');

                if (focus) {
                    themeSwitcher.focus();
                }
            };

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme();
                if (storedTheme !== 'light' && storedTheme !== 'dark') {
                    setTheme(getPreferredTheme());
                }
            });

            $(document).ready(() => {
                showActiveTheme(getPreferredTheme());

                $('[data-bs-theme-value]').on('click', function() {
                    const theme = $(this).attr('data-bs-theme-value');
                    setStoredTheme(theme);
                    setTheme(theme);
                    showActiveTheme(theme, true);
                });
            });
        })();
    </script>
    <style>
        :root {
            --bs-font-monospace: "Iosevka HWP Mono Web", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        html,
        body,
        input,
        select,
        button {
            font-variant-numeric: proportional-nums;
        }

        input[type="number"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="time"],
        input[type="month"],
        input[type="week"] {
            font-variant-numeric: tabular-nums;
        }

        input[type="password"] {
            font-family: var(--bs-font-monospace);
        }

        .date {
            font-variant-numeric: tabular-nums;
        }

        html,
        body {
            height: 100%;
        }

        .kbd {
            border-radius: 4px !important;
        }

        .no-fluid-content {
            --bs-gutter-x: 0;
            --bs-gutter-y: 0;
            width: 100%;
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
            margin-right: auto;
            margin-left: auto;
            max-width: 1140px;
        }

        .svg-inline--fa {
            vertical-align: middle;
            transform: translateY(-0.1em);
        }

        .loading-spinner {
            fill: currentcolor;
        }
    </style>
    <?= $this->include('spinner/spinner-css'); ?>
</head>

<!-- Mengganti bg-success-subtle menjadi bg-primary-subtle -->

<body class="bg-primary-subtle d-flex flex-column h-100 user-select-none">
    <div class="my-auto">
        <div class="no-fluid-content px-3 py-3 px-md-5">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7 text-center text-md-start align-self-start">
                    <!-- Pastikan file logo_bps.png ada di folder htdocs/assets/images/ -->
                    <img class="mb-3" src="<?= base_url('/assets/images/logo_bps.png'); ?>" width="128px">
                    <!-- Mengganti Tulisan Sistem Antrean -->
                    <h1 class="display-6 fw-bold lh-1 text-primary-emphasis mb-3">Sistem Antrean Layanan<br>BPS Kota Pekalongan</h1>
                    <p class="fs-6 text-primary-emphasis">Badan Pusat Statistik Kota Pekalongan<br><small class="fw-bold"><em>Melayani dengan Profesional, Integritas, dan Amanah</em></small></p>
                </div>
                <div class="col-md">
                    <?= form_open('check-login', 'id="loginForm"'); ?>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control form-control-sm <?= (validation_show_error('username')) ? 'is-invalid' : ''; ?> rounded-4" id="floatingInput" name="username" placeholder="Nama Pengguna" value="" autocomplete="off" list="username">
                        <datalist id="username">
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['username'] ?>">
                                <?php endforeach; ?>
                        </datalist>
                        <label for="floatingInput">
                            <div class="d-flex align-items-start">
                                <div style="width: 12px; text-align: center;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="w-100 ms-3">
                                    Nama Pengguna
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="d-flex flex-column flex-md-row column-gap-3">
                        <div class="flex-fill form-floating mb-3 mb-md-0">
                            <input type="password" class="form-control form-control-sm <?= (validation_show_error('password')) ? 'is-invalid' : ''; ?> rounded-4" id="floatingPassword" name="password" placeholder="Kata Sandi" autocomplete="off" data-bs-toggle="popover"
                                data-bs-placement="top"
                                data-bs-trigger="manual"
                                data-bs-title="<em>CAPS LOCK</em> AKTIF"
                                data-bs-content="Harap periksa status <span class='badge text-bg-dark bg-gradient kbd'>Caps Lock</span> pada papan tombol (<em>keyboard</em>) Anda.">
                            <label for="floatingPassword">
                                <div class="d-flex align-items-start">
                                    <div style="width: 12px; text-align: center;">
                                        <i class="fa-solid fa-key"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        Kata Sandi
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="d-grid w-auto">
                            <!-- Tombol menjadi btn-primary -->
                            <button id="loginBtn" class="w-100 btn btn-primary bg-gradient btn-lg rounded-4" type="submit">
                                <i class="fa-solid fa-right-to-bracket"></i> <span class="d-md-none">MASUK</span>
                            </button>
                        </div>
                    </div>
                    <div class="dropdown d-grid mt-3">
                        <!-- Outline menjadi btn-outline-primary -->
                        <button class="btn btn-outline-primary bg-gradient btn-sm rounded-4 dropdown-toggle" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (auto)">
                            <i class="fa-solid fa-palette"></i> Atur Tema
                        </button>
                        <ul class="dropdown-menu shadow-sm w-100 bg-body-tertiary transparent-blur" aria-labelledby="bd-theme-text">
                            <li>
                                <button type="button" class="dropdown-item" data-bs-theme-value="light" aria-pressed="false">
                                    Terang
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" data-bs-theme-value="dark" aria-pressed="false">
                                    Gelap
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item active" data-bs-theme-value="auto" aria-pressed="true">
                                    Sistem
                                </button>
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" name="url" value="<?= (isset($_GET['redirect'])) ? base_url('/' . urldecode($_GET['redirect'])) : base_url('/home'); ?>">
                    <hr class="border-primary-subtle opacity-100">
                    <div class="text-center text-primary-emphasis" style="font-size: 0.75em;">
                        <span class="">&copy; 2025 <?= (date('Y') !== "2025") ? "- " . date('Y') : ''; ?> BPS Kota Pekalongan</span>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST CONTAINER TETAP SAMA -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
        <?php if (session()->getFlashdata('msg')) : ?>
            <div id="msgToast" class="toast align-items-center text-bg-success border border-success transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="w-100 mx-2 text-start">
                        <?= session()->getFlashdata('msg'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <!-- ... sisa toast tetap sama ... -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // SCRIPT JAVASCRIPT TETAP SAMA PERSIS SESUAI PERMINTAAN
            $('input[type="password"]').each(function() {
                const passwordInput = $(this);
                const popover = new bootstrap.Popover(passwordInput[0], {
                    html: true,
                    template: '<div class="popover shadow-lg" role="tooltip">' +
                        '<div class="popover-arrow"></div>' +
                        '<h3 class="popover-header"></h3>' +
                        '<div class="popover-body">Caps Lock aktif!</div>' +
                        '</div>'
                });

                let capsLockActive = false;

                passwordInput.on('focus', function() {
                    passwordInput[0].addEventListener('keyup', function(event) {
                        const currentCapsLock = event.getModifierState('CapsLock');
                        if (currentCapsLock !== capsLockActive) {
                            capsLockActive = currentCapsLock;
                            if (capsLockActive) {
                                popover.show();
                            } else {
                                popover.hide();
                            }
                        }
                    });
                });

                passwordInput.on('blur', function() {
                    popover.hide();
                    capsLockActive = false;
                });
            });

            if ($('#redirectToast').length) {
                var redirectToast = new bootstrap.Toast($('#redirectToast')[0]);
                redirectToast.show();
            }
            if ($('#msgToast').length) {
                var msgToast = new bootstrap.Toast($('#msgToast')[0]);
                msgToast.show();
            }
            if ($('#errorToast').length) {
                var errorToast = new bootstrap.Toast($('#errorToast')[0]);
                errorToast.show();
            }
            if ($('#validationToast').length) {
                var validationToast = new bootstrap.Toast($('#validationToast')[0]);
                validationToast.show();
            }

            setTimeout(function() {
                $('.toast').toast('hide');
            }, 5000);

            $('input.form-control').on('input', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').hide();
            });

            $(document).on('click', '#loginBtn', function(e) {
                e.preventDefault();
                $('#loginForm').submit();
                $('input').prop('disabled', true).removeClass('is-invalid');
                $('#loginForm button').prop('disabled', true);
                $('#loginBtn').html(`
                    <?= $this->include('spinner/spinner'); ?> <span class="d-md-none">SILAKAN TUNGGU</span>
                `);
            });
        });
    </script>
</body>

</html>