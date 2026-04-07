@extends('admin.layout.app')

@section('title', 'Bài viết')
@section('page_title', 'Quản lý bài viết')
@section('page_kicker', 'Bảng posts')

@section('content')
<section class="admin-page-section">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách bài viết</h2>
                <p>Dữ liệu lấy từ bảng <code>posts</code>.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Lượt xem</th>
                        <th>Tạo lúc</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td>{{ $blog->id }}</td>
                            <td>{{ $blog->title }}</td>
                            <td>{{ $blog->author?->full_name ?? '-' }}</td>
                            <td>{{ $blog->category ?: '-' }}</td>
                            <td>{{ $blog->status }}</td>
                            <td>{{ $blog->views }}</td>
                            <td>{{ optional($blog->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Chưa có dữ liệu trong bảng posts.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
