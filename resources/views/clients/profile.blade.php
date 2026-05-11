@extends('clients.layout.app')

@section('title', 'Thông tin cá nhân')

@php
    $user = auth()->user();
    $avatarUrl = $user?->avatar_url ?: null;
    $displayName = $user?->full_name ?: 'Người dùng';
    $initial = mb_strtoupper(mb_substr($displayName, 0, 1));
@endphp

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/profile.css') }}">

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
<script src="{{ asset('js/clients/profile.js') }}"></script>
@endpush


