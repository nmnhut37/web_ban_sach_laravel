@extends('layout.master')

@section('content')
<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Sửa danh mục con</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-arrow-left"></i>
        </span>
        <span class="text">Quay lại danh sách</span>
    </a>
</div>

<!-- Card containing the form -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thông tin danh mục</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Danh mục cha -->
            <div class="mb-3">
                <label for="parent_id" class="form-label">Danh mục cha</label>
                <select name="parent_id" id="parent_id" class="form-control" required>
                    @foreach ($categories as $parentCategory)
                        @if ($parentCategory->id !== $category->id) <!-- Không hiển thị chính danh mục -->
                            <option value="{{ $parentCategory->id }}" 
                                {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tên danh mục -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control" 
                       value="{{ old('name', $category->name) }}" required>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Mô tả danh mục -->
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả danh mục</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fa fa-save"></i>
                    </span>
                    <span class="text">Lưu thay đổi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
