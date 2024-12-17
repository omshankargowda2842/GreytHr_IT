<div class="main d-flex" style=" align-items: center; justify-content:center; flex-direction:column">
    <style>
        .Page {
            width: 100%;

            overflow: hidden;
            display: flex;
            justify-content: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 10px;
        }

        .pagination {
            overflow: auto;
            width: fit-content;
        }

        .pagination .page-item.active .page-link {
            background-color: #02114f;
            /* Custom color (e.g., blue) */
            border-color: #02114f;
            /* To match the border color */
            color: white;
            /* Change text color */
        }

        .page-link {
            font-size: 10px;
            color: #02114f;

        }
    </style>
    <div wire:loading wire:target="dashboard,itRequest,itMembers,oldRecords,vendor,vendorAssets,assets,assignAsset">
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



    <div class="col-11 mb-3 mt-4 ml-4 employeeAssetList ">
        <!-- Align items to the same row with space between -->

        <div class="row">
            <div class="col-11 d-flex justify-content-between" >
                <h6 style="background-color:#02114f ;padding:10px;color:#f8f9fa;border-radius:5px">Total Assets:  {{$all_Assets}}</h6>
                <h6 style="background-color:#02114f ;padding:10px;color:#f8f9fa;border-radius:5px">Filtered Assets:  {{count($totalAssetDetails)}}</h6>

            </div>
        </div>
        <div class="row">
            <div class="col-11 col-md-11 mb-2 mb-md-0">
                <div class="row d-flex justify-content-between align-items-center ">
                    <!-- Employee ID Search Input -->
                    <div class="col-6 col-md-4 col-sm-6">

                        <label for=""></label>
                        <input type="text" class="form-control" placeholder="Search..." wire:model="searchEmp"
                            wire:input="getAssetList">

                    </div>
                    <div class="col-6 col-md-3 col-sm-6">
                        <label for="">Asset Type</label>
                        <select name="" id="" wire:model="selectedAsset_type" wire:change="getAssetList" class="form-select">
                            <option value="">All</option>
                            @foreach($asset_types as $asset_type)
                            <option value="{{$asset_type->id}}">{{$asset_type->asset_names}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-sm-6">
                        <label for="">Status</label>
                        <select name="" wire:model="selectedStatus" wire:change="getAssetList" id="" class="form-select">
                            <option value="">All</option>
                            <option value="In Use">In Use</option>
                            <option value="In Repair">In Repair</option>
                            <option value="Available">Available</option>
                            <option value="null" selected>Others</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2 col-sm-6">
                        <label for="" style="margin-right: 2px;">Items-Per-Page</label>
                        <select class="form-select" wire:change='getAssetList' wire:model="perPage" style="width: 100%;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>

                    <!-- Add Member Button aligned to the right -->
                    @if(auth()->check() && (auth()->user()->hasRole('admin') ||
                    auth()->user()->hasRole('super_admin')))
                    <!-- <div class="col-auto">
                    <button class="btn text-white btn-sm" wire:click='showAddVendorMember'
                        style="padding: 7px;background-color: #02114f;">
                        <i class="fas fa-box " style="margin-right: 5px;"></i> Add Asset
                    </button>
                </div> -->
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-11 mt-4 ">
        <div class="table-responsive it-add-table-res">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="vendor-table-head">S.No</th>



                        <th class="vendor-table-head">Asset ID
                            <span wire:click.debounce.500ms="toggleSortOrder('asset_id')" style="cursor: pointer;">
                                @if($sortColumn == 'asset_id')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>

                        <th class="vendor-table-head">Manufacturer
                            <span wire:click.debounce.500ms="toggleSortOrder('manufacturer')" style="cursor: pointer;">

                            </span>
                        </th>


                        </th>
                        <th class="vendor-table-head">Serial Number
                            <span wire:click.debounce.500ms="toggleSortOrder('serial_number')" style="cursor: pointer;">
                                @if($sortColumn == 'serial_number')
                                <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort"></i>
                                @endif
                            </span>
                        </th>
                        <th class="vendor-table-head">Asset Type
                            <span wire:click.debounce.500ms="toggleSortOrder('$vendorAssets->asset_type_name')"
                                style="cursor: pointer;">

                            </span>
                        </th>

                        <th class="vendor-table-head">Status

                        </th>



                    </tr>
                </thead>
                <tbody>


                    @if(count($assetDetails)>0 )
                    @foreach($assetDetails as $assetDetail)
                    <tr>
                        <td class="vendor-table-head">{{ $loop->iteration }}</td>
                        <td class="vendor-table-head">{{ $assetDetail['asset_id'] ?? '-'}}</td>
                        <td class="vendor-table-head">{{ucwords(strtolower($assetDetail['manufacturer'] )) ?? '-' }}</td>
                        <td class="vendor-table-head">{{ $assetDetail['serial_number'] ?? '-'}}</td>
                        <td class="vendor-table-head">{{ $assetDetail['asset_names'] ?? '-'}}</td>

                        <td class="vendor-table-head">{{ $assetDetail['status'] ?? '-'}}</td>


                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="20">
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

        @if(count($assetDetails)>0 )
        <div class="mt-4 mb-4">
            <nav aria-label="Page navigation d-flex justify-content-center" style="display: flex;justify-content:center">
                <ul class="pagination">
                    {{--<li class="page-item {{ $currentPage === 1 ? 'disabled' : '' }}">
                    <button class="page-link" wire:click="setPage(1)">First</button>
                    </li>--}}
                    <li class="page-item {{ $currentPage === 1 ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="setPage({{$currentPage}} - 1)">Pre</button>
                    </li>
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $currentPage === $i ? 'active' : '' }}">
                        <button class="page-link" wire:click="setPage({{ $i }})">{{ $i }}</button>
                        </li>
                        @endfor
                        <li class="page-item {{ $currentPage === $totalPages ? 'disabled' : '' }}">
                            <button class="page-link" wire:click="setPage({{$currentPage}} + 1)">Next</button>
                        </li>
                        {{-- <li class="page-item {{ $currentPage === $totalPages || $totalPages === 1 ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="setPage({{ $totalPages }})">Last</button>
                        </li>--}}
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>
