        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">BlogCast</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ \App\Helpers\RoutingHelper::isDashboardRoute() ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Utilities
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ \App\Helpers\RoutingHelper::isCategoryRoute() ? 'active' : '' }}">
                <a class="nav-link {{ \App\Helpers\RoutingHelper::isCategoryRoute() ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseCategory"
                aria-expanded="true" aria-controls="collapseCategory">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Category</span>
                </a>
                <div id="collapseCategory" class="collapse {{ \App\Helpers\RoutingHelper::isCategoryRoute() ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Category Operations:</h6>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isCategoryIndex() ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Show All</a>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isCategoryCreate() ? 'active' : '' }}" href="{{ route('admin.categories.create') }}">Create Category</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ \App\Helpers\RoutingHelper::isTagRoute() ? 'active' : '' }}">
                <a class="nav-link {{ \App\Helpers\RoutingHelper::isTagRoute() ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseTag"
                aria-expanded="true" aria-controls="collapseTag">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Tag</span>
                </a>
                <div id="collapseTag" class="collapse {{ \App\Helpers\RoutingHelper::isTagRoute() ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tag Operations:</h6>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isTagIndex() ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">Show All</a>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isTagCreate() ? 'active' : '' }}" href="{{ route('admin.tags.create') }}">Create Tag</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ \App\Helpers\RoutingHelper::isPostRoute() ? 'active' : '' }}">
                <a class="nav-link {{ \App\Helpers\RoutingHelper::isPostRoute() ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapsePost"
                aria-expanded="true" aria-controls="collapsePost">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Blog</span>
                </a>
                <div id="collapsePost" class="collapse {{ \App\Helpers\RoutingHelper::isPostRoute() ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Blogs Operations:</h6>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isPostIndex() ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">Show All</a>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isPostCreate() ? 'active' : '' }}" href="{{ route('admin.posts.create') }}">Create Blogs</a>
                        <a class="collapse-item {{ \App\Helpers\RoutingHelper::isPostDraft() ? 'active' : '' }}" href="{{ route('admin.posts.draft') }}">Drafts</a>
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
