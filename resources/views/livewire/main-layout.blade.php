<div>

    <div wire:loading
        wire:target="dashboard,itRequest,itMembers,oldRecords,vendor,vendorAssets,assets,assignAsset,toggleNotifications">
        <div class="loader-overlay">
            <div>
                <div class="logo">

                    <img src="{{ asset('images/Screenshot 2024-10-15 120204.png') }}" width="58" height="50"
                        alt="">&nbsp;
                    <span>IT</span>&nbsp;&nbsp;
                    <span>EXPERT</span>
                </div>
            </div>
            <div class="loader-bouncing">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>


    <div class="sidebar-overlay" data-dismiss="sidebar"></div>
    <div class="sidebar">
        <div class="sidebar-brand-wrapper">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <img src="{{ asset('images/it-xpert.png') }}" alt="" class="sidebar-brand-image" />
                <span class="sidebar-brand-name">IT Expert</span>
            </a>
        </div>
        <div class="sidebar-menu-wrapper">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item-title">
                    <span class="sidebar-menu-item-title-expanded">Menu</span>
                    <span class="sidebar-menu-item-title-collapsed">
                        <i class="ri-more-fill"></i>
                    </span>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#" wire:click='dashboard'
                        class="{{ request()->routeIs('dashboard') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                        <span class="sidebar-menu-item-link-icon"><i class="ri-home-3-line"></i></span>
                        <span class="sidebar-menu-item-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#"
                        class="{{ request()->routeIs('oldItMembers') || request()->routeIs('requests') || request()->routeIs('incident') || request()->routeIs('service') || request()->routeIs('itMembers') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                        <span class="sidebar-menu-item-link-icon"><i class="ri-group-line"></i></span>
                        <span class="sidebar-menu-item-link-text">IT</span>
                        <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li
                            class="{{ request()->routeIs('requests') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='itRequest' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Catalog Requests</span>
                            </a>
                        </li>

                        <li
                            class="{{ request()->routeIs('incidentRequests') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='incRequest' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Incident Requests</span>
                            </a>
                        </li>

                        <li
                            class="{{ request()->routeIs('serviceRequests') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='serRequest' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Service Requests</span>
                            </a>
                        </li>

                        <li
                            class="{{ request()->routeIs('itMembers') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='itMembers' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">IT Members</span>
                            </a>
                        </li>


                        <li
                            class="{{ request()->routeIs('oldItMembers') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='oldRecords' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Old IT Members</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a href="#"
                        class="{{ request()->routeIs('assignAsset') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                        <span class="sidebar-menu-item-link-icon"><i class="ri-store-2-line"></i></span>
                        <span class="sidebar-menu-item-link-text">Employee</span>
                        <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li
                            class="{{ request()->routeIs('assignAsset') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='assignAsset' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Employee Assets</span>
                            </a>
                        </li>

                    </ul>
                </li>



                <li class="sidebar-menu-item">
                    <a href="#"
                        class="{{ request()->routeIs('vendors') || request()->routeIs('vendorAssets') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                        <span class="sidebar-menu-item-link-icon"><i class="ri-store-2-line"></i></span>
                        <span class="sidebar-menu-item-link-text">Vendors</span>
                        <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li
                            class="{{ request()->routeIs('vendors') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='vendor' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Vendor Management</span>
                            </a>
                        </li>
                        <li
                            class="{{ request()->routeIs('vendorAssets') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='vendorAssets' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Vendor Assets</span>
                            </a>
                        </li>
                        <li
                            class="{{ request()->routeIs('assets') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                            <a href="#" wire:click='assets' class="sidebar-submenu-item-link">
                                <span class="sidebar-submenu-item-link-text">Assets</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="sidebar-menu-item">
                    <a href="#"
                        class="{{ request()->routeIs('password') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                        <span class="sidebar-menu-item-link-icon"><i class="ri-lock-line"></i></span>
                        <span class="sidebar-menu-item-link-text">Password</span>
                    </a>
                </li> -->
            </ul>

            <ul class="sidebar-menu">
                <li class="sidebar-menu-item-title">
                    <span class="sidebar-menu-item-title-expanded">Account</span>
                    <span class="sidebar-menu-item-title-collapsed">
                        <i class="ri-more-fill"></i>
                    </span>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-item-link" style="padding-left: 12px;">
                        <span class="sidebar-menu-item-link-icon">
                            <h6 class="m-0 profile">{{ $employeeInitials }}</h6>
                        </span>
                        <span><strong>{{ ucwords(strtolower($employeeName)) }}</strong></span>
                    </a>
                </li>
            </ul>

        </div>
    </div>

    <!-- start: Main -->
    <div class="main">
        <div class="topbar" id="customNav">
            <a href="#" class="topbar-brand">
                <img src="{{ asset('images/it-xpert.png') }}" alt="" class="topbar-brand-image" />
            </a>
            <button type="button" class="topbar-button topbar-toggle" data-toggle="sidebar">
                <i class="ri-menu-line"></i>
            </button>

            <h5 class="mb-0 page-name topbar-page-name">
                @switch(Route::currentRouteName())
                @case('dashboard')
                Dashboard
                @break

                @case('requests')
                Catalog Requests
                @break

                @case('incidentRequests')
                Incident Requests
                @break

                @case('serviceRequests')
                Service Requests
                @break

                @case('itMembers')
                IT Members
                @break

                @case('oldItMembers')
                Old IT Members
                @break

                @case('assignAsset')
                Employee Assets
                @break

                @case('vendors')
                Vendor Management
                @break

                @case('vendorAssets')
                Vendor Assets
                @break

                @case('assetsList')
                Assets List
                @break

                @case('password')
                Password
                @break

                @case('employeeAssetList')
                Employee Assets
                @break

                @default
                IT Admin
                @endswitch
            </h5>

            <div class="topbar-search-form-wrapper" id="topbar-search-form-wrapper">
                <button type="button" class="topbar-button topbar-button-search-form-back" data-dismiss="topbar-search">
                    <i class="ri-arrow-left-line"></i>
                </button>
                <h5 class="mb-0 page-name">
                    @switch(Route::currentRouteName())
                    @case('dashboard')
                    Dashboard
                    @break

                    @case('requests')
                    Catalog Requests
                    @break

                    @case('incidentRequests')
                    Incident Requests
                    @break

                    @case('serviceRequests')
                    Service Requests
                    @break

                    @case('itMembers')
                    IT Members
                    @break

                    @case('oldItMembers')
                    Old IT Members
                    @break

                    @case('assignAsset')
                    Employee Assets
                    @break

                    @case('vendors')
                    Vendor Management
                    @break

                    @case('vendorAssets')
                    Vendor Assets
                    @break
                    @case('assetsList')
                    Assets List
                    @break

                    @case('password')
                    Password
                    @break

                    @case('employeeAssetList')
                    Employee Assets
                    @break

                    @default
                    IT Admin
                    @endswitch
                </h5>
            </div>
            <div class="topbar-right">
                <div class="d-flex align-items-center actions-container">
                    <!-- Notification Icon with Unread Count -->
                    <a class="notify position-relative" href="#" wire:click="toggleNotifications">
                        <i class="fa-regular fa-bell" style="font-size: 23px;"></i>
                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            {{ $unreadCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                        @endif
                    </a>




                    <!-- Notification Sidebar -->
                    <div class="notification-sidebar {{ $isVisible ? 'show' : '' }}">
                        <div class="sidebar-header">
                            <h6 style="color:#778899">
                                Notifications
                                <span style="color: black;">( {{ $unreadCount }} )</span>
                            </h6>

                            <button class="btn-close notification-close-btn"
                                wire:click="toggleNotifications">&times;</button>
                        </div>
                        <hr class="mb-3 mt-0">
                        <div class="sidebar-body">
                            @livewire('all-tickets-notifications')
                            <!-- Include the notifications component here -->
                        </div>
                    </div>

                    @livewire('logOut')
                </div>
            </div>





        </div>
    </div>
    <!-- end: Main -->

</div>
