<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ \App\Helpers\RoutingHelper::isDashboardRoute() ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Utilities
    </div>

    <!-- Users -->
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Blogs (with Drafts inside) -->
    <li class="nav-item {{ \App\Helpers\RoutingHelper::isPostRoute() ? 'active' : '' }}">
        <a class="nav-link {{ \App\Helpers\RoutingHelper::isPostRoute() ? '' : 'collapsed' }}"
           href="#"
           data-toggle="collapse"
           data-target="#collapseBlogs">

            <i class="fas fa-fw fa-newspaper"></i>
            <span>Blogs</span>
        </a>

        <div id="collapseBlogs"
             class="collapse {{ \App\Helpers\RoutingHelper::isPostRoute() ? 'show' : '' }}"
             data-parent="#accordionSidebar">

             <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isPostCreate() ? 'active' : '' }}"
                   href="{{ route('admin.posts.create') }}">
                    Create Blog
                </a>

                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isPostDraft() ? 'active' : '' }}"
                   href="{{ route('admin.posts.drafts') }}">
                    Draft Blogs
                </a>

                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isPostIndex() ? 'active' : '' }}"
                   href="{{ route('admin.posts.index') }}">
                    Show All Blogs
                </a>
            </div>
        </div>
    </li>

    <!-- Comments -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-comments"></i>
            <span>Comments</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Categories -->
    <li class="nav-item {{ \App\Helpers\RoutingHelper::isCategoryRoute() ? 'active' : '' }}">
        <a class="nav-link {{ \App\Helpers\RoutingHelper::isCategoryRoute() ? '' : 'collapsed' }}"
           href="#"
           data-toggle="collapse"
           data-target="#collapseCategories">

            <i class="fas fa-fw fa-list"></i>
            <span>Categories</span>
        </a>

        <div id="collapseCategories"
             class="collapse {{ \App\Helpers\RoutingHelper::isCategoryRoute() ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isCategoryCreate() ? 'active' : '' }}"
                href="{{ route('admin.categories.create') }}">
                Create Category
                </a>

                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isCategoryIndex() ? 'active' : '' }}"
                href="{{ route('admin.categories.index') }}">
                    Show All
                </a>
            </div>
        </div>
    </li>

    <!-- Tags -->
    <li class="nav-item {{ \App\Helpers\RoutingHelper::isTagRoute() ? 'active' : '' }}">
        <a class="nav-link {{ \App\Helpers\RoutingHelper::isTagRoute() ? '' : 'collapsed' }}"
           href="#"
           data-toggle="collapse"
           data-target="#collapseTags">

            <i class="fas fa-fw fa-tags"></i>
            <span>Tags</span>
        </a>

        <div id="collapseTags"
             class="collapse {{ \App\Helpers\RoutingHelper::isTagRoute() ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isTagCreate() ? 'active' : '' }}"
                   href="{{ route('admin.tags.create') }}">
                    Create Tag
                </a>

                <a class="collapse-item {{ \App\Helpers\RoutingHelper::isTagIndex() ? 'active' : '' }}"
                   href="{{ route('admin.tags.index') }}">
                    Show All
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
