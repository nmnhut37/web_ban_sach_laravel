@extends('Layout.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển</h1>
</div>
<div class="row">
    <!-- Thu nhập tháng hiện tại -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Thu nhập tháng hiện tại</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($currentMonthSales, 0) }} đ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tổng thu nhập -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tổng thu nhập</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($totalSales, 0) }} đ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng đang xử lý -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Đơn hàng đang chờ xử lý</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $processingOrders }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cogs fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng đang vận chuyển -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Đơn hàng đang vận chuyển</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $shippingOrders }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Revenue Chart -->
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header d-flex justify-content-between">
                <span>Thu nhập của năm {{ now()->year }}</span>
                <span>Tổng thu nhập: {{ number_format($totalSales, 0) }} đ</span>
            </div>
            <div class="card-body">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Orders Count Chart -->
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header d-flex justify-content-between">
                <span>Đơn hàng của năm {{ now()->year }}</span>
                <span>Tổng đơn hàng: {{ $processingOrders + $shippingOrders }}</span>
            </div>
            <div class="card-body">
                <canvas id="myBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('myAreaChart').getContext('2d');
    var myAreaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($incomeData->pluck('month')),
            datasets: [{
                label: 'Thu nhập theo tháng',
                data: @json($incomeData->pluck('income')),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: { display: true, text: 'Tháng' }
                },
                y: {
                    title: { display: true, text: 'Thu nhập (VND)' }
                }
            }
        }
    });

    var ctx2 = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($orderData->pluck('month')),
            datasets: [{
                label: 'Số lượng đơn hàng',
                data: @json($orderData->pluck('order_count')),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: { display: true, text: 'Tháng' }
                },
                y: {
                    title: { display: true, text: 'Số lượng đơn hàng' }
                }
            }
        }
    });
</script>
@endpush
