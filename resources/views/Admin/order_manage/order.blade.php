@extends('layout.master')

@section('content')

<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Quản lý đơn hàng</h1>
    <a href="{{route('orders.create')}}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="text">Thêm đơn hàng</span>
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã đơn hàng</th>
                        <th>Người đặt</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt hàng</th>
                        <th>
                            Trạng thái
                            <select id="statusFilter" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="pending">Đang xử lý</option>
                                <option value="processing">Đang vận chuyển</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>                         
                        </th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Mã đơn hàng</th>
                        <th>Người đặt</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt hàng</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><a href="{{ route('orders.show', $order->id) }}">{{ $order->order_number }}</a></td>  <!-- Liên kết đến chi tiết đơn hàng -->
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->email }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ number_format($order->final_amount, 0, ',', '.') }} đ</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="badge 
                                    @if($order->order_status == 'pending') bg-warning
                                    @elseif($order->order_status == 'processing') bg-primary
                                    @elseif($order->order_status == 'completed') bg-success
                                    @elseif($order->order_status == 'cancelled') bg-danger
                                    @else bg-secondary @endif text-white rounded-pill">
                                    @if($order->order_status == 'pending')
                                        Đang xử lý
                                    @elseif($order->order_status == 'processing')
                                        Đang vận chuyển
                                    @elseif($order->order_status == 'completed')
                                        Hoàn thành
                                    @elseif($order->order_status == 'cancelled')
                                        Đã hủy
                                    @else
                                        {{ ucfirst($order->order_status) }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Chi tiết">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-info btn-circle" data-toggle="tooltip" data-placement="top" title="Sửa">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Không có đơn hàng nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ đơn hàng",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của tổng _TOTAL_ đơn hàng",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ mục)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Trang đầu",
                    "last": "Trang cuối",
                    "next": "Trang sau",
                    "previous": "Trang trước"
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0 },  // ID
                { "width": "10%", "targets": 1 },  // Mã đơn hàng
                { "width": "15%", "targets": 2 },  // Người đặt
                { "width": "15%", "targets": 3 },  // Email
                { "width": "15%", "targets": 4 },  // Địa chỉ
                { "width": "10%", "targets": 5 },  // Tổng tiền
                { "width": "10%", "targets": 6 },  // Ngày đặt hàng
                { "width": "10%", "targets": 7, "orderable": false }, // Trạng thái đơn hàng
                { "width": "10%", "targets": 8 }   // Chức năng
            ],
            "autoWidth": false,
        });

        $('#statusFilter').on('change', function() {
            var status = $(this).val();

            // Kiểm tra trạng thái và chuyển đổi trạng thái trong bảng sang tiếng Anh để lọc
            var statusMap = {
                'pending': 'Đang xử lý',
                'processing': 'Đang vận chuyển',
                'completed': 'Hoàn thành',
                'cancelled': 'Đã hủy'
            };

            // Nếu có trạng thái, thực hiện lọc theo giá trị tiếng Anh
            if (status) {
                table.column(7).search(statusMap[status]).draw();  // Cột 7 là Trạng thái đơn hàng
            } else {
                table.column(7).search('').draw();  // Hiển thị tất cả khi không lọc
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
