@extends('layout.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Chỉnh sửa đơn hàng #{{ $order->id }}</h1>
    <div>
        <a href="{{route('orders.index')}}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại danh sách</span>
        </a>
        <a href="javascript:void(0)" class="btn btn-danger btn-icon-split btn-delete-order">
            <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
            </span>
            <span class="text">Xóa</span>
        </a>
        <form id="delete-order-form" action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <button type="submit" form="orderForm" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-floppy-fill"></i>
            </span>
            <span class="text">Lưu</span>
        </button>
    </div>
</div>

<form id="orderForm" action="{{ route('orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="content-page">
        <div class="content">
            <div class="row mt-2">
                <!-- Shipping Information -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Thông tin giao hàng</h4>
                            <div class="form-group">
                                <label for="name">Họ và tên khách hàng</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $order->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Địa chỉ giao hàng</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required>{{ $order->address }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $order->phone }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $order->email }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status and Payment -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Thông tin thanh toán</h4>
                            <div class="form-group">
                                <label for="payment_method">Phương thức thanh toán</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="cod" {{ $order->payment_method == 'cod' ? 'selected' : '' }}>COD</option>
                                    <option value="vnpay" {{ $order->payment_method == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="order_status">Trạng thái đơn hàng</label>
                                <select class="form-control" id="order_status" name="order_status" required>
                                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipping" {{ $order->order_status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="note">Ghi chú</label>
                                <textarea class="form-control" id="note" name="note" rows="3">{{ $order->note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Sản phẩm trong đơn hàng</h4>
                            <div class="table-responsive">
                                <table class="table mb-0" id="order-items-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th>Tổng cộng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $index => $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->product->product_name }}</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm" 
                                                    name="items[{{ $index }}][quantity]" 
                                                    value="{{ $orderItem->quantity }}" min="1">
                                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $orderItem->id }}">
                                            </td>
                                            <td>{{ $orderItem->price }} đ</td>
                                            <td>{{ $orderItem->quantity * $orderItem->price }} đ</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm btn-delete-item" data-order-id="{{ $order->id }}" data-item-id="{{ $orderItem->id }}">
                                                    Xóa
                                                </button>                                                
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" id="btn-add-item">
                                                    Thêm
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Tóm tắt đơn hàng</h4>
                            <div class="form-group">
                                <label for="total_amount">Tổng cộng</label>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" value="{{ $order->total_amount }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="discount_amount">Giảm giá</label>
                                <input type="number" class="form-control" id="discount_amount" name="discount_amount" value="{{ $order->discount_amount }}">
                            </div>
                            <div class="form-group">
                                <label for="final_amount">Thành tiền</label>
                                <input type="number" class="form-control" id="final_amount" name="final_amount" value="{{ $order->final_amount }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tìm Kiếm Sản Phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoryFilter">Danh mục</label>
                        <select class="form-control" id="categoryFilter">
                            <option value="">Tất cả</option>
                            <!-- Danh mục được lấy từ cơ sở dữ liệu -->
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="searchProduct">Tìm kiếm sản phẩm</label>
                        <input type="text" id="searchProduct" class="form-control" placeholder="Nhập tên sản phẩm">
                    </div>                
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thêm</th>
                            </tr>
                        </thead>
                        <tbody id="productList">
                            <!-- Danh sách sản phẩm sẽ được điền bằng AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')
    <script>
        var searchProductUrl = "{{ route('products.search') }}";
        var addItemUrl = "{{ route('orders.items.add', ['orderId' => $order->id]) }}";
        var orderId = "{{ $order->id }}";
    </script>
    <script src="{{asset('js/edit_order.js')}}"></script>
@endpush
