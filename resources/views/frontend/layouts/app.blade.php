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

<!-- MODERN REPORT MODAL -->
<div id="reportModal" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index:9999;
    align-items:center;
    justify-content:center;
">

    <div style="
        background:white;
        width:420px;
        border-radius:12px;
        padding:20px;
        box-shadow:0 10px 30px rgba(0,0,0,0.2);
        animation:fadeIn 0.25s ease;
    ">

        <!-- HEADER -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h4 style="margin:0; font-weight:600;">Report Content</h4>
            <button onclick="closeReportModal()" style="
                border:none;
                background:none;
                font-size:20px;
                cursor:pointer;
            ">×</button>
        </div>

        <!-- OPTIONS -->
        <div style="margin-bottom:15px;">
            <label style="font-size:13px; color:#666;">Reason</label>

            <select id="report_reason" class="form-control" style="
                border-radius:8px;
                margin-top:5px;
                padding:8px;
            ">
                <option value="spam">Spam</option>
                <option value="abuse">Abusive Content</option>
                <option value="violence">Violence</option>
                <option value="misinformation">Misinformation</option>
                <option value="other">Other</option>
            </select>
        </div>

        <!-- DESCRIPTION -->
        <div style="margin-bottom:20px;">
            <label style="font-size:13px; color:#666;">Additional details (optional)</label>

            <textarea id="report_description"
                class="form-control"
                rows="3"
                placeholder="Tell us more..."
                style="border-radius:8px; margin-top:5px;"></textarea>
        </div>

        <!-- ACTIONS -->
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button onclick="closeReportModal()" class="btn btn-light">
                Cancel
            </button>

            <button onclick="submitReport()" class="btn btn-danger">
                Submit Report
            </button>
        </div>

        <input type="hidden" id="report_type">
        <input type="hidden" id="report_id">
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
<style>
@keyframes fadeIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>
</body>
</html>
