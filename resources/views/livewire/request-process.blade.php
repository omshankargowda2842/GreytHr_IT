<div>
    <style>
        body,
        html {

            margin: 0;

            padding: 0;

            font-size: 0.875rem;
            overflow-x: hidden;

        }

        .req-pro-req-pro-nav {

            width: 66%;

        }

        @media (max-width: 992px) {
            .req-pro-req-pro-nav {
                width: 89%;
            }
        }

        .req-pro-head {

            display: flex;

            justify-content: center;

            margin-top: 3%;

        }

        req-pro-nav ul {

            list-style: none;

            display: flex;

            justify-content: space-around;

            margin-top: 10px;

            padding: 0;

        }

        req-pro-nav .tab {

            cursor: pointer;

            padding: 10px 20px;

            border-radius: 5px;

            background-color: #007BFF;

            color: white;

            display: flex;

            align-items: center;

            position: relative;

            margin-right: 10px;

            width: 20%;

            justify-content: center;

        }

        req-pro-nav .tab i {

            margin-right: 10px;

        }

        req-pro-nav .tab::after {

            content: '';

            position: absolute;

            top: 0;

            right: -12px;

            border-top: 23px solid transparent;

            border-bottom: 21px solid transparent;

            border-left: 15px solid #007BFF;

        }

        req-pro-nav .tab:nth-child(1) {

            background-color: #e2ad17;

        }

        req-pro-nav .tab:nth-child(2) {

            background-color: #87B24B;

        }

        req-pro-nav .tab:nth-child(3) {

            background-color: #4499DD;

        }

        req-pro-nav .tab:nth-child(1)::after {

            border-left-color: #e2ad17;

        }

        req-pro-nav .tab:nth-child(2)::after {

            border-left-color: #87B24B;

        }

        req-pro-nav .tab:nth-child(3)::after {

            border-left-color: #4499DD;

        }

        .req-pro-tab-content {

            display: none;

            margin-left: 20px;

            width: 87%;

        }

        .req-pro-tab-content h3 {

            margin-top: 0;

        }

        .request-card {

            border: 1px solid #ccc;

            border-radius: 30px;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);

            margin-bottom: 20px;

            padding: 10px;

            background-color: #eeeeee;

        }

        .req-pro-card {

            display: flex;

            flex-wrap: wrap;

            gap: 18px;

        }

        .req-pro-card-body {

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 10px;

        }

        .req-pro-card-body p {

            margin: 5px 0;

        }

        .req-pro-view-details-btn {

            background-color: #000000;

            color: white;

            border: 3px solid white;

            padding: 8px 16px;

            border-radius: 15px;

            cursor: pointer;

            transition: background-color 0.3s ease;
        }

        .req-pro-view-details-btn:hover {
            background-color: #008000;
        }

        .request-card {
            flex: 1 1 calc(33.33% - 36px);
            box-sizing: border-box;
        }

        .req-pro-table th {
            text-align: center;
            vertical-align: middle;
            background-color: #f8f8f8;
            color: #333;
        }

        .req-pro-details {
            display: flex;
            justify-content: space-between;
        }

        .req-pro-tablediv {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }

        .req-pro-overview-card {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
            width: 100%
        }

        .req-pro-overview-card .card {
            padding-bottom: 40px;
            transition: transform 0.3s ease;
        }

        .req-pro-overview-card .card:hover {
            transform: scale(1.05);
            /* Scale up the card slightly on hover */
        }

        .req-pro-col3 {
            padding-right: 25px;
            display: flex;
            flex-direction: column;
            margin-top: 7%;
            background-color: #eeeeee;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            margin-bottom: 3%;
            height: max-content;
        }

        .req-pro-arrow {
            position: absolute;
            bottom: -22px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            color: #000;
        }

        .req-pro-overview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .req-pro-over-card1 {
            background-color: rgb(251 221 132);
        }

        .req-pro-over-card2 {
            background-color: #b6f19b;
        }

        .req-pro-over-card3 {
            background-color: #8bc1e4;
        }

        .req-pro-overview-val {
            width: 25px;
            height: 25px;
            margin-left: 5px;
        }

        .req-pro-dropdown-arrow {
            background: white;
            width: fit-content;
            padding: 6px;
            border-radius: 15px;
            border: 2px solid black;
            transition: transform 0.3s ease;
        }

        .rotated {
            transform: rotate(180deg);
        }

        .modal-dialog.custom-modal {
            max-width: 60%;
            height: 60vh;
        }

        .modal-content.custom-modal-content {
            height: 100%;
        }

        .img-fullscreen {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .image-grid img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }

        .image-grid a {
            display: block;
        }

        @media (min-width: 768px) and (max-width: 1220px) {
            .req-pro-head {
                width: 110%;
            }

            req-pro-nav .tab {
                width: 26%;
            }
        }

        @media (min-width: 360px) and (max-width: 550px) {
            .req-pro-main-page {
                display: flex;
                flex-direction: column;
            }

            req-pro-nav .tab {
                width: 25%;
            }

            req-pro-nav .tab {
                width: 20%;
            }
        }

        @media (max-width: 768px) {
            .request-card {
                flex: 1 1 calc(50% - 36px);
            }

            .req-pro-tab-content {
                margin: 10px;
            }

            .req-pro-overview-container {
                margin: 20px;
            }

            req-pro-nav .tab {
                width: 29%;
            }
        }

        @media (max-width: 480px) {
            .request-card {
                flex: 1 1 calc(100% - 18px);
            }

            req-pro-nav .tab {
                width: 29%;
            }

            req-pro-nav .tab:nth-child(1) {
                font-size: 10px;
            }

            req-pro-nav .tab:nth-child(2) {
                font-size: 10px;
            }

            req-pro-nav .tab:nth-child(3) {
                font-size: 10px;
            }

        }



        .table-container {

            overflow-x: auto;

        }
    </style>



    <div class="row req-pro-main-page">

        <div class="col-lg-9 col-md-8 col-xs-12">

            <div class="req-pro-head" x-data="{ activeTab: @entangle('activeTab') }">

                <req-pro-nav class="req-pro-req-pro-nav">

                    <ul class="tabs">

                        <li class="tab" wire:click="setActiveTab('active')" :class="$activeTab === 'active' ? 'active' : ''" :style="$activeTab === 'active' ? 'color: black;' : 'color: gray;'">

                            <i class="fas fa-check"></i> Active

                            @if($activeTab === 'active')
                            <img src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png" style="width:20px;height:20px" alt="">
                            @endif
                        </li>

                        <li class="tab" wire:click="setActiveTab('pending')" :class="$activeTab === 'pending' ? 'active' : ''" :style="$activeTab === 'pending' ? 'color: black;' : 'color: gray;'">

                            <i class="fas fa-clock"></i> Inprogress

                            @if($activeTab === 'pending')
                            <img src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png" style="width:20px;height:20px" alt="">
                            @endif

                        </li>

                        <li class="tab" wire:click="setActiveTab('closed')" :class="$activeTab === 'closed' ? 'active' : ''" :style="$activeTab === 'closed' ? 'color: black;' : 'color: gray;'">

                            <i class="fas fa-times"></i> Closed

                            @if($activeTab === 'closed')
                            <img src="https://png.pngtree.com/png-vector/20221215/ourmid/pngtree-green-check-mark-png-image_6525691.png" style="width:20px;height:20px" alt="">
                            @endif

                        </li>

                    </ul>

                </req-pro-nav>



            </div>



            <div class="mt-5">

                <div style="display:flex;justify-content:space-evenly">





                    <div id="active" class="req-pro-tab-content" style="display: {{ $activeTab === 'active' ? 'block' : 'none' }};">

                        <div class="req-pro-details">

                            <h3>Request Details</h3>



                            @if($viewingDetails)

                            <button class="btn bg-dark text-white float:right" wire:click="closeDetails">

                                <i class="fas fa-arrow-left"></i> Back

                            </button>

                            @endif

                        </div>



                        @if($viewingDetails && $request)

                        <div class="req-pro-tablediv">

                            <table class="table table-bordered mt-3 req-pro-table">

                                <thead>

                                    <tr>

                                        <th>Field</th>

                                        <th>Value</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>Requested By</td>

                                        <td>{{ $request['requested_by'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Department</td>

                                        <td>{{ $request['category'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Subject</td>

                                        <td>{{ $request['subject'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Description</td>

                                        <td>{{ $request['description'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Distributor</td>

                                        <td>{{ $request['distributor'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Mobile</td>

                                        <td>{{ $request['mobile'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>MailBox</td>

                                        <td>{{ $request['mailbox'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Attach Files</td>

                                        <td>

                                            <a href="#" data-toggle="modal" data-target="#exampleModalCenter-{{ $request['id'] }}">

                                                <i class="fas fa-eye"></i> View Attachments

                                            </a>

                                        </td>



                                    </tr>



                                    <div wire:key="{{ $request['id'] }}">

                                        <div class="modal fade" id="exampleModalCenter-{{ $request['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

                                            <div class="modal-dialog modal-dialog-centered" role="document">

                                                <div class="modal-content">

                                                    <div class="modal-body">

                                                        <div id="carouselExampleIndicators-{{ $request['id'] }}" class="carousel slide" data-ride="carousel">

                                                            <ol class="carousel-indicators">

                                                                @foreach ($request['attach_files'] as $index => $file)

                                                                <li data-target="#carouselExampleIndicators-{{ $request['id'] }}" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>

                                                                @endforeach

                                                            </ol>

                                                            <div class="carousel-inner">

                                                                @foreach ($request['attach_files'] as $index => $file)

                                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

                                                                    <a href="{{ $file }}" target="_blank">

                                                                        <img src="{{ $file }}" class="d-block w-100" alt="Attachment Image">

                                                                    </a>

                                                                </div>

                                                                @endforeach

                                                            </div>

                                                            <a class="carousel-control-prev" href="#carouselExampleIndicators-{{ $request['id'] }}" role="button" data-slide="prev">

                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>

                                                                <span class="sr-only">Previous</span>

                                                            </a>

                                                            <a class="carousel-control-next" href="#carouselExampleIndicators-{{ $request['id'] }}" role="button" data-slide="next">

                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>

                                                                <span class="sr-only">Next</span>

                                                            </a>

                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <tr>

                                        <td>CC To</td>

                                        <td>{{ $request['cc_to'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Priority</td>

                                        <td>{{ $request['priority'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Select Equipment</td>

                                        <td>{{ $request['select_equipment'] }}</td>

                                    </tr>

                                    <tr>

                                        <td>Status</td>

                                        <td>

                                            <select wire:model="request.status" wire:change="save" class="form-control">

                                                <option value="pending">Pending</option>

                                                <option value="inprogress">In Progress</option>

                                                <option value="closed">Closed</option>

                                            </select>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>Assign to</td>

                                        <td>

                                            <select wire:model="request.assignTo" wire:change="save" class="form-control">

                                                <option value="0">Vijay Kumar Keshumala</option>

                                                <option value="1">Meghna Shrimali</option>

                                            </select>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>Comments</td>

                                        <td>

                                            <div class="row">

                                                <div class="col-10">

                                                    <textarea wire:model.lazy="comments" class="form-control"></textarea>

                                                </div>

                                                <div class="col-2 d-flex align-items-center">

                                                    <button class="btn btn-primary">Post</button>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        @else

                        <div class="req-pro-card">

                            @foreach($requests as $index => $request)

                            <div class="request-card">

                                <div class="req-pro-card-body">

                                    <div>

                                        <p>Requested By: <strong>{{ $request['requested_by'] }}</strong></p>

                                        <p title="{{ $request['category'] }}">Department: <span style="width:50%">{{ $request['category'] }}</span></p>

                                    </div>

                                    <button wire:click="viewDetails({{ $index }})" class="req-pro-view-details-btn">View</button>

                                </div>

                            </div>

                            @endforeach

                        </div>

                        @endif

                    </div>









                    <div id="pending" class="req-pro-tab-content" style="display: {{ $activeTab === 'pending' ? 'block' : 'none' }};">

                        <h3>In Progress Requests</h3>

                        <div class="row">

                            <div class="col-12">

                                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;border-radius:15px;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);">

                                    <table class="table table-dark table-striped">

                                        <thead>

                                            <tr>

                                                <th scope="col" style="white-space: nowrap;">#</th>

                                                <th style="white-space: nowrap;">Requested By</th>

                                                <th style="white-space: nowrap;">Department</th>

                                                <th style="white-space: nowrap;">Subject</th>

                                                <th style="white-space: nowrap;">Description</th>

                                                <th style="white-space: nowrap;">Distributor</th>

                                                <th style="white-space: nowrap;">Mobile</th>

                                                <th style="white-space: nowrap;">MailBox</th>

                                                <th style="white-space: nowrap;">Attach Files</th>

                                                <th style="white-space: nowrap;">CC To</th>

                                                <th style="white-space: nowrap;">Priority</th>

                                                <th style="white-space: nowrap;">Select Equipment</th>

                                                <th style="white-space: nowrap;">Status</th>

                                                <th style="white-space: nowrap;">Assign to</th>

                                                <th style="white-space: nowrap;">Comments</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($inProgressRequests as $request)

                                            <tr>

                                                <th scope="row">{{ $request['id'] }}</th>

                                                <td>{{ $request['requested_by'] }}</td>

                                                <td>{{ $request['category'] }}</td>

                                                <td>{{ $request['subject'] }}</td>

                                                <td>{{ $request['description'] }}</td>

                                                <td>{{ $request['distributor'] }}</td>

                                                <td>{{ $request['mobile'] }}</td>

                                                <td>{{ $request['mailbox'] }}</td>

                                                <td>

                                                    @if(isset($request['attach_files']) &&
                                                    is_array($request['attach_files']))

                                                    <div class="image-grid">

                                                        @foreach($request['attach_files'] as $image)

                                                        <a href="{{ $image }}" target="_blank">

                                                            <img src="{{ $image }}" alt="Attached Image">

                                                        </a>

                                                        @endforeach

                                                    </div>

                                                    @else

                                                    No images attached.

                                                    @endif

                                                </td>



                                                <td>{{ $request['cc_to'] }}</td>

                                                <td>{{ $request['priority'] }}</td>

                                                <td>{{ $request['select_equipment'] }}</td>

                                                <td><button class="btn btn-primary text-white">
                                                        {{ $request['status'] }}</button></td>

                                                <td>Vijay</td>

                                                <td> Working on this</td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>



                    </div>





                    <div id="closed" class="req-pro-tab-content" style="display: {{ $activeTab === 'closed' ? 'block' : 'none' }};">

                        <h3>Closed Requests</h3>





                        <div class="row">

                            <div class="col-12">

                                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;border-radius:15px;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);">

                                    <table class="table table-dark tabl5e-striped">

                                        <thead>

                                            <tr>

                                                <th scope="col" style="white-space: nowrap;">#</th>

                                                <th style="white-space: nowrap;">Requested By</th>

                                                <th style="white-space: nowrap;">Department</th>

                                                <th style="white-space: nowrap;">Subject</th>

                                                <th style="white-space: nowrap;">Description</th>

                                                <th style="white-space: nowrap;">Distributor</th>

                                                <th style="white-space: nowrap;">Mobile</th>

                                                <th style="white-space: nowrap;">MailBox</th>

                                                <th style="white-space: nowrap;">Attach Files</th>

                                                <th style="white-space: nowrap;">CC To</th>

                                                <th style="white-space: nowrap;">Priority</th>

                                                <th style="white-space: nowrap;">Select Equipment</th>

                                                <th style="white-space: nowrap;">Status</th>

                                                <th style="white-space: nowrap;">Assign to</th>

                                                <th style="white-space: nowrap;">Comments</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($ClosedRequests as $request)

                                            <tr>

                                                <th scope="row">{{ $request['id'] }}</th>

                                                <td>{{ $request['requested_by'] }}</td>

                                                <td>{{ $request['category'] }}</td>

                                                <td>{{ $request['subject'] }}</td>

                                                <td>{{ $request['description'] }}</td>

                                                <td>{{ $request['distributor'] }}</td>

                                                <td>{{ $request['mobile'] }}</td>

                                                <td>{{ $request['mailbox'] }}</td>

                                                <td>

                                                    @if(isset($request['attach_files']) &&
                                                    is_array($request['attach_files']))

                                                    <div class="image-grid">

                                                        @foreach($request['attach_files'] as $image)

                                                        <a href="{{ $image }}" target="_blank">

                                                            <img src="{{ $image }}" alt="Attached Image">

                                                        </a>

                                                        @endforeach

                                                    </div>

                                                    @else

                                                    No images attached.

                                                    @endif

                                                </td>

                                                <td>{{ $request['cc_to'] }}</td>

                                                <td>{{ $request['priority'] }}</td>

                                                <td>{{ $request['select_equipment'] }}</td>

                                                <td><button class="btn btn-primary text-white">
                                                        {{ $request['status'] }}</button></td>

                                                <td>Vijay</td>

                                                <td> Working on this</td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>





                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-4 col-xs-12  req-pro-col3">



            <div class="row" style="display: flex; align-items: center;">

                <div class="col-10">

                    <h5 class="mb-3" style="background-color: white;display:flex;justify-content:center; margin: 10px; padding: 8px 0px; border-radius: 10px;">
                        Overview</h5>



                </div>

                <div class="col-2">

                    <!-- <i wire:click="toggleOverview" class="fas fa-caret-down req-pro-dropdown-arrow" style="margin-left: auto; cursor: pointer;"></i> -->



                    <i wire:click="toggleOverview" class="fas fa-caret-down req-pro-dropdown-arrow {{ $showOverview ? 'rotated' : '' }}" style="margin-left: auto; cursor: pointer;"></i>



                </div>

            </div>





            @if($showOverview)

            <div class="req-pro-overview-container">



                <div class="req-pro-overview-card">

                    <div class="card text-dark  req-pro-over-card1">

                        <div class="req-pro-card-body">

                            <h5 class="card-title"> Active</h5>

                            <p class="card-text">Total Active

                                <span class="bg-white text-primary rounded-circle  p-2 d-inline-flex align-items-center justify-content-center req-pro-overview-val">

                                    <strong> 1 </strong>

                                </span>

                            </p>

                        </div>

                    </div>

                    <i class="fas fa-arrow-down req-pro-arrow"></i>

                </div>

                <div class="req-pro-overview-card">

                    <div class="card text-dark  req-pro-over-card2">

                        <div class="req-pro-card-body">

                            <h5 class="card-title">In Progress</h5>

                            <p class="card-text">Total In Progress

                                <span class="bg-white text-primary rounded-circle p-2 d-inline-flex align-items-center justify-content-center req-pro-overview-val">

                                    <strong>2</strong>

                                </span>

                            </p>

                        </div>

                    </div>

                    <i class="fas fa-arrow-down req-pro-arrow"></i>

                </div>

                <div class="req-pro-overview-card">

                    <div class="card text-dark  req-pro-over-card3">

                        <div class="req-pro-card-body">

                            <h5 class="card-title">Closed</h5>

                            <p class="card-text">Total Closed

                                <span class="bg-white text-primary rounded-circle p-2 d-inline-flex align-items-center justify-content-center req-pro-overview-val">

                                    <strong>3</strong>

                                </span>

                            </p>

                        </div>

                    </div>

                </div>



            </div>



            @endif



        </div>



    </div>









    <!-- jQuery and Bootstrap Bundle (includes Popper) -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <script>
        function showTab(tab) {

            document.querySelectorAll('.req-pro-tab-content').forEach(el => el.style.display = 'none');

            document.getElementById(tab).style.display = 'block';

        }



        document.addEventListener("DOMContentLoaded", () => {
            showTab('{{ $activeTab }}'); // Show the initially active tab
        });

        Livewire.on('tabSwitched', tab => {
            showTab(tab);
        });




        $(document).ready(function() {

            // Handle click on small image to open larger modal

            $('#modalImage').on('click', function() {

                $('#imageModal').modal('show');

            });

        });
    </script>

</div>