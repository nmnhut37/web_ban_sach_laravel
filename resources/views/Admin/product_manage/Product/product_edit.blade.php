@extends('Layout.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Sửa sản phẩm</h1>
        <a href="{{ route('product_list') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại danh sách sản phẩm</span>
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cập nhật thông tin sản phẩm</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <table class="table table-bordered">
                    <tr>
                        <td>Danh mục</td>
                        <td>
                            <select name="category_id" class="form-control" required>
                                <option value="">Chọn danh mục con</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                    {{ $category->parent->name ?? 'Không có danh mục cha' }} -> {{ $category->name }}
                                </option>                                                             
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Tên sản phẩm</td>
                        <td><input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required></td>
                    </tr>
                    <tr>
                        <td>Mô tả</td>
                        <td><textarea name="description" class="form-control" cols="40" rows="10" required>{{ $product->description }}</textarea></td>
                    </tr>
                    <tr>
                        <td>Giá</td>
                        <td><input type="number" name="price" class="form-control" value="{{ $product->price }}" required min="0" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Số lượng tồn kho</td>
                        <td><input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required min="0"></td>
                    </tr>
                    <tr>
                        <td>Hình hiện tại</td>
                        <td>
                            @if ($product->img)
                                <img src="{{ asset('storage/images/product/' . $product->img) }}" alt="{{ $product->product_name }}" width="100">
                            @else
                                <span>Chưa có hình</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Thay đổi hình</td>
                        <td><input type="file" name="img" class="form-control-file"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection