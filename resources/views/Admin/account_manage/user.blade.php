@extends('layout.master')

@section('content')

<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Quản lý tài khoản</h1>
    <a href="{{route('user.create')}}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="text">Thêm tài khoản</span>
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách tài khoản</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered delete-review" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Avatar</th> <!-- Thêm cột Avatar -->
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Avatar</th> <!-- Thêm cột Avatar -->
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <!-- Hiển thị Avatar nếu có -->
                                <img src="{{ asset('storage/images/user/' . $user->avatar) }}" alt="{{ $user->name }}" width="50">
                            </td>
                            <td>
                                <!-- Hiển thị vai trò với badge -->
                                <div class="badge 
                                    @if($user->role == 'super_admin') bg-danger
                                    @elseif($user->role == 'admin') bg-primary
                                    @else bg-secondary @endif text-white rounded-pill">
                                    {{ $user->role }}
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $user->status == 'verified' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $user->status == 'verified' ? 'Đã xác minh' : 'Chưa xác minh' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-info btn-circle"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;" class="delete-review-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có tài khoản nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ tài khoản",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của tổng _TOTAL_ tài khoản",
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
                { "width": "20%", "targets": 1 },  // Họ và tên
                { "width": "20%", "targets": 2 },  // Email
                { "width": "15%", "targets": 3 },  // Avatar
                { "width": "10%", "targets": 4 },  // Vai trò
                { "width": "10%", "targets": 5 },  // Trạng thái
                { "width": "15%", "targets": 6 }   // Chức năng
            ],
            "autoWidth": false
        });
        $('[data-toggle="tooltip"]').tooltip();
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
                title: 'Bạn có chắc chắn muốn xóa người dùng này?',
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
