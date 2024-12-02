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
                                <h3 class="d-flex justify-content-start mb-5">Service Requests</h3>

                            </div>
                            <!-- <div>
            <button class="btn btn-success" style="background-color: #02114f;color:white"
                wire:click="showRecentRequest">Recent Requests</button>
        </div> -->

                        </div>

                        @if($serviceRequestDetails && $serviceRequest)

                        @if($serviceRequestDetails)
                        <button class="btn text-white float:right mb-3" style="background-color: #02114f;"
                            wire:click="closeServiceDetails" @if($loading) disabled @endif>
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

                                        <td>Service ID</td>

                                        <td class="view-td">{{$serviceRequest->snow_id ?? 'N/A' }}</td>

                                    </tr>


                                    <tr>

                                        <td>Requested By</td>

                                        <td class="view-td">{{$serviceRequest->emp->first_name }}
                                            {{$serviceRequest->emp->last_name }}
                                        </td>

                                    </tr>

                                    <tr>

                                        <td>Assigned Department</td>

                                        <td class="view-td">{{$serviceRequest->assigned_dept ?? 'N/A' }}</td>

                                    </tr>

                                    <tr>

                                        <td>Short description</td>

                                        <td class="view-td">{{$serviceRequest->short_description ??'N/A' }}</td>

                                    </tr>

                                    <tr>

                                        <td>Description</td>

                                        <td class="view-td">{{$serviceRequest->description ??'N/A' }}</td>

                                    </tr>

                                    <tr>

                                        <td>Priority</td>

                                        <td class="view-td">{{$serviceRequest->priority ??'N/A' }}</td>

                                    </tr>

                                    <tr>
                                        <td>Assign to <span class="text-danger">*</span></td>
                                        <td class="view-td">
                                            <select class="req-selected-status" wire:model="selectedAssigne"
                                                wire:change="updateAssigne('{{ $serviceRequest->id }}')">
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
                                                wire:change="handleStatusChange('{{ $serviceRequest->id }}')">
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
                                                            wire:click="postComment('{{ $serviceRequest->id }}')"
                                                            @if($loading) disabled @endif>Post</button>
                                                    </div>
                                                </div>


                                            </div>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>Attach Files</td>
                                        <td class="view-td">
                                            @if($serviceRequest->image_url)
                                            <a href="#" data-toggle="modal" class="requestAttachments"
                                                data-target="#attachmentsModal-{{ $serviceRequest->id }}">
                                                <i class="fas fa-eye"></i> View Attachments
                                            </a>
                                            @else
                                            <span>-</span>
                                            @endif

                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="attachmentsModal-{{ $serviceRequest->id }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="attachmentsModalLabel-{{ $serviceRequest->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="attachmentsModalLabel-{{ $serviceRequest->id }}">
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

                                                                <img src="{{ $serviceRequest->image_url }}"
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

                        @if($serviceDetails->count() > 0)

                        <div class="scrollable-container">
                            <div class="req-pro-card">

                                @foreach ($serviceDetails as $index => $request)

                                <div class="request-card">

                                    <div class="req-pro-card-body">

                                        <div>
                                            <p class="req-reqBy-Dep">Service ID:
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
                                            <button wire:click="viewServiceDetails({{ $index }})"
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
                        <h3 class="req-inprogress-heading">Pending Requests</h3>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="table-responsive req-table-res">
                                <table class="custom-table">
                                    @if($servicePendingDetails->count() > 0)
                                    <thead>
                                        <tr>
                                            <th scope="col" class="req-table-head">Service ID
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

                                            <th class="req-table-head">Remarks</th>
                                            <th class="req-table-head"> change Status</th>
                                            <th class="req-table-head"> Logs</th>
                                        </tr>
                                    </thead>
                                    @endif
                                    <tbody>
                                        @if($servicePendingDetails->count() > 0)
                                        @foreach ($servicePendingDetails as $index =>
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
                                            <td>{{ $record->ser_assign_to ?? 'N/A' }}</td>

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


                                            <td>
                                                <i wire:click="loadLogs('{{ $record->snow_id }}')" class="fas fa-eye"
                                                    style="cursor: pointer;"></i>
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
                                    @if($serviceInprogressDetails->count() > 0)
                                    <thead>
                                        <tr>
                                            <th scope="col" class="req-table-head">Service ID
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

                                            <th class="req-table-head">Remarks</th>
                                            <th class="req-table-head">Time tracker</th>
                                            <th class="req-table-head"> change Status</th>
                                            <th class="req-table-head"> Logs</th>
                                        </tr>
                                    </thead>
                                    @endif
                                    <tbody>
                                        @if($serviceInprogressDetails->count() > 0)
                                        @foreach ($serviceInprogressDetails as $index =>
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
                                            <td>{{ $record->ser_assign_to ?? 'N/A' }}</td>


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


                                                    @if($record->status_code == '16' && $record->ser_progress_since)
                                                    @php

                                                    $totalElapsedMinutes =
                                                    \Carbon\Carbon::parse($record->ser_progress_since)->diffInMinutes(now());

                                                    if (isset($record->total_ser_progress_time)) {
                                                    $totalElapsedMinutes += $record->total_ser_progress_time;
                                                    }

                                                    $days = floor($totalElapsedMinutes / 1440);
                                                    $remainingHours = floor(($totalElapsedMinutes % 1440) / 60);
                                                    $minutes = $totalElapsedMinutes % 60;


                                                    $maxTime = 30 * 1440;
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
                                            <td>
                                                <i wire:click="loadLogs('{{ $record->snow_id }}')" class="fas fa-eye"
                                                    style="cursor: pointer;"></i>
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
                                <select wire:model="statusFilter" wire:change='loadServiceClosedDetails'
                                    id="statusFilter" class="form-select">
                                    <option value="">All</option>
                                    <option value="11">Completed</option>
                                    <option value="15">Cancelled</option>
                                </select>
                            </div>

                            <div class="table-responsive  req-closed-table-res">

                                <table class="custom-table">
                                    @if($serviceClosedDetails->count() > 0)
                                    <thead>

                                        <tr>

                                            <th scope="col" class="req-closed-th"> Service ID
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
                                            <th class="req-table-head"> Logs</th>

                                        </tr>

                                    </thead>
                                    @endif
                                    <tbody>

                                        @if($serviceClosedDetails->count() > 0)
                                        @foreach ($serviceClosedDetails as $record)
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
                                                    wire:click='viewRecord({{ $record->id }})'> <i
                                                        class="fas fa-eye"></i></button>
                                            </td>
                                            <td>
                                                <i wire:click="loadLogs('{{ $record->snow_id }}')" class="fas fa-eye"
                                                    style="cursor: pointer;"></i>
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
                                <div class="modal fade show" id="serviceModal" tabindex="-1" role="dialog"
                                    aria-labelledby="serviceModalLabel" style="display: block;" aria-hidden="false">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="serviceModalLabel">Closed Request Details
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
                                                        <strong>Service ID:</strong>
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
                                                        <span>{{ $selectedRecord->active_ser_comment ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row service-detail-item">
                                                    <div class="col-6">
                                                        <strong>Pending Remarks:</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <span>{{ $selectedRecord->ser_pending_remarks ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row service-detail-item">
                                                    <div class="col-6">
                                                        <strong>Inprogress Remarks:</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <span>{{ $selectedRecord->ser_inprogress_remarks ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row service-detail-item">
                                                    <div class="col-6">
                                                        <strong>Assigned To:</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <span>{{ $selectedRecord->ser_assign_to ?? 'N/A' }}</span>
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
                                                    <div class="col-6">
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


                                                    <div class="col-6">
                                                        <div class="modal fade" id="attachmentsModal" tabindex="-1"
                                                            role="dialog" aria-labelledby="attachmentsModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="attachmentsModalLabel">
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



    @if ($showPopup)
    @if ($activityLogs)
    <div class="popup-overlay">
        <div class="popup-content col-11 mx-auto">
            <!-- Popup Header -->
            <div class="popup-header d-flex justify-content-between align-items-center">
                <h5 class="popup-title">Activity Logs
                    @if ($serviceIDHeader)
                    <span style="color: #4A90E2;">{{ $serviceIDHeader }}</span> <!-- Display the request ID of the first log -->
                    @else
                    <span>No Request ID</span> <!-- Fallback if no logs are present -->
                    @endif
                </h5>
                <button class="close-popup" wire:click="closePopup">×</button>
            </div>

            <!-- Popup Body -->
            <div class="popup-body">
                @if (is_countable($activityLogs) && count($activityLogs) > 0)

                <!-- Header with Activity Count -->
                <div class="activity-header d-flex justify-content-between mb-4">
                    <h3 class="text-lg font-semibold">Activities: {{ count($activityLogs) }}</h3>
                    <button wire:click="filterLogs('field-change')" class="filter-btn text-sm text-gray-500">
                        <i class="fas fa-filter"></i> Filter by Field Change
                    </button>
                </div>

                <!-- Activity Log Entries -->
                <div class="d-flex flex-column">
                    @foreach ($activityLogs as $index => $log)
                    <div class="activity-entry bg-white p-4 rounded-lg shadow-md mb-4">
                        <div class="log-header d-flex justify-content-between align-items-center">
                            <div class="log-user d-flex align-items-center">
                                <!-- Display initials -->
                                <span class="initials text-lg font-bold">
                                    {{ $employeeInitials[$index] ?? '' }}
                                </span>
                                <span class="user-name ms-4 text-sm text-gray-600">
                                    {{ $log->performed_by ?? 'Unknown' }}
                                </span>
                            </div>
                            <div class="timestamp text-sm text-gray-500">
                                Field changes <span style="font-size: 15px;">&#8226;</span>
                                {{ $log->created_at ? $log->created_at->format('d-m-Y H:i:s') : 'N/A' }}
                            </div>
                        </div>

                        <!-- Log Details -->
                        <div class="log-details mt-2">
                            @if ($log->action && $log->details)
                            <div class="log-action text-sm text-gray-800 d-flex">
                                <div class="log-label" style="width: 150px; font-weight: bold;">
                                    {{ $log->action }}
                                </div>
                                <div class="log-value" style="width: calc(100% - 150px);">
                                    {{ $log->details }}
                                </div>
                            </div>
                            @endif
                            @if ($log->assigned_to)
                            <div class="log-sub-details mt-2 text-sm">
                                <div class="log-label" style="width: 150px; font-weight: bold;">Assigned to</div>
                                <div class="log-value" style="width: calc(100% - 150px);">
                                    {{ $log->assigned_to }}
                                </div>
                            </div>
                            @endif

                            @if ($log->impact)
                            <div class="log-sub-details mt-2 text-sm">
                                <div class="log-label" style="width: 150px; font-weight: bold;">Impact</div>
                                <div class="log-value" style="width: calc(100% - 150px);">
                                    {{ $log->impact }}
                                </div>
                            </div>
                            @endif

                            @if ($log->opened_by)
                            <div class="log-sub-details mt-2 text-sm">
                                <div class="log-label" style="width: 150px; font-weight: bold;">Opened by</div>
                                <div class="log-value" style="width: calc(100% - 150px);">
                                    {{ $log->opened_by }}
                                </div>
                            </div>
                            @endif

                            @if ($log->priority)
                            <div class="log-sub-details mt-2 text-sm">
                                <div class="log-label" style="width: 150px; font-weight: bold;">Priority</div>
                                <div class="log-value" style="width: calc(100% - 150px);">
                                    {{ $log->priority }}
                                </div>
                            </div>
                            @endif

                            @if ($log->state)
                            <div class="log-sub-details mt-2 text-sm">
                                <div class="log-label" style="width: 150px; font-weight: bold;">State</div>
                                <div class="log-value" style="width: calc(100% - 150px);">
                                    {{ $log->state }}
                                </div>
                            </div>
                            @endif
                            <!-- Add more log details as needed -->
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500">No activity logs found.</p>
                @endif
            </div>
        </div>
    </div>
    @endif
    @endif

</div>
