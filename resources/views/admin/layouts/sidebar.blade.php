<div class="sidebar-header">
  <div>
    <img src="{{ asset('assets/images/group.png') }}" class="logo-icon" alt="logo icon">
  </div>
  <div class="toggle-icon ms-auto"></div>
</div>

<!--navigation-->
<ul class="metismenu" id="menu">
  <!-- Dashboard -->
  <li class="{{ request()->routeIs('admin.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.index')}}">
          <div class="parent-icon"><i class="bi-house-door"></i></div>
          <div class="menu-title">{{ __('messages.dashboard') }}</div>
      </a>
  </li>

  <!-- Products -->
  @can('products-view')
  <li class="{{ request()->routeIs('admin.products.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.products.index')}}">
          <div class="parent-icon"><i class="bi-box"></i></div>
          <div class="menu-title">{{ __('messages.products') }}</div>
      </a>
  </li>
  @endcan

  <!-- Orders -->
  @can('orders-view')
  <li class="{{ request()->routeIs('admin.orders.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.orders.index')}}">
          <div class="parent-icon"><i class="bi-list"></i></div>
          <div class="menu-title">{{ __('messages.orders') }}</div>
      </a>
  </li>
  @endcan

  <!-- Customers -->
  @can('customers-view')
  <li class="{{ request()->routeIs('admin.customers.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.customers.index')}}">
          <div class="parent-icon"><i class="bi bi-person-plus"></i></div>
          <div class="menu-title">{{ __('messages.customer') }}</div>
      </a>
  </li>
  @endcan

  <!-- Users -->
  @can('users-view')
  <li class="{{ request()->routeIs('admin.users.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.users.index')}}">
          <div class="parent-icon"><i class="bi-person"></i></div>
          <div class="menu-title">{{ __('messages.users') }}</div>
      </a>
  </li>
  @endcan

  <!-- Roles -->
  @can('roles-view')
  <li class="{{ request()->routeIs('admin.roles.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.roles.index')}}">
          <div class="parent-icon"><i class="bi bi-person-lock"></i></div>
          <div class="menu-title">{{ __('messages.roles') }}</div>
      </a>
  </li>
  @endcan

  <!-- Vendors -->
  @can('vendors-view')
  <li class="{{ request()->routeIs('admin.vendors.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.vendors.index')}}">
          <div class="parent-icon"><i class="bi bi-people"></i></div>
          <div class="menu-title">{{ __('messages.vendor') }}</div>
      </a>
  </li>
  @endcan

  <!-- Cart Settings -->
  @can('settings-view')
  <li class="{{ request()->routeIs('admin.settings.cart') ? 'mm-active' : '' }}">
      <a href="{{route('admin.settings.cart')}}">
          <div class="parent-icon"><i class="bi bi-gear"></i></div>
          <div class="menu-title">{{ __('messages.cart_settings') }}</div>
      </a>
  </li>
  @endcan
  @can('ads-view')
  <li class="{{ request()->routeIs('admin.ads.index') ? 'mm-active' : '' }}">
    <a href="{{route('admin.ads.index')}}">
      <div class="parent-icon"><i class="bi bi-badge-ad"></i>
      </div>
      <div class="menu-title">ADS</div>
    </a>
  </li>
  @endcan
  
  <!-- Discounts -->
  @can('discounts-view')
  <li class="{{ request()->routeIs('admin.discounts.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.discounts.index')}}">
          <div class="parent-icon"><i class="bi bi-disc"></i></div>
          <div class="menu-title">{{ __('messages.offers') }}</div>
      </a>
  </li>
  @endcan

  <!-- Categories -->
  @can('categories-view')
  <li class="{{ request()->routeIs('admin.categories.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.categories.index')}}">
          <div class="parent-icon"><i class="bi bi-bookmarks"></i></div>
          <div class="menu-title">{{ __('messages.categories') }}</div>
      </a>
  </li>
  @endcan

  <!-- Carts -->
  @can('carts-view')
  <li class="{{ request()->routeIs('admin.carts.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.carts.index')}}">
          <div class="parent-icon"><i class="bi bi-0-circle"></i></div>
          <div class="menu-title">{{ __('messages.re_addition_item') }}</div>
      </a>
  </li>
  @endcan

  <!-- Searches -->
  @can('searches-view')
  <li class="{{ request()->routeIs('admin.search.index') ? 'mm-active' : '' }}">
      <a href="{{route('admin.search.index')}}">
          <div class="parent-icon"><i class="bi bi-0-circle"></i></div>
          <div class="menu-title">{{ __('messages.search_sales') }}</div>
      </a>
  </li>
  @endcan
</ul>
<!--end navigation-->