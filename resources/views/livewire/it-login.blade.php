<div class="container-fluid p-0">

    <div wire:loading
        wire:target="itLogin,show,remove,createNewPassword,verifyLoginId,closeSuccessModal,showPasswordChangeModal,closePasswordChangedModal,closePasswordChangedModal">
        <div class="loader-overlay">
            <div>
                <div class="logo">
                    <!-- <i class="fas fa-user-headset"></i> -->
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




    <div class="row m-0 min-vh-100 d-flex align-items-center justify-content-center bg-light">

        {{-- Session and Error Messages --}}
        @if (session('sessionExpired'))
            <div class="alert alert-danger text-center">
                {{ session('sessionExpired') }}
            </div>
        @endif

        <div class="col-12 col-md-6 d-flex flex-column align-items-center justify-content-center p-5">
            <div class="mb-4">
                <img src="{{ asset('images/it_xpert_logo.png') }}" class="itloginImage1 img-fluid" alt="Company Logo">
            </div>

            {{-- Error Message --}}
            @if ($error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong class="itloginFont">{{ $error }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form class="container form" wire:submit.prevent="itLogin" wire:key="empLogin-{{ $resetKey }}"
                style="border-radius: 15px;padding-left: 17%;padding-top:5% ;">
                @csrf
                @if ($error)
                    <div class="alert alert-danger alert-dismissible fade show me-4 ms-3" role="alert">
                        <strong class="itloginFont">{{ $error }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="input-block">
                    <label for="email" style="margin-left: 2%;">ID / Mail</label>
                    <input class="input" type="text" id="email" wire:model="form.emp_id"
                        wire:keydown="resetValidationForField('form.emp_id')">
                    @error('form.emp_id')
                        <p class="pt-2 px-1 text-danger itloginFont">
                            {{ str_replace('form.emp id', 'Employee ID', $message) }}
                        </p>
                    @enderror
                </div>
                <div class="input-block">
                    <label for="pass" style="margin-left: 2%;">Password</label>
                    <div class="input-group" style="align-items: center;">
                        <input class="input" type="{{ $showPassword ? 'text' : 'password' }}" id="pass"
                            wire:model="form.password" wire:keydown="resetValidationForField('form.password')"
                            style="border-right: none; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                        @if ($showEyeIcon)
                            <span style="background-color: #fff; height: 42px; top: 10px; right: 17.5%;"
                                class="input-group-text pointer position-absolute"
                                wire:click="togglePasswordVisibility"><i
                                    class="{{ $showPassword ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></span>
                        @endif
                    </div>
                    @error('form.password')
                        <p class="pt-2 px-1 text-danger itloginFont">
                            {{ str_replace('form.password', 'Password', $message) }}
                        </p>
                    @enderror
                </div>
                <div class="input-block">
                    <span class="forgot ms-3"><a href="#" wire:click="show">Forgot Password?</a></span>
                    <button class="button-login" data-bs-toggle="modal" data-bs-target="#loginLoader"
                        type='submit'>Submit</button>
                </div>
            </form>
        </div>

        {{-- Right Column --}}
        <div class="col-12 col-md-6 d-flex flex-column align-items-center justify-content-center text-center p-5">
            <div class="mt-5">
                <img src="{{ asset('images/Loginlogo1.png') }}" class="img-fluid" width="420" height="50"
                    alt="Company Logo">
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center pt-4">
            <small>
                © Xsilica Software Solutions Pvt.Ltd |
                <a href="/Privacy&Policy" target="_blank" style="color: rgb(2, 17, 79);">Privacy Policy</a> |
                <a href="/Terms&Services" target="_blank" style="color: rgb(2, 17, 79);">Terms of Service</a>
            </small>
        </div>
    </div>

    @if ($showDialog)
        <div class="modal itlogin11" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header itlogin12">
                        <h5 class="itlogin13 mb-0" class="modal-title">
                            <b>{{ $verified ? 'Reset Password' : 'Forgot Password' }}</b>
                        </h5>
                        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                            wire:click="remove" style="background-color: white; height:10px;width:10px;">
                        </button>
                    </div>
                    <div class="modal-body itlogin14">
                        @if ($verified)
                            <form wire:submit.prevent="createNewPassword">
                                @if ($pass_change_error)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong class="itloginFont1">{{ $pass_change_error }}</strong>
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" id="newPassword" name="newPassword"
                                        class="form-control itloginFont" placeholder="Enter your new password"
                                        wire:model.lazy="newPassword"
                                        wire:keydown.debounce.500ms="validateField('newPassword')">
                                    @error('newPassword')
                                        <span class="text-danger itloginFont">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="newPassword_confirmation">Confirm New Password</label>
                                    <input type="password" id="newPassword_confirmation"
                                        name="newPassword_confirmation" class="form-control itloginFont"
                                        placeholder="Enter your new password"
                                        wire:model.lazy="newPassword_confirmation"
                                        wire:keydown.debounce.500ms="validateField('newPassword_confirmation')">
                                    @error('newPassword_confirmation')
                                        <span class="text-danger itloginFont">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-dark mt-2">Reset Password</button>
                                </div>



                                @if (session()->has('passwordMessage'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('passwordMessage') }}
                                    </div>
                                @endif
                            </form>
                        @else
                            <form wire:submit.prevent="verifyLoginId">
                                @if ($verify_error)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong style="font-size: 10px;">{{ $verify_error }}</strong>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="emp_id">IT Employee ID</label>
                                    <input type="text" id="emp_id" name="emp_id" class="form-control"
                                        placeholder="Enter your IT Employee ID" wire:model.lazy="emp_id"
                                        wire:keydown="resetValidationForField('emp_id')" style="font-size: 14px; padding:10px;margin :8px;">
                                    @error('emp_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-center mt-2">
                                    <button type="submit" class="submit-btn" wire:loading.attr="disabled"
                                        wire:loading.class="btn-loading" aria-disabled="true">
                                        <span wire:loading.remove wire:target='verifyLoginId'>Verify</span>
                                        <span wire:loading wire:target='verifyLoginId'>
                                            <i class="fa fa-spinner fa-spin"></i> Verifying...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
    @endif



    @if ($showSuccessModal)
        <div class="modal itlogin15" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header itlogin12">
                        <h5 class="itlogin16" class="modal-title">
                            <b>Verification successful!</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:click="closeSuccessModal">
                            <span aria-hidden="true" class="itloginClose">x</span>
                        </button>
                    </div>
                    <div class="modal-body itlogin17">
                        <p class="itlogin18">Do you want to Reset your password?</p>
                        <div class="d-flex justify-content-center">
                            <button class="itlogin19" type="submit" class="btn btn-dark"
                                wire:click="showPasswordChangeModal">Reset Password</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
    @endif



    @if ($passwordChangedModal)
        <div class="modal itlogin15" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header itlogin12">
                        <h5 class="itlogin13" class="modal-title">
                            <b>Success Message</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:click="closePasswordChangedModal">
                            <span aria-hidden="true" class="itloginClose">x</span>
                        </button>
                    </div>
                    <div class="modal-body itlogin20">
                        <p>Password Changes Successfully...</p>
                        <button type="button" class="btn btn-dark d-flex justify-content-center"
                            wire:click="closePasswordChangedModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($showLoader)
        <div class="modal fade backdropModal" id="loginLoader" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="loginLoaderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content itlogin21">

                    <div class="modal-body">
                        <div class="logo text-center mb-1 itlogin5">
                            <img src="https://xsilica.com/images/xsilica_broucher_final_modified_05082016-2.png"
                                alt="Company Logo" width="200">
                        </div>
                        <div class="d-flex justify-content-center m-4">
                            <div class="spinner-grow text-primary itlogin22" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- @script
        <script>
            $wire.on('inactive-user-alert', (event) => {
                //
                Swal.fire({
                    icon: 'error',
                    title: 'Account Inactive',
                    text: event[0].message || 'No message available',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(result);
                        $wire.$refresh(); // Call $wire.$refresh() to refresh the Livewire component
                    }
                });
            });
        </script>
    @endscript --}}

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('inactive-user-alert', event => {
            // Show SweetAlert with a timer
            Swal.fire({
                icon: 'error',
                title: 'Account Inactive',
                text: event[0].message || 'No message available',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33',
                timer: 5000, // Alert will auto-close after 5 seconds
                timerProgressBar: true, // Show timer progress bar
                willClose: () => {
                    Livewire.dispatch('refreshComponent'); // Call refresh when alert closes
                }
            }).then((result) => {
                // Refresh the component if the user clicks "OK"
                if (result.isConfirmed) {
                    Livewire.dispatch('refreshComponent');
                }
            });
        });
    });
</script>




<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('inactive-user-alert', event => {
            console.log(event.detail
                .message); // This should print the message if the event is triggered
            Swal.fire({
                icon: 'error',
                title: 'Account Inactive',
                text: event.detail.message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        });
    });
</script> -->
