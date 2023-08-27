<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

</head>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="" alt="">
        <span class="d-none d-sm-block">School Management</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        @php
            $profileImg = App\Http\Controllers\Admin\UserController::getProfileImage();
            if ($profileImg != '') {
              $profileImgUrl = $profileImg;
            } else {
              $profileImgUrl = 'assets/images/profile.jpg';
            }   
            $role = Auth::user()->role;

            $roleList = App\Http\Controllers\Admin\UserController::getDepartment();
            $roleName = 'Super Admin';
            foreach ($roleList as $roleData) {
              if ($role == $roleData['id']) {
                $roleName = $roleData['name'];
                break;
              }              
            } 
        @endphp
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset($profileImgUrl)}}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ Auth::user()->name }}</h6>
              <span>{{ $roleName }}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('admin/profile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('admin/logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/home') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

          @php
            $dashboard = App\Http\Controllers\Admin\UserController::getDashboardPermission();
            $icon_array = [
                "User Management"    => "bi bi-menu-button-wide",
                "Category"           => "bi bi-journal-text",
                "Create Information" => "bi bi-layout-text-window-reverse",
                "Registration"       => "bi bi-card-list",
                "Exam"               => "bi bi-layout-text-window-reverse",
                "Wallet"             => "bi bi-layout-text-window-reverse",
                "Shop"               => "bi bi-layout-text-window-reverse",
                "Reporting"          => "bi bi-view-list",
              ];

            foreach ($dashboard as $main_menu => $sub_menu) {
              $targetNavName = '#'.str_replace(' ', '', $main_menu).'-nav';
              $navId         =  str_replace(' ', '', $main_menu).'-nav';
              @endphp
              <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="{{$targetNavName}}" data-bs-toggle="collapse" href="#">
                  <i class="{{$icon_array[$main_menu]}}"></i><span>{{$main_menu}}</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="{{$navId}}" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                  @php 
                    foreach ($sub_menu as $menu) { 
                  @endphp
                        @if ($menu['type'] == 'route')
                          <li>
                            <a href="{{route($menu['menu_route'])}}">
                              <span>{{$menu['sub_menu']}}</span>
                            </a>
                          </li>
                        @else 
                          <li>
                            <a href="{{ url($menu['menu_route']) }}">
                              <span>{{$menu['sub_menu']}}</span>
                            </a>
                          </li>
                        @endif
                  @php
                    }
                  @endphp
                </ul>
              </li>
       @php } @endphp

      <!--<li class="nav-heading">Pages</li>-->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('admin/logout') }}">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Logout Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

      @yield('content')

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <!--<footer id="footer" class="footer">
    <div class="copyright">
      <strong><span>School Management System</span></strong>
    </div>
    <div class="credits">
      Designed by <a href="https://schoolmanagementsystem.com/">Bootstrap</a>
    </div>
  </footer>--><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Template Main JS File -->
  <script src="{{ asset('js/dashboard.js')}}"></script>
</body>

</html>
