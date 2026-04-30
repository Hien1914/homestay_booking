@extends('host.layout.app')

@section('title', 'Quản lý điểm đến')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Quản lý các khu vực, điểm đến bạn đăng ký</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('host.destinations.create') }}" class="admin-btn admin-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Thêm mới
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-geo-alt-fill me-2"></i>Điểm đến của tôi</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ảnh</th>
                        <th>Tên điểm đến</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                        <tr>
                            <td><span class="admin-id-badge">#{{ $loop->iteration }}</span></td>
                            <td>
                                <img src="{{ $destination->image ? asset('storage/' . $destination->image) : asset('images/no-image.png') }}" 
                                     alt="{{ $destination->name }}" class="admin-table-thumb-sm">
                            </td>
                            <td><strong>{{ $destination->name }}</strong></td>
                            <td><code>{{ $destination->slug }}</code></td>
                            <td>{{ Str::limit($destination->description, 50) }}</td>
                            <td>
                                @if($destination->is_approved)
                                    <span class="admin-status-badge status-active">Đã duyệt</span>
                                @else
                                    <span class="admin-status-badge status-pending">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>{{ $destination->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('host.destinations.edit', $destination->id) }}" class="admin-action-btn" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('host.destinations.destroy', $destination->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có điểm đến nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $destinations->links() }}
        </div>
    </div>
</div>
@endsection
