<div class="sidebar-header">
  <div>
    <img src="{{ asset('assets/images/group.png') }}" class="logo-icon" alt="logo icon">
  </div>
  <div class="toggle-icon ms-auto"></div>
</div>

<!--navigation-->
<ul class="metismenu" id="menu">
  <!-- Dashboard -->
  {{-- @can('dashboard-view') --}}
  <li class="{{ request()->routeIs('vendor.vendor_index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.vendor_index')}}">
          <div class="parent-icon"><i class="bi-house-door"></i></div>
          <div class="menu-title">{{ __('messages.dashboard') }}</div>
      </a>
  </li>
  {{-- @endcan --}}

  <!-- Products -->
  @can('products-view')
  <li class="{{ request()->routeIs('vendor.products.index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.products.index')}}">
          <div class="parent-icon"><i class="bi-box"></i></div>
          <div class="menu-title">{{ __('messages.products') }}</div>
      </a>
  </li>
  @endcan

  <!-- Orders -->
  @can('orders-view')
  <li class="{{ request()->routeIs('vendor.orders.index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.orders.index')}}">
          <div class="parent-icon"><i class="bi-list"></i></div>
          <div class="menu-title">{{ __('messages.orders') }}</div>
      </a>
  </li>
  @endcan

  <!-- Discounts -->
  @can('discounts-view')
  <li class="{{ request()->routeIs('vendor.discounts.index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.discounts.index')}}">
          <div class="parent-icon"><i class="bi bi-disc"></i></div>
          <div class="menu-title">{{ __('messages.discounts') }}</div>
      </a>
  </li>
  @endcan

  @can('ads-view')
  <li class="{{ request()->routeIs('vendor.discounts.index') ? 'mm-active' : '' }}">
      <a href="{{route('vendor.discounts.index')}}">
          <div class="parent-icon"><i class="bi bi-disc"></i></div>
          <div class="menu-title">{{ __('messages.analysis') }}</div>
      </a>
  </li>
  @endcan
</ul>
<!--end navigation-->
