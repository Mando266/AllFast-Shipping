<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{Auth::user()->company->name}}</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/logo.png')}}"/>
    <link href="{{asset('assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('assets/js/loader.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('fontawesome/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/structure.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/forms/theme-checkbox-radio.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/elements/alert.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{asset('assets/css/elements/breadcrumb.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <style>
        .alert-light-success span{
            color: #0e1726;
        }
        .paginating-container {
            display: flex;
            justify-content: center;
            margin-bottom: 0;
        }
        .bootstrap-select.btn-group > .dropdown-toggle{
            max-height: 100%;
        }
        .list li{
            color: #212529;
        }
    </style>
    @stack('styles')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>
<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top" style="padding-left: 0px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
            <ul class="navbar-item flex-row" style="margin-right: 15px;">
                <li class="nav-item align-self-center page-heading">
                    <div class="page-header">
                        <div class="page-title">
                            <h3> {{Auth::user()->company->name}} </h3>

                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-item flex-row search-ul">
                <!-- <li class="nav-item align-self-center search-animated">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <form class="form-inline search-full form-inline search" role="search">
                        <div class="search-bar">
                            <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
                        </div>
                    </form>
                </li> -->
            </ul>

            <ul class="navbar-item flex-row">
                <li class="nav-item align-self-center page-heading navbar-profile">
                    <div class="page-header">
                        <div class="user-title">
                        <div class="media mx-auto">
                            <img src="{{optional(Auth::user())->getAvatarUrl()}}" class="img-fluid mr-2" alt="avatar" style="width:36px;">
                            <div class="media-body">
                                <h5>{{optional(Auth::user())->full_name}}</h5>
                            </div>
                        </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-item flex-row navbar-dropdown">
                @include('layouts._profile_menu')
            </ul>

        </header>
    </div>
    <!--  END NAVBAR  -->
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div id="content" class="main-content" style="margin-left: 0px;">
            @if(session()->has('success'))
            <div class="alert alert-arrow-left alert-icon-left alert-light-success  m-4 d-block" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                <strong>Success!</strong><span> {{ session()->get('success')}}</span>
            </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-arrow-left alert-icon-left alert-light-danger  m-4 d-block"
                role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <strong>Error!</strong><span> {{ session()->get('error')}} </span>
                </div>
            @endif
            @yield('content')

            <div class="footer-wrapper hide">
                <div class="footer-section f-section-1">
                    <p class="hide">Copyright © 2021 Middle East Shipping Line, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <a href="https://dejatec.com/">Developed By <span style="color:red;">Deja Technology</span></a>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->


    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('assets/js/libs/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            App.init();
            function hasArabicCharacters(text){

            var regex = new RegExp("[\u0600-\u06FF]|[\u0750-\u077f]|[\ufb50-\ufdff]|[\ufe70-\ufeff]|[\u0660-\u0669]|[\u08A0-\u08FF]","gmu");

            return regex.test(text);
            }

            $('.no_arabic').on('keyup',function (e) {
                var value = (e.target.value);
                if(value === '.')return true;
                var self = $(this);
                var old = "";
                if(value.length > 1){
                    old = value.substring(0,value.length-1);
                    if(hasArabicCharacters(value[value.length-1])){
                        self.val(old);
                    }
                }else{
                    if(hasArabicCharacters(value)){
                        self.val(old);
                    }
                    old = value.substring(0,1);
                }

                return true;
            });
            $('.clone-table').on('keyup','.no_arabic',function (e) {
                var value = (e.target.value);
                if(value === '.')return true;
                var self = $(this);
                var old = "";
                if(value.length > 1){
                    old = value.substring(0,value.length-1);
                    if(hasArabicCharacters(value[value.length-1])){
                        self.val(old);
                    }
                }else{
                    if(hasArabicCharacters(value)){
                        self.val(old);
                    }
                    old = value.substring(0,1);
                }

                return true;
            });
            let numberOnlyInputs = $('.numbers-only');
            numberOnlyInputs.on('keydown',function (e) {
                if(e.target.value === '.')return true;
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
            $('.clone-table').on('keydown','.numbers-only',function (e) {
                if(e.target.value === '.')return true;
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    @stack('scripts')
</body>
<style>
@media print {
    .search_row,
    .hide {
        display: none !important;
        }
    }
</style>
</html>
