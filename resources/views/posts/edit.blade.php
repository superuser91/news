@extends('vgplay::posts.layout')

@push('head')
    <style>
        .ck-editor__editable {
            min-height: 500px;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <form action="{{ route('posts.update', $post->id) }}" method="POST" class="row"
            onsubmit="setFormSubmitting()">
            @csrf
            @method('PATCH')
            <div class="col-12 col-md-9">
                <div class="form-group">
                    <label for="title">Tiêu đề bài viết</label>
                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                        value="{{ old('title', $post->title) }}" required>

                    @error('title')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="category_id">Thuộc chuyên mục</label>
                    <select name="category_id" id="category_id" class="form-control">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if ($category->id == old('category_id', $post->category_id)) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="body">Nội dung</label>
                    <textarea class="form-control" id="body" name="body">{{ old('body', $post->body) }}</textarea>
                    @error('body')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="excerpt">Tóm tắt</label>
                    <textarea class="form-control" name="excerpt" id="excerpt" rows="3"
                        placeholder="Tóm tắt nội dung bài viết">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                        value="{{ old('slug', $post->slug) }}"
                        placeholder="Bỏ trống để tự động tạo theo tiêu đề bài viết">

                    @error('slug')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status" class="form-control">
                        @foreach (config('vgplay.news.posts.statuses') as $key => $value)
                            <option value="{{ $key }}" @if ($key == old('status', $post->status)) selected @endif>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="published_at">Ngày xuất bản</label>
                    <input required type="text" class="form-control mb-3 datetimepicker" id="published_at"
                        name="published_at"
                        value="{{ old('published_at',is_null($post->published_at) ? $post->created_at->format('d/m/Y H:i') : $post->published_at->format('d/m/Y H:i')) }}">

                    @error('published_at')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="seo_title">SEO Title</label>
                    <input id="seo_title" type="text" class="form-control @error('seo_title') is-invalid @enderror"
                        name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" autocomplete="seo_title">

                    @error('seo_title')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" type="text"
                        class="form-control @error('meta_description') is-invalid @enderror"
                        name="meta_description">{{ old('meta_description', $post->meta_description) }}</textarea>

                    @error('meta_description')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input id="meta_keywords" type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                        name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}"
                        autocomplete="meta_keywords">

                    @error('meta_keywords')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mt-4">
                    @can('post.delete')
                        <a data-action="{{ route('posts.destroy', $post->id) }}"
                            class="btn btn-danger btn-delete form-control mb-3">
                            <i class="fas fa-trash"></i>
                            Xoá</a>
                    @endcan
                    <button class="btn btn-primary form-control">Lưu lại</button>
                </div>
            </div>
        </form>
    </div>
    <form method="POST" id="form-delete">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script src="/vendor/summernote/summernote-bs4.min.js"></script>
    <script src="/vendor/summernote/ckfinder-ext-plugin.js"></script>
    <link rel="stylesheet" href="/vendor/summernote/summernote-bs4.min.css">
    <script>
        var formSubmitting = false;
        var setFormSubmitting = function() {
            formSubmitting = true;
        };

        window.onload = function() {
            window.addEventListener("beforeunload", function(e) {
                if (formSubmitting) {
                    return undefined;
                }

                var message = 'Continue?';

                (e || window.event).returnValue = message;
                return message;
            });
        };
    </script>
    <script>
        $(function() {
            $(`textarea[name="body"]`).summernote({
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']],
                    ['CKFinder', ['CKFinder']]
                ]
            });
        })
    </script>

    <script src="/vendor/admin-lte/plugins/daterangepicker/moment.min.js"></script>
    <script src="/vendor/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
    <script>
        $(function() {
            $('.datetimepicker').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10),
                locale: {
                    format: 'DD/MM/YYYY HH:mm',
                    "applyLabel": "Áp dụng",
                    "cancelLabel": "Huỷ",
                    "fromLabel": "Từ",
                    "toLabel": "Đến",
                    "weekLabel": "W",
                    "daysOfWeek": [
                        "CN", "T2", "T3", "T4", "T5", "T6", "T7"
                    ],
                    "monthNames": [
                        "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7",
                        "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12",
                    ]
                },
            });
        });
    </script>
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
