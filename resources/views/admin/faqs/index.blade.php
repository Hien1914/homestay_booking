@extends('admin.layout.app')

@section('title', 'FAQ')
@section('page_title', 'Quản lý FAQ')
@section('page_kicker', 'Bảng faqs')

@section('content')
<section class="admin-page-section">
    <article class="admin-card">
        <div class="admin-card-head">
            <div>
                <h2>Danh sách FAQ</h2>
                <p>Dữ liệu lấy từ bảng <code>faqs</code>.</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Câu hỏi</th>
                        <th>Danh mục</th>
                        <th>Hiển thị</th>
                        <th>Tạo lúc</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td>{{ $faq->id }}</td>
                            <td>{{ $faq->question }}</td>
                            <td>{{ $faq->category ?: '-' }}</td>
                            <td>{{ $faq->is_active ? 'Có' : 'Không' }}</td>
                            <td>{{ optional($faq->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Chưa có dữ liệu trong bảng faqs.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
