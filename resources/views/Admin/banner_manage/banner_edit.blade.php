@extends('Layout.master')

@section('content')
    <h1 class="h3 text-gray-800">Quản lý Banner</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Chỉnh sửa Banner</h6>
            <a href="{{ route('banners.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fa fa-arrow-left"></i>
                </span>
                <span class="text">Quay lại danh sách</span>
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('banners.update', $banner->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <table class="table table-bordered">
                    <tr>
                        <td>Thứ tự hiển thị</td>
                        <td><input type="number" name="order" class="form-control" value="{{ $banner->order }}" required min="0"></td>
                    </tr>
                    <tr>
                        <td>Mô tả</td>
                        <td><textarea name="description" class="form-control" cols="40" rows="5">{{ $banner->description }}</textarea></td>
                    </tr>
                    <tr>
                        <td>Liên kết URL</td>
                        <td><input type="url" name="url" class="form-control" value="{{ $banner->url }}"></td>
                    </tr>
                    <tr>
                        <td>Hình hiện tại</td>
                        <td>
                            @if ($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner" class="img-fluid" style="max-width: 200px;">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Thay hình mới</td>
                        <td><input type="file" name="image" class="form-control-file"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
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