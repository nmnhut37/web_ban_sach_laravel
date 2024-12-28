@extends('layout.master')

@section('content')
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Quản lý Mã giảm giá</h1>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại danh sách</span>
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sửa Mã giảm giá</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('discounts.update', $discount->id) }}" method="post">
                @csrf
                @method('PUT')
                <table class="table table-bordered">
                    <tr>
                        <td>Mã giảm giá</td>
                        <td><input type="text" name="code" class="form-control" value="{{ $discount->code }}" required></td>
                    </tr>
                    <tr>
                        <td>Tỷ lệ giảm giá (%)</td>
                        <td><input type="number" name="discount_percentage" class="form-control" value="{{ $discount->discount_percentage }}" required min="0" max="100" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Ngày hết hạn</td>
                        <td><input type="date" name="expires_at" class="form-control" value="{{ $discount->expires_at }}" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <button type="submit" class="btn btn-success">Cập nhật Mã giảm giá</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection
