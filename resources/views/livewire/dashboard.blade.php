<div class="main">

<div wire:loading
        wire:target="itRequest,assetMod,itMemeber,vendorMod">
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


    <!-- ======================= Cards ================== -->
    <div class="row m-0 mt-3">
        <div class="col-md-3 mb-3">
            <div class="card-home" wire:click='itRequest'>
                <div class="text-home row m-0">
                    <div class="col-6">
                        <i class="fas fa-users fs-1"></i>
                    </div>
                    <div class="col-6">
                        <p class="fs-1 mb-0">{{$activeCount}}</p>
                    </div>
                </div>
                <div class="icons-text">
                    <h6 class="m-0 fw-bold">Active Requests</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card-home" wire:click='itMemeber'>
                <div class="text-home row m-0">
                    <div class="col-6">
                        <p class="badge dash-custom-bg-color text-black">Active</p>
                        <p class="fs-1 mb-0">{{$activeItRelatedEmye}}</p>
                    </div>
                    <div class="col-6">
                        <p class="badge dash-custom-bg-color3 text-black">InActive</p>
                        <p class="fs-1 mb-0">{{$inactiveItRelatedEmye}}</p>
                    </div>
                </div>
                <div class="icons-text">
                    <h6 class="m-0 fw-bold">IT Members</h6>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card-home" wire:click='vendorMod'>
                <div class="text-home row m-0">
                    <div class="col-6">
                        <i class="fas fa-store fs-1"></i>
                    </div>
                    <div class="col-6">
                        <p class="fs-1 mb-0">{{$vendors}}</p>
                    </div>
                </div>
                <div class="icons-text">
                    <h6 class="m-0 fw-bold">Vendors</h6>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card-home" wire:click='assetMod'>
                <div class="text-home row m-0">
                    <div class="col-6">
                        <p class="badge dash-custom-bg-color text-black">Active</p>
                        <p class="fs-1 mb-0">{{$activeAssets}}</p>
                    </div>
                    <div class="col-6">
                        <p class="badge dash-custom-bg-color3 text-black">InActive</p>
                        <p class="fs-1 mb-0">{{$inactiveAssets}}</p>
                    </div>
                </div>
                <div class="icons-text">
                    <h6 class="m-0 fw-bold">Assest</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- ================ Order Details List ================= -->
    <div class="details">
        <div class="recentOrders">
            <div class="cardHeader row m-0">
                <div class="col-md-6">
                    <h4>Category Wise Requests</h4>
                </div>
                <div class="col-md-6 text-end">
                    <!-- <a href="#" class="btn btn-outline-primary fs-6 mb-3">Month</a>
                    <a href="#" class="btn btn-outline-primary fs-6 mb-3">Week</a>
                    <a href="#" class="btn btn-outline-primary fs-6 mb-3">Day</a> -->
                    <a href="#" class="btn btn-primary fs-6 mb-3" wire:click='itRequest'>View All</a>
                </div>

            </div>

            <div class="table-responsive">
                <table class="mt-3">
                    <thead>
                        <tr>
                            <td>Category
                                <span wire:ignore wire:click="toggleSortOrder"
                                    style="cursor: pointer;margin-left:10px;">
                                    <i class="fas fa-sort"></i> <!-- Single Sort Icon -->
                                </span>
                            </td>
                            <td>Total Requests</td>
                            <td>Status</td>
                            <!-- <td>Action</td> -->
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sortedCategories as $category)
                        <tr>
                            <td>{{ $category }}</td> <!-- Display category in the Category column -->
                            <td class="text-primary">
                                @php
                                $statuses = ['Open', 'Pending', 'Completed'];
                                $filteredRequests = $countRequests->whereIn('status', $statuses)->where('category',
                                $category);
                                @endphp
                                <div class="badge rounded-pill bg-primary text-white">
                                    {{ $filteredRequests->count() }}
                                </div>
                            </td>
                            <td>
                                <span class="badge dash-custom-bg-color text-black">
                                    Active <span
                                        class="badge rounded-pill bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Open')->count() }}</span>
                                </span>
                                <span class="badge dash-custom-bg-color1 text-black">
                                    Pending <span
                                        class="badge rounded-pill  bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Pending')->count() }}</span>
                                </span>
                                <span class="badge dash-custom-bg-color2 text-black">
                                    Completed <span
                                        class="badge rounded-pill  bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Completed')->count() }}</span>
                                </span>
                            </td>
                            <!-- <td>
                                <button class="btn btn-outline-secondary"><i class="ri-arrow-right-line"></i></button>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ================= New Customers ================ -->
        <div class="recentCustomers">
            <div>
                <h2 class="mb-5">Graph Data</h2>
                <canvas id="myDonutChart" wire:ignore width="300" height="300"></canvas>
                <!-- <canvas id="myPieChart" width="400" height="400"></canvas> -->
            </div>
        </div>
    </div>
</div>

<?php

$activeCount = $activeCount;
$pendingCount = $pendingCount;
$completedCount = $closedCount;
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const drawChart = () => {
            const ctx = document.getElementById('myDonutChart').getContext('2d');
            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);


            // Sample data - these should be PHP variables in your actual implementation
            const activeCount = <?php echo json_encode($activeCount); ?>;
            const pendingCount = <?php echo json_encode($pendingCount); ?>;
            const completedCount = <?php echo json_encode($completedCount); ?>;

            const data = {
                labels: ['Active', 'Pending', 'Completed'],
                datasets: [{
                    label: 'Request Status',
                    data: [activeCount, pendingCount, completedCount], // Use the PHP variables
                    backgroundColor: [
                        '#ffcc80', // Orange
                        '#a5d6a7', // Green
                        '#64b5f6' // Blue
                    ],
                    borderColor: [
                        'rgba(255, 159, 64, 1)', // Orange
                        'rgba(75, 192, 192, 1)', // Green
                        'rgba(54, 162, 235, 1)', // Blue
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            };

            new Chart(ctx, config);
        };

        // Initial chart draw
        drawChart();

        // Redraw chart on Livewire updates
        document.addEventListener('livewire:load', drawChart);
        document.addEventListener('livewire:updated', () => {
            setTimeout(drawChart, 100); // Adjust the delay if necessary
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('noPermissionAlert', event => {
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: event[0].message || 'You dont have access',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
