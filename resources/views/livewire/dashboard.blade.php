<div class="main">


    <!-- ======================= Cards ================== -->
    <div class="cardBox">
        <div class="card">
            <div>
                <div class="numbers">{{$activeCount}} <span>  <i class="fas fa-eye"></i></span></div>
                <div class="cardName">Active Requests </div>
            </div>
        </div>

        <!-- <div class="card">
            <div>
                <div class="numbers">80</div>
                <div class="cardName">Sales</div>
            </div>

            <div class="iconBx">
                <ion-icon name="cart-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">284</div>
                <div class="cardName">Comments</div>
            </div>

            <div class="iconBx">
                <ion-icon name="chatbubbles-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
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
                        <td>Category</td>
                        <td>Total Requests</td>
                        <td>Status</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category }}</td> <!-- Display category in the Category column -->
                        <td class="text-primary">
                            <div class="badge rounded-pill bg-dark text-white">
                                {{ $countRequests->where('category', $category)->count() }}
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill dash-custom-bg-color text-black">
                                Active <span class="badge rounded-pill bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Open')->count() }}</span>
                            </span>
                            <span class="badge rounded-pill dash-custom-bg-color1 text-black">
                                Pending <span class="badge rounded-pill  bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Pending')->count() }}</span>
                            </span>
                            <span class="badge rounded-pill dash-custom-bg-color2 text-black">
                                Completed <span class="badge rounded-pill  bg-white text-dark">{{ $countRequests->where('category', $category)->where('status', 'Completed')->count() }}</span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- ================= New Customers ================ -->
        <div class="recentCustomers">
            <div class="cardHeader">
                <h2>Graph Data</h2>
            </div>

           <div>
           <canvas id="myDonutChart" width="300" height="300"></canvas>
           <!-- <canvas id="myPieChart" width="400" height="400"></canvas> -->
           </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('myDonutChart').getContext('2d');

    // Sample data
    const data = {
        labels: ['Active', 'Pending', 'Completed'],
        datasets: [{
            label: 'Request Status',
            data: [10, 5, 15], // Replace these values with your actual data
            backgroundColor: [
                '#a5d6a7', // Blue
                '#ffcc80',  // Orange
                '#64b5f6'   // Green
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',     // Blue
                'rgba(255, 159, 64, 1)',     // Orange
                'rgba(75, 192, 192, 1)'      // Green
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'doughnut', // Change type to 'doughnut'
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
});
</script>
