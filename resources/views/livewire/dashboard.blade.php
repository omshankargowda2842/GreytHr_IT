<div class="main">
    <div class="d-flex justify-content-center">
        <div class="col-3  mt-3 ml-3">
            @if (session()->has('loginSuccess'))
            <div id="flash-message" class="alert alert-success mt-1">
                {{ session('loginSuccess') }}
            </div>
            @endif
        </div>

    </div>
    <!-- ======================= Cards ================== -->
    <div class="cardBox">

        <div class="card" wire:click='itRequest'>
            <div>
                <div class="numbers1">{{$activeCount}} <span> <i class="fas fa-users"></i></span></div>
                <div class="cardName">Active Requests </div>
            </div>
        </div>

        <div class="card" wire:click='itMemeber'>
            <div>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="numbers">
                            <h6><i class="fas fa-check-circle text-success"></i> Active</h6>
                            <p>{{$activeItRelatedEmye}}</p>
                        </div>
                    </div>

                    <div class="col-md-6 text-center">
                        <div class="numbers">
                            <h6><i class="fas fa-times-circle text-danger"></i> InActive</h6>
                            <p>{{$inactiveItRelatedEmye}}</p>
                        </div>
                    </div>

                </div>

                <div class="cardName">IT Members</div>
            </div>


        </div>

        <div class="card" wire:click='vendorMod'>
            <div>
                <div class="numbers1">{{$vendors}} <span> <i class="fas fa-store"></i></span></div>
                <div class="cardName">Vendors </div>
            </div>
        </div>


        <div class="card" wire:click='assetMod'>
            <div>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="numbers">
                            <h6><i class="fas fa-check-circle text-success"></i> Active</h6>
                            <p>{{$activeAssets}}</p>
                        </div>
                    </div>

                    <div class="col-md-6 text-center">
                        <div class="numbers">
                            <h6><i class="fas fa-times-circle text-danger"></i> InActive</h6>
                            <p>{{$inactiveAssets}}</p>
                        </div>
                    </div>

                </div>

                <div class="cardName">Assets</div>
            </div>


        </div>


        <!--  <div class="card">
            <div>
                <div class="numbers">$7,842</div>
                <div class="cardName">Earning</div>
            </div>

            <div class="iconBx">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
        </div> -->
    </div>

    <!-- ================ Order Details List ================= -->
    <div class="details">
        <div class="recentOrders">
            <div class="cardHeader">
                <h2>Category Wise Requests</h2>
                <a href="#" class="btn" wire:click='itRequest'>View All</a>
            </div>

            <table class="mt-5">
                <thead>
                    <tr>
                        <td>Category
                            <span class="" wire:click="toggleSortOrder" style="cursor: pointer;margin-left:10px;">
                                <i class="fas fa-sort"></i> <!-- Single Sort Icon -->
                            </span>
                        </td>
                        <td>Total Requests</td>
                        <td>Status</td>
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
                            <div class="badge rounded-pill bg-dark text-white">
                                {{ $filteredRequests->count() }}
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill dash-custom-bg-color text-black">
                                Active <span
                                    class="badge rounded-pill bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Open')->count() }}</span>
                            </span>
                            <span class="badge rounded-pill dash-custom-bg-color1 text-black">
                                Pending <span
                                    class="badge rounded-pill  bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Pending')->count() }}</span>
                            </span>
                            <span class="badge rounded-pill dash-custom-bg-color2 text-black">
                                Completed <span
                                    class="badge rounded-pill  bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Completed')->count() }}</span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- ================= New Customers ================ -->
        <div class="recentCustomers">
            <div>
                <h2 class="mb-5" style="margin-left: 15px;margin-top: 9px;">Graph Data</h2>
                <canvas id="myDonutChart" width="300" height="300"></canvas>
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
