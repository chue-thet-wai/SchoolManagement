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
            if ($role == 2) {
              $roleName = 'Branch Admin';
            } else if ($role== 3){
              $roleName = 'Teacher';
            }  else {
              $roleName = 'Super Admin';
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

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('user.index')}}"">
        <i class="bi bi-menu-button-wide"></i>
        <span>User Management</span>
        </a>
      </li><!-- End User Management Nav -->

      <!-- Category Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#category-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="category-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('academic_year.index')}}">
              <span>Academic Year</span>
            </a>
          </li>
          <li>
            <a href="{{route('branch.index')}}">
              <span>Branch</span>
            </a>
          </li>
          <li>
            <a href="{{route('room.index')}}">
              <span>Room</span>
            </a>
          </li>
          <li>
            <a href="{{route('grade.index')}}">
              <span>Grade</span>
            </a>
          </li>
          <li>
            <a href="{{route('section.index')}}">
              <span>Section</span>
            </a>
          </li>
          <li>
            <a href="{{route('grade_level_fee.index')}}">
              <span>Grade Level Fee</span>
            </a>
          </li>
          <li>
            <a href="{{route('additional_fee.index')}}">
              <span>Additional Fee</span>
            </a>
          </li>
          <li>
            <a href="{{route('subject.index')}}">
              <span>Subject</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/township/list') }}">
              <span>Township</span>
            </a>
          </li>
        </ul>
      </li><!-- End Category Nav -->

      <!-- Create Information Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#createinfo-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Create Information</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="createinfo-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('teacher_info.index')}}">
              <span>Teacher Information</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/student_info/list') }}">
              <span>Student Information</span>
            </a>
          </li>
          <li>
            <a href="{{route('class_setup.index')}}">
              <span>Class Setup</span>
            </a>
          </li>
          <li>
            <a href="{{route('driver_info.index')}}">
              <span>Driver Information</span>
            </a>
          </li>
          <li>
            <a href="{{route('schedule.index')}}">
              <span>Schedule</span>
            </a>
          </li>
          <li>
            <a href="{{route('activity.index')}}">
              <span>Activity</span>
            </a>
          </li>
        </ul>
      </li><!-- End Create Information Nav -->

      <!-- Registration Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#registration-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-card-list"></i><span>Registration</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="registration-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('student_reg.index')}}">
              <span>Student Registration</span>
            </a>
          </li>
          <li>
            <a href="{{route('waitinglist_reg.index')}}">
              <span>Waiting List Registration</span>
            </a>
          </li>
          <li>
            <a href="{{route('cancel_reg.index')}}">
              <span>Cancel Registration</span>
            </a>
          </li>
          <li>
            <a href="{{route('payment.index')}}">
              <span>Payment Registration</span>
            </a>
          </li>
          <li>
            <a href="{{route('school_bus_track.index')}}">
              <span>School Bus Track</span>
            </a>
          </li>
          <li>
            <a href="{{route('teacher_attendance.index')}}">
              <span>Teacher Attendance</span>
            </a>
          </li>
          <li>
            <a href="{{route('student_attendance.index')}}">
              <span>Student Attendance</span>
            </a>
          </li>
        </ul>
      </li><!-- End Registration Nav -->
      
      <!-- Create Exam Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#exam-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Exam</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="exam-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{url('admin/exam_terms/list')}}">
              <span>Exam Terms</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/exam_marks/list') }}">
              <span>Exam Marks</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- End Exam Nav -->
      <!-- Create Wallet Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#wallet-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Wallet</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="wallet-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{url('admin/cash_counter/list')}}">
              <span>Cash Counter</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/cash_in_history/list') }}">
              <span>Cash In History</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- End Wallet Nav -->
      <!-- Create Shop Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#shop-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Shop</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="shop-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{url('admin/menu/list')}}">
              <span>Menu</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/food_order/list') }}">
              <span>Food Order</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- End Shop Nav -->

      <!-- Report Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#report-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-view-list"></i><span>Reporting</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="report-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('admin/reporting/studentregistration_report') }}">
              <span>Student Registration Report</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/reporting/payment_report') }}">
              <span>Payment Report</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/reporting/cancel_report') }}">
              <span>Cancel Report</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/reporting/ferry_report') }}">
              <span>Ferry Report</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/reporting/teacher_attendance_report') }}">
              <span>Teacher Attendance Report</span>
            </a>
          </li>
          <li>
            <a href="{{ url('admin/reporting/student_attendance_report') }}">
              <span>Student Attendance Report</span>
            </a>
          </li>
        </ul>
      </li><!-- End Report Nav -->

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
