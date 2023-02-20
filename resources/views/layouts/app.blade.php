<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Absensi | @yield('title')</title>

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css') }}" />

    <!-- Aos Animation Css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/aos/dist/aos.css') }}" />


    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.min.css?v=1.1.2') }}" />

    <!-- Custom Css -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css?v=1.1.2') }}" /> --}}

    <!-- Dark Css -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dark.min.cs') }}s" /> --}}

    <!-- RTL Css -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/rtl.min.cs') }}s" /> --}}

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.min.css') }}" />

    @stack('style')
</head>

<body>

    <aside class="sidebar sidebar-default navs-rounded-all ">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <a href="/dashboard" class="navbar-brand">
                <!--Logo start-->

                <!--logo End-->
                <h5 class="{{ asset('assets/images/kanim.logo.ico') }}">Absensi</h5>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">

            @if (Request::segment(1) == 'admin')
                @include('layouts.sidebar-admin')
            @else
                @include('layouts.sidebar')
            @endif

        </div>
        <!-- Sidebar Menu End -->

        <div class="sidebar-footer"></div>
    </aside>
    <main class="main-content">
        <div class="position-relative">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar bg-info">
                <div class="container-fluid navbar-inner">
                    <a href="../../dashboard/index.html" class="navbar-brand">

                        <h6 class="logo-title text-white">Absensi</h6>
                    </a>
                    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                        <i class="icon">
                            <svg width="20px" height="20px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                            </svg>
                        </i>
                    </div>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <span class="mt-2 navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (Auth::user()->foto === 'profile.png')
                                        <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User-Profile"
                                            class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                                    @else
                                        <img src="{{ asset('assets/images/profiles/' . Auth::user()->foto) }}"
                                            alt="User-Profile"
                                            class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                                    @endif

                                    <div class="caption ms-3 d-none d-md-block ">
                                        <h6 class="mb-0 caption-title text-white">{{ Auth::user()->nama }}</h6>
                                        @if (Auth::guard('web')->check())
                                            <p class="mb-0 caption-sub-title text-white">{{ Auth::user()->seksi }}</p>
                                        @endif
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    @if (Request::segment(1) == 'admin')
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                            <form action="{{ route('admin.logout') }}" method="POST" class="d-none"
                                                id="logout-form">@csrf</form>
                                        </li>
                                    @else
                                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                            <form action="{{ route('logout') }}" method="POST" class="d-none"
                                                id="logout-form">@csrf</form>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Nav Header Component Start -->
            @yield('header')
            <!-- Nav Header Component End -->
            <!--Nav End-->
        </div>

        <div class="conatiner-fluid content-inner mt-4 py-0">
            @yield('content')
        </div>

        <!-- Footer Section Start -->
        <footer class="footer bg-info">
            <div class="footer-body text-white">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item"><a href="" class="text-white fw-bold">Absensi</a></li>
                    {{-- <li class="list-inline-item"><a href="../../dashboard/extra/terms-of-service.html">Terms of Use</a></li> --}}
                </ul>
                <div class="right-panel">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> Made
                    <span class="text-gray">

                    </span> by <a href="" class="text-white fw-bold">Adit</a>.
                </div>
            </div>
        </footer>
        <!-- Footer Section End -->
    </main>

    <!-- Wrapper End-->
    <!-- offcanvas start -->

    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/js/core/external.min.js') }}"></script>

    <!-- Widgetchart Script -->
    <script src="{{ asset('assets/js/charts/widgetcharts.js') }}"></script>

    <!-- mapchart Script -->
    <script src="{{ asset('assets/js/charts/vectore-chart.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/charts/dashboard.js') }}"></script> --}}

    <!-- fslightbox Script -->
    <script src="{{ asset('assets/js/plugins/fslightbox.js') }}"></script>

    <!-- Settings Script -->
    <script src="{{ asset('assets/js/plugins/setting.js') }}"></script>

    <!-- Slider-tab Script -->
    <script src="{{ asset('assets/js/plugins/slider-tabs.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset('assets/js/plugins/form-wizard.js') }}"></script>

    <!-- AOS Animation Plugin-->
    <script src="{{ asset('assets/vendor/aos/dist/aos.js') }}"></script>

    <!-- App Script -->
    <script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>

    @stack('script')

</body>

</html>
