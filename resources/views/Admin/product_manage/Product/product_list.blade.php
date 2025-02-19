@extends('Layout.master')

@section('content')

<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Quản lý sản phẩm</h1>
    <a href="{{route('product.create')}}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="text">Thêm sản phẩm</span>
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Số lượng tồn kho</th>
                        <th>Hình</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Số lượng tồn kho</th>
                        <th>Hình</th>
                        <th>Chức năng</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>
                                @if ($product->category)
                                    @if ($product->category->parent_id)
                                        {{ $product->category->parent->name }} -> {{ $product->category->name }}
                                    @else
                                        {{ $product->category->name }}
                                    @endif
                                @else
                                    Chưa có danh mục
                                @endif
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                <img src="{{ asset('storage/images/product/' . ($product->img ?? 'no-image.jpg')) }}" alt="{{ $product->product_name }}" width="100">
                            </td>
                            <td>
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-info btn-circle"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('product.update', $product->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có sản phẩm nào</td>
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
                "lengthMenu": "Hiển thị _MENU_ sản phẩm",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của tổng _TOTAL_ sản phẩm",
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
                { "width": "10%", "targets": 0 },  // Mã sản phẩm
                { "width": "20%", "targets": 1 },  // Tên sản phẩm
                { "width": "15%", "targets": 2 },  // Danh mục
                { "width": "15%", "targets": 3 },  // Giá
                { "width": "10%", "targets": 4 },  // Số lượng tồn kho
                { "width": "20%", "targets": 5 },  // Hình
                { "width": "10%", "targets": 6 }   // Chức năng
            ],
            "autoWidth": false
        });
    $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection