@extends('layout.master')

@section('content')
<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Danh sách Đánh giá</h1>
    <a href="{{ route('reviews.create') }}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="text">Thêm Đánh giá</span>
    </a>
</div>

<!-- Card containing the table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách Đánh giá</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered delete-review" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người đánh giá</th>
                        <th>Sản phẩm</th>
                        <th>Sao</th>
                        <th>Nhận xét</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Người đánh giá</th>
                        <th>Sản phẩm</th>
                        <th>Sao</th>
                        <th>Nhận xét</th>
                        <th>Chức năng</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->user->name }}</td> <!-- Hiển thị tên người đánh giá -->
                            <td>{{ $review->product->product_name }}</td>
                            <td>{{ $review->rating }} / 5</td>
                            <td>{{ $review->comment }}</td>
                            <td>
                                <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-info btn-circle">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display: inline;" class="delete-review-form">
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
                "lengthMenu": "Hiển thị _MENU_ đánh giá",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của tổng _TOTAL_ đánh giá",
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
                { "width": "10%", "targets": 0 },  // ID
                { "width": "15%", "targets": 1 },  // User
                { "width": "20%", "targets": 2 },  // Product
                { "width": "10%", "targets": 3 },  // Rating
                { "width": "35%", "targets": 4 },  // Comment
                { "width": "10%", "targets": 5 }   // Actions
            ],
            "autoWidth": false
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Lắng nghe sự kiện submit của form xóa
        $('.delete-review-form').submit(function(e) {
            e.preventDefault(); // Ngừng hành động mặc định của form

            var form = this;

            // Hiển thị SweetAlert để xác nhận
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa đánh giá này?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Nếu chọn "Xóa", submit form
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
