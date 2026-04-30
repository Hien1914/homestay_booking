@extends('host.layout.app')

@section('title', 'Quản lý tiện nghi')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Quản lý danh sách tiện nghi bạn cung cấp</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="admin-create-btn" onclick="openAmenityModal()">
            <i class="bi bi-plus-lg"></i>
            Thêm nhanh
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-stars me-2 text-primary"></i>Danh sách tiện nghi của tôi
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">STT</th>
                        <th>Tên tiện nghi</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($amenities as $amenity)
                        <tr>
                            <td class="ps-4"><span class="admin-id-badge">#{{ $loop->iteration }}</span></td>
                            <td><div class="fw-bold text-dark">{{ $amenity->name }}</div></td>
                            <td class="text-center">
                                @if($amenity->is_approved)
                                    <span class="admin-badge admin-badge-success">Đã duyệt</span>
                                @else
                                    <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                @endif
                            </td>
                            <td class="text-center small text-muted">{{ $amenity->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="admin-actions d-flex justify-content-center gap-1">
                                    <button type="button" class="admin-action-btn" title="Sửa" 
                                            onclick="openAmenityModal({{ $amenity->id }}, '{{ addslashes($amenity->name) }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('host.amenities.destroy', $amenity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
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
                            <td colspan="5" class="text-center py-5">Chưa có tiện nghi nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $amenities->links() }}
        </div>
    </div>
</div>

<!-- Quick Amenity Modal -->
<div class="modal fade" id="amenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="amenityModalTitle">Thêm tiện nghi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="amenityForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label for="amenityName" class="form-label fw-bold mb-2">Tên tiện nghi <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="amenityName" class="form-control amenity-input-large" required placeholder="Ví dụ: WiFi, Hồ bơi, Chỗ đậu xe..." style="border-radius: 12px; height: 50px; padding-left: 15px;">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="admin-filter-clear-btn px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="admin-create-btn px-4" id="submitBtn">Lưu tiện nghi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modal = new bootstrap.Modal(document.getElementById('amenityModal'));
    const form = document.getElementById('amenityForm');
    const title = document.getElementById('amenityModalTitle');
    const nameInput = document.getElementById('amenityName');
    const methodField = document.getElementById('methodField');

    function openAmenityModal(id = null, name = '') {
        if (id) {
            title.innerText = 'Sửa tiện nghi';
            form.action = `/host/amenities/${id}`;
            methodField.innerHTML = '@method("PUT")';
            nameInput.value = name;
        } else {
            title.innerText = 'Thêm tiện nghi mới';
            form.action = '{{ route("host.amenities.store") }}';
            methodField.innerHTML = '';
            nameInput.value = '';
        }
        modal.show();
        setTimeout(() => nameInput.focus(), 500);
    }
</script>
@endpush

