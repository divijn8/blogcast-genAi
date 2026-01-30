<!-- Navigation Area
===================================== -->
<style>
.navbar-pasific .navbar-nav > li > a {
    font-weight: 500;
    color: #444;
    padding: 10px 14px;
    border-radius: 999px;
    transition: background 0.2s ease, color 0.2s ease;
}

.navbar-pasific .navbar-nav > li > a:hover {
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.08);
}

.navbar-pasific .navbar-nav > li.active > a {
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
    font-weight: 600;
}

</style>
<nav class="navbar navbar-pasific navbar-mp megamenu navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="/">
                <img src="{{ asset('frontend/assets/img/logo/logo-default.png') }}" alt="logo">
                BlogCast
            </a>
        </div>

        <div class="navbar-collapse collapse navbar-main-collapse">

            <ul class="nav navbar-nav">

                <li class="{{ request()->routeIs('frontend.home', 'frontend.show') ? 'active' : '' }}">
                    <a href="{{ route('frontend.home') }}">Blogs</a>
                </li>

                <li class="{{ request()->routeIs('frontend.podcasts.*') ? 'active' : '' }}">
                    <a href="{{ route('frontend.podcasts.index') }}">Podcasts</a>
                </li>

                @auth
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>

                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}">Login / Register</a>
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
