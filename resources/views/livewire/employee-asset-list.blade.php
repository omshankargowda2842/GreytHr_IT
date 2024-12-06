<div class="main">
    <!-- Search Filters -->
    <div class="row mb-3 mt-4 ml-4 employeeAssetList">
        <!-- Employee ID Search Input -->
        <div class="col-10 col-md-3 mb-2 mb-md-0">
            <input type="text" class="form-control" placeholder="Search by Employee ID"
                wire:model.debounce.500ms="searchEmpId" wire:keydown.enter="filter">
        </div>

        <!-- Asset ID Search Input -->
        <div class="col-10 col-md-3 mb-2 mb-md-0">
            <input type="text" class="form-control" placeholder="Search by Asset ID"
                wire:model.debounce.500ms="searchAssetId" wire:keydown.enter="filter">
        </div>

        <!-- Buttons -->
        <div class="col-10 col-md-3 d-flex gap-2 flex-column flex-md-row">
            <button class="btn btn-dark" wire:click="filter">
                <i class="fa fa-search"></i> Search
            </button>
            <button class="btn btn-secondary" wire:click="clearFilters">
                <i class="fa fa-times"></i> Clear
            </button>
        </div>
    </div>

    @if($showEditDeleteEmployeeAsset)
    <div class="col-11 mt-4 ml-4">
        <div class="table-responsive it-add-table-res">
            <div wire:loading>
                <div class="loader-overlay">
                    <div class="loader"></div>
                </div>
            </div>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="req-table-head" scope="col">Employee Id</th>
                        <th class="req-table-head">Vendor Id</th>
                        <th class="req-table-head">Asset Id</th>
                        <th class="req-table-head">Manufacturer</th>
                        <th class="req-table-head">Asset Type</th>
                        <th class="req-table-head">Employee Name</th>
                        <th class="req-table-head">Department</th>

                        <th class="req-table-head">Actions</th> <!-- Added Actions Column -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($employeeAssetLists as $employeeAssetList)
                    <tr>
                        <td>{{ $employeeAssetList->emp_id }}</td>
                        <td>{{ $employeeAssetList->vendorAsset->vendor_id }}</td>
                        <td>{{ $employeeAssetList->asset_id }}</td>
                        <td>{{ $employeeAssetList->manufacturer }}</td>
                        <td>{{ $employeeAssetList->asset_type }}</td>
                        <td>{{ $employeeAssetList->employee_name }}</td>
                        <td>{{ $employeeAssetList->department }}</td>
                        <td class="d-flex flex-direction-row">
                            <!-- Action Buttons -->
                            <div class="col">
                                <button class="btn btn-sm btn-dark"
                                    wire:click="viewDetails({{ $employeeAssetList->id }})" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-dark"
                                    wire:click="showEditItMember({{ $employeeAssetList->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>

                            <div class="col">
                                <button class="btn btn-sm btn-dark"
                                    wire:click="deleteMember({{ $employeeAssetList->id }})" title="Deactivate">
                                    <i class="fas fa-trash"  title="Deactivate"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center">No results found</td>
                    </tr>
                    @endforelse
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
            <h3>View Details</h3>
            <button class="btn btn-dark" wire:click="closeViewVendor" aria-label="Close">
                <i class="fas fa-times"></i> Close
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
                    <td>Employee Id</td>
                    <td>{{ $vendor->emp_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Vendor Id</td>
                    <td>{{ $vendor->vendorAsset->vendor_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Asset Id</td>
                    <td>{{ $vendor->asset_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Manufacturer</td>
                    <td>{{ $vendor->manufacturer ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Asset Type</td>
                    <td>{{ $vendor->asset_type ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Asset Model</td>
                    <td>{{ $vendor->vendorAsset->asset_model ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Employee Name</td>
                    <td>{{ $vendor->employee_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>{{ $vendor->department ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td>{{ $vendor->vendorAsset->color ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Invoice Amount</td>
                    <td>{{ $vendor->vendorAsset->invoice_amount ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Invoice Number</td>
                    <td>{{ $vendor->vendorAsset->invoice_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Serial Number</td>
                    <td>{{ $vendor->vendorAsset->serial_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Purchase Date</td>
                    <td>
                        {{ $vendor->vendorAsset->purchase_date ? \Carbon\Carbon::parse($vendor->vendorAsset->purchase_date)->format('d-m-Y') : 'N/A' }}
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

</div>
