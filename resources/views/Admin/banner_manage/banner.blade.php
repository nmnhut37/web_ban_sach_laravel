@extends('layout.master')

@section('content')
<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Danh sách Banner</h1>
    <a href="{{ route('banners.create') }}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="text">Thêm Banner</span>
    </a>
</div>

<!-- Card containing the table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách Banner</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered delete-review" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Mô tả</th>
                        <th>Thứ tự</th>
                        <th>Liên kết</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Mô tả</th>
                        <th>Thứ tự</th>
                        <th>Liên kết</th>
                        <th>Chức năng</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($banners as $banner)
                        <tr>
                            <td>{{ $banner->id }}</td>
                            <td>
                                <img src="{{ asset('storage/images/Banner/' . $banner->image) }}" alt="Banner Image" style="width: 100px; height: auto;">
                            </td>
                            <td>{{ $banner->description }}</td>
                            <td>{{ $banner->order }}</td>
                            <td><a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a></td>
                            <td>
                                <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-info btn-circle">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display: inline;" class="delete-review-form">
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
                "lengthMenu": "Hiển thị _MENU_ banner",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của tổng _TOTAL_ banner",
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
                { "width": "20%", "targets": 1 },  // Ảnh
                { "width": "30%", "targets": 2 },  // Mô tả
                { "width": "10%", "targets": 3 },  // Thứ tự
                { "width": "20%", "targets": 4 },  // Liên kết
                { "width": "10%", "targets": 5 }   // Chức năng
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
                title: 'Bạn có chắc chắn muốn xóa banner này?',
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