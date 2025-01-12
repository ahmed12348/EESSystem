<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/images/PDC Logo En-Hor.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-list"></i></div>
    </div>

    <!-- Navigation -->

    <ul class="metismenu" id="menu">

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-house-fill"></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
            @can('dashboard_1')
                <ul>
                    <li><a href="{{ route('dashboard.index') }}"><i class="bi bi-circle"></i> Non </a></li>
       
            </ul>
           @endcan
    </li>
    <li class="menu-label">Users And Roles</li>

    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bi bi-people-fill"></i></div>
            <div class="menu-title">Users</div>
        </a>
        <ul>
            <li><a href="{{ route('dashboard.users.index') }}"><i class="bi bi-circle"></i> Users Management</a></li>
          
            <li><a href="{{ route('dashboard.roles.index') }}"><i class="bi bi-circle"></i> Role Management</a></li>
        </ul>
    </li>

    
    </ul>
</aside>
