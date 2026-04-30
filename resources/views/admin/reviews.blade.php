@extends('admin.layout.app')

@section('title', 'Quản lý đánh giá')

@push('styles')
<style>
    .admin-reviews-table {
        table-layout: fixed;
    }
    .admin-reviews-table th,
    .admin-reviews-table td {
        width: calc(100% / 7);
    }
    .admin-reviews-table .admin-review-comment-cell {
        text-align: left !important;
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>
@endpush

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Xem tất cả đánh giá của người dùng trong hệ thống</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.reviews') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="{{ route('admin.reviews') }}" class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-star me-2 text-primary"></i>Danh sách đánh giá
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-wrap">
            <table class="admin-table admin-reviews-table">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th class="text-center">Homestay</th>
                        <th class="text-center">Điểm</th>
                        <th>Nội dung đánh giá</th>
                        <th class="text-center">Thời gian</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="fw-bold">{{ $review->user?->full_name ?? '-' }}</td>
                            <td>{{ $review->user?->email ?? '-' }}</td>
                            <td class="text-center">{{ $review->homestay?->title ?? '-' }}</td>
                            <td class="text-center">
                                <div class="admin-rating justify-content-center">
                                    @for($i = 0; $i < (int) $review->rating; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                    @for($i = (int) $review->rating; $i < 5; $i++)
                                        <i class="bi bi-star text-warning"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="admin-review-comment-cell">{{ $review->comment ?: '-' }}</td>
                            <td class="text-center small">{{ optional($review->created_at)->format('H:i d/m/Y') }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa đánh giá" aria-label="Xóa đánh giá">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-star"></i> Chưa có đánh giá nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection


