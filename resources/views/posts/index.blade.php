@extends('vgplay::posts.layout')

@section('content')
    <div class="container-fluid">
        <table class="table table-hover" id="datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th>Trạng thái</th>
                    <th>Chỉnh sửa bởi</th>
                    <th>Sửa lần cuối vào</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @if (count($posts) > 0)
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->id ?? '' }}</td>
                            <td>{{ $post->title ?? '' }}</td>
                            <td>{{ $post->categoryName() ?? '' }}</td>
                            <td>{{ $post->status ?? '' }}</td>
                            <td>{{ $post->author->name ?? ($post->author->name ?? ($post->author->username ?? ($post->author->email ?? ''))) }}
                            </td>
                            <td>{{ is_null($post->updated_at) ? '' : $post->updated_at->format('H:i d/m/Y') }}
                            </td>
                            <td>
                                @can('posts.update')
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                        Sửa
                                    </a>
                                @endcan
                                @if (Route::has(config('vgplay.news.posts.route_show')))
                                    <a class="btn btn-sm btn-light"
                                        onclick="copyToClipboard('{{ route(config('vgplay.news.posts.route_show'), $post->slug) }}')">
                                        <i class="fas fa-copy"></i>
                                        URL
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "processing": "Đang xử lý...",
                "infoFiltered": "(được lọc từ _MAX_ mục)",
                "emptyTable": "Không có dữ liệu",
                "info": "Hiển thị _START_ tới _END_ của _TOTAL_ bản ghi",
                "infoEmpty": "Hiển thị 0 tới 0 của 0 bản ghi",
                "lengthMenu": "Hiển thị _MENU_ bản ghi",
                "loadingRecords": "Đang tải...",
                "paginate": {
                    "first": "Đầu tiên",
                    "last": "Cuối cùng",
                    "next": "Sau",
                    "previous": "Trước"
                },
                "search": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy kết quả"
            }
        });
    </script>
    <script>
        function copyToClipboard(text) {
            var inp = document.createElement('input');
            document.body.appendChild(inp)
            inp.value = text
            inp.select();
            document.execCommand('copy', false);
            inp.remove();
            Swal.fire("Đã copy đường dẫn vào clipboard");
        }
    </script>
@endpush
