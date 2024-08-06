<div>
    <div class="logout-icon-container">
        <i wire:click="handleLogout" class="fas fa-sign-out-alt"></i>
        <div wire:click="handleLogout" class="tooltip">Logout</div>
    </div>

    <!-- Logout Modal -->
    @if ($showLogoutModal)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style=" background-color: black;">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirm Logout</h6>
                </div>
                <div class="modal-body text-center" style="font-size: 16px;color:black">
                    Are you sure you want to logout?
                </div>
                <div class="d-flex justify-content-center p-3">
                    <button type="button" class="submit-btn mr-3" wire:click="confirmLogout">Logout</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancelLogout">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif


</div>
