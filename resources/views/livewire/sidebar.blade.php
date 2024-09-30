<div>
    <div class="main ">
        <div class="main-menus">
            <div class="row " wire:key="row-1">
                <div class="main-menu" wire:click="toggleSubmenu('row-1')">
                    <label for="">Tickets</label>
                    <div class="arrows">
                        @if(isset($showSubmenu['row-1']) && $showSubmenu['row-1'])
                        <a class="fa fa-chevron-down arrow-down "> </a>
                        @else
                        <a class="fa fa-chevron-right arrow-right"> </a>
                        @endif
                    </div>
                </div>
                @if(isset($showSubmenu['row-1']) && $showSubmenu['row-1'])
                <div class="sub-menus ps-4  ">
                    <ul class="sidebar-ul">
                        <li class="sidebar-li">
                            <a class="sub-menu" href="">View Tickets</a>
                        </li>
                        <li class="sidebar-li">
                            <a class="sub-menu" href="">Edit Tickets</a>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>

    </div>

</div>