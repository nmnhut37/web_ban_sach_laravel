@extends('Layout.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Quản lý Banner</h1>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại danh sách</span>
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thêm Banner</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('banners.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <table class="table table-bordered">
                    <tr>
                        <td>Thứ tự hiển thị</td>
                        <td><input type="number" name="order" class="form-control" required min="0"></td>
                    </tr>
                    <tr>
                        <td>Mô tả</td>
                        <td><textarea name="description" class="form-control" cols="40" rows="5"></textarea></td>
                    </tr>
                    <tr>
                        <td>Liên kết URL</td>
                        <td><input type="url" name="url" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Tải hình lên</td>
                        <td><input type="file" name="image" class="form-control-file" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <button type="submit" class="btn btn-success">Thêm Banner mới</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection