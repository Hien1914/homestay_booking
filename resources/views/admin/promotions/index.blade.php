@extends('admin.layout.app')

@section('title', 'Khuyến mãi')
@section('page_title', 'Quản lý khuyến mãi')
@section('page_kicker', 'Bảng promotions')

@section('content')
<section class="admin-page-section">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách khuyến mãi</h2>
                <p>Dữ liệu lấy từ bảng <code>promotions</code>.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Homestay</th>
                        <th>Giảm %</th>
                        <th>Giảm tiền</th>
                        <th>Thời gian</th>
                        <th>Active</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                        <tr>
                            <td>{{ $promotion->id }}</td>
                            <td>{{ $promotion->title }}</td>
                            <td>{{ $promotion->homestay?->title ?? '-' }}</td>
                            <td>{{ $promotion->discount_percent ?? '-' }}</td>
                            <td>{{ $promotion->discount_amount ? number_format((float) $promotion->discount_amount, 0, ',', '.') : '-' }}</td>
                            <td>{{ optional($promotion->start_date)->format('d/m/Y') }} - {{ optional($promotion->end_date)->format('d/m/Y') }}</td>
                            <td>{{ $promotion->is_active ? 'Có' : 'Không' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Chưa có dữ liệu trong bảng promotions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
