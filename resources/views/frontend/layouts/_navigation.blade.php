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
                BlogCast
            </a>
        </div>

        <div class="navbar-collapse collapse navbar-main-collapse">

            <ul class="nav navbar-nav">
                <li>
                    <a href="/" data-toggle="dropdown" class="dropdown-toggle color-light">Home </a>
                </li>

                @auth
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            LOGOUT
                        </a>
                    </li>

                @else

                    <li>
                        <a href="{{ route('login') }}">LOGIN / REGISTER</a>
                    </li>
                @endauth
            </ul>

            {{-- Hidden Logout Form --}}
            @auth
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth

        </div>
    </div>
</nav>
