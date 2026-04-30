@extends('clients.layout.app')

@section('title', $destination ? 'Điểm đến - ' . $destination->name : 'Điểm đến - Tất cả')

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/destinations.css') }}">

<section class="destinations-page">
    <div class="container-setting">
      <div class="destinations-header">
        <h1 class="destinations-title">
          @if($destination)
            Khám phá: {{ $destination->name }}
          @else
            Khám phá Tất cả Điểm Đến
          @endif
        </h1>
        <p class="destinations-subtitle">
          @if($destination)
            {{ $destination->description ?? 'Danh sách homestay tại ' . $destination->name }}
          @else
            Chọn một điểm đến hoặc xem tất cả homestay
          @endif
        </p>
      </div>
  
      {{-- Filter tabs --}}
      <div class="destinations-filters">
        <a href="{{ route('destinations.show') }}"
            class="destinations-filter {{ !$destination ? 'is-active' : '' }}">
          Tất cả
        </a>
        @foreach($allDestinations as $dest)
          <a href="{{ route('destinations.show', $dest->slug) }}"
              class="destinations-filter {{ $dest->id === $destination?->id ? 'is-active' : '' }}">
            {{ $dest->name }}
          </a>
        @endforeach
      </div>
  
      {{-- Homestay list --}}
      <div class="destinations-grid">
        @forelse($homestays as $homestay)
          @php
            $img = $homestay->images->where('is_primary', true)->first() ?? $homestay->images->first();
            $imgUrl = $img?->image_url
              ? asset('storage/' . $img->image_url)
              : 'https://placehold.co/600x400';
          @endphp
          <article class="destination-card">
            <img src="{{ $imgUrl }}" alt="{{ $homestay->title }}" class="destination-card-img">
            <div class="destination-card-body">
              <div class="destination-card-tag">{{ $homestay->province }}</div>
              <h2 class="destination-card-title">{{ $homestay->title }}</h2>
              <p class="destination-card-desc">{{ Str::limit($homestay->description, 100) }}</p>
              <div class="destination-card-meta">
                <span class="destination-card-price">
                  {{ number_format($homestay->discounted_price, 0, ',', '.') }}đ/đêm
                  @if($homestay->discounted_price < $homestay->price_per_night)
                    <small style="font-size:.78rem;color:#9ca3af;text-decoration:line-through;margin-left:6px;">
                      {{ number_format($homestay->price_per_night, 0, ',', '.') }}đ
                    </small>
                  @endif
                </span>
                  <span class="destination-card-rating">
                    <i class="fa-solid fa-star"></i> {{ number_format($homestay->reviews_avg_rating ?? 0, 1) }}
                    ({{ $homestay->reviews_count ?? 0 }})
                  </span>
              </div>
              <a href="{{ route('homestay.show', $homestay->slug) }}" class="destination-card-link">Xem chi tiết →</a>
            </div>
          </article>
        @empty
          <p style="color:#6b7280; grid-column:1/-1;">
            @if($destination)
              Chưa có homestay nào tại {{ $destination->name }}.
            @else
              Chưa có homestay nào.
            @endif
          </p>
        @endforelse
      </div>
    </div>
</section>
@endsection
