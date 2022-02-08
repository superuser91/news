@extends('vgplay::categories.layout')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">Tên chuyên mục</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name', $category->name) }}" required autocomplete="name">

                @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                    value="{{ old('slug', $category->slug) }}" placeholder="Bỏ trống để tự động tạo theo tên chuyên mục">

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
                    @foreach ($categories as $type)
                        <option value="{{ $type->id }}" @if (old('parent_id', $category->parent_id) == $type->id) selected @endif>
                            {{ $type->name }}</option>
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
                    name="order" value="{{ old('order', $category->order) }}">

                @error('order')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <button class="btn btn-success">Lưu lại</button>
                <a data-action="{{ route('categories.destroy', $category->id) }}"
                    class="btn btn-danger btn-delete float-right">
                    <i class="fas fa-trash"></i>
                    Xoá</a>
            </div>
        </form>
    </div>
    <form method="POST" id="form-delete">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let action = $(this).data('action');
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xoá?',
                text: "Sau khi xoá sẽ không thể phục hồi lại!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete').attr('action', action);
                    $('#form-delete').submit();
                }
            })
        });
    </script>
@endpush
