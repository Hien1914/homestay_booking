document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const imageUploadZone = document.getElementById('image-upload-zone');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const imagePlaceholder = imageUploadZone.querySelector('.admin-upload-placeholder');

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
        slugInput.value = slugify(nameInput.value);
    };

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', syncSlug);
        if (!slugInput.value) {
            syncSlug();
        }
    }

    if (imageUploadZone) {
        imageUploadZone.addEventListener('click', () => imageInput.click());
    }

    function handleImageFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePlaceholder.classList.add('d-none');
                imagePreview.classList.remove('d-none');
                const currentImg = document.getElementById('current-image-container');
                if (currentImg) currentImg.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    imageInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleImageFile(this.files[0]);
        }
    });

    // Drag & Drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, () => {
            imageUploadZone.style.borderColor = 'var(--admin-primary)';
            imageUploadZone.style.background = '#eff6ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, () => {
            imageUploadZone.style.borderColor = '#e2e8f0';
            imageUploadZone.style.background = '#f8fafc';
        }, false);
    });

    imageUploadZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            imageInput.files = files;
            handleImageFile(files[0]);
        }
    }, false);
});

window.removeImagePreview = function(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('d-none');
    document.getElementById('upload-placeholder').classList.remove('d-none');
    const currentImg = document.getElementById('current-image-container');
    if (currentImg) currentImg.classList.remove('d-none');
};
