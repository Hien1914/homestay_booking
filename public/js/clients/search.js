(function () {
  var form = document.getElementById('room-filter-form');
  var cards = document.querySelectorAll('[data-room-card]');
  var countEl = document.getElementById('rooms-result-count');
  var emptyEl = document.getElementById('rooms-empty');
  var paginationEl = document.getElementById('rooms-pagination');
  var prevBtn = document.getElementById('rooms-page-prev');
  var nextBtn = document.getElementById('rooms-page-next');
  var pageInfo = document.getElementById('rooms-page-info');
  var resultsGrid = document.getElementById('rooms-results');
  var panel = document.getElementById('rooms-filters-panel');
  var backdrop = document.getElementById('rooms-filters-backdrop');
  var openBtn = document.getElementById('rooms-filter-open');
  var closeBtn = document.getElementById('rooms-filters-close');

  var currentPage = 1;
  var filteredCards = [];
  var currentPerPage = getPerPage();

  function getGridColumns() {
    if (!resultsGrid) return 1;
    var cols = window.getComputedStyle(resultsGrid).gridTemplateColumns;
    if (!cols || cols === 'none') return 1;
    return cols.split(' ').filter(Boolean).length || 1;
  }

  function getPerPage() {
    return 15;
  }

  function parseAmenities(str) {
    if (!str) return [];
    return String(str).split(',').map(function (s) { return s.trim(); }).filter(Boolean);
  }

  function getCheckedValues(name) {
    return Array.prototype.slice.call(form.querySelectorAll('input[name="' + name + '"]:checked')).map(function (el) { return el.value; });
  }

  function applyFilters() {
    var fd = new FormData(form);
    var priceMin = parseInt(fd.get('price_min') || '0', 10);
    if (isNaN(priceMin) || priceMin < 0) priceMin = 0;
    var priceMaxRaw = fd.get('price_max');
    var priceMax = priceMaxRaw === '' || priceMaxRaw === null ? Infinity : parseInt(priceMaxRaw, 10);
    if (isNaN(priceMax)) priceMax = Infinity;

    var ratingMin = parseFloat(fd.get('rating_min') || '0') || 0;
    var destinationId = fd.get('destination_id') || '';
    var requiredAmenities = getCheckedValues('amenities[]');

    filteredCards = [];
    cards.forEach(function (card) {
      var price = parseInt(card.getAttribute('data-price') || '0', 10);
      var rating = parseFloat(card.getAttribute('data-rating') || '0');
      var roomDestinationId = card.getAttribute('data-destination-id') || '';
      var roomAmenities = parseAmenities(card.getAttribute('data-amenities'));

      var ok = true;
      if (price < priceMin || price > priceMax) ok = false;
      if (ok && rating < ratingMin - 1e-6) ok = false;
      if (ok && destinationId && roomDestinationId !== destinationId) ok = false;
      if (ok && requiredAmenities.length) {
        for (var i = 0; i < requiredAmenities.length; i++) {
          if (roomAmenities.indexOf(requiredAmenities[i]) === -1) {
            ok = false;
            break;
          }
        }
      }

      card.classList.toggle('is-hidden', !ok);
      card.classList.remove('is-page-hidden');
      if (ok) filteredCards.push(card);
    });

    currentPage = 1;
    renderPage();
  }

  function renderPage() {
    currentPerPage = getPerPage();
    var total = filteredCards.length;
    var totalPages = Math.max(1, Math.ceil(total / currentPerPage));
    if (currentPage > totalPages) currentPage = totalPages;

    var start = (currentPage - 1) * currentPerPage;
    var end = start + currentPerPage;
    filteredCards.forEach(function (card, idx) {
      card.classList.toggle('is-page-hidden', idx < start || idx >= end);
    });

    if (countEl) countEl.textContent = String(total);
    if (emptyEl) emptyEl.classList.toggle('is-visible', total === 0);
    if (paginationEl) paginationEl.hidden = total <= currentPerPage;
    if (pageInfo) pageInfo.textContent = 'Trang ' + currentPage + '/' + totalPages;
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
  }

  function closePanel() {
    if (!panel) return;
    panel.classList.remove('is-open');
    if (backdrop) {
      backdrop.classList.remove('is-visible');
      backdrop.setAttribute('hidden', '');
      backdrop.setAttribute('aria-hidden', 'true');
    }
    if (openBtn) {
      openBtn.setAttribute('aria-expanded', 'false');
    }
  }

  function openPanel() {
    if (!panel) return;
    panel.classList.add('is-open');
    if (backdrop) {
      backdrop.removeAttribute('hidden');
      backdrop.classList.add('is-visible');
      backdrop.setAttribute('aria-hidden', 'false');
    }
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      applyFilters();
      closePanel();
    });
    form.addEventListener('change', function () {
      applyFilters();
    });
  }

  var resetBtn = document.getElementById('room-filter-reset');
  if (resetBtn && form) {
    resetBtn.addEventListener('click', function () {
      form.reset();
      var anyRating = form.querySelector('input[name="rating_min"][value="0"]');
      if (anyRating) anyRating.checked = true;
      applyFilters();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      if (currentPage <= 1) return;
      currentPage -= 1;
      renderPage();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      var totalPages = Math.max(1, Math.ceil(filteredCards.length / currentPerPage));
      if (currentPage >= totalPages) return;
      currentPage += 1;
      renderPage();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  window.addEventListener('resize', function () {
    var nextPerPage = getPerPage();
    if (nextPerPage !== currentPerPage) {
      currentPerPage = nextPerPage;
      currentPage = 1;
      renderPage();
    }
  });

  if (openBtn) openBtn.addEventListener('click', openPanel);
  if (closeBtn) closeBtn.addEventListener('click', closePanel);
  if (backdrop) backdrop.addEventListener('click', closePanel);

  var csrfToken = document.querySelector('meta[name="csrf-token"]');
  var csrfValue = csrfToken ? csrfToken.getAttribute('content') : '';

  document.querySelectorAll('[data-favourite-toggle]').forEach(function (btn) {
    btn.addEventListener('click', async function (e) {
      e.preventDefault();
      e.stopPropagation();

      var endpoint = btn.getAttribute('data-endpoint');
      if (!endpoint) {
        var loginUrl = btn.getAttribute('data-login-url');
        if (loginUrl) window.location.href = loginUrl;
        return;
      }

      if (btn.dataset.loading === '1') return;
      btn.dataset.loading = '1';

      try {
        var res = await fetch(endpoint, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfValue || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });

        if (!res.ok) return;
        var data = await res.json();
        var active = !!data.active;
        btn.classList.toggle('is-active', active);
        btn.setAttribute('aria-label', active ? 'Bỏ yêu thích' : 'Thêm vào yêu thích');
      } finally {
        delete btn.dataset.loading;
      }
    });
  });

  applyFilters();
})();
