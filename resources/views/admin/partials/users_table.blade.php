<div class="card-body p-0" id="users-table-container">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>SĐT</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
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
                        <td><span class="admin-badge admin-badge-ongoing">{{ $user->bookings_count }}</span></td>
                        <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="admin-action-btn js-open-user-modal" title="Xem chi tiết"
                                    data-bs-toggle="modal" data-bs-target="#userDetailModal"
                                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->full_name }}"
                                    data-user-email="{{ $user->email }}"
                                    data-user-role="{{ $user->role === 'host' ? 'Host' : 'User' }}"
                                    data-user-phone="{{ $user->phone ?: '-' }}" data-user-gender="{{ $genderText }}"
                                    data-user-bank-name="{{ $user->bank_name ?: '-' }}"
                                    data-user-bank-account="{{ $user->bank_account_number ?: '-' }}"
                                    data-user-birthday="{{ optional($user->birthday)->format('d/m/Y') ?: '-' }}"
                                    data-user-bookings="{{ $user->bookings_count }}"
                                    data-user-created-at="{{ optional($user->created_at)->format('d/m/Y') }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Chưa có tài khoản nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
