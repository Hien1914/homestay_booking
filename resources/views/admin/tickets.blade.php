@extends('admin.layout.app')

@section('title', 'Hỗ trợ')
@section('page_title', 'Hỗ trợ & tickets')
@section('page_kicker', 'Chăm sóc khách hàng')

@section('content')
@php
    $tickets = [
        ['code' => '#TK210', 'subject' => 'Cần đổi ngày check-in', 'customer' => 'Khánh Linh', 'priority' => ['pill-warning', 'Ưu tiên cao']],
        ['code' => '#TK211', 'subject' => 'Yêu cầu hoàn tiền đặt cọc', 'customer' => 'Duy Anh', 'priority' => ['pill-danger', 'Khẩn cấp']],
        ['code' => '#TK212', 'subject' => 'Không nhận được email xác nhận', 'customer' => 'Minh Châu', 'priority' => ['pill-primary', 'Đang xử lý']],
    ];
@endphp

<section class="admin-two-col">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Hàng đợi hỗ trợ</h2>
                <p>Khung xử lý ticket ưu tiên tốc độ phản hồi và phân loại mức độ khẩn.</p>
            </div>
        </div>

        <div class="admin-list">
            @foreach($tickets as $ticket)
                <div class="admin-list-item">
                    <div>
                        <h3>{{ $ticket['code'] }} · {{ $ticket['subject'] }}</h3>
                        <p>Khách gửi: {{ $ticket['customer'] }}</p>
                    </div>
                    <span class="admin-pill {{ $ticket['priority'][0] }}">{{ $ticket['priority'][1] }}</span>
                </div>
            @endforeach
        </div>
    </article>

    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Hiệu suất phản hồi</h2>
                <p>Khối thống kê mẫu để bố trí dashboard hỗ trợ.</p>
            </div>
        </div>

        <div class="admin-mini-grid">
            <article class="admin-mini-card"><span>Phản hồi đầu tiên</span><strong>11 phút</strong><div class="admin-progress is-success"><span style="width: 82%;"></span></div></article>
            <article class="admin-mini-card"><span>Ticket mở</span><strong>23</strong><div class="admin-progress is-accent"><span style="width: 46%;"></span></div></article>
            <article class="admin-mini-card"><span>CSAT tạm tính</span><strong>94%</strong><div class="admin-progress"><span style="width: 94%;"></span></div></article>
        </div>
    </article>
</section>
@endsection
