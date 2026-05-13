@extends('admin.layout.app')

@section('title', 'Chi Tiết Bài Viết')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">
            <i class="bi bi-newspaper me-2"></i>Chi tiết bài viết #{{ $post->id }}
        </h1>
        <p class="admin-page-subtitle">{{ Str::limit($post->title, 60) }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.posts.edit', $post->id) }}" class="admin-btn admin-btn-primary admin-btn-sm">
            <i class="bi bi-pencil-square"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-outline admin-btn-sm">
            <i class="bi bi-arrow-left"></i>Quay lại
        </a>
        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>Xóa
            </button>
        </form>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-type-h1 me-2"></i>Tiêu đề</h5>
            </div>
            <div class="card-body">
                <h3 class="admin-post-show-title">{{ $post->title }}</h3>
            </div>
        </div>

        @if($post->description)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-text-left me-2"></i>Mô tả</h5>
            </div>
            <div class="card-body">
                <p class="admin-post-show-description">{{ $post->description }}</p>
            </div>
        </div>
        @endif

        @if($post->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-image me-2"></i>Hình ảnh</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-fluid rounded">
                <div class="admin-post-show-image-path">{{ $post->image }}</div>
            </div>
        </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Nội dung</h5>
            </div>
            <div class="card-body">
                <div class="content-display">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="admin-post-show-label"><strong>ID:</strong></td>
                            <td><span class="badge admin-post-show-id">{{ $post->id }}</span></td>
                        </tr>
                        <tr>
                            <td class="admin-post-show-label"><strong>Slug:</strong></td>
                            <td class="admin-post-show-meta">{{ $post->slug }}</td>
                        </tr>
                        <tr>
                            <td class="admin-post-show-label"><strong>Trạng thái:</strong></td>
                            <td>
                                @if($post->status === 'published')
                                    <span class="badge bg-success">Xuất bản</span>
                                @else
                                    <span class="badge bg-warning">Nháp</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="admin-post-show-label"><strong>Lượt xem:</strong></td>
                            <td class="admin-post-show-meta">{{ $post->views }}</td>
                        </tr>
                        <tr>
                            <td class="admin-post-show-label"><strong>Ngày tạo:</strong></td>
                            <td class="admin-post-show-meta">
                                {{ $post->created_at->format('d/m/Y') }}
                                <div class="admin-post-show-muted">{{ $post->created_at->format('H:i:s') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="admin-post-show-label"><strong>Cập nhật:</strong></td>
                            <td class="admin-post-show-meta">
                                {{ $post->updated_at->format('d/m/Y') }}
                                <div class="admin-post-show-muted">{{ $post->updated_at->format('H:i:s') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="admin-post-show-label"><strong>Thời gian:</strong></td>
                            <td class="admin-post-show-relative-time">{{ $post->created_at->diffForHumans() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="admin-btn admin-btn-primary admin-btn-sm">
                        <i class="bi bi-pencil-square"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-outline admin-btn-sm">
                        <i class="bi bi-plus-circle"></i>Tạo bài mới
                    </a>
                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-trash"></i>Xóa bài viết
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/posts.css') }}">
@endpush

