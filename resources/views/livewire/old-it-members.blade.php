<div class="main">

    <div wire:loading wire:target="cancel,cancelLogout,restore">
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


    @if($searchFilters)
    <div class="row mb-3 mt-4 ml-4 employeeAssetList">
        <!-- Align items to the same row with space between -->
        <div class="col-11 col-md-11 mb-2 mb-md-0">
            <div class="row d-flex justify-content-start">
                <!-- Employee ID Search Input -->
                <div class="col-lg-4 col-md-4 col-8">
                    <div class="input-group task-input-group-container">
                        <input type="text" class="form-control" placeholder="Search..."
                            wire:model="searchEmp" wire:input="filter">
                    </div>
                </div>


            </div>
        </div>
    </div>

    @endif
    <div class="col-11  mt-4 ml-4">

        <div class="table-responsive it-add-table-res">


            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="req-table-head" scope="col">S.No</th>
                        <!-- Non-sortable Image Column -->
                        <th class="req-table-head">Image</th>
                        <th class="req-table-head" wire:click="toggleSortOrder('it_emp_id')" style="cursor: pointer;">IT
                            Employee ID

                        </th>
                        <!-- Sortable Employee ID Column -->
                        <th class="req-table-head" wire:click="toggleSortOrder('emp_id')" style="cursor: pointer;">
                            Employee ID
                            @if($sortColumn == 'emp_id')
                            <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                            <i class="fas fa-sort"></i>
                            @endif
                        </th>

                        <!-- Sortable Employee Name Column -->
                        <th class="req-table-head" wire:click="toggleSortOrder('first_name')" style="cursor: pointer;">
                            Employee Name
                            @if($sortColumn == 'first_name' || $sortColumn == 'last_name')
                            <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                            <i class="fas fa-sort"></i>
                            @endif
                        </th>

                        <!-- Sortable Email Column -->
                        <th class="req-table-head" wire:click="toggleSortOrder('email')" style="cursor: pointer;">
                            Email
                            @if($sortColumn == 'email')
                            <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                            <i class="fas fa-sort"></i>
                            @endif
                        </th>
                        @if(auth()->check() && (
                            auth()->user()->hasRole('super_admin')))
                        <th class="req-table-head">Restore</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($itRelatedEmye) && $itRelatedEmye->isNotEmpty())
                    @foreach($itRelatedEmye as $itemployee)
                    <tr>

                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>
                            @if(!empty($itemployee->image))
                            <img class="profile-image" width="50" height="38"
                                src="data:image/jpeg;base64,{{ $itemployee->image }}"  style="border-radius: 50%; object-fit: cover;">
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ $itemployee->its->it_emp_id ?? 'N/A' }}</td>
                        <td>{{ $itemployee->emp_id?? 'N/A'  }}</td>
                        <td>{{ ucwords(strtolower($itemployee->first_name . ' ' . $itemployee->last_name)) ?? 'N/A' }}
                        </td>

                        <td>{{ $itemployee->email ?? 'N/A'  }}</td>
                        <td class="d-flex flex-direction-row">

                            <!-- Restore Action -->
                            @if(auth()->check() && (
                            auth()->user()->hasRole('super_admin')))
                            <div class="col mx-1">
                                <button class="btn text-white border-white" style="background-color: #02114f;"
                                    wire:click='cancelLogout'>
                                    <i class="fas fa-undo-alt"></i>
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>

                    @endforeach
                    @else
                    <tr>
                        <td colspan="20" >

                            <div class="req-td-norecords">
                                <img src="{{ asset('images/Closed.webp') }}" alt="No Records" class="req-img-norecords">


                                <h3 class="req-head-norecords">No data found</h3>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>



    @if ($showLogoutModal)
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
                        wire:click="restore('{{ $itemployee->its->it_emp_id }}')">Restore</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
