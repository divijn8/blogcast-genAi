<!DOCTYPE html>
<html lang="en">
<head>
    <title>BlogCast | Heaven for Creators</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="utf-8">
    <meta name="author" content="John Doe">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('frontend/assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('frontend/assets/img/favicon.png') }}">

    <!-- Load Core CSS
    =====================================-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/core/bootstrap-3.3.7.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/core/animate.min.css') }}">

    <!-- Load Main CSS
    =====================================-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main/main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main/setting.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main/hover.css') }}">


    <link rel="stylesheet" href="{{ asset('frontend/assets/css/color/pasific.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/icon/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/icon/et-line-font.css') }}">

    <!-- Load JS
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    WARNING: Respond.js doesn't work if you view the page via file://
    =====================================-->

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body id="topPage" data-spy="scroll" data-target=".navbar" data-offset="100">



<!-- Page Loader
===================================== -->
<div id="pageloader" class="bg-grad-animation-1">
    <div class="loader-item">
        <img src="{{ asset('frontend/assets/img/other/oval.svg') }}" alt="page loader">
    </div>
</div>

<a href="#page-top" class="go-to-top">
    <i class="fa fa-long-arrow-up"></i>
</a>


@include('frontend.layouts._navigation')
@include('frontend.layouts._header')

<!-- Blog Area
===================================== -->
<div id="blog" class="pt20 pb50">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mt25">
              @yield('main-content')
            </div>
            @include('frontend.layouts._sidebar')
        </div>
    </div>
</div>

@include('frontend.layouts._newsletter')
@include('frontend.layouts._footer')

<!-- JQuery Core
=====================================-->
<script src="{{ asset('frontend/assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/core/bootstrap-3.3.7.min.js') }}"></script>

<!-- Magnific Popup
=====================================-->
<script src="{{ asset('frontend/assets/js/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/magnific-popup/magnific-popup-zoom-gallery.js') }}"></script>

<!-- JQuery Main
=====================================-->
<script src="{{ asset('frontend/assets/js/main/jquery.appear.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main/parallax.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main/jquery.sticky.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main/main.js') }}"></script>

<div id="reportModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
    <div style="background:white; width:420px; margin:120px auto; padding:20px; border-radius:10px;">

        <h4><i class="fa fa-flag"></i> Report Content</h4>

        <input type="hidden" id="report_type">
        <input type="hidden" id="report_id">

        <label>Reason:</label>
        <select id="report_reason" class="form-control">
            <option value="spam">Spam or misleading</option>
            <option value="abuse">Hate speech or abuse</option>
            <option value="violence">Violence</option>
            <option value="harassment">Harassment</option>
            <option value="misinformation">Misinformation</option>
            <option value="other">Other</option>
        </select>

        <br>

        <label>Description (optional):</label>
        <textarea id="report_description" class="form-control"></textarea>

        <br>

        <button class="btn btn-danger btn-sm" onclick="submitReport()">Submit</button>
        <button class="btn btn-default btn-sm" onclick="closeReportModal()">Cancel</button>

    </div>
</div>

<div id="report-toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    display: none;
    z-index: 9999;
    font-size: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
">
    ✔ <span id="report-toast-msg"></span>
</div>

</body>
</html>
