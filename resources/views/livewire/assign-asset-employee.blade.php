<div class="main d-flex" style=" align-items: center; justify-content:center; flex-direction:column">

    <div wire:loading
        wire:target="cancel,backVendor,oldAssetlisting,toggleOverview,assignAsset,viewDetails,edit,selectedAsset,closeViewEmpAsset,viewOldAssetDetails,selectedEmployee,submit,createAssetType,showAddVendorMember,delete,clearFilters ,showEditAsset ,showViewVendor,showViewImage,showViewFile,showEditVendor,closeViewVendor,showViewImageDialog,showViewEmpImageDialog,showViewEmpImage,showViewEmpFile,closeViewEmpImage,closeViewEmpFile,downloadImages,closeViewImage,closeViewFile,confirmDelete ,cancelLogout,restore">
        <div class="loader-overlay">
            <div>
                <div class="logo">

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


    <div class="container AssetEmployee mt-4 d-flex"
        style=" align-items: center; justify-content:center; flex-direction:column">

        @if ($showEMployeeAssetBtn)
        <div class="col-11 d-flex justify-content-between mb-4" style="margin-left: 4%;">
            <!-- Button aligned to the left -->
            <div class="col-md-5 d-flex justify-content-start">
                <button class="btn text-white" style="background-color: #02114f;font-size:13px;"
                    wire:click="backVendor">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>

            <!-- Category Dropdown aligned to the right -->
            <div class="col-md-5 d-flex justify-content-end">
                <div class="d-flex flex-column align-items-end">
                    <label for="categorySelect" class="form-label" style="margin-right: 31%;">Select Category</label>
                    <select id="categorySelect" class="form-select" wire:model="selectedCategory"
                        wire:change="loadAssets">
                        <option value="" disabled hidden>Choose Category</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->asset_names }}
                        </option>
                        @endforeach
                    </select>
                    @error('selectedCategory')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        </div>

        @endif

        @if($assetEmpCreateUpdate)

        <div class="col-11 assetAddPage mb-3">
            <!-- Row for Dropdowns -->
            <div class="row mb-3 d-flex justify-content-around">

                <!-- Employee ID Dropdown -->
                <div class="col-md-5">
                    <label for="employeeSelect" class="form-label">Select Employee ID</label>
                    <select id="employeeSelect" class="form-select" wire:model="selectedEmployee"
                        wire:change="fetchEmployeeDetails" {{ $isUpdateMode ? 'disabled' : '' }}>
                        <option value="">Choose Employee</option>
                        @foreach ($assetSelectEmp as $employee)
                        <option value="{{ $employee->emp_id}}" class=""
                            {{ $isUpdateMode && $employee->emp_id == $selectedEmployee ? 'selected' : '' }}>
                            {{ $employee->emp_id }} - {{ ucwords(strtolower($employee->first_name ))}}
                            {{ ucwords(strtolower($employee->last_name ))}}
                        </option>
                        @endforeach

                    </select>


                    @error('selectedEmployee')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Asset Dropdown -->
                <div class="col-md-5">
                    <label for="assetSelect" class="form-label">Select Asset</label>
                    <select id="assetSelect" class="form-select" wire:model="selectedAsset"
                        wire:change="fetchAssetDetails" {{ $isUpdateMode ? 'disabled' : '' }}>
                        <option value="">Choose Asset</option>
                        @foreach ($assetSelect as $asset)
                        <option value="{{ $asset->asset_id }}"
                            class="{{ in_array($asset->asset_id, $assignedAssetIds) ? 'inactive-option' : 'active-option' }}"
                            {{ (in_array($asset->asset_id, $assignedAssetIds) && $asset->asset_id !== $selectedAsset) ? 'disabled' : '' }}
                            {{ $isUpdateMode && $asset->asset_id == $selectedAsset ? 'selected' : '' }}>
                            {{ $asset->asset_id }} - {{ $asset->asset_names }}
                        </option>
                        @endforeach
                    </select>
                    @error('selectedAsset')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="row">
                <div class="col-md-7" style="margin-left: 4%;">
                    <div class="row mt-2">
                        <div class="col">
                            <label for="fileInput"
                                style="color:#778899; font-weight:500; font-size:12px; cursor:pointer;">
                                <i class="fa fa-paperclip"></i> Attach Files
                            </label>
                        </div>
                        @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <input id="file_paths" type="file" wire:model="file_paths" multiple />

                    </div>


                    <!-- File Preview Modal -->
                    @if($showFilePreviewModal)

                    <div class="modal fade show d-block" tabindex="-1" role="dialog"
                        style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog modal-dialog-centered  modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="filePreviewModalLabel">File
                                        Preview</h5>
                                    <button type="button" class="btn-close" wire:click="hideFilePreviewModal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex flex-column align-items-center">
                                        <h6>Selected Files</h6>
                                        <div class="d-flex flex-wrap gap-3">
                                            <!-- Loop through files and display previews -->
                                            @foreach ($previews as $index => $preview)
                                            <div class="file-preview-container text-center"
                                                style="padding: 5px; border: 1px solid black; width: 120px; height: 120px; border-radius: 5px; position: relative; overflow: hidden;">
                                                @if ($preview['type'] == 'image')
                                                <img src="{{ $preview['url'] }}" alt="Preview" class="img-thumbnail"
                                                    style="width: 75px; height: 75px;" />
                                                <span class="mt-1 uploaded-file-name"
                                                    style="display: block; width: 100%;">{{ $preview['name'] }}</span>
                                                @else
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-file fa-3x" style="width: 75px; height: 75px;"></i>
                                                    <span class="mt-1 uploaded-file-name"
                                                        style="display: block; width: 100%;">{{ $preview['name'] }}</span>
                                                </div>
                                                @endif

                                                <!-- Delete icon -->
                                                <button type="button" class="delete-icon btn btn-danger"
                                                    wire:click="removeFile({{ $index }})"
                                                    style="position: absolute; top: 5%; right: 5%; z-index: 5; font-size: 12px;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="hideFilePreviewModal">Proceed</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    @endif
                </div>
            </div>

            <!-- Row for Details Cards -->
            <div class="row mt-4 d-flex justify-content-around">
                <div class="col-md-5">
                    @if ($empDetails)
                    <div class="assetEmpDetailsCard p-3 mb-3">
                        <h5><u>Employee Details</u></h5>
                        <p><strong>Employee ID:</strong> {{ $empDetails->emp_id }}</p>
                        <p><strong>Employee Name:</strong> {{ $empDetails->first_name }} {{ $empDetails->last_name }}
                        </p>
                        <p><strong>Email:</strong> {{ $empDetails->email }}</p>
                        <p><strong>Department:</strong> {{ $empDetails->job_role }}</p>
                        <p><strong>Job Mode:</strong> {{ $empDetails->job_mode }}</p>
                    </div>
                    @endif

                </div>

                <div class="col-md-5">
                    @if ($assetDetails)
                    <div class="assetEmpDetailsCard p-3 mb-3">
                        <h5><u>Asset Details</u></h5>
                        <p><strong>Manufacturer:</strong> <span>{{ $assetDetails->manufacturer }}</span></p>
                        <p><strong>Asset Type:</strong> {{ $assetDetails->asset_type_name }}</p>
                        <p><strong>Asset Model:</strong> {{ $assetDetails->asset_model }}</p>
                        <p><strong>Serial Number:</strong> {{ $assetDetails->serial_number }}</p>
                        <p><strong>Specifications:</strong> {{ $assetDetails->asset_specification }}</p>
                    </div>
                    @endif
                </div>
                
            </div>


            <div class="mt-4" style="margin-left: 4%;">
                <div>
                    <button class="btn text-white d-flex align-items-center" style="background-color: #02114f;"
                        wire:click="toggleOverview">
                        System Updates
                        <!-- <i wire:click="toggleOverview" class="fas fa-caret-down req-pro-dropdown-arrow" style="margin-left: auto; cursor: pointer;"></i> -->
                        <i
                            class="fas fa-caret-down req-pro-dropdown-arrow {{ $showOverview ? 'rotated' : '' }} req-overview-icon"></i>

                    </button>
                </div>

            </div>

            <!-- Form for System Update Fields -->
            @if($showSystemUpdateForm)
            <div class="p-4 border mt-3">
                <h5 class="system-details-head">System Update Details</h5>


                <div class="row" style="margin-bottom: 30px;">
                    <div class="form-group col-md-4 mb-3">
                        <label>Sophos Antivirus:</label>
                        <div class="input-group">
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="sophosAntivirus" value="Yes"
                                    id="sophosYes" name="sophosAntivirus">
                                <label class="form-check-label mb-0" for="sophosYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="sophosAntivirus" value="No"
                                    id="sophosNo" name="sophosAntivirus">
                                <label class="form-check-label mb-0" for="sophosNo">No</label>
                            </div>
                        </div>

                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label>VPN Creation:</label>
                        <div class="input-group">
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="vpnCreation" value="Yes"
                                    id="vpnYes" name="vpnCreation">
                                <label class="form-check-label mb-0" for="vpnYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="vpnCreation" value="No"
                                    id="vpnNo" name="vpnCreation">
                                <label class="form-check-label mb-0" for="vpnNo">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group col-md-4">
                        <label>Teramind:</label>
                        <div class="input-group">
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="teramind" value="Yes"
                                    id="teramindYes" name="teramind">
                                <label class="form-check-label mb-0" for="teramindYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="teramind" value="No"
                                    id="teramindNo" name="teramind">
                                <label class="form-check-label mb-0" for="teramindNo">No</label>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group col-md-4 mb-3">
                        <label>System Upgradation:</label>
                        <div class="input-group">
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="systemUpgradation" value="Yes"
                                    id="upgradeYes" name="systemUpgradation">
                                <label class="form-check-label mb-0" for="upgradeYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="systemUpgradation" value="No"
                                    id="upgradeNo" name="systemUpgradation">
                                <label class="form-check-label mb-0" for="upgradeNo">No</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 30px;">

                    <div class="form-group col-md-4 mb-3">
                        <label>OneDrive:</label>
                        <div class="input-group">
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="oneDrive" value="Yes"
                                    id="oneDriveYes" name="oneDrive">
                                <label class="form-check-label mb-0" for="oneDriveYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="oneDrive" value="No"
                                    id="oneDriveNo" name="oneDrive">
                                <label class="form-check-label mb-0" for="oneDriveNo">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label>Screenshot Programs:</label>
                        <div class="input-group">
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="screenshotPrograms" value="Yes"
                                    id="screenshotYes" name="screenshotPrograms">
                                <label class="form-check-label mb-0" for="screenshotYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mb-0 mx-2">
                                <input class="form-check-input" type="radio" wire:model="screenshotPrograms" value="No"
                                    id="screenshotNo" name="screenshotPrograms">
                                <label class="form-check-label mb-0" for="screenshotNo">No</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row" style="margin-bottom: 30px;">
                    <div class="form-group col-md-4 mb-3">

                        <label>Mac Address:</label>
                        <input type="text" class="form-control" wire:model="macAddress">
                    </div>
                    <div class="form-group col-md-4 mb-3">

                        <label>Assigned At (Date):</label>
                        <input type="date" class="form-control" wire:model="assignedAt">
                    </div>

                </div>

                <div class="row" style="margin-bottom: 30px;">
                    <div class="form-group col-md-4 mb-3">
                        <label>Laptop Received (Date):</label>
                        <input type="date" class="form-control" wire:model="laptopReceived">
                    </div>

                </div>


            </div>
            @endif
            <!-- Submit Button -->
            <div class="mt-4 text-center">

                <button class="btn text-white" style="background-color: #02114f;" wire:click="submit">
                    {{ $isUpdateMode ? 'Update Asset' : 'Assign' }}
                </button>

            </div>

        </div>
        @endif




        @if($employeeAssetListing)

        @if($searchFilters)

        <div class="col-11  mt-4 ml-4 employeeAssetList">
            <!-- Align items to the same row with space between -->
            <div class="col-11 col-md-11 mb-3 mb-md-0">
                <div class="row d-flex justify-content-between">
                    <!-- Employee ID Search Input -->
                    <div class="col-10 col-md-4 col-sm-6 mb-1">
                        <div class="input-group task-input-group-container">
                            <input type="text" class="form-control" placeholder="Search..." wire:model="searchEmp"
                                wire:input="filter">
                        </div>
                    </div>

                    <!-- Add Member Button aligned to the right -->
                    <div class="col-12 col-md-8 col-sm-12">
                        <div class="empAssetBtns">

                            @if ($showOldEMployeeAssetBtn)
                            <button class="btn text-white mr-3 mb-1" style="background-color: #02114f;font-size:13px;"
                                wire:click="oldAssetlisting">Previous Owners </button>
                            @endif
                            @if(auth()->check() && (auth()->user()->hasRole('admin') ||
                            auth()->user()->hasRole('super_admin')))
                            @if ($showAssignAssetBtn)
                            <button class="btn text-white mb-1" style="background-color: #02114f;font-size:13px;"
                                wire:click="assignAsset">Assign
                                Asset</button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @endif
        @if($showEditDeleteEmployeeAsset)
        <div class="col-11 mt-4 ml-4">

            <div class="table-responsive it-add-table-res">

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="req-table-head" scope="col">Employee ID
                                <span wire:click.debounce.500ms="toggleSortOrder('emp_id')" style="cursor: pointer;">
                                    @if($sortColumn == 'emp_id')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Employee Name
                                <span wire:click.debounce.500ms="toggleSortOrder('employee_name')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'employee_name')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Asset ID
                                <span wire:click.debounce.500ms="toggleSortOrder('asset_id')" style="cursor: pointer;">
                                    @if($sortColumn == 'asset_id')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Manufacturer
                                <span wire:click.debounce.500ms="toggleSortOrder('manufacturer')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'manufacturer')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Asset Type
                                <span wire:click.debounce.500ms="toggleSortOrder('asset_type')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'asset_type')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Department
                                <span wire:click.debounce.500ms="toggleSortOrder('department')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'department')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>


                            <th class="req-table-head d-flex justify-content-center">Actions</th>
                            <!-- Added Actions Column -->
                        </tr>
                    </thead>
                    <tbody>

                        {{-- Ensure $employeeAssetLists is initialized --}}
                        @php
                        $employeeAssetLists = $employeeAssetLists ?? collect(); // Initialize as an empty collection if
                        null
                        @endphp

                        @if($employeeAssetLists->count() > 0 && $employeeAssetLists->where('is_active', 1)->count() > 0)
                        @foreach($employeeAssetLists as $employeeAssetList)
                        @if($employeeAssetList->is_active == 1)
                        <tr>
                            <td>{{ $employeeAssetList->emp_id ?? 'N/A'}}</td>
                            <td>{{ ucwords(strtolower($employeeAssetList->employee_name)) ?? 'N/A' }}</td>
                            <td>{{ $employeeAssetList->asset_id ?? 'N/A'}}</td>
                            <td>{{ ucwords(strtolower($employeeAssetList->manufacturer)) ?? 'N/A'}}</td>
                            <td>{{ ucwords(strtolower($employeeAssetList['asset_type_name'])) ?? 'N/A' }}</td>
                            <td>{{ $employeeAssetList->department ?? 'N/A' }}</td>
                            <td class="d-flex ">
                                <!-- Action Buttons -->
                                <div class="col mx-1">
                                    <button class="btn btn-sm btn-white border-dark"
                                        wire:click="viewDetails({{ $employeeAssetList->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <!-- Edit Action -->
                                @if(auth()->check() && (auth()->user()->hasRole('admin') ||
                                auth()->user()->hasRole('super_admin')))
                                <div class="col mx-1">
                                    <button class="btn btn-sm btn-white border-dark"
                                        wire:click="edit({{ $employeeAssetList->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                @endif
                                <!-- Delete Action -->
                                @if(auth()->check() && (
                                auth()->user()->hasRole('super_admin')))
                                <div class="col mx-1">
                                    <button class="btn text-white btn-sm border-dark" style="background-color: #02114f;"
                                        wire:click="confirmDelete('{{ $employeeAssetList->id }}','{{$employeeAssetList->asset_id}}')"
                                        title="Deactivate">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endif

                        @endforeach
                        @else
                        <tr>
                            <td colspan="20">

                                <div class="req-td-norecords">
                                    <img src="{{ asset('images/Closed.webp') }}" alt="No Records"
                                        class="req-img-norecords">


                                    <h3 class="req-head-norecords">No data found</h3>
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
        @if($showViewEmployeeAsset && $currentVendorId)
        @php
        // Find the specific vendor by the currentVendorId
        $vendor = $employeeAssetLists->firstWhere('id', $currentVendorId);
        @endphp

        @if($vendor)
        <div class="col-10 mt-4 view-details-modal">
            <div class="d-flex justify-content-between align-items-center">
                <h5>View Details</h5>
                <button class="btn text-white" style="background-color: #02114f;" wire:click="closeViewVendor"
                    aria-label="Close">
                    Close
                </button>
            </div>

            <table class="table table-bordered mt-3 req-pro-table">

                <tbody>
                    <tr>
                        <td>Employee ID</td>
                        <td class="view-td">{{ $vendor->emp_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Employee Name</td>
                        <td class="view-td">{{ucwords(strtolower($vendor->employee_name)) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Vendor ID</td>
                        <td class="view-td">{{ $vendor->vendorAsset->vendor_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Vendor Name</td>
                        <td class="view-td">{{ $vendor->vendorAsset->vendor->vendor_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset ID</td>
                        <td class="view-td">{{ $vendor->asset_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Manufacturer</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->manufacturer)) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset Type</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor['asset_type_name'])) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset Model</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->vendorAsset->asset_model)) ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <td>Department</td>
                        <td class="view-td">{{ $vendor->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Color</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->vendorAsset->color)) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Invoice Amount</td>
                        <td class="view-td">Rs. {{ $vendor->vendorAsset->invoice_amount ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td class="view-td">{{ $vendor->vendorAsset->invoice_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Serial Number</td>
                        <td class="view-td">{{ $vendor->vendorAsset->serial_number ?? 'N/A' }}</td>
                    </tr>




                    <tr>
                        <td>Sophos Antivirus</td>
                        <td class="view-td">{{ $vendor->sophos_antivirus ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>VPN Creation</td>
                        <td class="view-td">{{ $vendor->vpn_creation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Teramind</td>
                        <td class="view-td">{{ $vendor->teramind ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>System Upgradation</td>
                        <td class="view-td">{{ $vendor->system_upgradation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>System Name</td>
                        <td class="view-td">{{ $vendor->system_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>One Drive</td>
                        <td class="view-td">{{ $vendor->one_drive ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Screenshot Of Programs</td>
                        <td class="view-td">{{ $vendor->screenshot_programs ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>MAC Address</td>
                        <td class="view-td">{{ $vendor->mac_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset Assigned Date</td>
                        <td class="view-td">
                            {{ \Carbon\Carbon::parse($vendor->laptop_received)->format('d-M-Y') ?? 'N/A' }}</td>
                    </tr>




                    <tr>
                        <td>Asset Purchase Date</td>
                        <td class="view-td">
                            {{ $vendor->vendorAsset->purchase_date ? \Carbon\Carbon::parse($vendor->vendorAsset->purchase_date)->format('d-M-Y') : 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td>No of Days</td>
                        <td class="view-td">
                            {{ $vendor->created_at ? \Carbon\Carbon::parse($vendor->created_at)->diffInDays(\Carbon\Carbon::now()) . ' days' : 'N/A' }}
                        </td>
                    </tr>


                    <tr>
                        <td class="fs-6 fs-md-3 fs-lg-2">IT Uploaded Files</td>


                        <td>
                            @php
                            $images = [];
                            $files = [];


                            // Check if $vendor->asset_assign_file_path is a string, array, or null
                            $fileDataArray = null;

                            if (isset($vendor->asset_assign_file_path) &&
                            is_string($vendor->asset_assign_file_path))
                            {
                            $fileDataArray = json_decode($vendor->asset_assign_file_path, true);
                            } elseif (isset($vendor->asset_assign_file_path) &&
                            is_array($vendor->asset_assign_file_path)) {
                            $fileDataArray = $vendor->asset_assign_file_path;
                            }

                            // Ensure $fileDataArray is a valid array before looping
                            if (is_array($fileDataArray)) {
                            // Separate images and files
                            foreach ($fileDataArray as $fileData) {
                            if (isset($fileData['mime_type'])) {
                            if (strpos($fileData['mime_type'], 'image/') === 0) {
                            $images[] = $fileData;
                            } else {
                            $files[] = $fileData;
                            }
                            }
                            }
                            }
                            @endphp




                            @php
                            // Initialize $images and $files as empty arrays to avoid null issues
                            $images = $images ?? [];
                            $files = $files ?? [];
                            @endphp
                            <!-- Trigger Links -->
                            @if (count($images) > 1)
                            <a href="#" wire:click.prevent="showViewEmpImage({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View Images
                            </a>
                            @elseif (count($images) == 1)
                            <a href="#" wire:click.prevent="showViewEmpImage({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View Image
                            </a>
                            @endif

                            @if (count($files) > 1)
                            <a href="#" wire:click.prevent="showViewEmpFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;margin-left:2px;">
                                View Files
                            </a>
                            @elseif (count($files) == 1)
                            <a href="#" wire:click.prevent="showViewEmpFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View File
                            </a>
                            @endif

                            @if (count($images) == 0 && count($files) == 0)
                            <label for="">N/A</label>
                            @endif

                            {{-- view file popup --}}
                            @if ($showViewEmpImageDialog && $currentassetID === $vendor->id)
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
                                                wire:click="closeViewEmpImage">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif


                            @if ($showViewEmpFileDialog && $currentassetID === $vendor->id)
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
                                                wire:click="closeViewEmpFile">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif

                        </td>


                    </tr>


                </tbody>
            </table>
        </div>
        @else
        <div class="col-10 mt-4 view-details-modal">
            <p>No details available.</p>
        </div>
        @endif

        @endif
        @endif

        @if($oldAssetEmp)



        @if($searchFilters)
        <!-- Search Filters -->
        <div class="col-11 mb-3 mt-4 ml-4 employeeAssetList">
            <!-- Align items to the same row with space between -->
            <div class="col-11 col-md-11 mb-2 mb-md-0">
                <div class="row d-flex justify-content-between">
                    <!-- Employee ID Search Input -->
                    <div class="col-lg-4 col-md-4 col-6">
                        @if($oldAssetBackButton)

                        <button class="btn text-white" style="background-color: #02114f;font-size:13px;"
                            wire:click="closeViewVendor" aria-label="Close">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>

                        @endif
                    </div>

                    <!-- Add Member Button aligned to the right -->
                    <div class="col-lg-4 col-md-4 col-6">

                        <input type="text" class="form-control" placeholder="Search..." wire:model="searchEmp"
                            wire:input="filter">
                    </div>
                </div>
            </div>
        </div>



        @endif
        @if($showEditDeleteEmployeeAsset)
        <div class="col-11 mt-4 ml-4">
            <div class="table-responsive it-add-table-res">

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="req-table-head" scope="col">Employee ID
                                <span wire:click.debounce.500ms="toggleSortOrder('emp_id')" style="cursor: pointer;">
                                    @if($sortColumn == 'emp_id')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Employee Name
                                <span wire:click.debounce.500ms="toggleSortOrder('employee_name')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'employee_name')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Asset ID
                                <span wire:click.debounce.500ms="toggleSortOrder('asset_id')" style="cursor: pointer;">
                                    @if($sortColumn == 'asset_id')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Manufacturer
                                <span wire:click.debounce.500ms="toggleSortOrder('manufacturer')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'manufacturer')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Asset Type
                                <span wire:click.debounce.500ms="toggleSortOrder('asset_type')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'asset_type')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">Department
                                <span wire:click.debounce.500ms="toggleSortOrder('department')"
                                    style="cursor: pointer;">
                                    @if($sortColumn == 'department')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                    <i class="fas fa-sort"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="req-table-head">View</th>


                        </tr>
                    </thead>
                    <tbody>

                        @if($employeeAssetLists->count() > 0 && $employeeAssetLists->where('is_active', 0)->count() > 0)
                        @foreach($employeeAssetLists as $employeeAssetList)
                        @if($employeeAssetList->is_active == 0)
                        <tr>
                            <td>{{ $employeeAssetList->emp_id ?? 'N/A'}}</td>
                            <td>{{ucwords(strtolower($employeeAssetList->employee_name)) ?? 'N/A'}}</td>
                            <!-- <td>{{ $employeeAssetList->vendorAsset->vendor_id }}</td> -->
                            <td>{{ $employeeAssetList->asset_id ?? 'N/A'}}</td>
                            <td>{{ ucwords(strtolower($employeeAssetList->manufacturer)) ?? 'N/A'}}</td>
                            <td>{{ ucwords(strtolower($employeeAssetList['asset_type_name'])) ?? 'N/A'}}</td>

                            <td>{{ $employeeAssetList->department ?? 'N/A'}}</td>
                            <td class="d-flex ">
                                <!-- Action Buttons -->
                                <div class="col">
                                    <button class="btn btn-sm btn-white border-dark"
                                        wire:click="viewOldAssetDetails({{ $employeeAssetList->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @endif

                        @endforeach
                        @else
                        <tr>
                            <td colspan="20">

                                <div class="req-td-norecords">
                                    <img src="{{ asset('images/Closed.webp') }}" alt="No Records"
                                        class="req-img-norecords">


                                    <h3 class="req-head-norecords">No data found</h3>
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
        @if($showViewEmployeeAsset && $currentVendorId)
        @php
        // Find the specific vendor by the currentVendorId
        $vendor = $employeeAssetLists->firstWhere('id', $currentVendorId);
        @endphp

        @if($vendor)
        <div class="col-10 mt-4 view-details-modal">
            <div class="d-flex justify-content-between align-items-center">
                <h5>View Details</h5>
                <button class="btn text-white" style="background-color: #02114f;font-size:13px;"
                    wire:click="closeViewEmpAsset" aria-label="Close">
                    Close
                </button>
            </div>

            <table class="table table-bordered mt-3 req-pro-table">

                <tbody>
                    <tr>
                        <td>Employee ID</td>
                        <td class="view-td">{{ $vendor->emp_id ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <td>Employee Name</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->employee_name)) ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <td>Vendor Id</td>
                        <td class="view-td">{{ $vendor->vendorAsset->vendor_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset ID</td>
                        <td class="view-td">{{ $vendor->asset_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Manufacturer</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->manufacturer)) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset Type</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor['asset_type_name'])) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset Model</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->vendorAsset->asset_model)) ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <td>Department</td>
                        <td class="view-td">{{$vendor->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Color</td>
                        <td class="view-td">{{ ucwords(strtolower($vendor->vendorAsset->color)) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Invoice Amount</td>
                        <td class="view-td">Rs. {{$vendor->vendorAsset->invoice_amount ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td class="view-td">{{ $vendor->vendorAsset->invoice_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Serial Number</td>
                        <td class="view-td">{{ $vendor->vendorAsset->serial_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Sophos Antivirus</td>
                        <td class="view-td">{{ $vendor->sophos_antivirus ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>VPN Creation</td>
                        <td class="view-td">{{ $vendor->vpn_creation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Teramind</td>
                        <td class="view-td">{{ $vendor->teramind ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>System Upgradation</td>
                        <td class="view-td">{{ $vendor->system_upgradation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>System Name</td>
                        <td class="view-td">{{ $vendor->system_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>One Drive</td>
                        <td class="view-td">{{ $vendor->one_drive ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Screenshot Of Programs</td>
                        <td class="view-td">{{ $vendor->screenshot_programs ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>MAC Address</td>
                        <td class="view-td">{{ $vendor->mac_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Asset Assigned Date</td>
                        <td class="view-td">
                            {{ \Carbon\Carbon::parse($vendor->laptop_received)->format('d-M-Y') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Purchase Date</td>
                        <td class="view-td">
                            {{ $vendor->vendorAsset->purchase_date ? \Carbon\Carbon::parse($vendor->vendorAsset->purchase_date)->format('d-M-Y') : 'N/A' }}
                        </td>
                    </tr>


                    <tr>
                        <td>No of Days</td>
                        <td class="view-td">
                            @if($vendor->deleted_at)
                            {{ $vendor->created_at ? \Carbon\Carbon::parse($vendor->created_at)->diffInDays(\Carbon\Carbon::parse($vendor->deleted_at)) . ' days' : 'N/A' }}
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <td>Active Uploaded Files</td>


                        <td>
                            @php
                            $images = [];
                            $files = [];


                            // Check if $vendor->asset_assign_file_path is a string, array, or null
                            $fileDataArray = null;

                            if (isset($vendor->asset_assign_file_path) &&
                            is_string($vendor->asset_assign_file_path))
                            {
                            $fileDataArray = json_decode($vendor->asset_assign_file_path, true);
                            } elseif (isset($vendor->asset_assign_file_path) &&
                            is_array($vendor->asset_assign_file_path)) {
                            $fileDataArray = $vendor->asset_assign_file_path;
                            }

                            // Ensure $fileDataArray is a valid array before looping
                            if (is_array($fileDataArray)) {
                            // Separate images and files
                            foreach ($fileDataArray as $fileData) {
                            if (isset($fileData['mime_type'])) {
                            if (strpos($fileData['mime_type'], 'image/') === 0) {
                            $images[] = $fileData;
                            } else {
                            $files[] = $fileData;
                            }
                            }
                            }
                            }
                            @endphp




                            @php
                            // Initialize $images and $files as empty arrays to avoid null issues
                            $images = $images ?? [];
                            $files = $files ?? [];
                            @endphp
                            <!-- Trigger Links -->
                            @if (count($images) > 1)
                            <a href="#" wire:click.prevent="showViewEmpImage({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View Images
                            </a>
                            @elseif (count($images) == 1)
                            <a href="#" wire:click.prevent="showViewEmpImage({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View Image
                            </a>
                            @endif

                            @if (count($files) > 1)
                            <a href="#" wire:click.prevent="showViewEmpFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;margin-left:2px;">
                                View Files
                            </a>
                            @elseif (count($files) == 1)
                            <a href="#" wire:click.prevent="showViewEmpFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View File
                            </a>
                            @endif

                            @if (count($images) == 0 && count($files) == 0)
                            <label for="">N/A</label>
                            @endif

                            {{-- view file popup --}}
                            @if ($showViewEmpImageDialog && $currentassetID === $vendor->id)
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
                                                wire:click="closeViewEmpImage">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif


                            @if ($showViewEmpFileDialog && $currentassetID === $vendor->id)
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
                                                wire:click="closeViewEmpFile">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif

                        </td>


                    </tr>


                    <tr>
                        <td>Deactive Uploaded Files</td>


                        <td>
                            @php
                            $images = [];
                            $files = [];


                            // Check if $vendor->asset_deactivate_file_path is a string, array, or null
                            $fileDataArray = null;

                            if (isset($vendor->asset_deactivate_file_path) &&
                            is_string($vendor->asset_deactivate_file_path))
                            {
                            $fileDataArray = json_decode($vendor->asset_deactivate_file_path, true);
                            } elseif (isset($vendor->asset_deactivate_file_path) &&
                            is_array($vendor->asset_deactivate_file_path)) {
                            $fileDataArray = $vendor->asset_deactivate_file_path;
                            }

                            // Ensure $fileDataArray is a valid array before looping
                            if (is_array($fileDataArray)) {
                            // Separate images and files
                            foreach ($fileDataArray as $fileData) {
                            if (isset($fileData['mime_type'])) {
                            if (strpos($fileData['mime_type'], 'image/') === 0) {
                            $images[] = $fileData;
                            } else {
                            $files[] = $fileData;
                            }
                            }
                            }
                            }
                            @endphp




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
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;margin-left:2px;">
                                View Files
                            </a>
                            @elseif (count($files) == 1)
                            <a href="#" wire:click.prevent="showViewFile({{ $vendor->id }})"
                                style="text-decoration: none; color: #007BFF; font-size: 12px; text-transform: capitalize;">
                                View File
                            </a>
                            @endif

                            @if (count($images) == 0 && count($files) == 0)
                            <label for="">N/A</label>
                            @endif

                            {{-- view file popup --}}
                            @if ($showViewImageDialog && $currentassetID === $vendor->id)
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
                                                wire:click.prevent="downloadITImages({{ $vendor->id }})">Download</button>
                                            <button type="button" class="cancel-btn1"
                                                wire:click="closeViewImage">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif


                            @if ($showViewFileDialog && $currentassetID === $vendor->id)
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

                        </td>


                    </tr>



                </tbody>
            </table>
        </div>
        @else
        <div class="col-10 mt-4 view-details-modal">
            <p>No details available.</p>
        </div>
        @endif

        @endif
        @endif
    </div>


    @if ($showLogoutModal)
    <div class="modal logout1" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white logout2">
                    <h6 class="modal-title logout3" id="logoutModalLabel">Confirm Deactivation</h6>
                </div>
                <div class="modal-body text-center logout4">
                    Are you sure you want to Deactivate?
                </div>
                <div class="modal-body text-center">
                    <form wire:submit.prevent="delete">

                        <div class="row">

                            <div class="col-lg-5 col-md-4 col-10 req-remarks-div"
                                style="display: flex;flex-direction:column">
                                <label style="text-align: left;" for="">Asset Status <span
                                        class="text-danger">*</span></label>
                                <select id="vendorStatus" wire:model.lazy="selectedStatus"
                                    wire:change="validatefield('selectedStatus')" class=" form-select"
                                    style="height: 33px; border-radius: 6px;">
                                    <option value="" disabled selected>Select Status</option>
                                    <!-- Placeholder option -->
                                    <option value="Available">Available</option>
                                    <option value="Absent">Absent</option>
                                    <option value="In Maintenance">In Maintenance</option>
                                    <option value="In Repair">In Repair</option>
                                    <option value="In Stock">In Stock</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Installed">Installed</option>
                                    <option value="On Order">On Order</option>
                                    <option value="Pending Install">Pending Install</option>
                                    <option value="Pending Repair">Pending Repair</option>
                                    <option value="Retired">Retired</option>
                                    <option value="Stolen"> Stolen</option>
                                    <option value="null" selected>Others</option>
                                </select>
                                @error('selectedStatus') <span
                                    class="text-danger d-flex align-start">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-7 col-md-4 col-10 req-remarks-div "
                                style="display: flex;flex-direction:column">
                                <label style="text-align: left;" for="">Reason<span class="text-danger">*</span></label>
                                <textarea wire:model.lazy="reason" class="form-control req-remarks-textarea logout4"
                                    wire:input="validatefield('reason')" style="font-size: 13px;"
                                    placeholder="Reason for Deactivation"></textarea>
                                @error('reason') <span
                                    class="text-danger d-flex align-start">{{ $message }}</span>@enderror

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-7">

                                <tr>
                                    <td>
                                        <!-- Attachments -->
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-4" style="margin-right: 3px;">
                                                        <p class="text-primary">
                                                            <label for="file"
                                                                class="vendor-asset-label">Attachments</label>

                                                        </p>
                                                    </div>
                                                    <div class="col-8">

                                                        <!-- File input hidden -->
                                                        <input id="fileInput-{{ $recordId }}" type="file"
                                                            wire:model="it_file_paths.{{ $recordId }}"
                                                            class="form-control-file" multiple
                                                            style="font-size: 12px; display: none;" />

                                                        <!-- Label triggers file input -->
                                                        <div class="req-attachmentsIcon d-flex"
                                                            style="align-items: baseline; gap: 5px;">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                for="fileInput-{{ $recordId }}"
                                                                onclick="document.getElementById('fileInput-{{ $recordId}}').click();">
                                                                <i class="fa-solid fa-paperclip"></i>
                                                            </button>
                                                        </div>


                                                        <div wire:loading wire:target="it_file_paths.{{ $recordId }}"
                                                            class="mt-2">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                            Uploading...
                                                        </div>

                                                        @error('it_file_paths.' . $recordId . '.*')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </td>



                                    <!-- File Preview Modal -->
                                    @if($showFilePreviewModal)

                                    <div class="modal fade show d-block" tabindex="-1" role="dialog"
                                        style="background-color: rgba(0, 0, 0, 0.5);">
                                        <div class="modal-dialog modal-dialog-centered  modal-lg">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="filePreviewModalLabel">File
                                                        Preview</h5>
                                                    <button type="button" class="btn-close"
                                                        wire:click="hideFilePreviewModal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <h6>Selected Files</h6>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <!-- Loop through files and display previews -->
                                                            @foreach ($previews as $index => $preview)
                                                            <div class="file-preview-container text-center"
                                                                style="padding: 5px; border: 1px solid black; width: 120px; height: 120px; border-radius: 5px; position: relative; overflow: hidden;">
                                                                @if ($preview['type'] == 'image')
                                                                <img src="{{ $preview['url'] }}" alt="Preview"
                                                                    class="img-thumbnail"
                                                                    style="width: 75px; height: 75px;" />
                                                                <span class="mt-1 uploaded-file-name"
                                                                    style="display: block; width: 100%;">{{ $preview['name'] }}</span>
                                                                @else
                                                                <div class="d-flex flex-column align-items-center">
                                                                    <i class="fas fa-file fa-3x"
                                                                        style="width: 75px; height: 75px;"></i>
                                                                    <span class="mt-1 uploaded-file-name"
                                                                        style="display: block; width: 100%;">{{ $preview['name'] }}</span>
                                                                </div>
                                                                @endif

                                                                <!-- Delete icon -->
                                                                <button type="button" class="delete-icon btn btn-danger"
                                                                    wire:click="removeFile({{ $index }})"
                                                                    style="position: absolute; top: 5%; right: 5%; z-index: 5; font-size: 12px;">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        wire:click="hideFilePreviewModal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        wire:click="uploadFiles({{ $recordId }})"
                                                        wire:key='upload-{{ $recordId }}'>Upload
                                                        Files</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    @endif


                                </tr>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center p-3">
                            <button type="submit" class="submit-btn mr-3"
                                wire:click="delete({{ $employeeAssetList->id }})">Confirm</button>
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
