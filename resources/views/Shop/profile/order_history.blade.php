<div class="container">
    <h2 class="mb-4">Danh sách Hóa đơn của bạn</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            Không có hóa đơn nào để hiển thị.
        </div>
    @else
        <table id="ordersTable" class="table table-striped table-bordered table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Tên người nhận</th>
                    <th>Điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ number_format($order->final_amount, 0, '.', ',') }} đ</td>
                        <td>
                            <span class="badge 
                                @if($order->order_status == 'pending') text-bg-secondary
                                @elseif($order->order_status == 'processing') text-bg-primary
                                @elseif($order->order_status == 'completed') text-bg-success
                                @elseif($order->order_status == 'cancelled') text-bg-danger
                                @endif">
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
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('order.details', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.13.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/Vietnamese.json',
                search: 'Tìm kiếm:',
                lengthMenu: 'Hiển thị _MENU_ mục mỗi trang',
                info: 'Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục',
                infoEmpty: 'Hiển thị 0 đến 0 trong tổng số 0 mục',
                infoFiltered: '(lọc từ _MAX_ mục)',
                paginate: {
                    first: 'Đầu tiên',
                    previous: 'Trước',
                    next: 'Tiếp theo',
                    last: 'Cuối cùng'
                }
            }
        });
    });
</script>
@endpush
