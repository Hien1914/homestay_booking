@extends('clients.layout.app')

@section('title', 'Điểm đến')

@section('content')
<style>
  @import url('{{ asset('css/clients/destinations.css') }}');
</style>

<section class="destinations-page section-py">
  <div class="container-setting">
    <div class="destinations-header">
      <h1 class="destinations-title">Khám phá điểm đến {{ $categoryLabels[$category] }}</h1>
      <p class="destinations-subtitle">Danh sách gợi ý theo đúng nhóm điểm đến bạn đã chọn.</p>
    </div>

    <div class="destinations-filters">
      @foreach($categoryLabels as $key => $label)
        <a
          href="{{ route('destinations.show', ['category' => $key]) }}"
          class="destinations-filter {{ $key === $category ? 'is-active' : '' }}"
        >
          {{ $label }}
        </a>
      @endforeach
    </div>

    <div class="destinations-grid">
      @foreach($destinations as $item)
        <article class="destination-card">
          <div class="destination-card-tag">{{ $item['tag'] }}</div>
          <h2 class="destination-card-title">{{ $item['name'] }}</h2>
          <p class="destination-card-desc">{{ $item['description'] }}</p>
          <a href="#" class="destination-card-link">Xem homestay phù hợp <span aria-hidden="true">→</span></a>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endsection

