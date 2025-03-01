<!-- Navigation Area
===================================== -->
<nav class="navbar navbar-pasific navbar-mp megamenu navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">
                <img src="{{ asset('frontend/assets/img/logo/logo-default.png') }}" alt="logo">
                Pen-It
            </a>
        </div>

        <div class="navbar-collapse collapse navbar-main-collapse">
            <ul class="nav navbar-nav d-flex align-items-center">
                <li class="nav-item">
                    <a href="index.html" data-toggle="dropdown" class="dropdown-toggle color-light">Home </a>
                </li>
                @auth
                    <li class="nav-item mt-36">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger btn-sm">LOGOUT</button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}">LOGIN</a>
                    </li>
                @endauth

            </ul>

        </div>
    </div>
</nav>
