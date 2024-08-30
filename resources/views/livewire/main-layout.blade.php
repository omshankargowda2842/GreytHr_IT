<div>
    <div class="topbar">
        <div class="d-flex align-items-center logo-container">

            <label>
                <img id="logo1" class="dash-logo" src="https://xsilica.com/images/xsilica_broucher_final_modified_05082016-2.png" alt="">
                <img id="logo2" class="dash-logo1" src="{{asset('images/it.jpeg')}}" alt="">
            </label>
            <div class="toggle d-flex align-items-center" onclick="toggleLogo()">
                <i class="fas fa-bars" style="color: white;font-size: 23px;"></i>

            </div>

        </div>


        <div class="d-flex align-items-center actions-container">
            <a class="notify" href=""><i class="fa-regular fa-bell"></i></a>
            <h6 class="ml-2 profile">{{ $employeeInitials }}</h6>
            @livewire('logOut')
        </div>
    </div>


    <div class="navigation">
        <ul>

            <li id="dashboard" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="#" wire:click='dashboard'>
                    <span class="icon">
                        <i class="fas fa-home"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li id="itrequest" class="{{ request()->routeIs('requests') ? 'active' : '' }}">
                <a href="#" wire:click='itRequest'>
                    <span class="icon">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="title">IT Requests</span>
                </a>
            </li>

            <li id="itMembers" class="{{ request()->routeIs('itMembers') ? 'active' : '' }}">
                <a href="#" wire:click='itMembers'>
                    <span class="icon">
                        <i class="fas fa-laptop"></i>
                    </span>
                    <span class="title">IT Members</span>
                </a>
            </li>

            <li id="oldRecords" class="{{ request()->routeIs('oldItMembers') ? 'active' : '' }}">
                <a href="#" wire:click='oldRecords'>
                    <span class="icon">
                        <i class="fas fa-laptop"></i>
                    </span>
                    <span class="title">Old IT Members</span>
                </a>
            </li>

            <li id="vendor" class="{{ request()->routeIs('vendor') ? 'active' : '' }}">
                <a href="#" wire:click='vendor'>
                    <span class="icon">
                        <i class="fas fa-store"></i>
                    </span>
                    <span class="title">Vendors</span>
                </a>
            </li>

            <li id="vendorAsset" class="{{ request()->routeIs('vendorAssets') ? 'active' : '' }}">
                <a href="#" wire:click='vendorAssets'>
                    <span class="icon">
                        <i class="fas fa-store"></i>
                    </span>
                    <span class="title">Vendor Assets</span>
                </a>
            </li>


            <li>
                <a href="#">
                    <span class="icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <span class="title">Password</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <span class="icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>
                    <span class="title">Sign Out</span>
                </a>
            </li>



        </ul>
    </div>

    <!-- ========================= Main ==================== -->

</div>



<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.toggle');
    const navigation = document.querySelector('.navigation');
    const main = document.querySelector('.main');

    function updateLayout() {
        if (localStorage.getItem('sidebarActive') === 'true') {
            navigation.classList.add('active');
            main.classList.add('active');
        } else {
            navigation.classList.remove('active');
            main.classList.remove('active');
        }
    }

    toggle.addEventListener('click', () => {
        const isActive = navigation.classList.toggle('active');
        main.classList.toggle('active', isActive);

        // Store the sidebar state in local storage
        localStorage.setItem('sidebarActive', isActive);
    });

    // Initialize layout based on stored state
    updateLayout();

    // Handle navigation item activation
    const navItems = document.querySelectorAll('.navigation ul li');

    // Get current URL path
    const currentPath = window.location.pathname;

    navItems.forEach(item => {
        const link = item.querySelector('a').getAttribute('href');
        if (currentPath.includes(link)) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }

        item.addEventListener('click', function() {
            // Remove active class from all items
            navItems.forEach(navItem => navItem.classList.remove('active'));

            // Add active class to the clicked item
            this.classList.add('active');

            // Store the active item in local storage
            localStorage.setItem('activeNavItem', this.id);
        });
    });

    // Set the initial active item based on local storage
    const activeNavItemId = localStorage.getItem('activeNavItem') || 'dashboard'; // Default to 'dashboard'
    const activeNavItem = document.getElementById(activeNavItemId);
    if (activeNavItem) {
        activeNavItem.classList.add('active');
    }
});
</script>
