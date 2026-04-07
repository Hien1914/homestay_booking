@extends('admin.layout.app')

@section('title', 'Đánh giá')
@section('page_title', 'Quản lý đánh giá')
@section('page_kicker', 'Bảng reviews')

@section('content')
<section class="admin-page-section">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách đánh giá</h2>
                <p>Dữ liệu lấy từ bảng <code>reviews</code>.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người đánh giá</th>
                        <th>Homestay</th>
                        <th>Điểm</th>
                        <th>Nội dung</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->reviewer?->full_name ?? '-' }}</td>
                            <td>{{ $review->homestay?->title ?? '-' }}</td>
                            <td>{{ $review->rating }}/5</td>
                            <td>{{ $review->comment ?: '-' }}</td>
                            <td>{{ optional($review->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Chưa có dữ liệu trong bảng reviews.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
