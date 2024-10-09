<div class="main">
    <div class="col-11  mt-4 ml-4">
    <div class="col-10 d-flex justify-content-center">
            @if (session()->has('updateMessage'))
            <div id="flash-message" class="alert alert-success mt-1">
                {{ session('updateMessage') }}
            </div>
            @endif

        </div>
        <div class="table-responsive it-add-table-res">

            <div wire:loading.delay>
                <div class="loader-overlay">
                    <div class="loader"></div>
                </div>
            </div>
            <table class="table  table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="req-table-head">Id</th>
                        <th class="req-table-head">It Employee Id</th>
                        <th class="req-table-head">Employee Id</th>
                        <th class="req-table-head">Employee Name</th>
                        <th class="req-table-head">Image</th>

                        <th class="req-table-head">Date Of Birth</th>
                        <th class="req-table-head">Phone</th>
                        <th class="req-table-head">Email</th>
                        <th class="req-table-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($itRelatedEmye->count() > 0)
                    @foreach($itRelatedEmye as $itemployee)
                    <tr>
                        <!-- <th scope="row">{{ $loop->iteration }}</th> -->
                        <td>{{ $itemployee->id }}</td>
                        <td>{{ $itemployee->it_emp_id }}</td>
                        <td>{{ $itemployee->emp_id }}</td>
                        <td>{{ ucwords(strtolower($itemployee->employee_name)) }}</td>
                        <td><img src="{{ $itemployee->image_url }}" class="oldItMemImage" alt="Image" ></td>

                        <td>{{ \Carbon\Carbon::parse($itemployee->date_of_birth)->format('d-M-Y') }}</td>
                        <td>{{ $itemployee->phone_number }}</td>
                        <td>{{ $itemployee->email }}</td>
                        <td class="d-flex flex-direction-row">

                            <!-- Delete Action -->
                            <div class="col">
                                <button class="btn text-white border-white" style="background-color: #02114f;" wire:click='cancelLogout'>
                                    <i class="fas fa-undo"></i>
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
                <div class="modal-header text-white logout2" >
                    <h6 class="modal-title logout3" id="logoutModalLabel">Confirm Restore</h6>
                </div>
                <div class="modal-body text-center logout4" >
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
