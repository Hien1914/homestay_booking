@extends('host.layout.app')

@section('title', 'Đánh giá & Bình luận')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Xem phản hồi của khách hàng về các chỗ nghỉ của bạn</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-chat-left-text-fill me-2 text-primary"></i>Tất cả đánh giá
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">Khách hàng</th>
                        <th>Email</th>
                        <th>Chỗ nghỉ</th>
                        <th class="text-center">Đánh giá</th>
                        <th>Bình luận</th>
                        <th class="text-center">Ngày gửi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $review->user->full_name }}</div>
                            </td>
                            <td>
                                <div class="text-muted small">{{ $review->user->email }}</div>
                            </td>
                            <td>
                                <div class="small text-truncate">
                                    <a href="{{ route('homestay.show', $review->homestay->slug) }}" target="_blank" class="text-decoration-none">
                                        {{ $review->homestay->title }}
                                    </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-warning small mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div class="admin-review-text small text-muted mx-auto" style="max-width: 250px; white-space: normal;">
                                    {{ $review->comment }}
                                </div>
                            </td>
                            <td class="text-center"><small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Chưa có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection

