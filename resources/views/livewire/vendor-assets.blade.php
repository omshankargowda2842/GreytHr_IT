<div class="main">

    @if($showAddVendor)
    <div class="col-11 mt-4 itadd-maincolumn">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 addEditHeading">{{ $editMode ? 'Edit Asset' : 'Add Asset' }}</h2>
            <button class="btn btn-dark btn-sm" wire:click='cancel'> <i class="fas fa-arrow-left"></i> Back</button>
        </div>

        <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">

                <div class="col-md-6">
                    <label for="vendor" class="form-label"><span class="text-danger">*</span> Vendor</label>
                    <select id="vendor" wire:model="selectedVendorId" class="form-control">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                    @error('selectedVendorId')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <!-- Manufacturer -->
                    <div class="col-md-6">
                        <label for="manufacturer" class="form-label"><span class="text-danger">*</span>
                            Manufacturer</label>
                        <input type="text" id="manufacturer" wire:model="manufacturer" class="form-control">
                    </div>
                    <!-- Asset Type -->
                    <div class="col-md-6">
                        <label for="assetType" class="form-label"><span class="text-danger">*</span> Asset Type</label>
                        <input type="text" id="assetType" wire:model.lazy="assetType" class="form-control">
                        @error('assetType') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>


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
                        <label for="color" class="form-label"><span class="text-danger">*</span> Color</label>
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
                    </div>


                </div>


                <div class="row mb-3">
                    <!-- GST State -->
                    <div class="col-md-6">
                        <label for="gstState" class="form-label">GST State</label>
                        <input type="text" id="gstState" wire:model="gstState" class="form-control">
                    </div>

                    <!-- GST Central -->
                    <div class="col-md-6">
                        <label for="gstCentral" class="form-label">GST Central</label>
                        <input type="text" id="gstCentral" wire:model="gstCentral" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Taxable Amount -->
                    <div class="col-md-6">
                        <label for="taxableAmount" class="form-label"><span class="text-danger">*</span> Taxable
                            Amount</label>
                        <input type="number" id="taxableAmount" wire:model="taxableAmount" class="form-control">
                    </div>
                    <!-- Invoice Amount -->
                    <div class="col-md-6">
                        <label for="invoiceAmount" class="form-label"><span class="text-danger">*</span> Invoice
                            Amount</label>
                        <input type="number" id="invoiceAmount" wire:model="invoiceAmount" class="form-control">
                    </div>


                </div>


                <div class="row mb-3">
                    <!-- Purchase Date -->
                    <div class="col-md-6">
                        <label for="purchaseDate" class="form-label">Purchase Date</label>
                        <input type="date" id="purchaseDate" wire:model="purchaseDate" class="form-control">
                    </div>
                </div>

                <!-- Attachments -->
                <div class="mb-3">
                    <p class="text-primary"><label for="file">Attachments</label><i class="fas fa-paperclip"></i></p>
                    <input id="file" type="file" wire:model="file_paths" wire:loading.attr="disabled" multiple
                        style="font-size: 12px;" />
                    @error('file_paths.*') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="button" wire:click="submit"
                    class="btn btn-dark border-white">{{ $editMode ? 'Update' : 'Submit' }}</button>
            </form>
        </div>
    </div>
    @endif


    @if($showEditDeleteVendor)
    <div class="d-flex justify-content-end mt-5">
        <button class="btn btn-dark btn-sm" wire:click='showAddVendorMember' style="margin-right: 9%;padding: 7px;"><i
                class="fas fa-box"></i> Add Assets</button>
    </div>
    <div class="col-11 mt-4 ml-4">
        <div class="table-responsive it-add-table-res">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="vendor-table-head">Id</th>
                        <th class="vendor-table-head">Manufacturer</th>
                        <th class="vendor-table-head">Asset Type</th>
                        <th class="vendor-table-head">Asset Model</th>
                        <th class="vendor-table-head">Asset Specification</th>
                        <th class="vendor-table-head">Color</th>
                        <th class="vendor-table-head">Version</th>
                        <th class="vendor-table-head">Serial Number</th>
                        <th class="vendor-table-head">Invoice Number</th>
                        <th class="vendor-table-head">Taxable Amount</th>
                        <th class="vendor-table-head">Invoice Amount</th>
                        <th class="vendor-table-head">GST State</th>
                        <th class="vendor-table-head">GST Central</th>
                        <th class="vendor-table-head">Purchase Date</th>
                        <th class="vendor-table-head">Attachments</th>
                        <th class="vendor-table-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($vendorAssets->count() > 0)
                    @foreach($vendorAssets as $vendorAsset)
                    <tr>
                        <td class="vendor-table-head">{{ $vendorAsset->id }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->manufacturer }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->asset_type }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->asset_model }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->asset_specification }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->color }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->version }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->serial_number }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->invoice_number }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->taxable_amount }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->invoice_amount }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->gst_state }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->gst_central }}</td>
                        <td class="vendor-table-head">{{ $vendorAsset->purchase_date }}</td>
                        <td class="vendor-table-head">
                            @if (!empty($vendorAsset->file_paths))
                            @php
                            // Check if $vendorAsset->file_paths is a string or an array
                            $fileDataArray = is_string($vendorAsset->file_paths)
                            ? json_decode($vendorAsset->file_paths, true)
                            : $vendorAsset->file_paths;

                            // Separate images and files
                            $images = array_filter(
                            $fileDataArray,
                            fn($fileData) => strpos($fileData['mime_type'], 'image') !== false
                            );
                            $files = array_filter(
                            $fileDataArray,
                            fn($fileData) => strpos($fileData['mime_type'], 'image') === false
                            );
                            @endphp

                            {{-- View file popup --}}
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

                            <!-- Trigger Links -->
                            @if (!empty($images) && count($images) > 1)
                            <a href="#" wire:click.prevent="showViewImage({{ $vendorAsset->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; color: #007BFF; text-transform: capitalize;">View
                                Images</a>
                            @elseif(count($images) == 1)
                            <a href="#" wire:click.prevent="showViewImage({{ $vendorAsset->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; color: #007BFF; text-transform: capitalize;">View
                                Image</a>
                            @endif

                            @if (!empty($files) && count($files) > 1)
                            <a href="#" wire:click.prevent="showViewFile({{ $vendorAsset->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; color: #007BFF; text-transform: capitalize;">View
                                Files</a>
                            @elseif(count($files) == 1)
                            <a href="#" wire:click.prevent="showViewFile({{ $vendorAsset->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; color: #007BFF; text-transform: capitalize;">View
                                File</a>
                            @endif
                            @endif
                        </td>
                        <td class="d-flex flex-direction-row">
                            <!-- Edit Action -->
                            <div class="col">
                                <button class="btn btn-white border-dark"
                                    wire:click="showEditAsset({{ $vendorAsset->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <!-- Delete Action -->
                            <div class="col">
                                <button class="btn btn-dark border-white"
                                    wire:click='confirmDelete({{ $vendorAsset->id }})'>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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
                    <button type="button" class="submit-btn mr-3" wire:click="delete({{ $vendorAsset->id }})">Delete</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

</div>
