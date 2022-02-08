@extends('vgplay::categories.layout')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Tên chuyên mục</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required autocomplete="name">

                @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                    value="{{ old('slug') }}" placeholder="Bỏ trống để tự động tạo theo tên chuyên mục">

                @error('slug')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="parent_id">Chuyên mục cha</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">Không thuộc chuyên mục nào</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="order">Thứ tự hiển thị</label>
                <input id="order" type="number" min="0" class="form-control @error('order') is-invalid @enderror"
                    name="order" value="{{ old('order') }}">

                @error('order')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-4">
                <button class="btn btn-success">Lưu lại</button>
            </div>
        </form>
    </div>
@endsection
