<div class="main">



    <div class="col-lg-12 col-md-7 col-xs-12">
        <div class="req-pro-head">

            <req-pro-nav class="req-pro-req-pro-nav mb-4">

                <ul class="tabss">

                    <li class="tab text-white" wire:click="setActiveTab('active')">

                        <i class="fas fa-check"></i> Active

                        @if($activeTab === 'active')
                        <img class="req-active-tick"
                            src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png"
                            alt="">
                        @endif
                    </li>


                    <li class="tab text-white" wire:click="setActiveTab('pending')">

                        <i class="fas fa-clock"></i> Pending

                        @if($activeTab === 'pending')
                        <img class="req-active-tick"
                            src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png"
                            alt="">
                        @endif

                    </li>


                    <li class="tab text-white" wire:click="setActiveTab('inprogress')">

                        <i class="fas fa-times"></i> Inprogress

                        @if($activeTab === 'inprogress')
                        <img class="req-active-tick"
                            src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png"
                            alt="">
                        @endif

                    </li>


                    <li class="tab text-white" wire:click="setActiveTab('closed')">

                        <i class="fas fa-times"></i> Closed

                        @if($activeTab === 'closed')
                        <img class="req-active-tick"
                            src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png"
                            alt="">
                        @endif

                    </li>




                </ul>

            </req-pro-nav>



        </div>



        <div class="mt-2">

            <div class="req-main-page">
                <div id="active" class="req-pro-tab-content"
                    style="display: {{ $activeTab === 'active' ? 'block' : 'none' }};">



                    <div class="col-lg-9 col-md-10 col-xs-12" style="margin-left: 4%;">

                        <div class="d-flex  justify-content-between mb-4">
                            <div>
                                <h3 class="d-flex justify-content-start mb-5">Incident Requests</h3>

                            </div>
                            <!-- <div>
            <button class="btn btn-success" style="background-color: #02114f;color:white"
                wire:click="showRecentRequest">Recent Requests</button>
        </div> -->

                        </div>

                        @if($incidentRequestDetails && $incidentRequest)

                        @if($incidentRequestDetails)
                        <button class="btn text-white float:right mb-3" style="background-color: #02114f;"
                            wire:click="closeincidentDetails" @if($loading) disabled @endif>
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        @endif
                        <div class="req-pro-tablediv">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="m-2">View Details</h5>

                            </div>

                            <table class="table table-bordered mt-3 req-pro-table">



                                <tbody>
                                    <tr>

                                        <td>Incident ID</td>

                                        <td class="view-td">{{$incidentRequest->snow_id ?? 'N/A' }}</td>

                                    </tr>


                                    <tr>

                                        <td>Requested By</td>

                                        <td class="view-td">{{$incidentRequest->emp->first_name }}
                                            {{$incidentRequest->emp->last_name }}
                                        </td>

                                    </tr>

                                    <tr>

                                        <td>Assigned Department</td>

                                        <td class="view-td">{{$incidentRequest->assigned_dept ?? 'N/A' }}</td>

                                    </tr>

                                    <tr>

                                        <td>Short description</td>

                                        <td class="view-td">{{$incidentRequest->short_description ??'N/A' }}</td>

                                    </tr>

                                    <tr>

                                        <td>Description</td>

                                        <td class="view-td">{{$incidentRequest->description ??'N/A' }}</td>

                                    </tr>

                                    <tr>

                                        <td>Priority</td>

                                        <td class="view-td">{{$incidentRequest->priority ??'N/A' }}</td>

                                    </tr>

                                    <tr>
                                        <td>Assign to <span class="text-danger">*</span></td>
                                        <td class="view-td">
                                            <select class="req-selected-status" wire:model="selectedAssigne"
                                                wire:change="updateAssigne('{{ $incidentRequest->id }}')">
                                                <option value="" disabled hidden>Select Assignee</option>
                                                @foreach($itData as $itName)
                                                <option
                                                    value="{{ $itName->empIt->first_name }} {{ $itName->empIt->last_name }} {{ $itName->empIt->emp_id }}">
                                                    {{ ucwords(strtolower($itName->empIt->first_name)) }}
                                                    {{ ucwords(strtolower($itName->empIt->last_name)) }}
                                                    {{ ucwords(strtolower($itName->empIt->emp_id)) }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('selectedAssigne') <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </td>

                                    </tr>

                                    <tr>
                                        <td>Status <span class="text-danger">*</span></td>

                                        <td class="view-td">
                                            <select wire:model="selectedStatus" class="req-selected-status"
                                                wire:change="handleStatusChange('{{ $incidentRequest->id }}')">
                                                <option value="" disabled hidden>Select Status </option>
                                                <option value="5">Pending</option>
                                                <option value="16">Inprogress</option>
                                                <option value="11">Completed</option>
                                                <option value="15">Cancel</option>
                                                <!-- Add other status options as needed -->
                                            </select>
                                            @error('selectedStatus') <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Comments</td>
                                        <td>

                                            <div>
                                                <div class="row">
                                                    <div class="col-10">
                                                        <textarea wire:model.lazy="comments"
                                                            class="form-control"></textarea>
                                                    </div>
                                                    <div class="col-2 d-flex align-items-center">
                                                        <button class="btn text-white"
                                                            style="background-color: #02114f;"
                                                            wire:click="postComment('{{ $incidentRequest->id }}')"
                                                            @if($loading) disabled @endif>Post</button>
                                                    </div>
                                                </div>


                                            </div>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>Attach Files</td>
                                        <td class="view-td">
                                            @if($incidentRequest->image_url)
                                            <a href="#" data-toggle="modal" class="requestAttachments"
                                                data-target="#attachmentsModal-{{ $incidentRequest->id }}">
                                                <i class="fas fa-eye"></i> View Attachments
                                            </a>
                                            @else
                                            <span>-</span>
                                            @endif

                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="attachmentsModal-{{ $incidentRequest->id }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="attachmentsModalLabel-{{ $incidentRequest->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="attachmentsModalLabel-{{ $incidentRequest->id }}">
                                                        Attachments</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">

                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Swiper -->
                                                    <div class="swiper-container">
                                                        <div class="swiper-wrapper">

                                                            <div class="swiper-slide reqResSwiper">

                                                                <img src="{{ $incidentRequest->image_url }}"
                                                                    class="req-Res-Image" alt="Image">
                                                            </div>
                                                        </div>
                                                        <!-- Add Pagination -->
                                                        <div class="swiper-pagination"></div>
                                                        <!-- Add Navigation -->
                                                        <div class="swiper-button-next"></div>
                                                        <div class="swiper-button-prev"></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn text-white"
                                                        style="background-color: #02114f;"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                </tbody>

                            </table>

                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn text-white mb-3" style="background-color: #02114f;"
                                    wire:click="redirectBasedOnStatus" @if($loading) disabled @endif>Submit</button>
                            </div>

                        </div>



                        @else

                        @if($incidentDetails->count() > 0)

                        <div class="scrollable-container">
                            <div class="req-pro-card">

                                @foreach ($incidentDetails as $index => $request)

                                <div class="request-card">

                                    <div class="req-pro-card-body">

                                        <div>
                                            <p class="req-reqBy-Dep">Incident ID:
                                                <span class="req-res-depart1">

                                                    {{ $request->snow_id }}

                                                </span>
                                            </p>

                                            <p class="req-reqBy-Dep">Requested By:
                                                <span class="req-res-depart1">
                                                    {{ $request->emp->first_name }}
                                                    {{ $request->emp->last_name }}
                                                </span>
                                            </p>


                                        </div>

                                        <div class="p-3">
                                            <button wire:click="viewincidentDetails({{ $index }})"
                                                class="req-pro-view-details-btn" @if($loading) disabled
                                                @endif>View</button>
                                        </div>


                                    </div>

                                </div>

                                @endforeach

                            </div>
                        </div>

                        @else
                        <div class="req-requestnotfound">
                            <td colspan="20">

                                <div class="req-td-norecords">
                                    <img src="{{ asset('images/Closed.webp') }}" alt="No Records"
                                        class="req-img-norecords">

                                    <h3 class="req-head-norecords">No requests found
                                    </h3>
                                </div>
                            </td>
                        </div>
                        @endif
                        @endif




                    </div>






                </div>

                <div id="pending" class="req-pro-tab-content"
                    style="display: {{ $activeTab === 'pending' ? 'block' : 'none' }};">



                    <div>
                        <h3 class="req-inprogress-heading">In Progress Requests</h3>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="table-responsive req-table-res">
                                <table class="custom-table">
                                    @if($incidentPendingDetails->count() > 0)
                                    <thead>
                                        <tr>
                                            <th scope="col" class="req-table-head">Incident ID
                                                <span wire:click.debounce.500ms="toggleSortOrder('snow_id')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'snow_id')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Category
                                                <span wire:click.debounce.500ms="toggleSortOrder('category')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'category')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Short Description</th>
                                            <th class="req-table-head">Description</th>
                                            <th class="req-table-head">Priority
                                                <span wire:click.debounce.500ms="toggleSortOrder('priority')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'priority')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Assigned Department</th>
                                            <th class="req-table-head">File(s)</th>
                                            <th class="req-table-head">Status</th>
                                            <th class="req-table-head">Assigned To
                                                <span wire:click.debounce.500ms="toggleSortOrder('assign_to')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'assign_to')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Active Comments</th>

                                            <th class="req-table-head">Remarks</th>
                                            <th class="req-table-head"> change Status</th>
                                        </tr>
                                    </thead>
                                    @endif
                                    <tbody>
                                        @if($incidentPendingDetails->count() > 0)
                                        @foreach ($incidentPendingDetails as $index =>
                                        $record)

                                        <tr>
                                            <td>{{ $record->snow_id }}</td>
                                            <td>{{ $record->category ?? 'N/A' }}</td>
                                            <td>{{ $record->short_description ?? 'N/A' }}</td>
                                            <td>{{ $record->description ?? 'N/A' }}</td>
                                            <td>{{ $record->priority ?? 'N/A' }}</td>
                                            <td>{{ $record->assigned_dept ?? 'N/A' }}</td>
                                            <td>
                                                -
                                            </td>
                                            <td>
                                                @if($record->status_code == 5) Pending

                                                @endif
                                            </td>
                                            <td>{{ $record->inc_assign_to ?? 'N/A' }}</td>
                                            <td>{{ $record->active_inc_comment ?? 'N/A' }}</td>

                                            <td>
                                                <form wire:submit.prevent="postRemarks('{{ $record->id }}')">
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center">
                                                            <textarea wire:model.lazy="remarks"
                                                                class="form-control me-2 req-remarks-textarea"
                                                                placeholder="Enter remarks here..."></textarea>
                                                            <button type="submit"
                                                                class="btn btn-primary text-white p-2">Post</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>


                                            <td>
                                                <button wire:click="inprogressForDesks('{{ $record->id }}')"
                                                    class="btn btn-white border-black text-black" @if($loading) disabled
                                                    @endif>Inprogress</button>
                                            </td>
                                        </tr>


                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="12">
                                                <div class="req-td-norecords">
                                                    <img src="{{ asset('images/Closed.webp') }}" alt="No Records"
                                                        class="req-img-norecords">
                                                    <h3 class="req-head-norecords">No records found</h3>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>


                <div id="inprogress" class="req-pro-tab-content"
                    style="display: {{ $activeTab === 'inprogress' ? 'block' : 'none' }};">


                    <div>
                        <h3 class="req-inprogress-heading">In Progress Requests</h3>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="table-responsive req-table-res">
                                <table class="custom-table">
                                    @if($incidentInprogressDetails->count() > 0)
                                    <thead>
                                        <tr>
                                            <th scope="col" class="req-table-head">Incident ID
                                                <span wire:click.debounce.500ms="toggleSortOrder('snow_id')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'snow_id')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Category
                                                <span wire:click.debounce.500ms="toggleSortOrder('category')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'category')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Short Description</th>
                                            <th class="req-table-head">Description</th>
                                            <th class="req-table-head">Priority
                                                <span wire:click.debounce.500ms="toggleSortOrder('priority')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'priority')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Assigned Department</th>
                                            <th class="req-table-head">File(s)</th>
                                            <th class="req-table-head">Status</th>
                                            <th class="req-table-head">Assigned To
                                                <span wire:click.debounce.500ms="toggleSortOrder('assign_to')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'assign_to')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>
                                            <th class="req-table-head">Active Comments</th>
                                            <th class="req-table-head">Pending Comments</th>

                                            <th class="req-table-head">Remarks</th>
                                            <th class="req-table-head">Time tracker</th>
                                            <th class="req-table-head"> change Status</th>
                                        </tr>
                                    </thead>
                                    @endif
                                    <tbody>
                                        @if($incidentInprogressDetails->count() > 0)
                                        @foreach ($incidentInprogressDetails as $index =>
                                        $record)

                                        <tr>
                                            <td>{{ $record->snow_id }}</td>
                                            <td>{{ $record->category ?? 'N/A' }}</td>
                                            <td>{{ $record->short_description ?? 'N/A' }}</td>
                                            <td>{{ $record->description ?? 'N/A' }}</td>
                                            <td>{{ $record->priority ?? 'N/A' }}</td>
                                            <td>{{ $record->assigned_dept ?? 'N/A' }}</td>
                                            <td>
                                                -
                                            </td>
                                            <td>
                                                @if($record->status_code == 5) Pending

                                                @endif
                                            </td>
                                            <td>{{ $record->inc_assign_to ?? 'N/A' }}</td>
                                            <td>{{ $record->active_inc_comment ?? 'N/A' }}</td>
                                            <td>{{ $record->inc_pending_remarks ?? 'N/A' }}</td>

                                            <td>
                                                <form wire:submit.prevent="postInprogressRemarks('{{ $record->id }}')">
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center">
                                                            <textarea wire:model.lazy="remarks"
                                                                class="form-control me-2 req-remarks-textarea"
                                                                placeholder="Enter remarks here..."></textarea>
                                                            <button type="submit"
                                                                class="btn btn-primary text-white p-2">Post</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>


                                            <td>

                                                <div class="req-timebar">


                                                    @if($record->status_code == '16' && $record->in_progress_since)
                                                    @php

                                                    $totalElapsedMinutes =
                                                    \Carbon\Carbon::parse($record->in_progress_since)->diffInMinutes(now());

                                                    if (isset($record->total_in_progress_time)) {
                                                    $totalElapsedMinutes += $record->total_in_progress_time;
                                                    }

                                                    $days = floor($totalElapsedMinutes / 1440);
                                                    $remainingHours = floor(($totalElapsedMinutes % 1440) / 60);
                                                    $minutes = $totalElapsedMinutes % 60;


                                                    $maxTime = 7 * 1440;
                                                    $percentage = min(($totalElapsedMinutes / $maxTime) * 100, 100);


                                                    @endphp

                                                    <!-- Display elapsed time based on the total elapsed time -->
                                                    @if ($totalElapsedMinutes < 60) <span>{{ $minutes }} minutes</span>
                                                        @elseif ($totalElapsedMinutes < 1440) <span>
                                                            {{ $remainingHours }} hours {{ $minutes }} minutes</span>
                                                            @else
                                                            <span>{{ $days }} days {{ $remainingHours }} hours
                                                                {{ $minutes }} minutes</span>
                                                            @endif

                                                            <!-- Custom Progress Bar -->
                                                            <div class="custom-progress">
                                                                <div class="custom-progress-bar"
                                                                    style="width: {{ $percentage }}%"
                                                                    aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                    <span
                                                                        class="progress-text">{{ round($percentage) }}%</span>
                                                                </div>
                                                            </div>

                                                            @else
                                                            <span>No time tracked</span>
                                                            @endif

                                                </div>
                                            </td>





                                            <td>
                                                <div class="req-changeStatus ">
                                                    <button wire:click="pendingForDesks('{{ $record->id }}')"
                                                        class="btn btn-white border-black text-black" @if($loading)
                                                        disabled @endif>Pending</button>

                                                    <button wire:click="closeForDesks('{{ $record->id }}')"
                                                        class="btn btn-white border-black text-black" @if($loading)
                                                        disabled @endif>Close</button>
                                                </div>

                                            </td>
                                        </tr>


                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="12">
                                                <div class="req-td-norecords">
                                                    <img src="{{ asset('images/Closed.webp') }}" alt="No Records"
                                                        class="req-img-norecords">
                                                    <h3 class="req-head-norecords">No records found</h3>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>


                <div id="closed" class="req-pro-tab-content"
                    style="display: {{ $activeTab === 'closed' ? 'block' : 'none' }};">




                    <div>
                        <h3 class="req-closed-head">
                            Closed Requests</h3>
                    </div>
                    <div class="row">

                        <div class="col-12 mt-2">

                            <div class="col-3">
                                <label for="statusFilter" class="form-label">Filter by Status</label>
                                <select wire:model="statusFilter" wire:change='loadIncidentClosedDetails'
                                    id="statusFilter" class="form-select">
                                    <option value="">All</option>
                                    <option value="11">Completed</option>
                                    <option value="15">Cancelled</option>
                                </select>
                            </div>

                            <div class="table-responsive  req-closed-table-res">

                                <table class="custom-table">
                                    @if($incidentClosedDetails->count() > 0)
                                    <thead>

                                        <tr>

                                            <th scope="col" class="req-closed-th"> Incident ID
                                                <span wire:click.debounce.500ms="toggleSortOrder('snow_id')"
                                                    style="cursor: pointer;">
                                                    @if($sortColumn == 'snow_id')
                                                    <i
                                                        class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </span>
                                            </th>

                                            <th class="req-closed-th"> Requested By</th>
                                            <th class="req-closed-th"> Service Request</th>

                                            <th class="req-closed-th">Short Description
                                            </th>
                                            <th class="req-closed-th">Status</th>
                                            <th class="req-closed-th">View</th>

                                        </tr>

                                    </thead>
                                    @endif
                                    <tbody>

                                        @if($incidentClosedDetails->count() > 0)
                                        @foreach ($incidentClosedDetails as $record)
                                        <tr>

                                            <td scope="row">{{ $record->snow_id }}</td>

                                            <td>{{ $record->emp->first_name }} {{ $record->emp->last_name }}
                                                <br>
                                                <strong class="req-res-emp_id">({{$record->emp_id}})
                                            </td>

                                            <td>{{ $record->category ?? 'N/A'}}</td>
                                            <td>{{ $record->short_description ?? 'N/A'}}</td>
                                            <td>
                                                <div class="req-status-closed
                                                @if($record['status_code'] == 11)
                                                    status-completed
                                                @elseif($record['status_code'] == 15)
                                                    status-cancelled
                                                @else
                                                    status-default
                                                @endif">
                                                    {{
                                                    $record['status_code'] == 11 ? 'Completed' :
                                                    ($record['status_code'] == 15 ? 'Cancelled' : 'N/A')
                                                }}
                                                </div>
                                            </td>



                                            <td>
                                                <button class="btn" style="background-color: #02114f;color:white"
                                                    wire:input="viewRecord({{ $record->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>

                                        </tr>





                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="20">

                                                <div class="req-td-norecords">
                                                    <img src="{{ asset('images/Closed.webp') }}" alt="No Records"
                                                        class="req-img-norecords">

                                                    <h3 class="req-head-norecords">No records found
                                                    </h3>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <!-- Modal for displaying record details -->
                                @if($showModal)
                                <div class="modal fade show" id="incidentModal" tabindex="-1" role="dialog"
                                    aria-labelledby="incidentModalLabel" style="display: block;" aria-hidden="false">
                                    <div class="modal-dialog col-11" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="incidentModalLabel">Closed Request Details
                                                </h5>
                                                <button type="button" class="close p-2" wire:click="closeModal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="service-details">
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Incident ID:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->snow_id ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Requested By:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>
                                                                {{ $selectedRecord->emp->first_name ?? 'N/A' }}
                                                                {{ $selectedRecord->emp->last_name ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Category:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->category ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Short Description:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->short_description ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Description:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->description ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Priority:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->priority ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Active Comments:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->active_inc_comment ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Pending Remarks:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->inc_pending_remarks ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Inprogress Remarks:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->inc_inprogress_remarks ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Assigned To:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span>{{ $selectedRecord->inc_assign_to ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong>Status:</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span class="
                                                                    {{ $selectedRecord && $selectedRecord->status_code == 11 ? 'text-success' : '' }}
                                                                    {{ $selectedRecord && $selectedRecord->status_code == 15 ? 'text-danger' : '' }}
                                                                ">
                                                                {{
                                                                    $selectedRecord ?
                                                                    ($selectedRecord->status_code == 11 ? 'Completed' :
                                                                    ($selectedRecord->status_code == 15 ? 'Cancelled' : 'N/A'))
                                                                    : 'N/A'
                                                                }}
                                                            </span>
                                                        </div>


                                                    </div>


                                                    <!-- Display files if available -->
                                                    <div id="modalFiles" class="service-detail-item">
                                                        @if (isset($selectedRecord->file_path))
                                                        <strong>Attachments:</strong>
                                                        <!-- Button to trigger the modal -->
                                                        <button type="button" class="btn btn-link" data-toggle="modal"
                                                            data-target="#attachmentsModal">
                                                            View Attachments
                                                        </button>
                                                        @else
                                                        <p>No files attached.</p>
                                                        @endif
                                                    </div>


                                                    <div class="modal fade" id="attachmentsModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="attachmentsModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="attachmentsModalLabel">
                                                                        Attachments
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if (isset($selectedRecord->file_path))
                                                                    @php
                                                                    // Convert the BLOB data to base64
                                                                    $base64Image =
                                                                    base64_encode($selectedRecord->file_path);
                                                                    @endphp

                                                                    <!-- Render the BLOB image directly if it's base64 -->
                                                                    <div class="mb-3">
                                                                        <img src="data:image/jpeg;base64,{{ $base64Image }}"
                                                                            class="img-fluid" alt="Attachment" />
                                                                    </div>
                                                                    @else
                                                                    <p>No attachments available.</p>
                                                                    @endif
                                                                </div>



                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row service-detail-item">
                                                        <div class="col-6">
                                                            <strong></strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <span></span>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    wire:click="$set('selectedRecord', null)">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endif
                                @if($selectedRecord)
                                <div class="modal-backdrop fade show" style="background-color: rgba(0, 0, 0, 0.7);">
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>





                </div>

            </div>

        </div>

    </div>







</div>
