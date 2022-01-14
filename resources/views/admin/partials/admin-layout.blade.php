<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href={{asset('/admin/img/apple-icon.png')}}>
    <link rel="icon" type="image/png" href={{asset('/admin/img/favicon.png')}}>
    <title>
        Black Dashboard by Creative Tim
    </title>
    <!--     Fonts and icons     -->
{{--    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />--}}
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href={{asset('admin/css/nucleo-icons.css')}} rel="stylesheet" />
    <!-- CSS Files -->

    <link href={{asset('admin/css/black-dashboard.css')}} rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
{{--    <link href={{asset('/admin/css/all.css')}} rel="stylesheet" />--}}
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href={{asset('/admin/css/demo.css')}} rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf">
<style>
.perfect-scrollbar-on .sidebar,
.perfect-scrollbar-on .main-panel {
   height: 90vh !important;
    padding-bottom:20px !important;
}
    </style>
    @yield('styles')
</head>

<body class="">
<div class="wrapper" id="container">
    @include('admin.partials.admin-sidebar')
    <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <div class="navbar-toggle d-inline">
                        <button type="button" class="navbar-toggler">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </button>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                </button>
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav ml-auto">
                        
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <div class="photo">
                                    <img src={{asset(auth()->user()->getAvatar())}} alt="Profile">
                                </div>
                                <b class="caret d-none d-lg-block d-xl-block"></b>
                                <p class="d-lg-none">
                                    Log out
                                </p>
                            </a>
                            <ul class="dropdown-menu dropdown-navbar">
                                <li class="nav-link">
                                    <a href="{{route('users.my-profile')}}" class="nav-item dropdown-item">Profile</a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                    </a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                        <li class="separator d-lg-none"></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="tim-icons icon-simple-remove"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Navbar -->
        <div class="content">
@yield('content')
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <ul class="nav">
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link">
                            Creative Tim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link">
                            Blog
                        </a>
                    </li>
                </ul>
                <div class="copyright">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script> made with <i class="tim-icons icon-heart-2"></i> by
                    <a href="javascript:void(0)" target="_blank">Creative Tim</a> for a better web.
                </div>
            </div>
        </footer>
    </div>
</div>
@include('admin.partials.fixed-plugin')

<!--   Core JS Files   -->

<script src={{asset('admin/js/core/jquery.min.js')}}></script>
<script src={{asset('admin/js/core/popper.min.js')}}></script>
<script src={{asset('admin/js/core/bootstrap.min.js')}}></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src={{asset('admin/js/plugins/perfect-scrollbar.jquery.min.js')}}></script>

<!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
<script src={{asset('/admin/js/black-dashboard.js')}}></script>
<script src={{asset('/admin/js/demo.js')}}></script>
<script src={{asset('/admin/js/page.js')}}></script>
<script src={{asset('/js/my-app.js')}}></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
    
    $(document).ready(function() {
       const ps = new PerfectScrollbar('#container');
        // Javascript method's body can be found in assets/js/demos.js
        //demo.initDashboardPageCharts();
        // initialise Datetimepicker and Sliders
       //blackDashboard.initDateTimePicker();
        //if ($('.slider').length != 0) {
          //  demo.initSliders();
        //}

    });
    
</script>
@yield('scripts')
</body>

</html>
