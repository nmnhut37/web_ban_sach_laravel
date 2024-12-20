@extends('Layout.master')

@section('content')
    <h1 class="h3 text-gray-800">Quản lý sản phẩm</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thêm sản phẩm</h6>
            <a href="{{ route('product_list') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fa fa-arrow-left"></i>
                </span>
                <span class="text">Quay lại danh sách</span>
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <table class="table table-bordered">
                    <tr>
                        <td>Danh mục</td>
                        <td>
                            <select name="category_id" class="form-control" required>
                                <option value="">Chọn danh mục con</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->parent->name ?? 'Không có danh mục cha' }} -> {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Tên sản phẩm</td>
                        <td><input type="text" name="product_name" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Mô tả</td>
                        <td><textarea name="description" class="form-control" cols="40" rows="10" required></textarea></td>
                    </tr>
                    <tr>
                        <td>Giá</td>
                        <td><input type="number" name="price" class="form-control" required min="0" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Số lượng tồn kho</td>
                        <td><input type="number" name="stock_quantity" class="form-control" required min="0"></td>
                    </tr>
                    <tr>
                        <td>Tải hình lên</td>
                        <td><input type="file" name="img" class="form-control-file" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <button type="submit" class="btn btn-success">Thêm sản phẩm mới</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
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