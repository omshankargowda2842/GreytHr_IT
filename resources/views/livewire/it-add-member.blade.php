<div class="main">


    <div wire:loading
        wire:target="submit, showEditItMember, cancel,cancelLogout, confirmDelete, delete, showAddItMember">
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
    <div class="d-flex justify-content-end mt-5">
        <button class="btn text-white btn-sm itAdd3" style="background-color: #02114f;" wire:click='addMember'><i
                class="fas fa-user-plus "></i>
            Add Member</button>
    </div>
    <div class="col-11  mt-4 ml-4">

        <div class="table-responsive it-add-table-res">


            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="req-table-head" scope="col">S.No</th>

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
                    @if($itRelatedEmye)
                    @foreach($itRelatedEmye as $itemployee)
                    <tr>

                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $itemployee->it_emp_id }}</td>
                        <td>{{ $itemployee->emp_id }}</td>
                        <td>{{ ucwords(strtolower($itemployee->employee_name)) }}</td>
                        <td><img src="{{ $itemployee->image_url }}" alt="Image" class="itAdd4"></td>

                        <td>{{ \Carbon\Carbon::parse($itemployee->date_of_birth)->format('d-M-Y') }}</td>
                        <td>{{ $itemployee->phone_number }}</td>
                        <td>{{ $itemployee->email }}</td>
                        <td class="d-flex flex-direction-row">
                            <!-- Edit Action -->
                            <div class="col mx-1">
                                <button class="btn btn-white border-dark"
                                    wire:click="showEditItMember({{ $itemployee->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>

                            <!-- Delete Action -->
                            <div class="col mx-1">
                                <button class="btn text-white border-white" style="background-color: #02114f;"
                                    wire:click='cancelLogout'>
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







    @if($itmember1)

    <button wire:click='Cancel'>Back</button>



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
                            {{ $employee->emp_id }} - {{ $employee->first_name }} {{ $employee->last_name }}

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

</div>
