<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title') - {{ env('APP_NAME', 'Base Project IndraKsma') }}</title>
    @livewireStyles
    @stack('headscripts')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="nav-fixed">
    <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
        id="sidenavAccordion">
        <!-- Sidenav Toggle Button-->
        <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i
                data-feather="menu"></i></button>
        <!-- Navbar Brand-->
        <!-- * * Tip * * You can use text or an image for your navbar brand.-->
        <!-- * * * * * * When using an image, we recommend the SVG format.-->
        <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
        <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ route('home') }}">{{ env('APP_NAME', 'BasePro') }}</a>
        <!-- Navbar Items-->
        <ul class="navbar-nav align-items-center ms-auto">
            <!-- User Dropdown-->
            <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                    href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"><img class="img-fluid"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&length=2" /></a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                    aria-labelledby="navbarDropdownUserImage">
                    <h6 class="dropdown-header d-flex align-items-center">
                        <img class="dropdown-user-img"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&length=2" />
                        <div class="dropdown-user-details">
                            <div class="dropdown-user-details-name">{{ Auth::user()->name }}</div>
                            <div class="dropdown-user-details-email">{{ Auth::user()->email }}</div>
                        </div>
                    </h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('changepass') }}">
                        <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                        Change Password
                    </a>
                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sidenav shadow-right sidenav-light">
                <div class="sidenav-menu">
                    <div class="nav accordion" id="accordionSidenav">
                        <!-- Sidenav Menu Heading (Core)-->
                        {{-- <div class="sidenav-menu-heading">Heading</div> --}}
                        <!-- Sidenav Accordion (Dashboard)-->

                        <a class="{{ request()->routeIs('home') ? 'nav-link active' : 'nav-link' }}"
                            href="{{ route('home') }}">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                            Dashboard
                        </a>
                        <a class="{{ request()->routeIs('jurnal') ? 'nav-link active' : 'nav-link' }}"
                            href="{{ route('jurnal') }}">
                            <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                            Jurnal
                        </a>
                        @if (Auth::user()->hasRole(['admin', 'superadmin', 'guru_piket']))
                            <a class="{{ request()->routeIs('verjurnal') ? 'nav-link active' : 'nav-link' }}"
                                href="{{ route('verjurnal') }}">
                                <div class="nav-link-icon"><i data-feather="check"></i></div>
                                Verifikasi Jurnal
                            </a>
                        @endif
                        </a>
                        <a class="{{ request()->routeIs('materi') ? 'nav-link active' : 'nav-link' }}"
                            href="{{ route('materi') }}">
                            <div class="nav-link-icon"><i data-feather="file"></i></div>
                            Materi
                        </a>
                        <a class="{{ request()->routeIs('agenda') ? 'nav-link active' : 'nav-link' }}"
                            href="{{ route('agenda') }}">
                            <div class="nav-link-icon"><i data-feather="clipboard"></i></div>
                            Agenda Harian
                        </a>
                        <a class="{{ request()->routeIs('siswa') ? 'nav-link active' : 'nav-link' }}"
                            href="{{ route('siswa') }}">
                            <div class="nav-link-icon"><i data-feather="user"></i></div>
                            Siswa
                        </a>
                        <a class="{{ request()->routeIs('laporan') ? 'nav-link active' : 'nav-link' }}"
                            href="{{ route('laporan') }}">
                            <div class="nav-link-icon"><i data-feather="printer"></i></div>
                            Laporan
                        </a>
                        @if (Auth::user()->hasRole(['admin', 'superadmin']))
                            <a class="{{ request()->routeIs('mapel') ? 'nav-link active' : 'nav-link' }}"
                                href="{{ route('mapel') }}">
                                <div class="nav-link-icon"><i data-feather="bookmark"></i></div>
                                Mata Pelajaran
                            </a>
                            <a class="{{ request()->routeIs('rombel') ? 'nav-link active' : 'nav-link' }}"
                                href="{{ route('rombel') }}">
                                <div class="nav-link-icon"><i data-feather="server"></i></div>
                                Rombongan Belajar
                            </a>
                            <a class="{{ request()->routeIs(['user', 'roles', 'kepsek', 'jurusan', 'kelas', 'semester', 'ta', 'config']) ? 'nav-link active' : 'nav-link collapsed' }}"
                                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseSetting"
                                aria-expanded="false" aria-controls="collapseSetting">
                                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                                Settings
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="{{ request()->routeIs(['user', 'roles', 'kepsek', 'jurusan', 'kelas', 'semester', 'ta', 'config']) ? 'collapse show' : 'collapse' }}"
                                id="collapseSetting" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                                    <a class="{{ request()->routeIs('kepsek') ? 'nav-link active' : 'nav-link' }}"
                                        href="{{ route('kepsek') }}">Kepala Sekolah</a>
                                    <a class="{{ request()->routeIs('ta') ? 'nav-link active' : 'nav-link' }}"
                                        href="{{ route('ta') }}">Tahun Ajaran</a>
                                    <a class="{{ request()->routeIs('semester') ? 'nav-link active' : 'nav-link' }}"
                                        href="{{ route('semester') }}">Semester</a>
                                    <a class="{{ request()->routeIs('user') ? 'nav-link active' : 'nav-link' }}"
                                        href="{{ route('user') }}">User</a>
                                    <a class="{{ request()->routeIs('jurusan') ? 'nav-link active' : 'nav-link' }}"
                                        href="{{ route('jurusan') }}">Paket</a>
                                    <a class="{{ request()->routeIs('kelas') ? 'nav-link active' : 'nav-link' }}"
                                        href="{{ route('kelas') }}">Kelas</a>
                                    @if (Auth::user()->hasRole(['superadmin']))
                                        <a class="{{ request()->routeIs('roles') ? 'nav-link active' : 'nav-link' }}"
                                            href="{{ route('roles') }}">Role</a>
                                        <a class="{{ request()->routeIs('config') ? 'nav-link active' : 'nav-link' }}"
                                            href="{{ route('config') }}">Site Config</a>
                                    @endif
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Sidenav Footer-->
                <div class="sidenav-footer">
                    <div class="sidenav-footer-content">
                        <div class="sidenav-footer-subtitle">Logged in as:</div>
                        <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                {{ $slot }}
            </main>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <footer class="footer-admin mt-auto footer-light">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-12 small text-center">Copyright &copy; IndraKsma 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    <x-livewire-alert::flash />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables/datatables-simple-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/litepicker.js') }}"></script>

    @stack('footscripts')
</body>

</html>
