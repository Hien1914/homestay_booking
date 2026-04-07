@extends('clients.layout.app')

@section('title', 'Điểm đến - ' . $destination->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/destinations.css') }}">

<section class="destinations-page section-py">
  <div class="container-setting">
    <div class="destinations-header">
      <h1 class="destinations-title">Khám phá: {{ $destination->name }}</h1>
      <p class="destinations-subtitle">{{ $destination->description ?? 'Danh sách homestay tại ' . $destination->name }}</p>
    </div>

    {{-- Filter tabs --}}
    <div class="destinations-filters">
      @foreach($allDestinations as $dest)
        <a href="{{ route('destinations.show', $dest->slug) }}"
           class="destinations-filter {{ $dest->id === $destination->id ? 'is-active' : '' }}">
          {{ $dest->name }}
        </a>
      @endforeach
    </div>

    {{-- Homestay list --}}
    <div class="destinations-grid">
      @forelse($homestays as $homestay)
        @php
          $img = $homestay->images->first();
          $imgUrl = $img?->image_url
            ? asset('storage/' . ltrim($img->image_url, '/'))
            : 'https://placehold.co/600x400';
        @endphp
        <article class="destination-card">
          <img src="{{ $imgUrl }}" alt="{{ $homestay->title }}" class="destination-card-img">
          <div class="destination-card-body">
            <div class="destination-card-tag">{{ $homestay->type }}</div>
            <h2 class="destination-card-title">{{ $homestay->title }}</h2>
            <p class="destination-card-desc">{{ Str::limit($homestay->description, 100) }}</p>
            <div class="destination-card-meta">
              <span class="destination-card-price">{{ number_format($homestay->price_per_night, 0, ',', '.') }}đ/đêm</span>
              @if($homestay->reviews_avg_rating)
                <span class="destination-card-rating">
                  <i class="fa-solid fa-star"></i> {{ number_format($homestay->reviews_avg_rating, 1) }}
                  ({{ $homestay->reviews_count }})
                </span>
              @endif
            </div>
            <a href="{{ route('homestay.show', $homestay->id) }}" class="destination-card-link">Xem chi tiết →</a>
          </div>
        </article>
      @empty
        <p style="color:#6b7280; grid-column:1/-1;">Chưa có homestay nào tại {{ $destination->name }}.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
