@extends('layout.master')

@section('content')
<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Mã giảm giá</h1>
    <a href="{{ route('discounts.create') }}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="text">Thêm mã giảm giá</span>
    </a>
</div>

<!-- Card containing the table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách mã giảm giá</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã giảm giá</th>
                        <th>Tỷ lệ giảm giá</th>
                        <th>Ngày hết hạn</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Mã giảm giá</th>
                        <th>Tỷ lệ giảm giá</th>
                        <th>Ngày hết hạn</th>
                        <th>Chức năng</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($discounts as $discount)
                        <tr>
                            <td>{{ $discount->code }}</td>
                            <td>{{ $discount->discount_percentage }}%</td>
                            <td>{{ $discount->expires_at }}</td>
                            <td>
                                <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-info btn-circle">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
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
        $('#dataTable').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ mã giảm giá",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của tổng _TOTAL_ mã giảm giá",
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
                { "width": "20%", "targets": 0 },  // Mã giảm giá
                { "width": "20%", "targets": 1 },  // Tỷ lệ giảm giá
                { "width": "40%", "targets": 2 },  // Ngày hết hạn
                { "width": "20%", "targets": 3 }   // Chức năng
            ],
            "autoWidth": false
        });
    });
</script>
@endsection

@section('js')
@if(session('success'))
    <script>
        Swal.fire({
            title: 'Thành công!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@if(session('error'))
    <script>
        Swal.fire({
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endsection