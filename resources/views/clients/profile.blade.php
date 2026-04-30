@extends('clients.layout.app')

@section('title', 'Thông tin cá nhân')

@php
    $user = auth()->user();
    $avatarUrl = $user?->avatar_url ?: null;
    $displayName = $user?->full_name ?: 'Người dùng';
    $initial = mb_strtoupper(mb_substr($displayName, 0, 1));
@endphp

@section('content')
<style>
  .profile-page-simple {
    padding: 32px 0 48px;
    background: #f7faf6;
  }

  .profile-card-simple {
    max-width: 560px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #e5ece2;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 10px 22px rgba(28, 54, 33, 0.05);
  }

  .profile-card-head-simple {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
  }

  .profile-card-head-simple h1 {
    margin: 0;
    font-size: 1.2rem;
    color: #1d3523;
  }

  .profile-avatar-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
  }

  .profile-avatar-simple {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--green-400) 0%, var(--green-500) 100%);
    color: #fff;
    font-size: 32px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .profile-avatar-simple img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-form-simple {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
  }

  .profile-field-simple {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  .profile-field-simple label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #4f6252;
  }

  .profile-field-simple input,
  .profile-field-simple select {
    height: 40px;
    padding: 0 12px;
    border: 1px solid transparent;
    border-radius: 10px;
    background: #f6f8f5;
    color: #1d3523;
    font-size: 0.92rem;
    transition: all 0.2s ease;
  }

  .profile-field-simple input:disabled,
  .profile-field-simple select:disabled {
    color: #5f7062;
    opacity: 1;
    background: #f6f8f5;
    border-color: transparent;
    pointer-events: none;
  }

  .profile-form-simple.is-editing .profile-field-simple input:not([readonly]),
  .profile-form-simple.is-editing .profile-field-simple select {
    background: #fff;
    border-color: #d7e2d5;
    pointer-events: auto;
  }

  .profile-form-simple.is-editing .profile-field-simple input[readonly] {
    background: #f2f4f1;
    border-color: #e2e8de;
  }

  .profile-actions-simple {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 8px;
  }

  .profile-btn-simple {
    border: 0;
    border-radius: 999px;
    padding: 9px 14px;
    font-size: 0.86rem;
    font-weight: 600;
  }

  .profile-btn-primary {
    background: #25492d;
    color: #fff;
  }

  .profile-btn-secondary {
    background: #edf3eb;
    color: #27412c;
  }

  .profile-alert-simple {
    margin-bottom: 16px;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 0.9rem;
  }

  .profile-alert-success {
    background: #ebf7ee;
    color: #1f7a38;
  }

  .profile-alert-error {
    background: #fff1f0;
    color: #c0392b;
  }

  @media (max-width: 768px) {
    .profile-page-simple {
      padding: 28px 0 40px;
    }

    .profile-card-simple {
      padding: 18px;
      border-radius: 16px;
    }

    .profile-card-head-simple {
      flex-direction: column;
      align-items: flex-start;
    }

    .profile-form-simple {
      grid-template-columns: 1fr;
    }

    .profile-actions-simple {
      justify-content: stretch;
      flex-direction: column;
    }
  }
</style>

<section class="profile-page-simple">
  <div class="container-setting">
    <div class="profile-card-simple">
      <div class="profile-card-head-simple">
        <div>
          <h1>Thông tin cá nhân</h1>
        </div>
        <button type="button" class="profile-btn-simple profile-btn-secondary" id="profile-edit-btn">
          Cập nhật thông tin
        </button>
      </div>

      @if(session('success'))
        <div class="profile-alert-simple profile-alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="profile-alert-simple profile-alert-error">{{ $errors->first() }}</div>
      @endif

      <div class="profile-avatar-wrap">
        <div class="profile-avatar-simple">
          @if($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="{{ $displayName }}">
          @else
            <span>{{ $initial }}</span>
          @endif
        </div>
      </div>

      <form method="POST" action="{{ route('profile.update') }}" class="profile-form-simple" id="profile-form">
        @csrf
        @method('PUT')

        <div class="profile-field-simple">
          <label for="full_name">Họ và tên</label>
          <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user?->full_name) }}" disabled required>
        </div>

        <div class="profile-field-simple">
          <label for="email">Email</label>
          <input type="email" id="email" value="{{ $user?->email }}" disabled readonly>
        </div>

        <div class="profile-field-simple">
          <label for="phone">Số điện thoại</label>
          <input type="text" id="phone" name="phone" value="{{ old('phone', $user?->phone) }}" disabled>
        </div>

        <div class="profile-field-simple">
          <label for="bank_name">Ngân hàng</label>
          <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $user?->bank_name) }}" disabled>
        </div>

        <div class="profile-field-simple">
          <label for="bank_account_number">Số tài khoản</label>
          <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $user?->bank_account_number) }}" disabled>
        </div>

        <div class="profile-field-simple">
          <label for="gender">Giới tính</label>
          <select id="gender" name="gender" disabled>
            <option value="">Chưa cập nhật</option>
            <option value="male" {{ old('gender', $user?->gender) === 'male' ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ old('gender', $user?->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
            <option value="other" {{ old('gender', $user?->gender) === 'other' ? 'selected' : '' }}>Khác</option>
          </select>
        </div>

        <div class="profile-field-simple">
          <label for="birthday">Ngày sinh</label>
          <input type="date" id="birthday" name="birthday" value="{{ old('birthday', optional($user?->birthday)->format('Y-m-d')) }}" disabled>
        </div>

        <div class="profile-field-simple">
          <label for="created_at">Ngày tạo tài khoản</label>
          <input type="text" id="created_at" value="{{ optional($user?->created_at)->format('d/m/Y H:i') ?: 'Chưa có dữ liệu' }}" disabled readonly>
        </div>

        <div class="profile-actions-simple" id="profile-actions" hidden>
          <button type="button" class="profile-btn-simple profile-btn-secondary" id="profile-cancel-btn">Hủy</button>
          <button type="submit" class="profile-btn-simple profile-btn-primary">Lưu thông tin</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const editBtn = document.getElementById('profile-edit-btn');
  const cancelBtn = document.getElementById('profile-cancel-btn');
  const actions = document.getElementById('profile-actions');
  const form = document.getElementById('profile-form');
  const editableFields = Array.from(document.querySelectorAll('#profile-form input[name], #profile-form select[name]'));

  const initialValues = Object.fromEntries(
    editableFields.map((field) => [field.name, field.value])
  );

  function setEditing(isEditing) {
    editableFields.forEach((field) => {
      field.disabled = !isEditing;
    });
    actions.hidden = !isEditing;
    editBtn.hidden = isEditing;
    form.classList.toggle('is-editing', isEditing);
  }

  editBtn.addEventListener('click', function () {
    setEditing(true);
  });

  cancelBtn.addEventListener('click', function () {
    editableFields.forEach((field) => {
      field.value = initialValues[field.name] ?? '';
    });
    setEditing(false);
  });
});
</script>
@endpush
