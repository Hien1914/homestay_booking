@extends('clients.layout.app')

@section('title', 'Blog')

@section('content')
@php
    $blogs = [
        [
            'id' => 1,
            'title' => 'Top 10 Homestay Đà Lạt View Đẹp Nhất 2026',
            'slug' => 'top-10-homestay-da-lat-view-dep-nhat-2026',
            'excerpt' => 'Khám phá những homestay có view núi đồi, rừng thông tuyệt đẹp tại Đà Lạt. Nơi lý tưởng để nghỉ dưỡng và check-in sống ảo.',
            'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop',
            'author' => 'Minh Anh',
            'author_avatar' => 'https://ui-avatars.com/api/?name=Minh+Anh&background=random',
            'date' => '2026-04-03',
            'category' => 'Địa điểm',
        ],
        [
            'id' => 2,
            'title' => 'Kinh Nghiệm Đặt Homestay Lần Đầu Cho Người Mới',
            'slug' => 'kinh-nghiem-dat-homestay-lan-dau',
            'excerpt' => 'Những tips hữu ích giúp bạn chọn được homestay phù hợp, tránh các sai lầm phổ biến khi đặt phòng trực tuyến.',
            'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop',
            'author' => 'Thu Hà',
            'author_avatar' => 'https://ui-avatars.com/api/?name=Thu+Ha&background=random',
            'date' => '2026-04-01',
            'category' => 'Mẹo hay',
        ],
        [
            'id' => 3,
            'title' => 'Sapa Mùa Xuân - Thời Điểm Vàng Để Du Lịch',
            'slug' => 'sapa-mua-xuan-thoi-diem-vang',
            'excerpt' => 'Tháng 3-4 là thời điểm lý tưởng để ghé thăm Sapa với thời tiết mát mẻ và ruộng bậc thang xanh mướt.',
            'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?w=600&h=400&fit=crop',
            'author' => 'Quang Huy',
            'author_avatar' => 'https://ui-avatars.com/api/?name=Quang+Huy&background=random',
            'date' => '2026-03-28',
            'category' => 'Du lịch',
        ],
        [
            'id' => 4,
            'title' => 'Review Homestay Phú Quốc Giá Rẻ Dưới 500K',
            'slug' => 'review-homestay-phu-quoc-gia-re',
            'excerpt' => 'Tổng hợp các homestay chất lượng tại Phú Quốc với mức giá bình dân, phù hợp cho chuyến đi tiết kiệm.',
            'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600&h=400&fit=crop',
            'author' => 'Thanh Tùng',
            'author_avatar' => 'https://ui-avatars.com/api/?name=Thanh+Tung&background=random',
            'date' => '2026-03-25',
            'category' => 'Review',
        ],
        [
            'id' => 5,
            'title' => 'Những Điều Cần Biết Khi Ở Homestay Cùng Gia Đình',
            'slug' => 'nhung-dieu-can-biet-khi-o-homestay-cung-gia-dinh',
            'excerpt' => 'Lưu ý về an toàn, tiện nghi và các hoạt động phù hợp khi đi du lịch cùng trẻ nhỏ và người lớn tuổi.',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&h=400&fit=crop',
            'author' => 'Ngọc Linh',
            'author_avatar' => 'https://ui-avatars.com/api/?name=Ngoc+Linh&background=random',
            'date' => '2026-03-22',
            'category' => 'Mẹo hay',
        ],
        [
            'id' => 6,
            'title' => 'Hội An - Vẻ Đẹp Cổ Kính Và Những Homestay Độc Đáo',
            'slug' => 'hoi-an-ve-dep-co-kinh-va-homestay-doc-dao',
            'excerpt' => 'Khám phá kiến trúc độc đáo của phố cổ Hội An qua những homestay mang đậm nét văn hóa truyền thống.',
            'image' => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=600&h=400&fit=crop',
            'author' => 'Văn Đức',
            'author_avatar' => 'https://ui-avatars.com/api/?name=Van+Duc&background=random',
            'date' => '2026-03-18',
            'category' => 'Địa điểm',
        ],
    ];
@endphp

<main class="blog-page">
    <div class="container">
        <div class="blog-header">
            <h1 class="blog-title">Blog Du Lịch</h1>
            <p class="blog-subtitle">Khám phá những địa điểm tuyệt vời, mẹo du lịch và review homestay từ cộng đồng NestAway</p>
        </div>
        
        <div class="blog-categories">
            <button class="blog-category-btn is-active" data-category="all">Tất cả</button>
            <button class="blog-category-btn" data-category="dia-diem">Địa điểm</button>
            <button class="blog-category-btn" data-category="meo-hay">Mẹo hay</button>
            <button class="blog-category-btn" data-category="review">Review</button>
            <button class="blog-category-btn" data-category="du-lich">Du lịch</button>
        </div>
        
        <div class="blog-grid">
            @foreach($blogs as $blog)
                <article class="blog-card">
                    <a href="{{ url('/blog/' . $blog['slug']) }}" class="blog-card-image">
                        <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}">
                        <span class="blog-card-category">{{ $blog['category'] }}</span>
                    </a>
                    <div class="blog-card-content">
                        <h2 class="blog-card-title">
                            <a href="{{ url('/blog/' . $blog['slug']) }}">{{ $blog['title'] }}</a>
                        </h2>
                        <p class="blog-card-excerpt">{{ $blog['excerpt'] }}</p>
                        <div class="blog-card-meta">
                            <div class="blog-card-author">
                                <img src="{{ $blog['author_avatar'] }}" alt="{{ $blog['author'] }}" class="blog-author-avatar">
                                <span class="blog-author-name">{{ $blog['author'] }}</span>
                            </div>
                            <span class="blog-card-date">
                                <i class="far fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($blog['date'])->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        
        <div class="blog-pagination">
            <button class="blog-page-btn" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="blog-page-btn is-active">1</button>
            <button class="blog-page-btn">2</button>
            <button class="blog-page-btn">3</button>
            <button class="blog-page-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<style>
.blog-page {
    padding: 40px 0 80px;
    background: #f8faf8;
    min-height: 70vh;
}

.blog-header {
    text-align: center;
    margin-bottom: 40px;
}

.blog-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a2e1a;
    margin: 0 0 12px;
}

.blog-subtitle {
    font-size: 1.1rem;
    color: #5f7066;
    margin: 0;
    max-width: 600px;
    margin: 0 auto;
}

.blog-categories {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.blog-category-btn {
    padding: 10px 20px;
    border: 1px solid #d7e3d2;
    border-radius: 999px;
    background: white;
    font-size: 0.9rem;
    font-weight: 500;
    color: #5f7066;
    cursor: pointer;
    transition: all 0.2s;
}

.blog-category-btn:hover {
    border-color: #003b0d;
    color: #003b0d;
}

.blog-category-btn.is-active {
    background: #003b0d;
    border-color: #003b0d;
    color: white;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

@media (max-width: 992px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .blog-grid {
        grid-template-columns: 1fr;
    }
    
    .blog-title {
        font-size: 1.8rem;
    }
    
    .blog-subtitle {
        font-size: 1rem;
    }
}

.blog-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s;
}

.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.blog-card-image {
    display: block;
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.blog-card:hover .blog-card-image img {
    transform: scale(1.08);
}

.blog-card-category {
    position: absolute;
    top: 14px;
    left: 14px;
    padding: 6px 14px;
    background: white;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #003b0d;
}

.blog-card-content {
    padding: 20px;
}

.blog-card-title {
    margin: 0 0 12px;
    font-size: 1.1rem;
    font-weight: 700;
    line-height: 1.4;
}

.blog-card-title a {
    color: #1a2e1a;
    text-decoration: none;
    transition: color 0.2s;
}

.blog-card-title a:hover {
    color: #003b0d;
}

.blog-card-excerpt {
    margin: 0 0 16px;
    font-size: 0.9rem;
    color: #5f7066;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #eef3ec;
}

.blog-card-author {
    display: flex;
    align-items: center;
    gap: 10px;
}

.blog-author-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.blog-author-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1a2e1a;
}

.blog-card-date {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    color: #5f7066;
}

.blog-card-date i {
    font-size: 0.85rem;
}

.blog-pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 50px;
}

.blog-page-btn {
    min-width: 42px;
    height: 42px;
    padding: 0 14px;
    border: 1px solid #d7e3d2;
    border-radius: 8px;
    background: white;
    font-size: 0.9rem;
    font-weight: 500;
    color: #1a2e1a;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.blog-page-btn:hover:not(:disabled) {
    border-color: #003b0d;
    color: #003b0d;
}

.blog-page-btn.is-active {
    background: #003b0d;
    border-color: #003b0d;
    color: white;
}

.blog-page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
document.querySelectorAll('.blog-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.blog-category-btn').forEach(b => b.classList.remove('is-active'));
        this.classList.add('is-active');
    });
});
</script>
@endpush
