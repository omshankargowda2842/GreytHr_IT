<div class="main">

    <div wire:loading
        wire:target="cancel,submit,showAddVendorMember ,pinCode,clearFilters ,showViewVendor,showViewImage,showViewFile,showEditVendor,closeViewVendor,downloadImages,closeViewImage,closeViewFile,confirmDelete ,cancelLogout,">
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


    @if($showAddVendor)

    <div class="col-11 d-flex justify-content-start mb-1 mt-4" style="margin-left: 5%;">
        <button class="btn text-white btn-sm" style="background-color: #02114f;" wire:click='cancel'> <i
                class="fas fa-arrow-left"></i> Back</button>

    </div>
    <div class="col-11 mt-4 view-details-modal">

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 addEditHeading">{{ $editMode ? 'Edit Vendor' : 'Add Vendor' }}</h2>
        </div>


        <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">

            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="row mb-3">
                    <!-- Vendor Name -->
                    <div class="col-md-6">
                        <div class="row">


                            <div class="col-4">
                                <label for="vendorName" class="vendor-label"><span class="text-danger">*</span> Vendor
                                    Name</label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="vendorName" wire:model.lazy="vendorName"
                                    wire:keydown="resetValidationForField('vendorName')" class="form-control">
                                @error('vendorName') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                        </div>

                    </div>

                    <!-- Contact Name -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="contactName" class="vendor-label"><span class="text-danger">*</span>Contact
                                    Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="contactName" wire:model.lazy="contactName"
                                    wire:keydown="resetValidationForField('contactName')" class="form-control">
                                @error('contactName') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="phone" class="vendor-label"><span class="text-danger">*</span>Phone</label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="phone" wire:model.lazy="phone"
                                    wire:keydown="resetValidationForField('phone')" maxlength="10"
                                    oninput="formatPhoneNumber(this)" class="form-control">
                                @if($phoneError) <div class="text-danger">{{ $phoneError }}</div> @endif

                                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>

                    <!-- GST -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="gst" class="vendor-label"><span class="text-danger">*</span>GSTIN</label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="gst" wire:model.lazy="gst"
                                    wire:keydown="resetValidationForField('gst')" class="form-control">
                                @error('gst') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>


                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Bank Name -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="bankName" class="vendor-label">Bank Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="bankName" wire:model="bankName" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Account Number -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="accountNumber" class="vendor-label">Account Number</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="accountNumber" wire:model.lazy="accountNumber"
                                    wire:keydown="resetValidationForField('accountNumber')" maxlength="19"
                                    oninput="formatAccountNumber(this)" class="form-control">
                                @error('accountNumber') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- IFSC Code -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="ifscCode" class="vendor-label">IFSC Code</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="ifscCode" wire:model="ifscCode" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Branch -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="branch" class="vendor-label">Branch</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="branch" wire:model="branch" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Contact Email -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="contactEmail" class="vendor-label"><span class="text-danger">*</span>Contact
                                    Email</label>
                            </div>
                            <div class="col-8">
                                <input type="email" id="contactEmail" wire:model.lazy="contactEmail"
                                    wire:keydown="resetValidationForField('contactEmail')" class="form-control">
                                @error('contactEmail') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pin Code -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="pinCode" class="vendor-label">Pin Code</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="pinCode" wire:model.lazy="pinCode" maxlength="6"
                                    class="form-control">
                                @error('pinCode') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Street -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="street" class="vendor-label">Town/Mandal</label>
                            </div>
                            <div class="col-8">
                                <select id="street" wire:model="street" class="input-div-vendor">
                                    <option disabled hidden>Select Town/Mandal</option>
                                    @foreach($postOffices as $office)
                                    <option value="{{ $office['name'] }} - {{ $office['mandal'] }}"
                                        @if($street===$office['name'] . ' - ' . $office['mandal']) selected @endif>
                                        {{ $office['name'] }} - {{ $office['mandal'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="city" class="vendor-label">District</label>
                            </div>
                            <div class="col-8">
                                <div id="city" class="input-div-vendor" wire:model="city" readonly>
                                    {{ $city }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- State -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="state" class="vendor-label">State</label>
                            </div>
                            <div class="col-8">
                                <div id="state" class="input-div-vendor" wire:model="state" readonly>
                                    {{ $state }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Note/Description -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="noteDescription" class="vendor-label">Description</label>
                            </div>
                            <div class="col-8">
                                <textarea id="noteDescription" wire:model="noteDescription"
                                    class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <p class="text-primary"><label for="file" class="vendor-label">Attachments</label> <i
                                        class="fas fa-paperclip"></i>
                                </p>
                            </div>
                            <div class="col-8">
                                <input id="file" type="file" wire:model="file_paths" wire:loading.attr="disabled"
                                    multiple style="font-size: 12px;" />
                                <div wire:loading wire:target="file_paths" class="mt-2">
                                    <i class="fas fa-spinner fa-spin"></i> Uploading...
                                </div>
                                @error('file_paths.*') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn text-white border-white"
                        style="background-color: #02114f;">{{ $editMode ? 'Update' : 'Submit' }}</button>
                </div>
            </form>
        </div>

    </div>
    @endif


    @if($showEditDeleteVendor)

    @if($searchFilters)


    <div class="row mb-3 mt-4 ml-4 employeeAssetList">
        <!-- Align items to the same row with space between -->
        <div class="col-11 col-md-11 mb-2 mb-md-0">
            <div class="row d-flex justify-content-between">
                <!-- Employee ID Search Input -->
                <div class="col-4">
                    <div class="input-group task-input-group-container">
                        <input type="text" class="form-control" placeholder="Search..." wire:model="searchVendor"
                            wire:input="filter">
                    </div>
                </div>

                <!-- Add Member Button aligned to the right -->
                <div class="col-auto">
                    <button class="btn text-white btn-sm" wire:click='showAddVendorMember'
                        style="margin-right: 9%;padding: 7px;background-color: #02114f;white-space:nowrap;"><i
                            class="fas fa-user-plus"></i> Add
                        Vendor</button>
                </div>
            </div>
        </div>
    </div>

    @endif

    <div class="col-11 mt-4 ml-4">
        <div class="table-responsive it-add-table-res">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th scope="col" class="vendor-table-head">S.No</th>
                        <th scope="col" class="vendor-table-head">Vendor ID
                            <span wire:click="toggleSortOrder('vendor_id')" style="cursor: pointer;">
                                @if($sortColumn == 'vendor_id')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>

                        <th class="vendor-table-head">Vendor Name
                            <span wire:click="toggleSortOrder('vendor_name')" style="cursor: pointer;">
                                @if($sortColumn == 'vendor_name')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>

                        <th class="vendor-table-head">Contact Name
                            <span wire:click="toggleSortOrder('contact_name')" style="cursor: pointer;">
                                @if($sortColumn == 'contact_name')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>

                        <th class="vendor-table-head">GSTIN
                            <span wire:click="toggleSortOrder('gst')" style="cursor: pointer;">
                                @if($sortColumn == 'gst')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>

                        <th class="vendor-table-head">Contact Email
                            <span wire:click="toggleSortOrder('contact_email')" style="cursor: pointer;">
                                @if($sortColumn == 'contact_email')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>
                        <th class="vendor-table-head d-flex justify-content-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($vendors->count() > 0)
                    @foreach($vendors as $vendor)
                    <tr>
                        <td class="vendor-table-head">{{ $loop->iteration }}</td>
                        <td class="vendor-table-head">{{ $vendor->vendor_id ?? 'N/A'}}</td>
                        <td class="vendor-table-head">{{  ucwords(strtolower($vendor->vendor_name)) ?? 'N/A' }}</td>
                        <td class="vendor-table-head">{{  ucwords(strtolower($vendor->contact_name)) ?? 'N/A'}}</td>
                        <td class="vendor-table-head">{{ $vendor->gst ?? 'N/A'}}</td>
                        <td class="vendor-table-head">{{ $vendor->contact_email ?? 'N/A'}}</td>
                        <td class="d-flex">
                            <!-- View Action -->
                            <div class="col mx-1">
                                <button class="btn btn-white border-dark"
                                    wire:click="showViewVendor({{ $vendor->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- Edit Action -->
                            <div class="col mx-1">
                                <button class="btn btn-white border-dark"
                                    wire:click="showEditVendor({{ $vendor->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <!-- Delete Action -->
                            @if(auth()->check() && (auth()->user()->hasRole('admin') ||
                            auth()->user()->hasRole('super_admin')))

                            <div class="col mx-1">
                                <button class="btn text-white border-white" style="background-color: #02114f;"
                                    wire:click='confirmDelete({{ $vendor->id }})'>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7">
                            <div class="req-td-norecords">
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
    <div class="col-10 mt-4 view-details-modal">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>View Details</h5>
            </div>
            <button class="btn text-white" style="background-color: #02114f;" wire:click="closeViewVendor"
                aria-label="Close">

                Close
            </button>
        </div>


        <table class="table table-bordered mt-3 req-pro-table">

            <tbody>


                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Vendor ID</td>
                    <td class="view-td">{{ $vendor->vendor_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Vendor Name</td>
                    <td class="view-td">{{ ucwords(strtolower($vendor->vendor_name)) ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Contact Name</td>
                    <td class="view-td">{{ ucwords(strtolower($vendor->contact_name)) ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Phone</td>
                    <td class="view-td">{{ $vendor->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">GSTIN</td>
                    <td class="view-td">{{ $vendor->gst ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Contact Email</td>
                    <td class="view-td">{{ $vendor->contact_email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Bank Name</td>
                    <td class="view-td">{{ $vendor->bank_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Account Number</td>
                    <td class="view-td">{{ $vendor->account_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">IFSC Code</td>
                    <td class="view-td">{{ $vendor->ifsc_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Branch</td>
                    <td class="view-td">{{ $vendor->branch ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">City/Village</td>
                    <td class="view-td">{{ $vendor->street ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">District</td>
                    <td class="view-td">{{ $vendor->city ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">State</td>
                    <td class="view-td">{{ $vendor->state ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Pin Code</td>
                    <td class="view-td">{{ $vendor->pin_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Note/Description</td>
                    <td class="view-td">{{ $vendor->description ?? 'N/A' }}</td>
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
                        foreach ($fileDataArray as $fileData) {
                        if (isset($fileData['mime_type'])) {
                        if (strpos($fileData['mime_type'], 'image') !== false) {
                        $images[] = $fileData;
                        } else {
                        $files[] = $fileData;
                        }
                        }
                        }

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

                        @php
                        // Initialize $images and $files as empty arrays to avoid null issues
                        $images = $images ?? [];
                        $files = $files ?? [];
                        @endphp
                        <!-- Trigger Links -->
                        @if (count($images) > 1)
                        <a href="#" wire:click.prevent="showViewImage({{ $vendor->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View Images
                        </a>
                        @elseif (count($images) == 1)
                        <a href="#" wire:click.prevent="showViewImage({{ $vendor->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View Image
                        </a>
                        @endif

                        @if (count($files) > 1)
                        <a href="#" wire:click.prevent="showViewFile({{ $vendor->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View Files
                        </a>
                        @elseif (count($files) == 1)
                        <a href="#" wire:click.prevent="showViewFile({{ $vendor->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View File
                        </a>
                        @endif

                        @if (count($images) == 0 && count($files) == 0)
                        <label for="">No Attachments</label>
                        @endif


                        @endif

                    </td>

                </tr>

            </tbody>
        </table>

    </div>


    @endif




    @if ($showLogoutModal)
    <div class="modal logout1" id="logoutModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white logout2">
                    <h6 class="modal-title logout3" id="logoutModalLabel">Confirm Delete</h6>
                </div>
                <div class="modal-body text-center logout4">
                    Are you sure you want to delete?
                </div>
                <div class="modal-body text-center">
                    <form wire:submit.prevent="delete">
                        <span class="text-danger d-flex align-start">*</span>
                        <div class="row">
                            <div class="col-12 req-remarks-div">

                                <textarea wire:model.lazy="reason" class="form-control req-remarks-textarea logout5"
                                    placeholder="Reason for deactivation"></textarea>

                            </div>
                        </div>
                        @error('reason') <span class="text-danger d-flex align-start">{{ $message }}</span>@enderror
                        <div class="d-flex justify-content-center p-3">
                            <button type="submit" class="submit-btn mr-3"
                                wire:click="confirmDelete({{ $vendor->id }})">Delete</button>
                            <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

</div>


<script>
function formatAccountNumber(input) {
    // Remove all non-digit characters
    let value = input.value.replace(/\D/g, '');

    // Add spaces every 4 characters
    value = value.replace(/(.{4})/g, '$1 ').trim();

    // Set the formatted value back to the input field
    input.value = value;
}

function formatPinCode(input) {
    // Allow only digits and limit to 6 characters
    input.value = input.value.replace(/\D/g, '').substring(0, 6);
}

function formatPhoneNumber(input) {
    // Allow only digits and limit to 10 characters
    input.value = input.value.replace(/\D/g, '').substring(0, 10);
}
</script>
