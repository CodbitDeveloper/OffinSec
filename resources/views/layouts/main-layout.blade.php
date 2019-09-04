<!--

    Created By: Nii Codbit
    Date: 30/03/13

    empty container with the base layout of the dashboard.
    Created this so that we can have something to play around layouts with.
    Copy this whenever you want to create a new page.

    
-->
<?php $user = Auth::user() ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{$page_title}} | GUARD ATTENDANCE MANAGEMENT SYSTEM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Guard attendance management system. Allows security agencies to track the attendance of their guards."
        name="description" />
    <meta content="Codbit Developers" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!--Custom css-->
    @yield('styles')


    <!-- App css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/jquery-toastr/jquery.toast.min.css')}}" rel="stylesheet"/>


    <script src="{{asset('assets/js/modernizr.min.js')}}"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Rubik" rel="stylesheet">

</head>

<body>

    <!-- Navigation Bar-->
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">

                <!-- Logo container-->
                <div class="logo">
                    <a href="/" class="logo">
                        <img src="{{asset('assets/images/offin-logo.png')}}" alt="" height="48" class="logo-small">
                        <img src="{{asset('assets/images/offin-logo.png')}}" alt="" height="42" class="logo-large">
                    </a>

                </div>
                <!-- End Logo container-->


                <div class="menu-extras topbar-custom">

                    <ul class="list-unstyled topbar-right-menu float-right mb-0">

                        <li class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle nav-link">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle waves-effect nav-user" data-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <img class="rounded-circle" height="30" style="width: 30px" avatar="{{ucwords($user->firstname.' '.$user->lastname)}}" />
                                <span class="ml-1 pro-user-name">
                                    {{ucwords($user->firstname.' '.$user->lastname)}} <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">


                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="account()">
                                    <i class="fi-head"></i> <span>My Account</span>
                                </a>

                                <a href="{{ route('logout') }}" class="dropdown-item notify-item" 
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <i class="fi-power"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                            </div>
                        </li>
                    </ul>
                </div>
                <!-- end menu-extras -->

                <div class="clearfix"></div>

            </div> <!-- end container -->
        </div>
        <!-- end topbar-main -->

        <div class="navbar-custom">
            <div class="container-fluid">
                <div id="navigation">
                    <!-- Navigation Menu-->
                    <ul class="navigation-menu">

                        <li>
                            <a href="/home" class="active">Dashboard</a>
                        </li>

                        <li class="has-submenu">
                            <a href="javascript:void(0)">Guards</a>
                            <ul class="submenu">
                                <li class="has-submenu">
                                    <a href="javascript:void(0)">View Guards</a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="/guards">Active Guards</a>
                                        </li>
                                        <li>
                                            <a href="/archived-guards">Archived Guards</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="/guards/add">New Guard</a></li>
                                <li><a href="/guards/reports">Guard Reports</a></li>
                                <li class="has-submenu">
                                    <a href="javascript:void(0)">Back Office</a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="/upload">Upload CSV</a>
                                        </li>
                                        <li>
                                            <a href="/biometrics">Update Guard Biometrics</a>
                                        </li>
                                        <li>
                                            <a href="/add-guarantors">Add Guarantors</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="/clients">Clients</a>
                        </li>

                        <li class="has-submenu">
                            <a href="javascript:void(0)">Attendance</a>
                            <ul class="submenu">
                                <li><a href="/attendance">View Attendance</a></li>
                                <li><a href="/permissions">Permissions</a></li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="javascript:void(0)">Offences</a>
                            <ul class="submenu">
                                <li>
                                    <a href="/offences">Guard Offences</a>
                                </li>
                                <li>
                                    <a href="/offence-types">Offence Types</a>
                                </li>
                                <li>
                                    <a href="/view-deductions">View Monthly Offences</a>
                                </li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="javascript:void(0)">Reports</a>
                            <ul class="submenu">
                                <li class="has-submenu">
                                    <a href="javascript:void(0)">Client Reports</a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="/send-report">New Client Report</a>
                                        </li>
                                        <li>
                                            <a href="/view-reports">View Generated Reports</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="/incidents">Incidents</a>
                                </li>
                                <li>
                                    <a href="/occurrences">Occurrences</a>
                                </li>
                            </ul>
                        </li>
                        @if(strtolower(Auth::user()->role) == 'admin')
                        <li>
                            <a href="/users">Users</a>
                        </li>
                        <li class="has-submenu">
                            <a href="javascript:void(0)">Salaries</a>
                            <ul class="submenu">
                                <li>
                                    <a href="/salaries">Generate Salary Report</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- End Navigation Bar-->


    <div class="wrapper">
        <div class="container-fluid">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="btn-group float-right">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="#">OSAMS</a></li>
                                <li class="breadcrumb-item active">{{$page_title}}</li>
                            </ol>
                        </div>
                        <h4 class="page-title">{{$page_title}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title end breadcrumb -->

            <!--
                content you want comes here
            -->
            @yield('content')
            
        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->
    @yield('modals')
    <div class="modal fade" id="my-account">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Edit Account</h4>
                </div>
                <div class="modal-body p-4">
                    <form role="form" id="my-account_form">   
                        @csrf
                    <div class="form-row mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="name">Firstname</label>
                            <input class="form-control resetable" type="text" id="edit_fname" placeholder="Kwasi" name="firstname" value="{{$user->firstname}}">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="name">Lastname</label>
                            <input class="form-control resetable" type="text" id="edit_lname" placeholder="Koomson" name="lastname" value="{{$user->lastname}}">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="email">Phone</label>
                            <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable" name="phone_number" value="{{$user->phone_number}}">
                            <input type="hidden" name="id" value="{{$user->id}}"/>
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="password">Old Password</label>
                            <input class="form-control resetable" type="password" id="old_password" name="old_password">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="password">Enter New Password</label>
                            <input class="form-control resetable" type="password" id="edit_new_password_confirm" name="password">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="password">Confirm Password</label>
                            <input class="form-control resetable" type="password" id="edit_password_confirm" name="password_confirmation">
                        </div>
                    </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-custom ml-1 waves-effect waves-light save-category">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    2019 &copy; OSAMS. - Made with ❤️ By <a href="http://codbitgh.com">Codbit Ghana Limited</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->


    <!-- jQuery  -->
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/waves.js')}}"></script>
    <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('assets/js/avatar.js')}}"></script>
    <script src="{{asset('plugins/jquery-toastr/jquery.toast.min.js')}}" type="text/javascript"></script>

    
    @yield('scripts')

    <!-- App js -->
    <script src="{{asset('assets/js/jquery.core.js')}}"></script>
    <script src="{{asset('assets/js/jquery.app.js')}}"></script>
    <script>
        function account(){
            $('#my-account').modal('show');
        }

        $('#my-account_form').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');

            data = $(this).serialize();

            var error = false;

            if(!error){
                $(this).find('.text-danger').css('display', 'none');

                applyLoading(btn);

                $.ajax({
                    url: '/api/user/update/my-account',
                    method: 'PUT',
                    data: data,
                    success: function(data){
                        removeLoading(btn, 'Update');
                            if(data.error){
                                $.toast({
                                    text : data.message,
                                    heading : 'Error',
                                    position: 'top-right',
                                    showHideTransition : 'slide', 
                                    bgColor: '#d9534f'
                                });
                            }else{
                                $.toast({
                                    text : data.message,
                                    heading : 'Done',
                                    position: 'top-right',
                                    bgColor : '#5cb85c',
                                    showHideTransition : 'slide'
                                });

                                setTimeout(function(){
                                    location.reload();
                                }, 500);
                            }
                    },
                    error: function(err){
                        removeLoading(btn, 'Update');
                        $.toast({
                            text : 'Network error',
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }
                });
            }
        })
    </script>

</body>

</html>