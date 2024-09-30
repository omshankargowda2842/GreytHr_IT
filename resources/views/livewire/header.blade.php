<div>
    <div>
        <nav class="navbar navbar-expand-lg bg-dark text-white p-0 ">
            <div class=" logosection">
                <div>
                    <img class="logo" src="{{asset('images/xsilica-logo.png')}}" alt="">
                </div>
                <div class="labels">
                    <label onclick="toggleSidebar()" class="tabs" for="">All</label>
                    <label class="tabs" for="">Favorites</label>
                    <label class="tabs" for="">History</label>
                    <label class="tabs" for="">Workspaces</label>
                    <label class="tabs" for="" wire:click='addMember'>Add Member</label>
                </div>
            </div>
            <div class="profilesection">
                <a class="notify" href=""><i class="fa-regular fa-bell"></i></a>
                <div>
                    <h6 class="profile">{{ $employeeInitials }}</h6>
                </div>

            </div>
            @livewire('logOut')
        </nav>
    </div>

</div>
