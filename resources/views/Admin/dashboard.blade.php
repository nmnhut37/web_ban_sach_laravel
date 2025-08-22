@extends('layout.master')

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
    <!-- Biểu đồ doanh thu -->
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Thu nhập của </span>
                        <select name="year" id="year" class="form-control form-control-sm d-inline-block" style="width: auto; min-width: 100px;">
                            <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>tất cả các năm</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>năm {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span>Tổng thu nhập: <span id="totalSalesText">{{ number_format($totalSales, 0) }}</span> đ</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Biểu đồ đơn hàng -->
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Đơn hàng của </span>
                        <select name="year2" id="year2" class="form-control form-control-sm d-inline-block" style="width: auto; min-width: 100px;">
                            <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>tất cả các năm</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>năm {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span>Tổng đơn hàng: <span id="totalOrdersText">{{ $processingOrders + $shippingOrders }}</span></span>
                </div>
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
let bieuDoDoanhThu, bieuDoDonHang;

// Hàm khởi tạo và cập nhật biểu đồ
function capNhatBieuDo(duLieu) {
    const ctx1 = document.getElementById('myAreaChart').getContext('2d');
    const ctx2 = document.getElementById('myBarChart').getContext('2d');
    
    // Xóa biểu đồ cũ nếu đã tồn tại
    if (bieuDoDoanhThu) bieuDoDoanhThu.destroy();
    if (bieuDoDonHang) bieuDoDonHang.destroy();

    // Khởi tạo biểu đồ doanh thu
    bieuDoDoanhThu = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: duLieu.income.map(item => `Tháng ${item.month}`),
            datasets: [{
                label: 'Doanh thu theo tháng',
                data: duLieu.income.map(item => item.income),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Tháng' } },
                y: { 
                    title: { display: true, text: 'Doanh thu (VNĐ)' },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN').format(context.raw) + ' đ';
                        }
                    }
                }
            }
        }
    });

    // Khởi tạo biểu đồ đơn hàng
    bieuDoDonHang = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: duLieu.orders.map(item => `Tháng ${item.month}`),
            datasets: [{
                label: 'Số lượng đơn hàng',
                data: duLieu.orders.map(item => item.order_count),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Tháng' } },
                y: { 
                    title: { display: true, text: 'Số đơn hàng' },
                    beginAtZero: true
                }
            }
        }
    });
}

// Khởi tạo biểu đồ với dữ liệu ban đầu
capNhatBieuDo({
    income: @json($chartData['income']),
    orders: @json($chartData['orders'])
});

// Xử lý sự kiện khi thay đổi năm trên dropdown thu nhập
document.getElementById('year').addEventListener('change', function() {
    const namDuocChon = this.value;
    // Đồng bộ với dropdown đơn hàng
    document.getElementById('year2').value = namDuocChon;
    // Hiển thị trạng thái đang tải
    document.querySelectorAll('.card-body').forEach(el => {
        el.style.opacity = '0.5';
    });

    // Gọi API lấy dữ liệu mới
    fetch(`{{ route('dashboard') }}?year=${namDuocChon}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Cập nhật biểu đồ
        capNhatBieuDo(data.chartData);
        
        // Cập nhật tổng số liệu
        document.getElementById('totalSalesText').textContent = new Intl.NumberFormat('vi-VN').format(data.totalSales);
        document.getElementById('totalOrdersText').textContent = data.totalOrders;
        
        // Bỏ trạng thái đang tải
        document.querySelectorAll('.card-body').forEach(el => {
            el.style.opacity = '1';
        });
    })
    .catch(error => {
        console.error('Lỗi:', error);
        alert('Có lỗi xảy ra khi lấy dữ liệu. Vui lòng thử lại.');
    });
});

// Đồng bộ hóa khi thay đổi dropdown đơn hàng
document.getElementById('year2').addEventListener('change', function() {
    document.getElementById('year').value = this.value;
    document.getElementById('year').dispatchEvent(new Event('change'));
});
</script>
@endpush