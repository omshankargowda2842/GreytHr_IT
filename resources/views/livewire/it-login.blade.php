<div class="container-fluid p-0 loginBGGradiant">


    <div class="row m-0">
        <!-- Left Side (Login Form) -->
        <div class="col-md-6 px-5 py-2 ">
            <div class="text-center mb-4">
            </div>
            @if (Session::has('success'))
            <div class="text-center mb-4 itlogin3">
                <div class="alert alert-success alert-dismissible fade show itloginFont" role="alert">
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
            <form wire:submit.prevent="itLogin" class="login-form-with-shadow col-md-10 itlogin4">
                <div class="text-center mb-1">
                    <img src="{{ asset('images/IIT.jpeg') }}" class="itloginImage1" alt="Company Logo">
                </div>
                <hr class="bg-white" />
                <header _ngcontent-hyf-c110="" class="mb-12 text-center">
                    <div _ngcontent-hyf-c110=""
                        class="text-12gpx font-bold font-title-poppins-bold opacity-90 text-text-default justify-items-center">
                        <span><img src="{{ asset('images/itlogo.jpg') }}" class="itloginImage2" width="70"
                                alt=""></span>
                       <span class="fs-5"> Hello there!</span> <span _ngcontent-hyf-c110="" class="font-emoji text-12gpx">👋</span>
                    </div>
                </header><br>
                @if ($error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong class="itloginFont">{{ $error }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="form-group">
                    <input type="text" class="form-control border-0 border-bottom itloginInput" placeholder="ID / Mail"
                    wire:model.lazy="form.emp_id"  wire:keydown="resetValidationForField('form.emp_id')"/>
                    @error('form.emp_id')
                    <p class="pt-2 px-1 text-danger itloginFont">
                        {{ str_replace('form.emp id', 'Employee ID', $message) }}</p>
                    @enderror
                </div>

                <div class="form-group itlogin6">
                    <input type="password" class="form-control border-0 border-bottom itloginInput"
                        placeholder="Password" wire:model.lazy="form.password"  wire:keydown="resetValidationForField('form.password')"/>

                    @error('form.password')

                    <p class="pt-2 px-1 text-danger itloginFont">
                        {{ str_replace('form.password', 'Password', $message) }}</p>
                    @enderror
                </div>

                <div class="mt-3 mb-3 itlogin7" wire:click="show">
                    <span><a href="#" wire:click="show" class="itlogin8">Forgot
                            Password?</a></span>
                </div>
                <div class="form-group itlogin9">
                    <input data-bs-toggle="modal" data-bs-target="#loginLoader" type="submit"
                        class="btn btn-dark btn-block itloginInput1" value="Login" />
                </div>
            </form>
        </div>



        <!-- Right Side (Carousel) -->

        <div class="col-md-6 p-0">

            <!-- Carousel -->

            <div id="demo" class="carousel slide itlogin1" data-bs-ride="carousel">

                <!-- Indicators/dots -->

                <div class="carousel-indicators">

                    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>

                    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>

                    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>

                </div>



                <!-- The slideshow/carousel -->

                <div class="carousel-inner">

                    <div class="carousel-item active d-flex justify-content-center">

                        <img class="mt-4 itloginImg" src="{{ asset('images/itlogin4.PNG') }}" alt="Los Angeles"
                            class="d-block">

                        <div class="carousel-caption itlogin2">



                        </div>

                    </div>



                </div>

            </div>

        </div>
        <div class="text-center pb-2 mt-5">
            <small>
                © Xsilica Software Solutions Pvt.Ltd |
                <a href="https://xsilicasoftwaresolutions.greythr.com/v2/static-content/privacy-policy"
                    class="itlogin10" target="_blank"> <span class="fs-6"> Privacy Policy</span></a> |
                <a href="https://xsilicasoftwaresolutions.greythr.com/v2/static-content/terms-of-use" class="itlogin10"
                    target="_blank"><span class="fs-6">Terms of Service</span> </a>
            </small>
        </div>

        @if ($showDialog)
        <div class="modal itlogin11" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header itlogin12">
                        <h5 class="itlogin13" class="modal-title">
                            <b>{{ $verified ? 'Reset Password' : 'Forgot Password' }}</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="remove">
                            <span aria-hidden="true" class="itloginClose">x</span>
                        </button>
                    </div>
                    <div class="modal-body itlogin14">
                        @if ($verified)
                        <!-- Form for creating a new password -->
                        <form wire:submit.prevent="createNewPassword">
                            <!-- Add input fields for new password and confirmation -->
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
                                <input type="password" id="newPassword_confirmation" name="newPassword_confirmation"
                                    class="form-control itloginFont" placeholder="Enter your new password"
                                    wire:model.lazy="newPassword_confirmation"
                                    wire:keydown.debounce.500ms="validateField('newPassword_confirmation')">
                                @error('newPassword_confirmation')
                                <span class="text-danger itloginFont">{{ $message }}</span>
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
                                <strong class="itloginFont1">{{ $verify_error }}</strong>
                                <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control mt-1 itloginFont"
                                    placeholder="Enter your email" wire:model.lazy="email"
                                    wire:keydown.debounce.500ms="validateField('email')" wire:input="forgotPassword">
                                @error('email')
                                <span class="text-danger itloginFont">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="dob">Date Of Birth</label>
                                <div class="input-group">
                                    <input type="date" id="dob" name="dob" class="form-control mt-1 itloginFont"
                                        max="{{ date('Y-m-d') }}" wire:model.lazy="dob"
                                        wire:keydown.debounce.500ms="validateField('dob')" wire:input="forgotPassword">
                                </div>
                                @error('dob')
                                <span class="text-danger itloginFont">{{ $message }}</span>
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

                        <!-- <button type="button" class="btn btn-secondary" wire:click="closeSuccessModal">Cancel</button> -->
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

        <!-- Button trigger modal -->
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginLoader">
        Launch static backdrop modal
        </button> -->
        @if ($showLoader)
        <!-- Modal -->
        <div class="modal fade backdropModal" id="loginLoader" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="loginLoaderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content itlogin21">
                    <!-- <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginLoaderLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
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
