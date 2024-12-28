@extends('Layout.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Thêm Đánh giá</h1>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại danh sách</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thêm Đánh giá mới</h6>
        </div>
        <div class="card-body">
            <!-- Hiển thị thông báo lỗi nếu có -->
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <form action="{{ route('reviews.add') }}" method="POST">
                @csrf

                <!-- Dropdown chọn người dùng -->
                <div class="mb-3">
                    <label for="user_id" class="form-label">Chọn Người Dùng</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Chọn người dùng</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown chọn danh mục sản phẩm -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Danh mục sản phẩm</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown chọn sản phẩm -->
                <div class="mb-3">
                    <label for="product_id" class="form-label">Sản phẩm</label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        <option value="">Chọn sản phẩm</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Đánh giá sao -->
                <div class="mb-3">
                    <label for="rating" class="form-label">Đánh giá (Sao)</label>
                    <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
                </div>

                <!-- Nhận xét -->
                <div class="mb-3">
                    <label for="comment" class="form-label">Nhận xét</label>
                    <textarea name="comment" id="comment" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Thêm Đánh giá</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Lắng nghe sự kiện thay đổi của dropdown danh mục
        $('#category_id').change(function() {
            var categoryId = $(this).val();

            if (categoryId) {
                // Gửi yêu cầu AJAX để lấy sản phẩm theo danh mục
                $.ajax({
                    url: "{{ route('reviews.getProductsByCategory') }}",
                    type: "GET",
                    data: { category_id: categoryId },
                    success: function(data) {
                        var productSelect = $('#product_id');
                        productSelect.empty();  // Xóa các lựa chọn cũ
                        productSelect.append('<option value="">Chọn sản phẩm</option>');  // Thêm lựa chọn mặc định

                        // Thêm các sản phẩm vào dropdown
                        data.products.forEach(function(product) {
                            productSelect.append('<option value="' + product.id + '">' + product.product_name + '</option>');
                        });
                    }
                });
            } else {
                // Nếu không có danh mục nào được chọn, xóa tất cả sản phẩm
                $('#product_id').empty();
                $('#product_id').append('<option value="">Chọn sản phẩm</option>');
            }
        });
    });
</script>
@endsection
