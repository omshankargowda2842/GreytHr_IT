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


    <div class="col-11  mt-4 ml-4">

        <div class="table-responsive it-add-table-res">

            <table class="custom-table">
                <thead>
                    <tr>
                        <th scope="col" class="req-table-head">S.No</th>
                        <th class="req-table-head" wire:click="toggleSortOrder('it_emp_id')" style="cursor: pointer;">IT
                            Employee ID
                            @if($sortColumn == 'it_emp_id')
                            <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                            <i class="fas fa-sort"></i>
                            @endif
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
                        <th class="req-table-head" wire:click="toggleSortOrder('employee_name')"
                            style="cursor: pointer;">
                            Employee Name
                            @if($sortColumn == 'employee_name')
                            <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                            <i class="fas fa-sort"></i>
                            @endif
                        </th>

                        <!-- Non-sortable Image Column -->
                        <th class="req-table-head">Image</th>

                        <!-- Sortable Date Of Birth Column -->
                        <th class="req-table-head" wire:click="toggleSortOrder('date_of_birth')"
                            style="cursor: pointer;">
                            Date Of Birth
                            @if($sortColumn == 'date_of_birth')
                            <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                            <i class="fas fa-sort"></i>
                            @endif
                        </th>

                        <!-- Sortable Phone Column -->
                        <th class="req-table-head" wire:click="toggleSortOrder('phone_number')"
                            style="cursor: pointer;">
                            Phone
                            @if($sortColumn == 'phone_number')
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

                        <th class="req-table-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($itRelatedEmye->count() > 0)
                    @foreach($itRelatedEmye as $itemployee)
                    <tr>
                        <!-- <th scope="row">{{ $loop->iteration }}</th> -->
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $itemployee->it_emp_id }}</td>
                        <td>{{ $itemployee->emp_id }}</td>
                        <td>{{ ucwords(strtolower($itemployee->employee_name)) }}</td>
                        <td><img src="{{ $itemployee->image_url }}" class="oldItMemImage" alt="Image"></td>

                        <td>{{ \Carbon\Carbon::parse($itemployee->date_of_birth)->format('d-M-Y') }}</td>
                        <td>{{ $itemployee->phone_number }}</td>
                        <td>{{ $itemployee->email }}</td>
                        <td class="d-flex flex-direction-row">

                            <!-- Delete Action -->
                            <div class="col">
                                <button class="btn text-white border-white" style="background-color: #02114f;"
                                    wire:click='cancelLogout'>
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
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
                        wire:click="restore({{ $itemployee->id }})">Restore</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
