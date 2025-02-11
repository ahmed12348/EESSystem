<div class="sidebar-header">
    <div>
      <img src="{{ asset('assets/images/group.png') }}" class="logo-icon" alt="logo icon">
    </div>
    <div>
      {{-- <h4 class="logo-text">EES</h4> --}}
    </div>
    <div class="toggle-icon ms-auto"> 
      {{-- <i class="bi bi-list"></i> --}}
    </div>
  </div>
  <!--navigation-->
  <ul class="metismenu" id="menu">
  
    <li class="{{ request()->routeIs('vendor.vendor_index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.vendor_index')}}">
        <div class="parent-icon"><i class="bi-house-door"></i>
        </div>
        <div class="menu-title">Dashboard</div>
      </a>
    </li>
    
    <li class="{{ request()->routeIs('vendor.products.index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.products.index')}}">
        <div class="parent-icon"><i class="bi-box"></i>
        </div>
        <div class="menu-title">Products</div>
      </a>
    </li>
    
    <li class="{{ request()->routeIs('vendor.orders.index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.orders.index')}}">
        <div class="parent-icon"><i class="bi-list"></i>
        </div>
        <div class="menu-title">Order List</div>
      </a>
    </li>
    
    
  </ul>
  <!--end navigation-->