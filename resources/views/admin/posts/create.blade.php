@extends('admin.layout.app')

@section('title', 'Thêm Bài Viết Mới')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">Điền thông tin để tạo bài viết mới</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-outline">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="bi bi-newspaper me-2"></i>Thông tin bài viết</h3>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-group">
                <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="admin-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" id="slug" name="slug" class="admin-form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="tu-dong-theo-tieu-de" readonly>
                @error('slug')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="description">Mô tả ngắn</label>
                <textarea id="description" name="description" class="admin-form-control @error('description') is-invalid @enderror" rows="3" placeholder="Mô tả ngắn cho bài viết">{{ old('description') }}</textarea>
                @error('description')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="image">Ảnh đại diện</label>
                <input type="file" id="image" name="image" class="admin-form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">JPEG, PNG, JPG, GIF, WebP · Tối đa 5MB</small>
                @error('image')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-group">
                <label class="mb-2">Trạng thái <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_draft" value="draft" {{ old('status') === 'draft' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_draft">Nháp</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_published" value="published" {{ old('status') === 'published' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_published">Xuất bản</label>
                    </div>
                </div>
                @error('status')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="editor">Nội dung <span class="text-danger">*</span></label>
                <input type="hidden" id="content" name="content" value="{{ old('content') }}">
                <div id="post-editor"></div>
                @error('content')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-actions">
                <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Lưu bài viết
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/posts-form.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }
        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    this._initRequest();
                    this._initListeners(resolve, reject, file);
                    this._sendRequest(file);
                }));
        }
        abort() {
            if (this.xhr) {
                this.xhr.abort();
            }
        }
        _initRequest() {
            const xhr = this.xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('admin.posts.upload-image') }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.responseType = 'json';
        }
        _initListeners(resolve, reject, file) {
            const xhr = this.xhr;
            const loader = this.loader;
            const genericErrorText = `Couldn't upload file: ${file.name}.`;

            xhr.addEventListener('error', () => reject(genericErrorText));
            xhr.addEventListener('abort', () => reject());
            xhr.addEventListener('load', () => {
                const response = xhr.response;
                if (!response || response.error) {
                    return reject(response && response.error ? response.error.message : genericErrorText);
                }
                resolve({
                    default: response.url
                });
            });

            if (xhr.upload) {
                xhr.upload.addEventListener('progress', evt => {
                    if (evt.lengthComputable) {
                        loader.uploadTotal = evt.total;
                        loader.uploaded = evt.loaded;
                    }
                });
            }
        }
        _sendRequest(file) {
            const data = new FormData();
            data.append('upload', file);
            this.xhr.send(data);
        }
    }

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    ClassicEditor
        .create(document.querySelector('#content'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                'imageUpload', 'insertTable', 'mediaEmbed', 'undo', 'redo'
            ]
        })
        .catch(error => {
            console.error(error);
        });

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const slugify = (value) => value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');

    const syncSlug = () => {
        slugInput.value = slugify(titleInput.value);
    };

    titleInput.addEventListener('input', syncSlug);
    if (!slugInput.value) {
        syncSlug();
    }
</script>

