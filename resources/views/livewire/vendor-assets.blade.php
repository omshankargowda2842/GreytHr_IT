<div class="main">

    @if($showAddVendor)

    <div class="col-11 d-flex justify-content-start mb-1 mt-4" style="margin-left: 5%;">
    <button class="btn btn-dark btn-sm" wire:click='cancel'> <i class="fas fa-arrow-left"></i> Back</button>

    </div>
    <div class="col-11 mt-4 itadd-maincolumn">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 addEditHeading">{{ $editMode ? 'Edit Asset' : 'Add Asset' }}</h2>
        </div>

        <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="vendor" class="form-label"><span class="text-danger">*</span> Vendor</label>
                        <select id="vendor" wire:model="selectedVendorId" class="vendor-selected-vendorID">
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                            @endforeach
                        </select>
                        @error('selectedVendorId')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($editMode == false)
                    <div class="col-md-6">
                        <label for="quantity" class="form-label"><span class="text-danger">*</span> Quantity</label>
                        <input type="number" id="quantity" wire:model="quantity" class="form-control" min="1" />
                        @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                </div>

                <div class="row mb-3">
                    <!-- Manufacturer -->
                    <div class="col-md-6">
                        <label for="manufacturer" class="form-label"><span class="text-danger">*</span>
                            Manufacturer</label>
                        <input type="text" id="manufacturer" wire:model="manufacturer" class="form-control">
                        @error('manufacturer')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Asset Type -->
                    <div class="col-md-6">
                        <label for="assetType" class="form-label"><span class="text-danger">*</span> Asset Type</label>
                        <div class="input-group">
                            <select wire:model="assetType" id="assetType" class="vendor-selected-AssetType">
                                <option value="">Select Asset Type</option>
                                @foreach($assetNames as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->asset_names }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-dark" wire:click="showModal">Add</button>
                        </div>
                        @error('assetType') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- Modal -->

                    @if ($isModalOpen)
                    <div class="modal fade show" style="display: block;" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Asset Type</h5>
                                    <!-- Close modal on clicking the button -->
                                    <button type="button" class="btn-close" wire:click="closeModal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="mb-3 col-10">
                                            <label for="assetName" class="form-label">Asset Name</label>
                                            <input type="text" class="form-control" id="assetName"
                                                wire:model="newAssetName">
                                            @error('newAssetName')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Row for centering the button -->
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-center" style="height: fit-content;margin-top: 25px;">
                                                <button wire:click="createAssetType"
                                                    class="btn btn-dark">Create</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backdrop -->
                    <div class="modal-backdrop fade show"></div>
                    @endif




                </div>

                <div class="row mb-3">
                    <!-- Asset Model -->
                    <div class="col-md-6">
                        <label for="assetModel" class="form-label"><span class="text-danger">*</span> Asset
                            Model</label>
                        <input type="text" id="assetModel" wire:model.lazy="assetModel" class="form-control">
                        @error('assetModel') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- Asset Specification -->
                    <div class="col-md-6">
                        <label for="assetSpecification" class="form-label"><span class="text-danger">*</span> Asset
                            Specification</label>
                        <input type="text" id="assetSpecification" wire:model.lazy="assetSpecification"
                            class="form-control">
                        @error('assetSpecification') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Color -->
                    <div class="col-md-6">
                        <label for="color" class="form-label"> Color</label>
                        <input type="text" id="color" wire:model="color" class="form-control">
                    </div>

                    <!-- Version -->
                    <div class="col-md-6">
                        <label for="version" class="form-label">Version</label>
                        <input type="text" id="version" wire:model="version" class="form-control">
                    </div>

                </div>

                <div class="row mb-3">

                    <!-- Serial Number -->
                    <div class="col-md-6">
                        <label for="serialNumber" class="form-label"><span class="text-danger">*</span> Serial
                            Number</label>
                        <input type="text" id="serialNumber" wire:model.lazy="serialNumber" class="form-control">
                        @error('serialNumber') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- Invoice Number -->
                    <div class="col-md-6">
                        <label for="invoiceNumber" class="form-label"><span class="text-danger">*</span> Invoice
                            Number</label>
                        <input type="text" id="invoiceNumber" wire:model="invoiceNumber" class="form-control">
                        @error('invoiceNumber') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>


                </div>


                <div class="row mb-3">
                    <!-- GST State -->
                    <div class="col-md-6">
                        <label for="gstState" class="form-label"><span class="text-danger">*</span>GST State</label>
                        <input type="text" id="gstState" wire:model="gstState" class="form-control">
                        @error('gstState') <div class="text-danger">{{ $message }}</div> @enderror

                    </div>

                    <!-- GST Central -->
                    <div class="col-md-6">
                        <label for="gstCentral" class="form-label"><span class="text-danger">*</span>GST Central</label>
                        <input type="text" id="gstCentral" wire:model="gstCentral" class="form-control">
                        @error('gstCentral') <div class="text-danger">{{ $message }}</div> @enderror

                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Taxable Amount -->
                    <div class="col-md-6">
                        <label for="taxableAmount" class="form-label"><span class="text-danger">*</span> Taxable
                            Amount</label>
                        <input type="number" id="taxableAmount" wire:model="taxableAmount" class="form-control">
                        @error('taxableAmount') <div class="text-danger">{{ $message }}</div> @enderror

                    </div>
                    <!-- Invoice Amount -->
                    <div class="col-md-6">
                        <label for="invoiceAmount" class="form-label"><span class="text-danger">*</span> Invoice
                            Amount</label>
                        <input type="number" id="invoiceAmount" wire:model="invoiceAmount" class="form-control">
                        @error('invoiceAmount') <div class="text-danger">{{ $message }}</div> @enderror

                    </div>


                </div>


                <div class="row mb-3">
                    <!-- Purchase Date -->
                    <div class="col-md-6">
                        <label for="purchaseDate" class="form-label"><span class="text-danger">*</span>Purchase
                            Date</label>
                        <input type="date" id="purchaseDate" wire:model="purchaseDate" class="form-control">
                        @error('purchaseDate') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">

                        @if($barcode)
                        <h6>Generated Barcode:</h6>
                        <img src="{{ asset('storage/' . $barcode) }}" alt="Barcode">
                        @endif

                    </div>
                </div>

                <!-- Attachments -->
                <div class="mb-3">
                    <p class="text-primary"><label for="file">Attachments</label><i class="fas fa-paperclip"></i></p>
                    <input id="file" type="file" wire:model="file_paths" wire:loading.attr="disabled" multiple
                        style="font-size: 12px;" />
                    @error('file_paths.*') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex justify-content-center">
                <button type="button" wire:click="submit"
                    class="btn btn-dark border-white">{{ $editMode ? 'Update' : 'Submit' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    @if($showEditDeleteVendor)
    <div class="d-flex justify-content-end mt-5">
        <button class="btn btn-dark btn-sm" wire:click='showAddVendorMember' style="margin-right: 9%; padding: 7px;">
            <i class="fas fa-box"></i> Add Asset
        </button>
    </div>

    <div class="col-11 mt-4 ml-4">
        <div class="table-responsive it-add-table-res">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                    <th scope="col" class="vendor-table-head">S.No</th>
                        <th class="vendor-table-head">Vendor ID </th>
                        <th class="vendor-table-head">Asset ID </th>
                        <th class="vendor-table-head">Manufacturer</th>
                        <th class="vendor-table-head">Asset Type</th>
                        <th class="vendor-table-head">Invoice Number</th>
                        <th class="vendor-table-head">Status</th>
                        <th class="vendor-table-head">Barcode Image</th>
                        <th class="vendor-table-head">Actions</th>
                    </tr>
                </thead>
                <tbody>


                    @if($vendorAssets->count() > 0)
                    @foreach($vendorAssets as $vendorAsset)
                    <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                        <td class="vendor-table-head">{{ $vendorAsset->vendor_id }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->asset_id}}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->manufacturer }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset['asset_type_name'] }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->invoice_number }}</td>


                        <td class="vendor-table-head">
                            @if ($vendorAsset->is_active == 1)
                            <span class="text-primary f-3"> Active</span>
                            @else
                            <span class="text-danger f-3"> Inactive</span>
                            @endif
                        </td>

                        <td class="vendor-table-head">
                            @if($vendorAsset->barcode)
                            <!-- Text "View Barcode" without any action -->
                            <span>View Barcode</span>

                            <!-- Eye icon to trigger the modal -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#barcodeModal{{ $vendorAsset->id }}"
                                class="ms-2">
                                <i class="fas fa-eye "></i>
                            </a>

                            <!-- Modal for image preview -->
                            <div class="modal fade" id="barcodeModal{{ $vendorAsset->id }}" tabindex="-1"
                                aria-labelledby="barcodeModalLabel{{ $vendorAsset->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="barcodeModalLabel{{ $vendorAsset->id }}">Barcode
                                                Preview</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $vendorAsset->barcode) }}" alt="Barcode"
                                                style="max-width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            No Barcode
                            @endif
                        </td>




                        <td class="d-flex flex-direction-row">

                            <!-- View Action -->
                            <div class="col">
                                <button
                                    class="btn btn-white border-dark"
                                    wire:click="showViewVendor({{ $vendorAsset->id }})"
                                   >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            @if($vendorAsset->is_active == 1)
                            <div class="col">

                                <button
                                    class="btn btn-white border-dark"
                                    wire:click="showEditAsset({{ $vendorAsset->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>

                            </div>
                            @endif

                            @if($vendorAsset->is_active == 1)
                            <div class="col">
                                <!-- Delete Button (Inactive if is_active is 0) -->
                                <button
                                    class="btn btn-dark border-white {{ $vendorAsset->is_active == 0 ? 'disabled' : '' }}"
                                    wire:click="confirmDelete({{ $vendorAsset->id }})"
                                    {{ $vendorAsset->is_active == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif

                @if($vendorAsset->is_active == 0)
                            <div class="col">
                                <!-- Restore Button (Inactive if is_active is 1) -->
                                <button
                                    class="btn btn-dark border-white {{ $vendorAsset->is_active == 1 ? 'disabled' : '' }}"
                                    wire:click="cancelLogout({{ $vendorAsset->id }})"
                                    {{ $vendorAsset->is_active == 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                    @endif

                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="20" class="req-td-norecords">
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
    $vendorAsset = \App\Models\VendorAsset::find($currentVendorId);
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


                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Id</td>
                    <td>{{ $vendorAsset->id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Manufacturer</td>
                    <td>{{ $vendorAsset->manufacturer ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Asset Type</td>
                    <td>{{ $vendorAsset->asset_type ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Asset Model</td>
                    <td>{{ $vendorAsset->asset_model ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Asset Specification</td>
                    <td>{{ $vendorAsset->asset_specification ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Color</td>
                    <td>{{ $vendorAsset->color ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Version</td>
                    <td>{{ $vendorAsset->version ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Serial Number</td>
                    <td>{{ $vendorAsset->serial_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Invoice Number</td>
                    <td>{{ $vendorAsset->invoice_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Taxable Amount</td>
                    <td>{{ $vendorAsset->taxable_amount ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Invoice Amount</td>
                    <td>{{ $vendorAsset->invoice_amount ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">GST State</td>
                    <td>{{ $vendorAsset->gst_state ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">GST Central</td>
                    <td>{{ $vendorAsset->gst_central ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Purchase Date</td>
                    <td>{{ \Carbon\Carbon::parse($vendorAsset->purchase_date)->format('d-M-Y') }}</td>
                </tr>
                <tr>
                    <td class="fs-6 fs-md-3 fs-lg-2">Attachments</td>
                    <td>
                        @if (!empty($vendorAsset->file_paths))
                        @php
                        // Check if $vendor->file_paths is a string or an array
                        $fileDataArray = is_string($vendorAsset->file_paths)
                        ? json_decode($vendorAsset->file_paths, true)
                        : $vendorAsset->file_paths;

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
                        @if ($showViewImageDialog && $currentVendorId === $vendorAsset->id)
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
                                            wire:click.prevent="downloadImages({{ $vendorAsset->id }})">Download</button>
                                        <button type="button" class="cancel-btn1"
                                            wire:click="closeViewImage">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                        @endif


                        @if ($showViewFileDialog && $currentVendorId === $vendorAsset->id)
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
                        <a href="#" wire:click.prevent="showViewImage({{ $vendorAsset->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View Images
                        </a>
                        @elseif (count($images) == 1)
                        <a href="#" wire:click.prevent="showViewImage({{ $vendorAsset->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View Image
                        </a>
                        @endif

                        @if (count($files) > 1)
                        <a href="#" wire:click.prevent="showViewFile({{ $vendorAsset->id }})"
                            style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                            View Files
                        </a>
                        @elseif (count($files) == 1)
                        <a href="#" wire:click.prevent="showViewFile({{ $vendorAsset->id }})"
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
    <div class="modal"  style="display: block;" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white"  style="background-color: black;">
                    <h6 class="modal-title" style=" align-items: center;" id="logoutModalLabel">Confirm Delete</h6>
                </div>
                <div class="modal-body text-center" style=" font-size: 16px;color:black;">
                    Are you sure you want to delete?
                </div>
                <div class="modal-body text-center">
                    <form wire:submit.prevent="delete">
                        <span class="text-danger d-flex align-start">*</span>
                        <div class="row">
                            <div class="col-12 req-remarks-div">

                                <textarea wire:model.lazy="reason" class="form-control req-remarks-textarea" style=" min-height: 76px;"
                                    placeholder="Reason for deactivation"></textarea>

                            </div>
                        </div>
                        @error('reason') <span class="text-danger d-flex align-start">{{ $message }}</span>@enderror
                        <div class="d-flex justify-content-center p-3">
                            <button type="submit" class="submit-btn mr-3">Delete</button>
                            <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- <div class="d-flex justify-content-center p-3">
                    <button type="button" class="submit-btn mr-3" wire:click="delete({{ $vendorAsset->id }})">Delete</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div> -->
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif


    @if ($restoreModal)
    <div class="modal logout1" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white logout2">
                    <h6 class="modal-title logout3" id="logoutModalLabel">Confirm Restore</h6>
                </div>
                <div class="modal-body text-center logout4">
                    Are you sure you want to Restore?
                </div>

                <div class="d-flex justify-content-center p-3">
                    <button type="button" class="submit-btn mr-3"
                        wire:click="restore({{ $vendorAssetIdToRestore }})">Restore</button>

                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

</div>
