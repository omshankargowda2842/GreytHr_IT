<div class="container-fluid p-0 loginBGGradiant">


    <div class="row m-0">
        <!-- Left Side (Login Form) -->
        <div class="col-md-7 p-0">

            <!-- Carousel -->

            <div id="demo" class="carousel slide" data-bs-ride="carousel"
                style="background-color: f0f0f0; aspect-ratio: 16/9;border-radius:10px">

                <!-- Indicators/dots -->

                <div class="carousel-indicators">

                    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>

                    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>

                    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>

                </div>



                <!-- The slideshow/carousel -->

                <div class="carousel-inner">

                    <div class="carousel-item active d-flex justify-content-center">

                        <img class="mt-4" src="{{ asset('images/itlogin4.PNG') }}" style="width:93%;border-radius: 50%;"
                            alt="Los Angeles" class="d-block">

                        <div class="carousel-caption" style="bottom: 0px; padding-bottom: 0px; color: #007bff;">



                        </div>

                    </div>



                </div>

            </div>

        </div>


        <!-- Right Side (Carousel) -->
        <div class="col-md-5 px-5 py-2 ">
            <div class="text-center mb-4">
            </div>
            @if (Session::has('success'))
            <div style="height: 30px;width:400px;margin-bottom:0px;margin-left:13%" class="text-center mb-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px;">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            @if (session('sessionExpired'))
            <div class="alert alert-danger">

                {{ session('sessionExpired') }}

            </div>
            @endif
            <form wire:submit.prevent="itLogin" class="login-form-with-shadow"
                style="margin-top: 0px;backdrop-filter: blur(36px);">
                <div class="text-center mb-1" style="padding-top: 20px;">
                    <img src="{{ asset('images/IIT.jpeg') }}" alt="Company Logo"
                        style="width: 14em !important; height: auto !important; margin-bottom: 10px;">
                </div>
                <hr class="bg-white" />
                <header _ngcontent-hyf-c110="" class="mb-12 text-center">
                    <div _ngcontent-hyf-c110=""
                        class="text-12gpx font-bold font-title-poppins-bold opacity-90 text-text-default justify-items-center">
                        <span><img src="{{ asset('images/itlogo.jpg') }}" width="70" style="border-radius: 50%;"
                                alt=""></span>
                        Hello there! <span _ngcontent-hyf-c110="" class="font-emoji text-12gpx">👋</span>
                    </div>
                </header><br>
                @if ($error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong style="font-size: 12px;">{{ $error }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="form-group">
                    <input type="text" class="form-control border-0 border-bottom" placeholder="ID / Mail"
                        wire:model="form.emp_id" style="border-radius: 0; box-shadow: none;font-size: 13px;" />
                    @error('form.emp_id')
                    <p class="pt-2 px-1 text-danger" style="font-size: 12px;">
                        {{ str_replace('form.emp id', 'Employee ID', $message) }}</p>
                    @enderror
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <input type="password" class="form-control border-0 border-bottom" placeholder="Password"
                        wire:model="form.password" style="border-radius: 0; box-shadow: none;font-size: 13px;" />

                    @error('form.password')

                    <p class="pt-2 px-1 text-danger" style="font-size: 12px;">
                        {{ str_replace('form.password', 'Password', $message) }}</p>
                    @enderror
                </div>

                <div class="mt-3 mb-3" style="margin-left: 60%; text-align: center;" wire:click="show">
                    <span><a href="#" wire:click="show" style="color: rgb(2, 17, 79);font-size:12px;">Forgot
                            Password?</a></span>
                </div>
                <div class="form-group" style="text-align:center; margin-top:10px;">
                    <input data-bs-toggle="modal" data-bs-target="#loginLoader"
                        style="background-color:black; font-size:small; margin: 0 auto;" type="submit"
                        class="btn btn-primary btn-block" value="Login" />
                </div>
            </form>
        </div>
        <div class="text-center pb-2">
            <small>
                © Xsilica Software Solutions Pvt.Ltd |
                <a href="https://xsilicasoftwaresolutions.greythr.com/v2/static-content/privacy-policy" target="_blank"
                    style="color: rgb(2, 17, 79);">Privacy Policy</a> |
                <a href="https://xsilicasoftwaresolutions.greythr.com/v2/static-content/terms-of-use" target="_blank"
                    style="color: rgb(2, 17, 79);">Terms of Service</a>
            </small>
        </div>

        @if ($showDialog)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:black;display: flex;justify-content: space-between;">
                        <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title">
                            <b>{{ $verified ? 'Reset Password' : 'Forgot Password' }}</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="remove">
                            <span aria-hidden="true" style="color: white;">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                        @if ($verified)
                        <!-- Form for creating a new password -->
                        <form wire:submit.prevent="createNewPassword">
                            <!-- Add input fields for new password and confirmation -->
                            @if ($pass_change_error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong style="font-size: 10px;">{{ $pass_change_error }}</strong>
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" class="form-control"
                                    placeholder="Enter your new password" wire:model.lazy="newPassword"
                                    wire:keydown.debounce.500ms="validateField('newPassword')" style="font-size: 12px;">
                                @error('newPassword')
                                <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="newPassword_confirmation">Confirm New Password</label>
                                <input type="password" id="newPassword_confirmation" name="newPassword_confirmation"
                                    class="form-control" placeholder="Enter your new password"
                                    wire:model.lazy="newPassword_confirmation"
                                    wire:keydown.debounce.500ms="validateField('newPassword_confirmation')" style="font-size: 12px;">
                                @error('newPassword_confirmation')
                                <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-dark mt-2">Reset Password</button>
                            </div>



                            <!-- Success or error message for password update -->
                            @if (session()->has('passwordMessage'))
                            <div class="alert alert-success mt-3">
                                {{ session('passwordMessage') }}
                            </div>
                            @endif
                        </form>
                        @else
                        <!-- Form for verifying email and DOB -->
                        <form wire:submit.prevent="verifyEmailAndDOB">
                            <!-- Add input fields for email and DOB verification -->
                            @if ($verify_error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong style="font-size: 10px;">{{ $verify_error }}</strong>
                                <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control mt-1"
                                    placeholder="Enter your email" wire:model.lazy="email"
                                    wire:keydown.debounce.500ms="validateField('email')" wire:input="forgotPassword" style="font-size: 12px;">
                                @error('email')
                                <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="dob">Date Of Birth</label>
                                <div class="input-group">
                                    <input type="date" id="dob" name="dob" class="form-control mt-1"
                                        max="{{ date('Y-m-d') }}" wire:model.lazy="dob"
                                        wire:keydown.debounce.500ms="validateField('dob')" wire:input="forgotPassword" style="font-size: 12px;">
                                </div>
                                @error('dob')
                                <span class="text-danger"  style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-center mt-2">
                                <button type="submit" class="btn btn-dark">Verify</button>
                            </div>



                            <!-- Success or error message for email and DOB verification -->
                            @if (session()->has('emailDobMessage'))
                            <div class="alert alert-{{ session('emailDobMessageType') }} mt-3">
                                {{ session('emailDobMessage') }}
                            </div>
                            @endif
                        </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif

        @if ($showSuccessModal)
        <!-- Success Message and Password Change Modal -->
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:black;display: flex;justify-content: space-between;">
                        <h5 style="padding: 10px; color: white; font-size: 15px;" class="modal-title">
                            <b>Verification successful!</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:click="closeSuccessModal">
                            <span aria-hidden="true" style="color: white;">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f0f0f0;">
                        <p style="padding: 30px 0px; text-align: center;">Do you want to Reset your password?</p>
                        <div class="d-flex justify-content-center">
                            <button style="margin-bottom: 70px;" type="submit" class="btn btn-dark"
                                wire:click="showPasswordChangeModal">Reset Password</button>
                        </div>

                        <!-- <button type="button" class="btn btn-secondary" wire:click="closeSuccessModal">Cancel</button> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif



        @if ($passwordChangedModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:black;display: flex;justify-content: space-between;">
                        <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title">
                            <b>Success Message</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            wire:click="closePasswordChangedModal">
                            <span aria-hidden="true" style="color: white;">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;display: flex; align-items: center; flex-direction: column;">
                        <p>Password Changes Successfully...</p>
                        <button type="button" class="btn btn-dark d-flex justify-content-center"
                            wire:click="closePasswordChangedModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Button trigger modal -->
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginLoader">
        Launch static backdrop modal
        </button> -->
        @if ($showLoader)
        <!-- Modal -->
        <div class="modal fade backdropModal" id="loginLoader" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="loginLoaderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color : transparent; border : none">
                    <!-- <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginLoaderLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
                    <div class="modal-body">
                        <div class="logo text-center mb-1" style="padding-top: 20px;">
                            <img src="https://xsilica.com/images/xsilica_broucher_final_modified_05082016-2.png"
                                alt="Company Logo" width="200">
                        </div>
                        <div class="d-flex justify-content-center m-4">
                            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div> -->
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
