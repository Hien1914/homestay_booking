@extends('admin.layout.app')

@section('title', $post ? 'Sửa bài viết' : 'Tạo bài viết mới')

@section('content')
<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title">@yield('title')</h1>
        <p class="admin-page-subtitle">
            {{ $post ? 'Cập nhật nội dung cho bài viết #' . $post->id : 'Điền thông tin để tạo bài viết mới' }}
        </p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-outline">
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
        <h5 class="mb-0 fw-bold h6">
            <i class="bi bi-pencil-square me-2 text-primary"></i>Thông tin bài viết
        </h5>
    </div>
            <div class="card-body p-4">
                <form action="{{ $post ? route('admin.posts.update', $post->id) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($post)
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        {{-- Tiêu đề --}}
                        <div class="col-12">
                            <label for="title" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $post?->title) }}"
                                   placeholder="Nhập tiêu đề bài viết..." required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="col-12">
                            <label for="slug" class="form-label fw-bold">Slug <small class="text-muted">(tự động tạo)</small></label>
                            <input type="text" id="slug" name="slug"
                                   class="form-control bg-light"
                                   value="{{ old('slug', $post?->slug) }}"
                                   readonly>
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold">Mô tả ngắn</label>
                            <textarea id="description" name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Tóm tắt ngắn gọn nội dung bài viết...">{{ old('description', $post?->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ảnh đại diện & Trạng thái (2 columns here maybe, or separate rows? User said "mỗi hàng là 1 trường" - I will do 1 per row for strict compliance) --}}
                        <div class="col-12">
                            <label for="thumbnail_url" class="form-label fw-bold">Ảnh đại diện</label>
                            @if($post?->thumbnail_url)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $post->thumbnail_url) }}" alt="Ảnh đại diện"
                                         class="img-fluid rounded shadow-sm" style="max-height: 200px; width: auto;">
                                    <div class="form-text mt-2">Ảnh hiện tại của bài viết.</div>
                                </div>
                            @endif
                            <input type="file" id="thumbnail_url" name="thumbnail_url"
                                   class="form-control @error('thumbnail_url') is-invalid @enderror"
                                   accept="image/*">
                            <div class="form-text">Định dạng: JPEG, PNG, JPG, WebP. Dung lượng tối đa: 5MB.</div>
                            @error('thumbnail_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="thumbnail-preview" class="mt-3" style="display: none;">
                                <img id="thumbnail-preview-img" src="" alt="Preview"
                                     class="img-fluid rounded shadow-sm" style="max-height: 200px; width: auto;">
                            </div>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="col-12">
                            <label for="status" class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $post?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Bản nháp (Ẩn)</option>
                                <option value="published" {{ old('status', $post?->status) === 'published' ? 'selected' : '' }}>Xuất bản (Hiển thị)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nội dung --}}
                        <div class="col-12">
                            <label for="content" class="form-label fw-bold">Nội dung chi tiết <span class="text-danger">*</span></label>
                            <div class="@error('content') is-invalid-editor @enderror">
                                <textarea id="content" name="content"
                                          class="form-control">{{ old('content', $post?->content) }}</textarea>
                            </div>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2">
                            <button type="submit" class="admin-filter-btn px-4">
                                <i class="bi bi-save me-2"></i>{{ $post ? 'Cập nhật bài viết' : 'Lưu bài viết' }}
                            </button>
                            <a href="{{ route('admin.posts.index') }}" class="admin-filter-clear-btn px-4">Hủy bỏ</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection

@push('styles')
<style>
    .ck-editor__editable {
        min-height: 400px;
    }
    .ck-editor__editable:focus {
        border-color: var(--admin-primary) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // === CKEditor Upload Adapter ===
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
            if (this.xhr) this.xhr.abort();
        }
        _initRequest() {
            const xhr = this.xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.posts.upload-image") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.responseType = 'json';
        }
        _initListeners(resolve, reject, file) {
            const xhr = this.xhr;
            const loader = this.loader;
            const genericErrorText = `Không thể upload: ${file.name}.`;

            xhr.addEventListener('error', () => reject(genericErrorText));
            xhr.addEventListener('abort', () => reject());
            xhr.addEventListener('load', () => {
                const response = xhr.response;
                if (!response || response.error) {
                    return reject(response && response.error ? response.error.message : genericErrorText);
                }
                resolve({ default: response.url });
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

    // === Khởi tạo CKEditor ===
    ClassicEditor
        .create(document.querySelector('#content'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'link', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'undo', 'redo'
            ],
            image: {
                toolbar: ['imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side']
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error('CKEditor error:', error);
        });

    // === Slug tự động ===
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    const slugify = (value) => value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd').replace(/Đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');

    titleInput.addEventListener('input', () => {
        slugInput.value = slugify(titleInput.value);
    });

    @if(!$post)
        if (!slugInput.value && titleInput.value) {
            slugInput.value = slugify(titleInput.value);
        }
    @endif

    // === Preview ảnh đại diện ===
    const thumbnailInput = document.getElementById('thumbnail_url');
    const previewContainer = document.getElementById('thumbnail-preview');
    const previewImg = document.getElementById('thumbnail-preview-img');

    thumbnailInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endpush

