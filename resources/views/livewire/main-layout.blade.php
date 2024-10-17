<div>
    <div class="sidebar-overlay" data-dismiss="sidebar"></div>
    <div class="sidebar">
        <div class="sidebar-brand-wrapper">
            <a href="#" class="sidebar-brand">
                <img src="{{ asset('images/it-xpert.png') }}" alt="" class="sidebar-brand-image" />
                <span class="sidebar-brand-name">IT XPERT</span>
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
                @if (auth()->user()->isSuperAdmin())
                    <li class="sidebar-menu-item">
                        <a href="#" wire:click='dashboard'
                            class="{{ request()->routeIs('dashboard') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-home-3-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#"
                            class="{{ request()->routeIs('oldItMembers') || request()->routeIs('requests') || request()->routeIs('itMembers') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-group-line"></i></span>
                            <span class="sidebar-menu-item-link-text">IT Requests</span>
                            <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li
                                class="{{ request()->routeIs('requests') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='itRequest' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">IT Request</span>
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
                            class="{{ request()->routeIs('vendor') || request()->routeIs('vendorAssets') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-store-2-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Vendors</span>
                            <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li
                                class="{{ request()->routeIs('vendor') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='vendor' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">Vendor Request</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('vendorAssets') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='vendorAssets' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">Vendor Assets</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#"
                            class="{{ request()->routeIs('assignAsset') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-user-2-line"></i></span>
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
                            class="{{ request()->routeIs('password') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-lock-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Password</span>
                        </a>
                    </li>
                @elseif(auth()->user()->isAdmin())
                    <li class="sidebar-menu-item">
                        <a href="#" wire:click='dashboard'
                            class="{{ request()->routeIs('dashboard') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-home-3-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#"
                            class="{{ request()->routeIs('oldItMembers') || request()->routeIs('requests') || request()->routeIs('itMembers') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-group-line"></i></span>
                            <span class="sidebar-menu-item-link-text">IT Requests</span>
                            <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li
                                class="{{ request()->routeIs('requests') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='itRequest' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">IT Request</span>
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
                            class="{{ request()->routeIs('vendor') || request()->routeIs('vendorAssets') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-store-2-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Vendors</span>
                            <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li
                                class="{{ request()->routeIs('vendor') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='vendor' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">Vendor Request</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('vendorAssets') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='vendorAssets' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">Vendor Assets</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#"
                            class="{{ request()->routeIs('assignAsset') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-user-2-line"></i></span>
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
                            class="{{ request()->routeIs('password') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-lock-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Password</span>
                        </a>
                    </li>
                @else
                    <li class="sidebar-menu-item">
                        <a href="#" wire:click='dashboard'
                            class="{{ request()->routeIs('dashboard') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-home-3-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#"
                            class="{{ request()->routeIs('oldItMembers') || request()->routeIs('requests') || request()->routeIs('itMembers') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-group-line"></i></span>
                            <span class="sidebar-menu-item-link-text">IT Requests</span>
                            <span class="sidebar-menu-item-link-arrow"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li
                                class="{{ request()->routeIs('requests') ? 'active-submenu sidebar-submenu-item' : 'sidebar-submenu-item' }}">
                                <a href="#" wire:click='itRequest' class="sidebar-submenu-item-link">
                                    <span class="sidebar-submenu-item-link-text">IT Request</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#"
                            class="{{ request()->routeIs('assignAsset') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-user-2-line"></i></span>
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
                            class="{{ request()->routeIs('password') ? 'active-menu sidebar-menu-item-link' : 'sidebar-menu-item-link' }}">
                            <span class="sidebar-menu-item-link-icon"><i class="ri-lock-line"></i></span>
                            <span class="sidebar-menu-item-link-text">Password</span>
                        </a>
                    </li>
                @endif
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
                        <span class="sidebar-menu-item-link-text"> {{ ucwords(strtolower($employeeName)) }}</span>
                    </a>
                </li>
                <!-- <li class="sidebar-menu-item">
                        <a href="#" class="sidebar-menu-item-link">
                            <span class="sidebar-menu-item-link-icon">
                                <i class="ri-logout-circle-r-line"></i>
                            </span>
                            <span class="sidebar-menu-item-link-text"
                                >Logout</span
                            >
                        </a>
                    </li> -->
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
                            IT Requests
                            @break
                        @case('itMembers')
                            IT Members
                            @break
                        @case('oldItMembers')
                            Old IT Members
                            @break
                        @case('vendor')
                            Vendor Request
                            @break
                        @case('vendorAssets')
                            Vendor Assets
                            @break
                        @case('assignAsset')
                            Employee Assets
                            @break
                        @case('password')
                            Password
                            @break
                        @default
                            IT Admin
                    @endswitch
                </h5>

            <div
                class="topbar-search-form-wrapper"
                id="topbar-search-form-wrapper">
                <button
                    type="button"
                    class="topbar-button topbar-button-search-form-back"
                    data-dismiss="topbar-search">
                    <i class="ri-arrow-left-line"></i>
                </button>
                <h5 class="mb-0 page-name">
                        @switch(Route::currentRouteName())
                            @case('dashboard')
                                Dashboard
                                @break
                            @case('requests')
                                IT Requests
                                @break
                            @case('itMembers')
                                IT Members
                                @break
                            @case('oldItMembers')
                                Old IT Members
                                @break
                            @case('vendor')
                                Vendor Request
                                @break
                            @case('vendorAssets')
                                Vendor Assets
                                @break
                            @case('assignAsset')
                                Employee Assets
                                @break
                            @case('password')
                                Password
                                @break
                            @default
                                IT Admin
                        @endswitch
                    </h5>
                <!-- <form action="" class="topbar-search-form">
                        <span class="topbar-search-form-icon"
                            ><i class="ri-search-line"></i
                        ></span>
                        <button
                            type="button"
                            class="topbar-search-form-clear"
                            id="topbar-search-clear"
                        >
                            <i class="ri-close-circle-fill"></i>
                        </button>
                        <input
                            type="text"
                            class="topbar-search-form-control"
                            placeholder="Search"
                            id="topbar-search-input"
                        />
                    </form> -->
            </div>
            <div class="topbar-right">
                <!-- <button
                        type="button"
                        class="topbar-button topbar-button-search"
                        data-toggle="topbar-search"
                    >
                        <i class="ri-search-line"></i>
                    </button> -->
                <!-- <div class="dropdown" id="topbar-language-dropdown">
                        <button
                            type="button"
                            class="topbar-button"
                            data-toggle="dropdown"
                        >
                            <img
                                src="https://flagsapi.com/US/shiny/64.png"
                                class="topbar-language-image"
                            />
                        </button>
                        <div class="dropdown-menu">
                            <button
                                type="button"
                                class="dropdown-menu-item"
                                data-language-image="https://flagsapi.com/US/shiny/64.png"
                            >
                                <img
                                    src="https://flagsapi.com/US/shiny/64.png"
                                    alt=""
                                    class="dropdown-menu-item-image"
                                />
                                <span class="dropdown-menu-item-text"
                                    >United State</span
                                >
                            </button>
                            <button
                                type="button"
                                class="dropdown-menu-item"
                                data-language-image="https://flagsapi.com/ES/shiny/64.png"
                            >
                                <img
                                    src="https://flagsapi.com/ES/shiny/64.png"
                                    alt=""
                                    class="dropdown-menu-item-image"
                                />
                                <span class="dropdown-menu-item-text"
                                    >Spanish</span
                                >
                            </button>
                            <button
                                type="button"
                                class="dropdown-menu-item"
                                data-language-image="https://flagsapi.com/ID/shiny/64.png"
                            >
                                <img
                                    src="https://flagsapi.com/ID/shiny/64.png"
                                    alt=""
                                    class="dropdown-menu-item-image"
                                />
                                <span class="dropdown-menu-item-text"
                                    >Indonesia</span
                                >
                            </button>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            type="button"
                            class="topbar-button"
                            data-toggle="dropdown"
                        >
                            <i class="ri-notification-3-line"></i>
                            <span
                                class="topbar-button-total topbar-button-total-notification"
                                >5</span
                            >
                        </button>
                        <div class="dropdown-menu dropdown-menu-notification">
                            <div class="dropdown-menu-header">
                                <p class="dropdown-menu-title">Notifications</p>
                                <span class="badge badge-primary">4 items</span>
                            </div>
                            <div class="dropdown-menu-notification-wrapper">
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon success"
                                    >
                                        <i class="ri-check-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon danger"
                                    >
                                        <i class="ri-alert-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon warning"
                                    >
                                        <i class="ri-alarm-warning-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon success"
                                    >
                                        <i class="ri-check-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon danger"
                                    >
                                        <i class="ri-alert-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon warning"
                                    >
                                        <i class="ri-alarm-warning-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon success"
                                    >
                                        <i class="ri-check-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon danger"
                                    >
                                        <i class="ri-alert-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                                <a
                                    href="#"
                                    class="dropdown-menu-notification-item"
                                >
                                    <span
                                        class="dropdown-menu-notification-item-icon warning"
                                    >
                                        <i class="ri-alarm-warning-line"></i>
                                    </span>
                                    <div
                                        class="dropdown-menu-notification-item-right"
                                    >
                                        <p
                                            class="dropdown-menu-notification-item-text"
                                        >
                                            Lorem ipsum dolor sit amet
                                            consectetur adipisicing.
                                        </p>
                                        <p
                                            class="dropdown-menu-notification-item-time"
                                        >
                                            3 days ago
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> -->
                <div class="d-flex align-items-center actions-container">
                    <a class="notify" href=""><i class="fa-regular fa-bell"></i></a>
                    <!-- <h6 class="ml-2 profile">{{ $employeeInitials }}</h6> -->
                    @livewire('logOut')
                </div>
            </div>
        </div>
    </div>
    <!-- end: Main -->

</div>
