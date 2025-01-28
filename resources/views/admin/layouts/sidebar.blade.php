<div class="sidebar-header">
    <div>
      <img src="{{ asset('assets/images/group.png') }}" class="logo-icon" alt="logo icon">
    </div>
    <div>
      <!-- <h4 class="logo-text">EES</h4> -->
    </div>
    <div class="toggle-icon ms-auto"> 
      <!-- <i class="bi bi-list"></i> -->
    </div>
  </div>
  <!--navigation-->
  <ul class="metismenu" id="menu">
  
  
    <!-- <li class="menu-label">Pages</li> -->
   
    <li class="{{ request()->routeIs('admin.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.index')}}">
        <div class="parent-icon"><i class="bi-house-door"></i>
        </div>
        <div class="menu-title">Dashboard</div>
      </a>
    </li>
     <li class="{{ request()->routeIs('admin.categories.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.categories.index')}}">
        <div class="parent-icon"><i class="bi bi-bookmarks"></i>
        </div>
        <div class="menu-title">Categories</div>
      </a>
    </li>
    <li class="{{ request()->routeIs('admin.products.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.products.index')}}">
        <div class="parent-icon"><i class="bi-box"></i>
        </div>
        <div class="menu-title">Products</div>
      </a>
    </li>


    
    <li>
      <a href="#">
        <div class="parent-icon"><i class="bi-list"></i>
        </div>
        <div class="menu-title">Order List</div>
      </a>
    </li>
    <li class="{{ request()->routeIs('admin.users.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.users.index')}}">
        <div class="parent-icon"><i class="bi-person"></i>
        </div>
        <div class="menu-title">Users</div>
      </a>
    </li>
    <li class="{{ request()->routeIs('admin.roles.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.roles.index')}}">
        <div class="parent-icon"><i class="bi bi-person-lock"></i>
        </div>
        <div class="menu-title">Roles & permission</div>
      </a>
    </li>
    <li class="{{ request()->routeIs('admin.vendor.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.vendors.index')}}">
        <div class="parent-icon"><i class="bi bi-people"></i>
        </div>
        <div class="menu-title">Vendor</div>
      </a>
    </li>
    
  </ul>
  <!--end navigation-->