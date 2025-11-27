<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zxx">

<!-- Mirrored from htmldesigntemplates.com/html/hotux/bootstrap4/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 May 2022 15:24:45 GMT -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Haramaynrooms</title>

<link rel="shortcut icon" type="image/x-icon" href="{{ asset('/frontend/images/favicon.png') }}">

<link href="{{ asset('/frontend/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('/frontend/css/default.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('/frontend/css/style.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('/frontend/css/plugin.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('/frontend/css/dashboard.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('/frontend/css/icons.css') }}" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('/frontend/../../../../cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('/frontend/../../../../cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css') }}">


<script async src='{{ asset("/frontend/../../../cdn-cgi/challenge-platform/h/g/scripts/invisible8828.js?ts=1653490800") }}'></script></head>


<body>

<div id="preloader">
<div id="status"></div>
</div>


<div id="container-wrapper">

<div id="dashboard">
    @include('hotel_manager.header')
    @include('hotel_manager.sidebar')
    @yield('content');
    @include('hotel_manager.footer')


</div>

</div>


<div id="back-to-top">
<a href="#"></a>
</div>

<script src="{{ asset('/frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/frontend/js/plugin.js') }}"></script>
<script src="{{ asset('/frontend/js/main.js') }}"></script>
<script src="{{ asset('/frontend/../../../../canvasjs.com/assets/script/canvasjs.min.js') }}"></script>
<script src="{{ asset('/frontend/js/chart.js') }}"></script>
<script src="{{ asset('/frontend/js/dashboard.js') }}"></script>
<script type="text/javascript">(function(){window['__CF$cv$params']={r:'710f44774fa4a02d',m:'NFiBX74HPjNkVkJAuGDXvnRy17HB9YZBc36UVRNvMfs-1653492156-0-ASBxmm1QQ6e0tMQpC5H1fJLOaaX+C0PFz+Fd+SRdGLMuQtNVWXejsqMAOwwk4BwWss3K9CrlRldgzWfXjloaYImp6xHofnAVqO6kqizezmCUhRAot7JPe1ZqBWFXBke1kFPz/UMrMH/EmxTKUOjGTOlNqT+UG1jWnb2FcUkssTrD',s:[0xd5f3bb6cfa,0x5235627b05],u:'/cdn-cgi/challenge-platform/h/g'}})();</script></body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
{{-- ...Some more scripts... --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
@yield('scripts')
<!-- Mirrored from htmldesigntemplates.com/html/hotux/bootstrap4/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 May 2022 15:24:51 GMT -->
</html>