<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>PasswordCreate |</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>



    <!--end::Web font -->
    <!--begin::Page Custom Styles -->
    <link href="{{asset('public/assetss/custom/user/login-v2.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->
    <!--begin::Page Vendors Styles -->
    <link href="{{asset('public/assetss/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
    <!--begin::Page Vendors Styles -->
    <link href="{{asset('public/assetss/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Page Vendors Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="{{asset('public/assetss/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />

    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="{{asset('public/assetss/vendors/general/tether/dist/css/tether.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/nouislider/distribute/nouislider.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/owl.carousel/dist/assets/owl.carousel.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/dropzone/dist/dropzone.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/animate.css/animate.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/morris.js/morris.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/sweetalert2/dist/sweetalert2.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/general/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/custom/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/custom/vendors/flaticon/flaticon.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/custom/vendors/flaticon2/flaticon.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assetss/vendors/custom/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css" />

    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles -->
    <link href="{{asset('public/assetss/demo/demo2/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins -->
    <link href="{{asset('public/assetss/demo/demo2/skins/aside/brand.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{asset('public/assetss/media/logos/logos.png')}}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="k-login-v2--enabled k-header--fixed k-header-mobile--fixed k-aside--enabled k-aside--fixed">

<!-- begin:: Page -->
<div class="k-grid k-grid--ver k-grid--root k-page">
    <div class="k-grid__item   k-grid__item--fluid k-grid  k-grid k-grid--hor k-login-v2" id="k_login_v2">

        <!--begin::Item-->
        <div class="k-grid__item  k-grid--hor">

            <!--begin::Heade-->
            <div class="k-login-v2__head">
                <div class="k-login-v2__head-logo">
                    <a href="#">
                        <img style="width:80px; height: auto;" src="{{asset('public/assetss/media/logos/logos.png')}}" alt="" />
                    </a>
                </div>
                <div class="k-login-v2__head-signup">
                    <span>Don't have an account?</span>
                    <a href="" class="k-link k-font-brand">Sign Up</a>
                </div>
            </div>

            <!--begin::Head-->
        </div>

        <!--end::Item-->

        <!--begin::Item-->
        @if(session()->has('message'))
            <div style="width: 350px;" class="alert alert-success mt-5">
                {{session('message')}}
            </div>
        @endif
        <div class="k-grid__item  k-grid  k-grid--ver  k-grid__item--fluid ">




            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif

        <!--begin::Body-->
            <div class="k-login-v2__body">

                <!--begin::Wrapper-->
                <div class="k-login-v2__body-wrapper">
                    <div class="k-login-v2__body-container">
                        @if($errors->first('email'))
                            <div class="alert alert-danger">
                                {{$errors->first('email')}}
                            </div>
                        @endif
                        @if($errors->first('password'))
                            <div class="alert alert-danger">
                                {{$errors->first('password')}}
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{session('error')}}
                            </div>
                        @endif
                        <div class="k-login-v2__body-title">
                            <h3>Create Your Password</h3>
                        </div>

                        <!--begin::Form-->
                        <form class="k-login-v2__body-form k-form k-login-v2__body-form--border" action="{{url('lead_passenger/create_password/submit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input class="form-control" type="email" placeholder="Email" name="lead_passenger_email"  required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Password" name="user_password"  required>
                            </div>


                            <!--end::Form-->

                            <!--begin::Action-->
                            <div class="k-login-v2__body-action k-login-v2__body-action--brand">
                                <a href="#" class="k-link k-link--brand">

                                </a>
                                <button type="submit" name="submit" class="btn btn-pill btn-brand btn-elevate">Create Password</button>
                            </div>
                        </form>
                        <!--end::Action-->




                        <!--begin::Options-->

                        <!--end::Options-->
                    </div>
                </div>

                <!--end::Wrapper-->

                <!--begin::Pic-->
                <div class="k-login-v2__body-pic">
                    <img src="{{asset('public/assetss/media/misc/bg_icon.svg')}}" alt="">
                </div>

                <!--begin::Pic-->
            </div>

            <!--begin::Body-->
        </div>

        <!--end::Item-->


    </div>
</div>

<!-- end:: Page -->


<!-- begin::Global Config -->
<script>
    var KAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "metal": "#c4c5d6",
                "light": "#ffffff",
                "accent": "#00c5dc",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995",
                "focus": "#9816f4"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>

<!--begin:: Global Mandatory Vendors -->
<script src="{{asset('public/assetss/vendors/general/jquery/dist/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/moment/min/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/wnumb/wNumb.js')}}" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="{{asset('public/assetss/vendors/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/block-ui/jquery.blockUI.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/theme/framework/vendors/bootstrap-datepicker/init.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/theme/framework/vendors/bootstrap-timepicker/init.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-select/dist/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/typeahead.js/dist/typeahead.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/handlebars/dist/handlebars.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/inputmask/dist/jquery.inputmask.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/inputmask/dist/inputmask/inputmask.phone.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/nouislider/distribute/nouislider.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/owl.carousel/dist/owl.carousel.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/autosize/dist/autosize.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/clipboard/dist/clipboard.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/dropzone/dist/dropzone.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/summernote/dist/summernote.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/markdown/lib/markdown.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/theme/framework/vendors/bootstrap-markdown/init.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/jquery-validation/dist/jquery.validate.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/jquery-validation/dist/additional-methods.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/theme/framework/vendors/jquery-validation/init.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/raphael/raphael.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/morris.js/morris.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/chart.js/dist/Chart.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/waypoints/lib/jquery.waypoints.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/counterup/jquery.counterup.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/es6-promise-polyfill/promise.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/custom/theme/framework/vendors/sweetalert2/init.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/jquery.repeater/src/lib.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/jquery.repeater/src/jquery.input.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/jquery.repeater/src/repeater.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assetss/vendors/general/dompurify/dist/purify.js')}}" type="text/javascript"></script>

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle -->
<script src="{{asset('public/assetss/demo/demo2/base/scripts.bundle.js')}}" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Vendors -->
<script src="{{asset('public/assetss/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts -->
<script src="{{asset('public/assetss/app/scripts/custom/dashboard.js')}}" type="text/javascript"></script>

<!--end::Page Scripts -->

<!--begin::Global App Bundle -->
<script src="{{asset('public/assetss/app/scripts/bundle/app.bundle.js')}}" type="text/javascript"></script>

<!--end::Global App Bundle -->
<!--begin::Page Vendors -->
<script src="{{asset('public/assetss/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts -->
<script src="{{asset('public/assetss/demo/default/custom/components/datatables/basic/scrollable.js')}}" type="text/javascript"></script>

<!--end::Page Scripts -->


<!-- begin::Page Loader -->
<script>
    $(window).on('load', function() {
        $('body').removeClass('k-page--loading');
    });
</script>





<!-- end::Page Loader -->
</body>

<!-- end::Body -->
</html>

































