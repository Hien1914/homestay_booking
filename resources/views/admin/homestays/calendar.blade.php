@extends('admin.layout.app')

@section('title', 'Lịch đặt phòng - ' . $homestay->title)

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">Lịch đặt phòng</h1>
        <p class="admin-page-subtitle">{{ $homestay->title }} - {{ $homestay->room_code }}</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.homestays') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
        <a href="{{ route('admin.homestays.edit', $homestay) }}" class="admin-btn admin-btn-primary">
            <i class="bi bi-pencil"></i>
            Chỉnh sửa
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="admin-stats-grid admin-stats-grid-3">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $bookings->count() }}</div>
            <div class="admin-stat-label">Tổng đặt phòng</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $bookings->where('status', 'confirmed')->count() }}</div>
            <div class="admin-stat-label">Đã xác nhận</div>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-warning">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value">{{ $bookings->where('status', 'pending')->count() }}</div>
            <div class="admin-stat-label">Chờ xác nhận</div>
        </div>
    </div>
</div>

<!-- Calendar -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-calendar3 me-2"></i>Lịch đặt phòng</h3>
    </div>
    <div class="admin-card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Booking List -->
<div class="admin-card mt-4">
    <div class="admin-card-header">
        <h3><i class="bi bi-list-ul me-2"></i>Danh sách đặt phòng</h3>
    </div>
    <div class="admin-card-body">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày nhận</th>
                        <th>Ngày trả</th>
                        <th>Số đêm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homestay->bookings()->with('user')->latest()->get() as $booking)
                        <tr>
                            <td><span class="admin-id-badge">#{{ $booking->id }}</span></td>
                            <td>{{ $booking->user->full_name ?? 'Khách' }}</td>
                            <td>{{ $booking->check_in_date->format('d/m/Y') }}</td>
                            <td>{{ $booking->check_out_date->format('d/m/Y') }}</td>
                            <td>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</td>
                            <td>{{ number_format($booking->total_price ?? 0) }}đ</td>
                            <td>
                                @switch($booking->status)
                                    @case('confirmed')
                                        <span class="admin-badge admin-badge-success">Đã xác nhận</span>
                                        @break
                                    @case('pending')
                                        <span class="admin-badge admin-badge-warning">Chờ xác nhận</span>
                                        @break
                                    @case('cancelled')
                                        <span class="admin-badge admin-badge-danger">Đã hủy</span>
                                        @break
                                    @case('completed')
                                        <span class="admin-badge admin-badge-info">Hoàn thành</span>
                                        @break
                                    @default
                                        <span class="admin-badge admin-badge-secondary">{{ $booking->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x"></i> Chưa có đặt phòng nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
#calendar {
    max-width: 100%;
}
.fc-event {
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 4px;
}
.fc-daygrid-event {
    white-space: normal;
}
.fc .fc-toolbar-title {
    font-size: 1.2rem;
    font-weight: 600;
}
.fc .fc-button-primary {
    background-color: #003b0d;
    border-color: #003b0d;
}
.fc .fc-button-primary:hover {
    background-color: #002a09;
    border-color: #002a09;
}
.fc .fc-button-primary:disabled {
    background-color: #003b0d;
    border-color: #003b0d;
}
.fc-day-today {
    background-color: rgba(0, 59, 13, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var bookings = @json($bookings);
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        buttonText: {
            today: 'Hôm nay',
            month: 'Tháng',
            week: 'Tuần'
        },
        events: bookings,
        eventClick: function(info) {
            alert('Đơn đặt phòng: ' + info.event.title + '\nTrạng thái: ' + info.event.extendedProps.status);
        },
        eventDidMount: function(info) {
            info.el.title = info.event.title + ' (' + info.event.extendedProps.status + ')';
        }
    });
    
    calendar.render();
});
</script>
@endpush
