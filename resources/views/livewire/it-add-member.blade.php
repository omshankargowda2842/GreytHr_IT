<div class="main">
<div class="row">
    @if($showAddIt)
    <div class="col-11  mt-4 itadd-maincolumn">
    <div wire:loading.delay>
                <div class="loader-overlay">
                    <div class="loader"></div>
                </div>
            </div>
        <div class="d-flex justify-content-between align-items-center ">
            <h2 class="mb-4 addEditHeading">{{ $editMode ? 'Edit IT Member' : 'Add IT Member' }}</h2>
            <button class="btn btn-dark btn-sm" wire:click='cancel'> <i class="fas fa-arrow-left"></i> Back</button>
        </div>

        <div class="border rounded p-3 bg-light itAdd1">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="employeeId" class="form-label">Employee ID</label>
                    <select id="employeeId" wire:model="employeeId" wire:change="updateEmployeeName" class="form-select"
                        {{ $editMode ? 'disabled' : '' }}>
                        <option value="" selected>Select Employee ID</option>
                        @foreach($itMembers as $member)
                        <option value="{{ $member->emp_id}}">{{ $member->emp_id }}</option>
                        @endforeach
                    </select>
                    @error('employeeId') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="employeeName" class="form-label">Employee Name</label>
                    <input type="text" id="employeeName" wire:model="employeeName"
                        wire:keydown.debounce.500ms="validateField('employeeName')" class="form-control" readonly>
                    @error('employeeName') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="dateOfBirth" class="form-label">Date Of Birth</label>
                    <input type="date" id="dateOfBirth" wire:model="dateOfBirth"
                        wire:keydown.debounce.500ms="validateField('dateOfBirth')" class="form-control" readonly>
                    @error('dateOfBirth') <div class="text-danger">{{ $message }}</div> @enderror
                </div>


                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="image" wire:model.lazy="image" class="form-control">

                    @if ($editMode && $image)
                    <!-- Use the temporary URL if it's a newly uploaded image -->
                    @if (is_string($image))
                    <img src="{{ $image }}" class="itAdd2" alt="Image" >
                    @else
                    <img src="{{ $image->temporaryUrl() }}" class="itAdd2" alt="Image" >
                    @endif
                    @elseif ($editMode && $this->image)
                    <!-- Display the existing image from the database -->
                    <img src="{{ $this->image }}" alt="Image" class="itAdd2" >
                    @endif

                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="number" id="phoneNumber" wire:model="phoneNumber"
                        wire:keydown.debounce.500ms="validateField('phoneNumber')" class="form-control"
                        {{ $editMode ? '' : 'readonly' }}>
                    @error('phoneNumber') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" wire:model="email" wire:keydown.debounce.500ms="validateEmail"
                        class="form-control" {{ $editMode ? '' : 'readonly' }}>
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>


                <button type="submit" class="btn btn-dark border-white">{{ $editMode ? 'Update' : 'Submit' }}</button>
            </form>
        </div>
    </div>
    @endif

    @if($showEditDeleteIt)

    <div class="d-flex justify-content-end mt-5">
        <button class="btn btn-dark btn-sm itAdd3" wire:click='showAddItMember'><i
                class="fas fa-user-plus "></i> Add Member</button>
    </div>
    <div class="col-11  mt-4 ml-4">
        <div class="table-responsive it-add-table-res">

            <div wire:loading.delay>
                <div class="loader-overlay">
                    <div class="loader"></div>
                </div>
            </div>
            <table class="table  table-striped">
                <thead class="table-dark">
                    <tr>

                        <th class="req-table-head">It Employee Id</th>
                        <th class="req-table-head">Employee Name</th>
                        <th class="req-table-head">Image</th>
                        <th class="req-table-head">Employee Id</th>
                        <th class="req-table-head">Date Of Birth</th>
                        <th class="req-table-head">Phone Number</th>
                        <th class="req-table-head">Email</th>
                        <th class="req-table-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itRelatedEmye as $itemployee)
                    <tr>
                        <!-- <th scope="row">{{ $loop->iteration }}</th> -->
                       
                        <td>{{ $itemployee->it_emp_id }}</td>
                        <td>{{ $itemployee->employee_name }}</td>
                        <td><img src="{{ $itemployee->image_url }}" alt="Image" class="itAdd4"></td>
                        <td>{{ $itemployee->emp_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($itemployee->date_of_birth)->format('d-M-Y') }}</td>
                        <td>{{ $itemployee->phone_number }}</td>
                        <td>{{ $itemployee->email }}</td>
                        <td class="d-flex flex-direction-row">
                            <!-- Edit Action -->
                            <div class="col">
                                <button class="btn btn-white border-dark"
                                    wire:click="showEditItMember({{ $itemployee->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>

                            <!-- Delete Action -->
                            <div class="col">
                                <button class="btn btn-dark border-white" wire:click='cancelLogout'>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif


    @if ($showLogoutModal)
    <div class="modal logout1" id="logoutModal" tabindex="-1" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white logout2" >
                    <h6 class="modal-title logout3" id="logoutModalLabel" >Confirm Delete</h6>
                </div>
                <div class="modal-body text-center logout4">
                    Are you sure you want to delete?
                </div>
                <div class="modal-body text-center">
                    <form wire:submit.prevent="delete">
                    <span class="text-danger d-flex align-start">*</span>
                        <div class="row">
                            <div class="col-12 req-remarks-div">

                                <textarea wire:model.lazy="reason" class="form-control req-remarks-textarea logout5"   placeholder="Reason for deactivation"></textarea>

                            </div>
                        </div>
                        @error('reason') <span class="text-danger d-flex align-start">{{ $message }}</span>@enderror
                        <div class="d-flex justify-content-center p-3">
                        <button type="submit" class="submit-btn mr-3" wire:click="confirmDelete({{ $itemployee->id }})"  @if($loading) disabled @endif>Delete</button>
                            <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- <div class="modal-body text-center">
                    <form wire:submit.prevent="postRemarks('{{ $itemployee->id }}')">
                        <div class="row">
                            <div class="col-12 req-remarks-div">
                                <textarea wire:model.lazy="remarks.{{ $itemployee->id }}" class="form-control req-remarks-textarea" placeholder="Reason for deletion"></textarea>
                                <button type="submit" class="btn btn-dark ml-2" @if($loading) disabled @endif>Post</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-center p-3">
                    <button type="button" class="submit-btn mr-3"
                        wire:click="delete({{ $itemployee->id }})">Delete</button>
                    <button type="button" class="cancel-btn1 ml-3" wire:click="cancel">Cancel</button>
                </div> -->
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

</div>
</div>
