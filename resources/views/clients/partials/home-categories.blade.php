<!-- CATEGORIES - Nền trắng -->
<section class="section-py bg-light-blue reveal">
  <div class="container-setting py-3">
    <div class="text-center mb-4">
      <h4 class="text-primary text-uppercase fw-bold mb-2">Danh mục</h4>
      <h2 class="display-5 fw-light" style="font-family: 'Google Sans', sans-serif;">
        Phong cách <span class="text-soft-blue">lưu trú</span><br/>dành cho bạn
      </h2>
    </div>

    @php
      $categoriesRow1 = [
        ['img' => 'ven-bien.jpg', 'alt' => 'Biển', 'count' => '214 nơi', 'name' => '🌊 Ven biển', 'col' => 'col-12 col-md-6'],
        ['img' => 'nui.jpg', 'alt' => 'Núi', 'count' => '183 nơi', 'name' => '🏔️ Miền núi', 'col' => 'col-12 col-md-6'],
      ];
      $categoriesRow2 = [
        ['img' => 'thanh-pho.jpg', 'alt' => 'Thành thị', 'count' => '341 nơi', 'name' => '🏙️ Thành thị', 'col' => 'col-12 col-md-4'],
        ['img' => 'song-ho.jpg', 'alt' => 'Hồ & Sông', 'count' => '75 nơi', 'name' => '🏞️ Hồ & Sông', 'col' => 'col-12 col-md-4'],
        ['img' => 'sang-trong.jpg', 'alt' => 'Sang trọng', 'count' => '56 nơi', 'name' => '✨ Sang trọng', 'col' => 'col-12 col-md-4'],
      ];
    @endphp

    <div class="row g-4">
      @foreach($categoriesRow1 as $cat)
        <div class="{{ $cat['col'] }}">
          <div class="cat-card">
            <img src="{{ asset('img/' . $cat['img']) }}" alt="{{ $cat['alt'] }}" class="cat-img">
            <div class="cat-overlay"></div>
            <div class="cat-count">{{ $cat['count'] }}</div>
            <div class="cat-name">{{ $cat['name'] }}</div>
          </div>
        </div>
      @endforeach

      @foreach($categoriesRow2 as $cat)
        <div class="{{ $cat['col'] }}">
          <div class="cat-card">
            <img src="{{ asset('img/' . $cat['img']) }}" alt="{{ $cat['alt'] }}" class="cat-img">
            <div class="cat-overlay"></div>
            <div class="cat-count">{{ $cat['count'] }}</div>
            <div class="cat-name">{{ $cat['name'] }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

