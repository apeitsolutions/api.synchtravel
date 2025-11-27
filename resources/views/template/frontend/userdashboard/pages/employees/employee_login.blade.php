<!DOCTYPE html>
<html lang="en">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="{{asset('public/dist/images/logo.svg')}}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Midone admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>Login</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{asset('public/dist/css/app.css')}}" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->
<body class="login">

<!-- BEGIN: Header & Footer Modal -->
<div class="intro-y box mt-5">
    <div class="" id="header-footer-modal">
        <div class="preview">
            <div class="modal" id="header-footer-modal-preview">
                <div class="modal__content">
                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Apply for Leave
                        </h2>
                    </div>
                    <form action="{{url('super_admin/leaves/submit')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-12 sm:col-span-6">
                                <label>Employee Id</label>
                                <input type="text" class="input w-full border mt-2 flex-1" name="employee_id" placeholder="Employee Id">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label>Employee Name</label>
                                <input type="text" class="input w-full border mt-2 flex-1" name="employee_name" placeholder="Employee Name">
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <label>Enter Email</label>
                                <input type="email" class="input w-full border mt-2 flex-1" name="email" placeholder="Email">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label>Position</label>
                                <select name="position" class="input w-full border mt-2 flex-1">
                                    <option >Select Position</option>
                                    <option value="Programmer">Programmer</option>
                                    <option value="Developer">Developer</option>
                                    <option value="Writer">Writer</option>
                                    <option value="Android">Android</option>
                                    <option value="IOS">IOS</option>
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label>Pax Leave</label>
                                <input type="number" name="pax_leave" class="input w-full border mt-2 flex-1"  placeholder="">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label>From</label>
                                <input type="date" name="form_date" class="input w-full border mt-2 flex-1">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label>To</label>
                                <input type="date" name="to_date" class="input w-full border mt-2 flex-1">
                            </div>
                        </div>
                        <div class="px-5 py-3 text-right border-t border-gray-200">
                            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                            <button type="submit"  name="submit" class="button w-20 bg-theme-1 text-white">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="source-code hidden">
            <button data-target="#copy-header-footer-modal" class="copy-code button button--sm border flex items-center text-gray-700"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Copy code </button>
            <div class="overflow-y-auto h-64 mt-3">
                <pre class="source-preview" id="copy-header-footer-modal"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagdiv class=&quot;text-center&quot;HTMLCloseTag HTMLOpenTaga href=&quot;javascript:;&quot; data-toggle=&quot;modal&quot; data-target=&quot;#header-footer-modal-preview&quot; class=&quot;button inline-block bg-theme-1 text-white&quot;HTMLCloseTagShow ModalHTMLOpenTag/aHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;modal&quot; id=&quot;header-footer-modal-preview&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;modal__content&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;flex items-center px-5 py-5 sm:py-3 border-b border-gray-200&quot;HTMLCloseTag HTMLOpenTagh2 class=&quot;font-medium text-base mr-auto&quot;HTMLCloseTagBroadcast MessageHTMLOpenTag/h2HTMLCloseTag HTMLOpenTagbutton class=&quot;button border items-center text-gray-700 hidden sm:flex&quot;HTMLCloseTag HTMLOpenTagi data-feather=&quot;file&quot; class=&quot;w-4 h-4 mr-2&quot;HTMLCloseTagHTMLOpenTag/iHTMLCloseTag Download Docs HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTagdiv class=&quot;dropdown relative sm:hidden&quot;HTMLCloseTag HTMLOpenTaga class=&quot;dropdown-toggle w-5 h-5 block&quot; href=&quot;javascript:;&quot;HTMLCloseTag HTMLOpenTagi data-feather=&quot;more-horizontal&quot; class=&quot;w-5 h-5 text-gray-700&quot;HTMLCloseTagHTMLOpenTag/iHTMLCloseTag HTMLOpenTag/aHTMLCloseTag HTMLOpenTagdiv class=&quot;dropdown-box mt-5 absolute w-40 top-0 right-0 z-20&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;dropdown-box__content box p-2&quot;HTMLCloseTag HTMLOpenTaga href=&quot;javascript:;&quot; class=&quot;flex items-center p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md&quot;HTMLCloseTag HTMLOpenTagi data-feather=&quot;file&quot; class=&quot;w-4 h-4 mr-2&quot;HTMLCloseTagHTMLOpenTag/iHTMLCloseTag Download Docs HTMLOpenTag/aHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;p-5 grid grid-cols-12 gap-4 row-gap-3&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;col-span-12 sm:col-span-6&quot;HTMLCloseTag HTMLOpenTaglabelHTMLCloseTagFromHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput type=&quot;text&quot; class=&quot;input w-full border mt-2 flex-1&quot; placeholder=&quot;example@gmail.com&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;col-span-12 sm:col-span-6&quot;HTMLCloseTag HTMLOpenTaglabelHTMLCloseTagToHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput type=&quot;text&quot; class=&quot;input w-full border mt-2 flex-1&quot; placeholder=&quot;example@gmail.com&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;col-span-12 sm:col-span-6&quot;HTMLCloseTag HTMLOpenTaglabelHTMLCloseTagSubjectHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput type=&quot;text&quot; class=&quot;input w-full border mt-2 flex-1&quot; placeholder=&quot;Important Meeting&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;col-span-12 sm:col-span-6&quot;HTMLCloseTag HTMLOpenTaglabelHTMLCloseTagHas the WordsHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput type=&quot;text&quot; class=&quot;input w-full border mt-2 flex-1&quot; placeholder=&quot;Job, Work, Documentation&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;col-span-12 sm:col-span-6&quot;HTMLCloseTag HTMLOpenTaglabelHTMLCloseTagDoesn&#039;t HaveHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput type=&quot;text&quot; class=&quot;input w-full border mt-2 flex-1&quot; placeholder=&quot;Job, Work, Documentation&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;col-span-12 sm:col-span-6&quot;HTMLCloseTag HTMLOpenTaglabelHTMLCloseTagSizeHTMLOpenTag/labelHTMLCloseTag HTMLOpenTagselect class=&quot;input w-full border mt-2 flex-1&quot;HTMLCloseTag HTMLOpenTagoptionHTMLCloseTag10HTMLOpenTag/optionHTMLCloseTag HTMLOpenTagoptionHTMLCloseTag25HTMLOpenTag/optionHTMLCloseTag HTMLOpenTagoptionHTMLCloseTag35HTMLOpenTag/optionHTMLCloseTag HTMLOpenTagoptionHTMLCloseTag50HTMLOpenTag/optionHTMLCloseTag HTMLOpenTag/selectHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;px-5 py-3 text-right border-t border-gray-200&quot;HTMLCloseTag HTMLOpenTagbutton type=&quot;button&quot; class=&quot;button w-20 border text-gray-700 mr-1&quot;HTMLCloseTagCancelHTMLOpenTag/buttonHTMLCloseTag HTMLOpenTagbutton type=&quot;button&quot; class=&quot;button w-20 bg-theme-1 text-white&quot;HTMLCloseTagSendHTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
            </div>
        </div>
    </div>
</div>
<!-- END: Header & Footer Modal -->


<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <!-- BEGIN: Login Info -->
        <div class="hidden xl:flex flex-col min-h-screen">
            <a href="" class="-intro-x flex items-center pt-5">
                <img alt="Midone Tailwind HTML Admin Template" class="w-6" src="https://localhost/dow_superadmin/public/dist/images/2.png">
                <span class="text-white text-lg ml-3"> Mahatat Al <span class="font-medium">Alam</span> </span>
            </a>
            <div class="my-auto">
                <img alt="Midone Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{asset('public/dist/images/illustration.svg')}}">
                <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                    <?php
                    $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
                    echo $dateTime->format("d/m/y  H:i A");
                    ?>
                </div>
                <div class="-intro-x mt-5 text-lg text-white"></div>
            </div>
        </div>
        <!-- END: Login Info -->
        <!-- BEGIN: Login Form -->

        <form action="{{url('super_admin/login_emp')}}" method="post">
            @csrf
        <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
            <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                    Sign In
                </h2>
                <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">

                </div>
                <div class="intro-x mt-8">

                        <select name="time_in" class="intro-x login__input input input--lg border border-gray-300 block">
                            <option value="time_in">Time In</option>
                            <option value="time_out">Time Out</option>
                        </select>
                    <input type="text" name="employee_id" class="intro-x login__input input input--lg border border-gray-300 block mt-4" autocomplete="" placeholder="Employee Id">
                </div>
                <div class="intro-x flex text-gray-700 text-xs sm:text-sm mt-4">
                    <div class="flex items-center mr-auto">
                        <input type="checkbox" class="input border mr-2" id="remember-me">
                        <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                    </div>
                    <a href="">Forgot Password?</a>
                </div>
                <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                    <button type="submit" name="submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3">Login</button>
                    <button class="" >
                        <a href="javascript:;" data-toggle="modal" data-target="#header-footer-modal-preview" class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 mt-3 xl:mt-0">
                            Apply for Leave</a>
                    </button>
                </div>
                @if(session()->has('message'))
                    <div class="alert alert-danger">
                        {{session()->get('message')}}
                    </div>
                @endif
                <div id="result">
                </div>
                <div class="intro-x mt-10 xl:mt-24 text-gray-700 text-center xl:text-left">
                    By signin up, you agree to our
                    <br>
                    <a class="text-theme-1" href="">Terms and Conditions</a> & <a class="text-theme-1" href="">Privacy Policy</a>
                </div>
            </div>
        </div>
        </form>
        <!-- END: Login Form -->
    </div>
</div>
<!-- BEGIN: JS Assets-->
<script src="{{asset('public/dist/js/app.js')}}"></script>

</body>
</html>





