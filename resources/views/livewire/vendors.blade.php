<div class="main">

    @if($showAddVendor)
    <div class="col-11 mt-4 itadd-maincolumn">
        <!-- <div wire:loading>
            <div class="loader-overlay">
                <div class="loader"></div>
            </div>
        </div> -->
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 addEditHeading">{{ $editMode ? 'Edit Vendor' : 'Add Vendor' }}</h2>
            <button class="btn btn-dark btn-sm" wire:click='cancel'> <i class="fas fa-arrow-left"></i> Back</button>
        </div>


        <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="row mb-3">
                    <!-- Vendor Name -->
                    <div class="col-md-6">
                        <label for="vendorName" class="form-label"><span class="text-danger">*</span> Vendor
                            Name</label>
                        <input type="text" id="vendorName" wire:model.lazy="vendorName" class="form-control">
                        @error('vendorName') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- Contact Name -->
                    <div class="col-md-6">
                        <label for="contactName" class="form-label"><span class="text-danger">*</span>Contact
                            Name</label>
                        <input type="text" id="contactName" wire:model.lazy="contactName" class="form-control">
                        @error('contactName') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Phone -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label"><span class="text-danger">*</span>Phone</label>
                        <input type="text" id="phone" wire:model.lazy="phone" class="form-control">
                        @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- GST -->
                    <div class="col-md-6">
                        <label for="gst" class="form-label"><span class="text-danger">*</span>GST</label>
                        <input type="text" id="gst" wire:model.lazy="gst" class="form-control">
                        @error('gst') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Bank Name -->
                    <div class="col-md-6">
                        <label for="bankName" class="form-label">Bank Name</label>
                        <input type="text" id="bankName" wire:model="bankName" class="form-control">

                    </div>

                    <!-- Account Number -->
                    <div class="col-md-6">
                        <label for="accountNumber" class="form-label">Account Number</label>
                        <input type="text" id="accountNumber" wire:model="accountNumber" class="form-control">

                    </div>
                </div>

                <div class="row mb-3">
                    <!-- IFSC Code -->
                    <div class="col-md-6">
                        <label for="ifscCode" class="form-label">IFSC Code</label>
                        <input type="text" id="ifscCode" wire:model="ifscCode" class="form-control">

                    </div>

                    <!-- Branch -->
                    <div class="col-md-6">
                        <label for="branch" class="form-label">Branch</label>
                        <input type="text" id="branch" wire:model="branch" class="form-control">

                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Contact Email -->
                    <div class="col-md-6">
                        <label for="contactEmail" class="form-label"><span class="text-danger">*</span>Contact
                            Email</label>
                        <input type="email" id="contactEmail" wire:model.lazy="contactEmail" class="form-control">
                        @error('contactEmail') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- Street -->
                    <div class="col-md-6">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" id="street" wire:model="street" class="form-control">

                    </div>
                </div>

                <div class="row mb-3">
                    <!-- City -->
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" wire:model="city" class="form-control">

                    </div>

                    <!-- State -->
                    <div class="col-md-6">
                        <label for="state" class="form-label">State</label>
                        <input type="text" id="state" wire:model="state" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Pin Code -->
                    <div class="col-md-6">
                        <label for="pinCode" class="form-label">Pin Code</label>
                        <input type="text" id="pinCode" wire:model="pinCode" class="form-control">
                    </div>

                    <!-- Note/Description -->
                    <div class="col-md-6">
                        <label for="noteDescription" class="form-label">Note/Description</label>
                        <textarea id="noteDescription" wire:model="noteDescription" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <p class="text-primary"><label for="file">Attachments
                        </label><i class="fas fa-paperclip"></i></p>

                    <input id="file" type="file" wire:model="file_paths" wire:loading.attr="disabled" multiple
                        style="font-size: 12px;" />
                    @error('file_paths.*') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-dark border-white">{{ $editMode ? 'Update' : 'Submit' }}</button>
            </form>
        </div>

    </div>
    @endif


    @if($showEditDeleteVendor)
    <div class="d-flex justify-content-end mt-5">
        <button class="btn btn-dark btn-sm" wire:click='showAddVendorMember' style="margin-right: 9%;padding: 7px;"><i
                class="fas fa-user-plus"></i> Add Vendor</button>
    </div>
    <div class="col-11 mt-4 ml-4">
        <div class="table-responsive it-add-table-res">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="vendor-table-head">Id</th>
                        <th class="vendor-table-head">Vendor Name</th>
                        <th class="vendor-table-head">Contact Name</th>
                        <th class="vendor-table-head">Phone</th>
                        <th class="vendor-table-head">GST</th>
                        <th class="vendor-table-head">Contact Email</th>
                        <th class="vendor-table-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($vendors->count() > 0)
                    @foreach($vendors as $vendor)
                    <tr>
                        <td class="vendor-table-head">{{ $vendor->id }}</td>
                        <td class="vendor-table-head">{{ $vendor->vendor_name }}</td>
                        <td class="vendor-table-head">{{ $vendor->contact_name }}</td>
                        <td class="vendor-table-head">{{ $vendor->phone }}</td>
                        <td class="vendor-table-head">{{ $vendor->gst }}</td>
                        <td class="vendor-table-head">{{ $vendor->contact_email }}</td>
                        <td class="d-flex">
                            <!-- View Action -->
                            <div class="col">
                                <button class="btn btn-white border-dark" wire:click="showViewVendor({{ $vendor->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- Edit Action -->
                            <div class="col">
                                <button class="btn btn-white border-dark"
                                    wire:click="showEditVendor({{ $vendor->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <!-- Delete Action -->
                            <div class="col">
                                <button class="btn btn-dark border-white" wire:click='confirmDelete({{ $vendor->id }})'>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="req-td-norecords">
                            <div>
                                <img src="{{ asset('images/Closed.webp') }}" alt="No Records" class="req-img-norecords">
                                <h3 class="req-head-norecords">No records found</h3>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- View Vendor Details Modal -->
    @if($showViewVendorDialog && $currentVendorId)
    @php
    $vendor = \App\Models\Vendor::find($currentVendorId);
    @endphp
    <div class="col-10 mt-4 itadd-maincolumn">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>View Details</h3>
            </div>
            <button class="btn btn-dark" wire:click="closeViewVendor" aria-label="Close">
                <i class="fas fa-times"></i>
                Close
            </button>
        </div>


        <table class="table table-bordered mt-3 req-pro-table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <div>

                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Vendor Name</td>
                        <td>{{ $vendor->vendor_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Contact Name</td>
                        <td>{{ $vendor->contact_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Phone</td>
                        <td>{{ $vendor->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">GST</td>
                        <td>{{ $vendor->gst ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Contact Email</td>
                        <td>{{ $vendor->contact_email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Bank Name</td>
                        <td>{{ $vendor->bank_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Account Number</td>
                        <td>{{ $vendor->account_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">IFSC Code</td>
                        <td>{{ $vendor->ifsc_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Branch</td>
                        <td>{{ $vendor->branch ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Street</td>
                        <td>{{ $vendor->street ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">City</td>
                        <td>{{ $vendor->city ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">State</td>
                        <td>{{ $vendor->state ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Pin Code</td>
                        <td>{{ $vendor->pin_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Note/Description</td>
                        <td>{{ $vendor->note_description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">Attachments</td>
                        <td>
                            @if (!empty($vendor->file_paths))
                            @php
                            // Check if $vendor->file_paths is a string or an array
                            $fileDataArray = is_string($vendor->file_paths)
                            ? json_decode($vendor->file_paths, true)
                            : $vendor->file_paths;

                            // Separate images and files
                            $images = array_filter(
                            $fileDataArray,
                            fn($fileData) => strpos($fileData['mime_type'], 'image') !== false,
                            );
                            $files = array_filter(
                            $fileDataArray,
                            fn($fileData) => strpos($fileData['mime_type'], 'image') === false,
                            );
                            @endphp


                            {{-- view file popup --}}
                            @if ($showViewImageDialog && $currentVendorId === $vendor->id)
                            <div class="modal custom-modal" tabindex="-1" role="dialog" style="display: block;">
                                <div class="modal-dialog custom-modal-dialog custom-modal-dialog-centered modal-lg"
                                    role="document">
                                    <div class="modal-content custom-modal-content">
                                        <div class="modal-header custom-modal-header">
                                            <h5 class="modal-title view-file">Attached Images</h5>
                                        </div>
                                        <div class="modal-body custom-modal-body">
                                            <div class="swiper-container">
                                                <div class="swiper-wrapper">
                                                    @foreach ($images as $image)
                                                    @php
                                                    $base64File = $image['data'];
                                                    $mimeType = $image['mime_type'];
                                                    @endphp
                                                    <div class="swiper-slide">
                                                        <img src="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                            class="img-fluid" alt="Image">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer custom-modal-footer">
                                            <button type="button" class="submit-btn"
                                                wire:click.prevent="downloadImages({{ $vendor->id }})">Download</button>
                                            <button type="button" class="cancel-btn1"
                                                wire:click="closeViewImage">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif


                            @if ($showViewFileDialog && $currentVendorId === $vendor->id)
                            <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title viewfile">View Files</h5>
                                        </div>
                                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                            <ul class="list-group list-group-flush">

                                                @foreach ($files as $file)

                                                @php

                                                $base64File = $file['data'];

                                                $mimeType = $file['mime_type'];

                                                $originalName = $file['original_name'];

                                                @endphp

                                                <li>

                                                    <a href="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                        download="{{ $originalName }}"
                                                        style="text-decoration: none; color: #007BFF; margin: 10px;">

                                                        {{ $originalName }} <i class="fas fa-download"
                                                            style="margin-left:5px"></i>

                                                    </a>

                                                </li>

                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="cancel-btn1"
                                                wire:click="closeViewFile">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif


                            <!-- Trigger Links -->
                            @if (!empty($images) && count($images) > 1)
                            <a href="#" wire:click.prevent="showViewImage({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF;font-size: 12px; color: #007BFF; text-transform: capitalize;">
                                View Images
                            </a>
                            @elseif(count($images) == 1)
                            <a href="#" wire:click.prevent="showViewImage({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF;font-size: 12px; color: #007BFF; text-transform: capitalize;">
                                View Image
                            </a>
                            @elseif(count($images) != 1)
                            <label for=""> No </label>
                            @endif

                            @if (!empty($files) && count($files) > 1)
                            <a href="#" wire:click.prevent="showViewFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF;font-size: 12px; color: #007BFF; text-transform: capitalize;">
                                View Files
                            </a>
                            @elseif(count($files) == 1)
                            <a href="#" wire:click.prevent="showViewFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF;font-size: 12px; color: #007BFF; text-transform: capitalize;">
                                View File
                            </a>
                            @elseif(count($files) != 1)
                            <label for=""> Attachments</label>

                            @endif



                            @endif

                        </td>

                    </tr>

            </tbody>
        </table>

    </div>


    @endif




    @if ($showLogoutModal)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style=" background-color: black;">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirm Delete</h6>
                </div>
                <div class="modal-body text-center" style="font-size: 16px;color:black">
                    Are you sure you want to delete?
                </div>
                <div class="d-flex justify-content-center p-3">
                    <button type="button" class="submit-btn mr-3" wire:click="delete({{ $vendor->id }})">Delete</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

</div>
