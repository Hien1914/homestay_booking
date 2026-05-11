@extends('clients.layout.app')

@section('title', 'Cẩm Nang & Tin Tức Du Lịch')

@section('content')
<main class="blog-page">
    <div class="container">
        <div class="blog-header">
            <h1 class="blog-title">Cẩm Nang & Tin Tức Du Lịch</h1>
            <p class="blog-subtitle">Tuyển tập những kinh nghiệm du lịch, mẹo hay và cập nhật homestay mới nhất dành riêng cho bạn</p>
        </div>
        
        <div class="blog-grid">
            @forelse($posts as $post)
                @php
                    $imageUrl = $post->image;
                    if (!$imageUrl) {
                        $imageUrl = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop';
                    } elseif (!Str::startsWith($imageUrl, ['http://', 'https://'])) {
                        // Backend lưu ảnh dạng "/storage/posts/...", ta dùng asset() để tự sinh full URL
                        $imageUrl = asset($imageUrl);
                    }
                @endphp
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card" style="display: block; text-decoration: none; color: inherit;">
                    <div class="blog-card-image">
                        <img src="{{ $imageUrl }}" alt="{{ $post->title }}">
                        <span class="blog-card-category">Bài viết</span>
                    </div>
                    <div class="blog-card-content">
                        <h2 class="blog-card-title">
                            {{ $post->title }}
                        </h2>
                        <p class="blog-card-excerpt" style="color: #5f7066;">{{ $post->description ?? Str::limit(strip_tags($post->content), 120) }}</p>
                        <div class="blog-card-meta">
                            <span class="blog-read-more" style="color:#003b0d; font-weight:600; display:flex; align-items:center; gap:5px;">
                                Xem chi tiết <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                            </span>
                            <span class="blog-card-date" style="color:#666; font-size:0.85rem;">
                                <i class="far fa-eye"></i> {{ number_format($post->views ?? 0) }} lượt xem
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Chưa có bài viết nào được xuất bản.</p>
                </div>
            @endforelse
        </div>
        
        @if($posts->hasPages())
            {{ $posts->links() }}
        @endif
    </div>
</main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/clients/blog.css') }}">
@endpush

@push('scripts')
<script>
document.querySelectorAll('.blog-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.blog-category-btn').forEach(b => b.classList.remove('is-active'));
        this.classList.add('is-active');
    });
});
</script>
@endpush
