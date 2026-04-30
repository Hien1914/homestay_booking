@extends('admin.layout.app')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="admin-page-header">
        <div class="admin-page-header-content">
            <h1 class="admin-page-title">@yield('title')</h1>
            <p class="admin-page-subtitle">Xem danh sách tài khoản trong hệ thống, bao gồm cả user và host.</p>
        </div>
    </div>

    <div class="admin-stats-grid mb-4">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-primary"><i class="bi bi-people"></i></div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($totalUsers) }}</div>
                <div class="admin-stat-label">Tổng số tài khoản</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-success"><i class="bi bi-person-plus"></i></div>
            <div class="admin-stat-content">
                <div class="admin-stat-value">{{ number_format($newUsersToday) }}</div>
                <div class="admin-stat-label">Tài khoản mới hôm nay</div>
                @if($newUsersRate != 0)
                    <div class="admin-stat-meta {{ $newUsersRate >= 0 ? 'text-success' : 'text-danger' }} small fw-bold">
                        {{ $newUsersRate >= 0 ? '↑' : '↓' }} {{ number_format(abs($newUsersRate), 1) }}%
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="from_date" class="form-label small fw-bold">Từ ngày</label>
                    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ $fromDate }}">
                </div>
                <div class="col-md-4">
                    <label for="to_date" class="form-label small fw-bold">Đến ngày</label>
                    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ $toDate }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="admin-filter-btn">Lọc</button>
                    <a href="{{ route('admin.users') }}" class="admin-filter-clear-btn">Xóa lọc</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Tỷ lệ giới tính tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="admin-chart-container mb-3" style="height: 300px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <span class="small"><span class="admin-dot d-inline-block me-1"
                                style="background:#2563eb; width:10px; height:10px; border-radius:50%"></span>Nam</span>
                        <span class="small"><span class="admin-dot d-inline-block me-1"
                                style="background:#ec4899; width:10px; height:10px; border-radius:50%"></span>Nữ</span>
                        <span class="small"><span class="admin-dot d-inline-block me-1"
                                style="background:#94a3b8; width:10px; height:10px; border-radius:50%"></span>Chưa cập
                            nhật</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Phân bố độ tuổi tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="admin-chart-container mb-3" style="height: 300px;">
                        <canvas id="ageChart"></canvas>
                    </div>
                    <p class="text-center small text-muted mb-0">Số lượng người dùng theo nhóm tuổi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-people me-2 text-primary"></i>Danh sách tài khoản
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>SĐT</th>
                            <th>Ngân hàng</th>
                            <th>Số tài khoản</th>
                            <th>Giới tính</th>
                            <th>Ngày sinh</th>
                            <th>Độ tuổi</th>
                            <th>Lượt đặt</th>
                            <th>Ngày ĐK</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><span class="admin-id-badge">#{{ $user->id }}</span></td>
                                <td class="fw-bold">{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'host')
                                        <span class="admin-badge admin-badge-success">Host</span>
                                    @else
                                        <span class="admin-badge admin-badge-info">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?: '-' }}</td>
                                <td>{{ $user->bank_name ?: '-' }}</td>
                                <td>{{ $user->bank_account_number ?: '-' }}</td>
                                <td>
                                    @php
                                        $genderText = match ($user->gender) {
                                            'male' => 'Nam',
                                            'female' => 'Nữ',
                                            'other' => 'Khác',
                                            default => '-',
                                        };
                                    @endphp
                                    {{ $genderText }}
                                </td>
                                <td>{{ optional($user->birthday)->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $user->age ?: '-' }}</td>
                                <td><span class="admin-badge admin-badge-ongoing">{{ $user->bookings_count }}</span></td>
                                <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="admin-action-btn js-open-user-modal" title="Xem chi tiết"
                                            data-user-name="{{ $user->full_name }}" data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->role === 'host' ? 'Host' : 'User' }}"
                                            data-user-phone="{{ $user->phone ?: '-' }}" data-user-gender="{{ $genderText }}"
                                            data-user-bank-name="{{ $user->bank_name ?: '-' }}"
                                            data-user-bank-account="{{ $user->bank_account_number ?: '-' }}"
                                            data-user-birthday="{{ optional($user->birthday)->format('d/m/Y') ?: '-' }}"
                                            data-user-age="{{ $user->age ?: '-' }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted py-4">Chưa có tài khoản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div class="admin-modal" id="userDetailModal" hidden>
        <div class="admin-modal-backdrop"></div>
        <div class="admin-modal-dialog">
            <div class="admin-modal-head">
                <h3>Chi tiết người dùng</h3>
                <button type="button" class="admin-modal-close" id="closeUserDetailModal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="admin-detail-list">
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Họ và tên</span>
                    <span class="admin-detail-value" id="modalUserName"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Email</span>
                    <span class="admin-detail-value" id="modalUserEmail"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Vai trò</span>
                    <span class="admin-detail-value" id="modalUserRole"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Số điện thoại</span>
                    <span class="admin-detail-value" id="modalUserPhone"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Ngân hàng</span>
                    <span class="admin-detail-value" id="modalUserBankName"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Số tài khoản</span>
                    <span class="admin-detail-value" id="modalUserBankAccount"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Giới tính</span>
                    <span class="admin-detail-value" id="modalUserGender"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Ngày sinh</span>
                    <span class="admin-detail-value" id="modalUserBirthday"></span>
                </div>
                <div class="admin-detail-item">
                    <span class="admin-detail-label">Độ tuổi</span>
                    <span class="admin-detail-value" id="modalUserAge"></span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart !== 'undefined') {
                const genderChart = document.getElementById('genderChart');
                if (genderChart) {
                    new Chart(genderChart, {
                        type: 'pie',
                        data: {
                            labels: @json($genderLabels),
                            datasets: [{
                                data: @json($genderData),
                                backgroundColor: ['#2563eb', '#ec4899', '#94a3b8'],
                                borderWidth: 0,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' },
                                tooltip: {
                                    callbacks: {
                                        label(context) {
                                            const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                                            const percent = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0.0';
                                            return `${context.label}: ${context.raw} (${percent}%)`;
                                        },
                                    },
                                },
                            },
                        },
                    });
                }

                const ageChart = document.getElementById('ageChart');
                if (ageChart) {
                    new Chart(ageChart, {
                        type: 'bar',
                        data: {
                            labels: @json($ageLabels),
                            datasets: [{
                                label: 'Số người dùng',
                                data: @json($ageData),
                                backgroundColor: '#10b981',
                                borderRadius: 8,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { precision: 0 } },
                            },
                        },
                    });
                }
            }

            const modal = document.getElementById('userDetailModal');
            const closeButton = document.getElementById('closeUserDetailModal');
            const backdrop = modal?.querySelector('.admin-modal-backdrop');
            const openButtons = document.querySelectorAll('.js-open-user-modal');

            if (!modal || !closeButton || !backdrop) {
                return;
            }

            const closeModal = () => {
                modal.hidden = true;
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    document.getElementById('modalUserName').textContent = this.dataset.userName;
                    document.getElementById('modalUserEmail').textContent = this.dataset.userEmail;
                    document.getElementById('modalUserRole').textContent = this.dataset.userRole;
                    document.getElementById('modalUserPhone').textContent = this.dataset.userPhone;
                    document.getElementById('modalUserBankName').textContent = this.dataset.userBankName;
                    document.getElementById('modalUserBankAccount').textContent = this.dataset.userBankAccount;
                    document.getElementById('modalUserGender').textContent = this.dataset.userGender;
                    document.getElementById('modalUserBirthday').textContent = this.dataset.userBirthday;
                    document.getElementById('modalUserAge').textContent = this.dataset.userAge;
                    modal.hidden = false;
                });
            });

            closeButton.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
        });
    </script>
@endpush
