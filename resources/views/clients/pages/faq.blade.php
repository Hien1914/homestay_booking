@extends('clients.layout.app')

@section('title', 'Câu hỏi thường gặp (FAQ)')

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/faq.css') }}">

@php
$allFaqs = [
    'Đặt phòng' => [
        ['question' => 'Làm thế nào để đặt phòng trên hệ thống?',       'answer' => 'Bạn chỉ cần tìm kiếm homestay mong muốn, chọn ngày nhận/trả phòng, số lượng khách và nhấn "Đặt phòng". Sau đó làm theo hướng dẫn để hoàn tất.'],
        ['question' => 'Tôi có cần tạo tài khoản để đặt phòng không?',   'answer' => 'Có. Bạn cần đăng ký tài khoản để quản lý thông tin đặt phòng và nhận hỗ trợ khi cần thiết.'],
        ['question' => 'Khi nào đặt phòng của tôi được xác nhận?',       'answer' => 'Đặt phòng sẽ được xác nhận sau khi bạn hoàn tất thanh toán hoặc hệ thống xử lý thành công.'],
    ],
    'Thanh toán' => [
        ['question' => 'Tôi có thể thanh toán bằng những phương thức nào?', 'answer' => 'Bạn có thể thanh toán qua các phương thức được hệ thống hỗ trợ (ví dụ: chuyển khoản, ví điện tử...).'],
        ['question' => 'Thanh toán có an toàn không?',                       'answer' => 'Chúng tôi áp dụng các biện pháp bảo mật để đảm bảo thông tin thanh toán của bạn được an toàn.'],
    ],
    'Hủy và thay đổi đặt phòng' => [
        ['question' => 'Tôi có thể hủy đặt phòng không?',      'answer' => 'Bạn có thể hủy đặt phòng theo chính sách của từng homestay. Vui lòng kiểm tra kỹ trước khi đặt.'],
        ['question' => 'Tôi có được hoàn tiền khi hủy không?', 'answer' => 'Có. Hệ thống cam kết hoàn tiền 100% số tiền bạn đã thanh toán nếu yêu cầu hủy đặt phòng được thực hiện trước thời gian nhận phòng.'],
        ['question' => 'Tôi có thể thay đổi ngày ở không?',    'answer' => 'Bạn có thể yêu cầu thay đổi thông qua hệ thống, tuy nhiên có thể phát sinh chi phí tùy theo chính sách của homestay.'],
    ],
    'Trong thời gian lưu trú' => [
        ['question' => 'Tôi cần làm gì khi đến nhận phòng?',             'answer' => 'Bạn liên hệ theo thông tin đã được cung cấp trong email xác nhận để nhận phòng đúng giờ quy định.'],
        ['question' => 'Nếu có vấn đề trong quá trình lưu trú thì sao?', 'answer' => 'Bạn nên liên hệ trực tiếp với bộ phận hỗ trợ của NestAway để được giải quyết nhanh chóng.'],
    ],
    'Tài khoản & hỗ trợ' => [
        ['question' => 'Làm sao để liên hệ hỗ trợ?',          'answer' => 'Bạn có thể liên hệ qua:<br><ul><li>Hotline: 0967 798 825</li><li>Email: <a href="mailto:support@nestaway.vn">support@nestaway.vn</a></li></ul>'],
    ],
];

$search  = request('q');
$topic   = request('topic');
$topics  = array_keys($allFaqs);
$faqs    = $topic && isset($allFaqs[$topic]) ? [$topic => $allFaqs[$topic]] : $allFaqs;

if ($search) {
    $kw   = mb_strtolower($search);
    $faqs = array_filter(array_map(
        fn($items) => array_values(array_filter($items, fn($f) =>
            str_contains(mb_strtolower($f['question']), $kw) ||
            str_contains(mb_strtolower(strip_tags($f['answer'])), $kw)
        )),
        $faqs
    ));
}
@endphp

<div class="faq-page-wrapper">
    <!-- Hero Section -->
    <section class="faq-hero py-5 text-center bg-light">
        <div class="container">
            <h1 class="display-5 fw-bold mb-4">Câu Hỏi Thường Gặp (FAQ)</h1>
            <div class="faq-search-box mx-auto" style="max-width: 600px;">
                <form action="{{ route('pages.faq') }}" method="GET" class="d-flex shadow-sm rounded-pill overflow-hidden bg-white p-1">
                    @if($topic)
                        <input type="hidden" name="topic" value="{{ $topic }}">
                    @endif
                    <input type="text" name="q" value="{{ $search }}" class="form-control border-0 px-4" placeholder="Bạn đang tìm câu hỏi gì?" autocomplete="off">
                    <button type="submit" class="btn btn-success rounded-pill px-4" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container-setting faq-container py-5">
        <!-- Topics -->
        <div class="row g-3 mb-5 justify-content-center">
            <div class="col-auto">
                <a href="{{ route('pages.faq', ['q' => $search]) }}" class="btn {{ !$topic ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-4">
                    Tất cả chủ đề
                </a>
            </div>
            @foreach($topics as $t)
                <div class="col-auto">
                    <a href="{{ route('pages.faq', ['topic' => $t, 'q' => $search]) }}" class="btn {{ $topic == $t ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-4">
                        {{ $t }}
                    </a>
                </div>
            @endforeach
        </div>

        @php $catIndex = 0; @endphp

        @forelse($faqs as $category => $items)
            @if(!empty($items))
                <div class="faq-category">
                    @if(!$topic)
                        <div class="faq-category-title">{{ $category }}</div>
                    @endif
                    <div class="accordion faq-accordion" id="accordion{{ $catIndex }}">
                        @foreach($items as $i => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h{{ $catIndex }}_{{ $i }}">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#c{{ $catIndex }}_{{ $i }}"
                                        aria-expanded="false"
                                        aria-controls="c{{ $catIndex }}_{{ $i }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="c{{ $catIndex }}_{{ $i }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="h{{ $catIndex }}_{{ $i }}"
                                    data-bs-parent="#accordion{{ $catIndex }}">
                                    <div class="accordion-body">
                                        {!! $faq['answer'] !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @php $catIndex++; @endphp
            @endif
        @empty
            <div class="no-results">
                <h3>Không tìm thấy kết quả</h3>
                <p>Rất tiếc, chúng tôi không tìm thấy câu trả lời nào phù hợp.</p>
                <a href="{{ route('pages.faq') }}" class="btn btn-dark mt-3">Xem tất cả câu hỏi</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
