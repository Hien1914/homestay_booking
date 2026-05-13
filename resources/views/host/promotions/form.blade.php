@extends('host.layout.app')

@section('title', $promotion ? 'Sửa mã giảm giá' : 'Tạo mã giảm giá')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Thiết lập mã giảm giá cho chỗ nghỉ của bạn.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('host.promotions.index') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

@if($errors->any())
    <div class="admin-alert admin-alert-danger mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-ticket-perforated me-2 text-primary"></i>Thông tin mã giảm
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ $promotion ? route('host.promotions.update', $promotion) : route('host.promotions.store') }}" method="POST">
            @csrf
            @if($promotion)
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-12">
                    <label for="title" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $promotion?->title) }}" required placeholder="Ví dụ: Giảm giá mùa hè">
                </div>

                <div class="col-12">
                    <label for="discount_percent" class="form-label fw-bold">Phần trăm giảm <span class="text-danger">*</span></label>
                    <input type="number" id="discount_percent" name="discount_percent" class="form-control" min="1" max="100" value="{{ old('discount_percent', $promotion?->discount_percent) }}" required placeholder="Ví dụ: 10">
                </div>

                <div class="col-12">
                    <label for="start_date" class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('start_date', $promotion?->start_date?->format('Y-m-d')) }}" required>
                    <div class="form-text small text-muted">Ngày bắt đầu phải từ hôm nay trở đi.</div>
                </div>

                <div class="col-12">
                    <label for="end_date" class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $promotion?->end_date?->format('Y-m-d')) }}" required>
                    <div class="form-text small text-muted">Ngày kết thúc phải sau ngày bắt đầu ít nhất 1 ngày.</div>
                </div>

                <div class="col-12">
                    <label for="min_nights" class="form-label fw-bold">Số đêm tối thiểu</label>
                    <input type="number" id="min_nights" name="min_nights" class="form-control" min="1" value="{{ old('min_nights', $promotion?->min_nights ?? 1) }}" placeholder="Ví dụ: 2">
                </div>

                <div class="col-12">
                    <label for="is_active" class="form-label fw-bold">Trạng thái</label>
                    <select id="is_active" name="is_active" class="form-select">
                        <option value="1" @selected((int) old('is_active', $promotion?->is_active ? 1 : 0) === 1)>Kích hoạt</option>
                        <option value="0" @selected((int) old('is_active', $promotion?->is_active ? 1 : 0) === 0)>Tạm tắt</option>
                    </select>
                </div>
            </div>

            <div class="admin-form-actions mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('host.promotions.index') }}" class="admin-filter-clear-btn px-4">Hủy bỏ</a>
                <button type="submit" class="admin-create-btn px-4">
                    <i class="bi bi-check-circle me-2"></i>{{ $promotion ? 'Cập nhật' : 'Tạo mới' }} mã giảm giá
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Helper: format Date to YYYY-MM-DD
    function toDateStr(date) {
        return date.toISOString().split('T')[0];
    }

    // Helper: add days to a date string
    function addDays(dateStr, days) {
        const d = new Date(dateStr + 'T00:00:00');
        d.setDate(d.getDate() + days);
        return toDateStr(d);
    }

    const today = toDateStr(new Date());

    startDateInput.min = today;

    if (!startDateInput.value || startDateInput.value < today) {
        startDateInput.value = today;
    }

    function syncEndDate() {
        const startVal = startDateInput.value;

        if (!startVal) {
            return;
        }

        const minEnd = addDays(startVal, 1);
        endDateInput.min = minEnd;

        if (!endDateInput.value || endDateInput.value < minEnd) {
            endDateInput.value = minEnd;
        }
    }

    startDateInput.addEventListener('change', function () {
        if (this.value < today) {
            this.value = today;
        }
        syncEndDate();
    });

    endDateInput.addEventListener('change', function () {
        const startVal = startDateInput.value;
        if (startVal && this.value < addDays(startVal, 1)) {
            this.value = addDays(startVal, 1);
        }
    });

    syncEndDate();
});
</script>
@endpush
