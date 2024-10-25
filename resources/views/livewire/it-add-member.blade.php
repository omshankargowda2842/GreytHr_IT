<div class="main">


    <div wire:loading
        wire:target="submit, showEditItMember, Cancel, clearFilters,confirmDelete, delete, showAddItMember,addMember">
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


    @if($itmember)

    @if($searchFilters)
    <!-- Search Filters -->
    <div class="row mb-3 mt-4 ml-4 employeeAssetList">
        <!-- Align items to the same row with space between -->
        <div class="col-11 col-md-11 mb-2 mb-md-0">
            <div class="row d-flex justify-content-between">
                <!-- Employee ID Search Input -->
                <div class="col-4">
                    <div class="input-group task-input-group-container">
                        <input type="text" class="form-control" placeholder="Search..."
                            wire:model="searchEmp" wire:input="filter">
                    </div>
                </div>

                <!-- Add Member Button aligned to the right -->
                <div class="col-auto">
                    <button class="btn text-white btn-sm itAdd3" style="background-color: #02114f;white-space: nowrap;"
                        wire:click='addMember'>
                        <i class="fas fa-user-plus"></i> Add Member
                    </button>
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

                        <th class="req-table-head">Actions</th>
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
                                src="data:image/jpeg;base64,{{ $itemployee->image }}"
                                style="border-radius: 50%; object-fit: cover;">
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

                            <!-- Delete Action -->
                            <div class="col mx-1">
                                <button class="btn text-white border-white" style="background-color: #02114f;"
                                    wire:click="confirmDelete('{{ $itemployee->its->it_emp_id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @endforeach

                    @else
                    <tr>
                        <td colspan="20">

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
    @endif







    @if($addItmember)

    <div class="col-11 d-flex justify-content-start mb-4" style="margin-left: 5%;">
        <div class="">
            <button class="btn text-white" style="background-color: #02114f;" wire:click="Cancel" aria-label="Close">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </div>




    <div class="col-11  mt-4 itadd-maincolumn">

        <div class="d-flex justify-content-between align-items-center ">
            <h2 class="mb-4 addEditHeading">Add IT Member</h2>
        </div>

        <div class="border rounded p-3 bg-light itAdd1">
            <!-- Row for Dropdowns -->
            <div class="row mb-3 d-flex justify-content-around">

                <!-- Employee ID Dropdown -->
                <div class="col-md-5">
                    <label for="employeeSelect" class="form-label">Select Employee ID</label>
                    <select id="employeeSelect" class="form-select" wire:model="selectedEmployee"
                        wire:change="fetchEmployeeDetails">
                        <option value="">Choose Employee</option>
                        @foreach ($assetSelectEmp as $employee)
                        <option value="{{ $employee->emp_id }}" class="">
                            {{ $employee->emp_id }} - {{ ucwords($employee->first_name) }}
                            {{ ucwords($employee->last_name) }}
                        </option>

                        @endforeach
                    </select>
                    @error('selectedEmployee')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Row for Details Cards -->
            <div class="row mt-4 d-flex justify-content-around">
                <div class="col-md-4">
                    @if ($empDetails)
                    <div class="assetEmpDetailsCard p-3 mb-3">
                        <h5><u>Employee Details</u></h5>
                        <p><strong>Employee ID:</strong> {{ $empDetails->emp_id }}</p>
                        <p><strong>Employee Name:</strong> {{ $empDetails->first_name }}
                            {{ $empDetails->last_name }}</p>
                        <p><strong>Email:</strong> {{ $empDetails->email }}</p>
                        <p><strong>Department:</strong> {{ $empDetails->job_role }}</p>
                    </div>
                    @endif

                </div>
            </div>


            <!-- Submit Button -->
            <div class="mt-4 text-center">
                <button class="btn text-white" style="background-color: #02114f;" wire:click="submit">
                    Submit
                </button>

            </div>
        </div>
    </div>

    @endif



    @if ($showLogoutModal)
    <div class="modal logout1" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white logout2">
                    <h6 class="modal-title logout3" id="logoutModalLabel">Confirm Deactivation</h6>
                </div>
                <div class="modal-body text-center logout4">
                    Are you sure you want to deactivate?
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
                        @error('reason') <span class="text-danger d-flex align-start">{{ $message }}</span> @enderror
                        <div class="d-flex justify-content-center p-3">
                            <button type="submit" class="submit-btn mr-3">Deactivate</button>
                            <button type="button" class="cancel-btn1 ml-3" wire:click="Cancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif



</div>
