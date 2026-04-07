@extends('clients.layout.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<style>
  @import url('{{ asset('css/clients/profile.css') }}');
</style>

<section class="profile-page section-py">
  <div class="container-setting">
    <div class="profile-shell">
      <aside class="profile-summary-card">
        <div class="profile-avatar-wrap">
          <div class="profile-avatar" id="profile-avatar">?</div>
        </div>
        <h1 class="profile-name" id="profile-name">Đang tải...</h1>
        <p class="profile-email" id="profile-email">Đang tải dữ liệu người dùng</p>
        <div class="profile-status-row">
          <span class="profile-chip" id="profile-role">Người dùng</span>
          <span class="profile-chip profile-chip--soft" id="profile-active">Đang kiểm tra</span>
        </div>
        <button type="button" class="profile-primary-btn" id="profile-edit-trigger">
          <i class="fa-solid fa-pen-to-square"></i>
          Chỉnh sửa thông tin
        </button>
      </aside>

      <div class="profile-detail-card">
        <div class="profile-card-head">
          <div>
            <p class="profile-eyebrow">Tài khoản của bạn</p>
            <h2 class="profile-card-title">Thông tin cá nhân</h2>
          </div>
          <button type="button" class="profile-ghost-btn" id="profile-refresh">
            <i class="fa-solid fa-rotate-right"></i>
            Tải lại
          </button>
        </div>

        <div class="profile-alert profile-alert--error" id="profile-error" hidden></div>
        <div class="profile-alert profile-alert--success" id="profile-success" hidden></div>

        <div class="profile-grid">
          <div class="profile-field-card">
            <span class="profile-field-label">Họ và tên</span>
            <strong class="profile-field-value" id="profile-full-name">--</strong>
          </div>
          <div class="profile-field-card">
            <span class="profile-field-label">Email</span>
            <strong class="profile-field-value" id="profile-email-detail">--</strong>
          </div>
          <div class="profile-field-card">
            <span class="profile-field-label">Số điện thoại</span>
            <strong class="profile-field-value" id="profile-phone">Chưa cập nhật</strong>
          </div>
          <div class="profile-field-card">
            <span class="profile-field-label">Ngày tạo tài khoản</span>
            <strong class="profile-field-value" id="profile-created-at">--</strong>
          </div>
          <div class="profile-field-card">
            <span class="profile-field-label">Trạng thái email</span>
            <strong class="profile-field-value" id="profile-email-verified">--</strong>
          </div>
          <div class="profile-field-card">
            <span class="profile-field-label">Vai trò</span>
            <strong class="profile-field-value" id="profile-role-detail">Người dùng</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="profile-modal" id="profile-modal" hidden>
  <div class="profile-modal-backdrop" data-profile-close></div>
  <div class="profile-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="profile-modal-title">
    <div class="profile-modal-head">
      <h3 id="profile-modal-title">Chỉnh sửa thông tin</h3>
      <button type="button" class="profile-modal-close" data-profile-close aria-label="Đóng">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form id="profile-form" class="profile-form" novalidate>
      <div class="profile-upload-row">
        <div class="profile-upload-avatar" id="profile-modal-avatar">?</div>
        <div class="profile-upload-meta">
          <label class="profile-upload-btn" for="profile-avatar-input">
            <i class="fa-solid fa-image"></i>
            Chọn ảnh đại diện mới
          </label>
          <input type="file" id="profile-avatar-input" name="avatar" accept=".jpg,.jpeg,.png,.webp" hidden>
          <span class="profile-upload-hint">Hỗ trợ JPG, PNG, WEBP. Dung lượng tối đa 2MB.</span>
        </div>
      </div>

      <div class="profile-form-group">
        <label for="profile-input-name">Họ và tên</label>
        <input type="text" id="profile-input-name" name="name" maxlength="100" required>
      </div>

      <div class="profile-form-group">
        <label for="profile-input-email">Email</label>
        <input type="email" id="profile-input-email" name="email" readonly>
      </div>

      <div class="profile-form-group">
        <label for="profile-input-phone">Số điện thoại</label>
        <input type="text" id="profile-input-phone" name="phone" maxlength="15" placeholder="Nhập số điện thoại">
      </div>

      <div class="profile-modal-alert profile-alert--error" id="profile-modal-error" hidden></div>

      <div class="profile-modal-actions">
        <button type="button" class="profile-secondary-btn" data-profile-close>Hủy</button>
        <button type="submit" class="profile-primary-btn" id="profile-save-btn">Cập nhật</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  var token = localStorage.getItem('auth_token');
  var currentUser = null;
  var avatarFile = null;

  var profileError = document.getElementById('profile-error');
  var profileSuccess = document.getElementById('profile-success');
  var modalError = document.getElementById('profile-modal-error');
  var modal = document.getElementById('profile-modal');
  var form = document.getElementById('profile-form');
  var saveBtn = document.getElementById('profile-save-btn');
  var avatarInput = document.getElementById('profile-avatar-input');

  function showMessage(el, msg) {
    if (!el) return;
    el.textContent = msg || '';
    el.hidden = !msg;
  }

  function clearMessages() {
    showMessage(profileError, '');
    showMessage(profileSuccess, '');
    showMessage(modalError, '');
  }

  function getInitials(name) {
    if (!name) return '?';
    var parts = String(name).trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return '?';
    if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
  }

  function roleLabel(role) {
    return role === 'admin' ? 'Quản trị viên' : 'Người dùng';
  }

  function setAvatar(el, user) {
    if (!el) return;
    el.style.backgroundImage = '';
    el.classList.remove('has-image');
    el.textContent = getInitials(user && user.name);
    if (user && user.avatar) {
      el.style.backgroundImage = 'url(' + user.avatar + ')';
      el.classList.add('has-image');
      el.textContent = '';
    }
  }

  function renderProfile(user) {
    currentUser = user;
    document.getElementById('profile-name').textContent = user.name || 'Người dùng';
    document.getElementById('profile-email').textContent = user.email || '';
    document.getElementById('profile-full-name').textContent = user.name || '--';
    document.getElementById('profile-email-detail').textContent = user.email || '--';
    document.getElementById('profile-phone').textContent = user.phone || 'Chưa cập nhật';
    document.getElementById('profile-created-at').textContent = user.created_at || '--';
    document.getElementById('profile-email-verified').textContent = user.email_verified ? 'Đã xác minh' : 'Chưa xác minh';
    document.getElementById('profile-role').textContent = roleLabel(user.role);
    document.getElementById('profile-role-detail').textContent = roleLabel(user.role);
    document.getElementById('profile-active').textContent = 'Đang hoạt động';

    setAvatar(document.getElementById('profile-avatar'), user);
    setAvatar(document.getElementById('profile-modal-avatar'), user);

    document.getElementById('profile-input-name').value = user.name || '';
    document.getElementById('profile-input-email').value = user.email || '';
    document.getElementById('profile-input-phone').value = user.phone || '';

    localStorage.setItem('auth_user', JSON.stringify(user));
    updateHeaderUser(user);
  }

  function updateHeaderUser(user) {
    var userName = document.getElementById('user-name');
    var userAvatar = document.getElementById('user-avatar');
    if (userName) userName.textContent = user.name || 'Người dùng';
    if (userAvatar) setAvatar(userAvatar, user);
  }

  function openModal() {
    clearMessages();
    if (!currentUser) return;
    avatarFile = null;
    if (avatarInput) avatarInput.value = '';
    renderProfile(currentUser);
    modal.hidden = false;
    document.body.classList.add('profile-modal-open');
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('profile-modal-open');
    showMessage(modalError, '');
  }

  function apiJson(url, options) {
    return fetch(url, options).then(function (res) {
      return res.json().catch(function () { return {}; }).then(function (data) {
        return { ok: res.ok, status: res.status, data: data };
      });
    });
  }

  function loadProfile(showRefreshMessage) {
    clearMessages();
    apiJson('/api/user/profile', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
      }
    }).then(function (payload) {
      if (payload.status === 401 || !payload.ok) {
        window.location.href = '{{ route('login.page') }}?redirect=' + encodeURIComponent(window.location.pathname);
        return;
      }
      if (!payload.data || !payload.data.data) {
        showMessage(profileError, 'Không thể tải thông tin cá nhân.');
        return;
      }
      renderProfile(payload.data.data);
      if (showRefreshMessage) {
        showMessage(profileSuccess, 'Đã tải lại thông tin mới nhất.');
      }
    }).catch(function () {
      showMessage(profileError, 'Không thể kết nối tới máy chủ.');
    });
  }

  function uploadAvatarIfNeeded() {
    if (!avatarFile) return Promise.resolve(null);

    var formData = new FormData();
    formData.append('avatar', avatarFile);

    return fetch('/api/user/avatar', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: formData
    }).then(function (res) {
      return res.json().catch(function () { return {}; }).then(function (data) {
        if (!res.ok || data.success === false) {
          throw new Error(data.message || 'Cập nhật ảnh đại diện thất bại.');
        }
        return data.avatar_url || null;
      });
    });
  }

  if (!token) {
    window.location.href = '{{ route('login.page') }}?redirect=' + encodeURIComponent(window.location.pathname);
    return;
  }

  document.getElementById('profile-edit-trigger').addEventListener('click', openModal);
  document.getElementById('profile-refresh').addEventListener('click', function () {
    loadProfile(true);
  });

  document.querySelectorAll('[data-profile-close]').forEach(function (el) {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) {
      closeModal();
    }
  });

  avatarInput.addEventListener('change', function () {
    var file = avatarInput.files && avatarInput.files[0] ? avatarInput.files[0] : null;
    avatarFile = file;
    if (!file) {
      setAvatar(document.getElementById('profile-modal-avatar'), currentUser);
      return;
    }

    if (file.size > 2 * 1024 * 1024) {
      avatarFile = null;
      avatarInput.value = '';
      showMessage(modalError, 'Ảnh đại diện vượt quá giới hạn 2MB.');
      return;
    }

    var reader = new FileReader();
    reader.onload = function (event) {
      var avatar = document.getElementById('profile-modal-avatar');
      avatar.style.backgroundImage = 'url(' + event.target.result + ')';
      avatar.classList.add('has-image');
      avatar.textContent = '';
    };
    reader.readAsDataURL(file);
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    clearMessages();

    var name = document.getElementById('profile-input-name').value.trim();
    var phone = document.getElementById('profile-input-phone').value.trim();

    if (!name) {
      showMessage(modalError, 'Vui lòng nhập họ và tên.');
      return;
    }

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang cập nhật';

    apiJson('/api/user/profile', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({
        name: name,
        phone: phone || null
      })
    }).then(function (payload) {
      if (!payload.ok || !payload.data || payload.data.success === false) {
        throw new Error((payload.data && payload.data.message) || 'Cập nhật thông tin thất bại.');
      }

      var updatedUser = payload.data.data;
      return uploadAvatarIfNeeded().then(function (avatarUrl) {
        if (avatarUrl) {
          updatedUser.avatar = avatarUrl;
        }
        renderProfile(updatedUser);
        closeModal();
        showMessage(profileSuccess, 'Cập nhật thông tin thành công.');
      });
    }).catch(function (error) {
      showMessage(modalError, error.message || 'Không thể cập nhật thông tin.');
    }).finally(function () {
      saveBtn.disabled = false;
      saveBtn.textContent = 'Cập nhật';
    });
  });

  loadProfile(false);
})();
</script>
@endpush
