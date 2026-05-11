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
                    <div class="admin-chart-container" style="height: 300px;">
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
                    <div class="admin-chart-container" style="height: 300px;">
                        <canvas id="ageChart"></canvas>
                    </div>
                    <p class="text-center small text-muted mb-0">Số lượng người dùng theo nhóm tuổi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6">
                <i class="bi bi-people me-2 text-primary"></i>Danh sách tài khoản
            </h5>
            <form id="search-users-form" action="{{ route('admin.users') }}" method="GET" class="d-flex" style="width: 280px;">
                @if(request('from_date')) <input type="hidden" name="from_date" value="{{ request('from_date') }}"> @endif
                @if(request('to_date')) <input type="hidden" name="to_date" value="{{ request('to_date') }}"> @endif
                <input type="text" name="search_name" class="form-control form-control-sm" placeholder="Tìm kiếm theo tên..." value="{{ $searchName ?? '' }}">
                <button type="submit" class="btn btn-success btn-sm ms-2 rounded-4"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div id="users-table-wrapper">
            @include('admin.partials.users_table')
        </div>

    </div>

    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-light-subtle">
                    <h5 class="modal-title fw-bold">Chi tiết người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;">ID</th>
                                    <td id="modalUserId"></td>
                                </tr>
                                <tr>
                                    <th>Họ và tên</th>
                                    <td id="modalUserName"></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td id="modalUserEmail"></td>
                                </tr>
                                <tr>
                                    <th>Vai trò</th>
                                    <td id="modalUserRole"></td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại</th>
                                    <td id="modalUserPhone"></td>
                                </tr>
                                <tr>
                                    <th>Ngân hàng</th>
                                    <td id="modalUserBankName"></td>
                                </tr>
                                <tr>
                                    <th>Số tài khoản</th>
                                    <td id="modalUserBankAccount"></td>
                                </tr>
                                <tr>
                                    <th>Giới tính</th>
                                    <td id="modalUserGender"></td>
                                </tr>
                                <tr>
                                    <th>Ngày sinh</th>
                                    <td id="modalUserBirthday"></td>
                                </tr>
                                <tr>
                                    <th>Lượt đặt</th>
                                    <td id="modalUserBookings"></td>
                                </tr>
                                <tr>
                                    <th>Ngày đăng ký</th>
                                    <td id="modalUserCreatedAt"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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

            function attachModalListeners() {
                const openButtons = document.querySelectorAll('.js-open-user-modal');
                if (!openButtons.length) return;
                
                openButtons.forEach((button) => {
                    button.addEventListener('click', function () {
                        document.getElementById('modalUserId').textContent = this.dataset.userId;
                        document.getElementById('modalUserName').textContent = this.dataset.userName;
                        document.getElementById('modalUserEmail').textContent = this.dataset.userEmail;
                        document.getElementById('modalUserRole').textContent = this.dataset.userRole;
                        document.getElementById('modalUserPhone').textContent = this.dataset.userPhone;
                        document.getElementById('modalUserBankName').textContent = this.dataset.userBankName;
                        document.getElementById('modalUserBankAccount').textContent = this.dataset.userBankAccount;
                        document.getElementById('modalUserGender').textContent = this.dataset.userGender;
                        document.getElementById('modalUserBirthday').textContent = this.dataset.userBirthday;
                        document.getElementById('modalUserBookings').textContent = this.dataset.userBookings;
                        document.getElementById('modalUserCreatedAt').textContent = this.dataset.userCreatedAt;
                    });
                });
            }
            
            attachModalListeners();

            // AJAX Search
            const searchForm = document.getElementById('search-users-form');
            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const queryString = new URLSearchParams(formData).toString();
                    
                    // Show a simple loading state if needed
                    const tableWrapper = document.getElementById('users-table-wrapper');
                    tableWrapper.style.opacity = '0.5';

                    fetch(`${this.action}?${queryString}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableWrapper.innerHTML = html;
                        tableWrapper.style.opacity = '1';
                        attachModalListeners(); // Re-attach listeners to new buttons
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                        tableWrapper.style.opacity = '1';
                    });
                });
            }
        });
    </script>
@endpush

