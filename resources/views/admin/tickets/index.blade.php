@extends('admin.layout.app')

@section('title', 'Yêu cầu hỗ trợ')
@section('page_title', 'Quản lý yêu cầu hỗ trợ')
@section('page_kicker', 'Bảng inquiries')

@section('content')
<section class="admin-page-section">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách liên hệ hỗ trợ</h2>
                <p>Dữ liệu lấy từ bảng <code>inquiries</code>.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách</th>
                        <th>Homestay</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Trả lời lúc</th>
                        <th>Gửi lúc</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->guest?->full_name ?? '-' }}</td>
                            <td>{{ $ticket->homestay?->title ?? '-' }}</td>
                            <td>{{ $ticket->message }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td>{{ optional($ticket->replied_at)->format('d/m/Y H:i') ?: '-' }}</td>
                            <td>{{ optional($ticket->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Chưa có dữ liệu trong bảng inquiries.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
