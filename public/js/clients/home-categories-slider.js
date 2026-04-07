(function () {
  var root = document.querySelector('[data-categories-slider]');
  if (!root) return;

  var viewport = root.querySelector('[data-cat-viewport]');
  var track = root.querySelector('[data-cat-track]');
  var prevBtn = document.querySelector('[data-cat-prev]');
  var nextBtn = document.querySelector('[data-cat-next]');
  var originalSlides = Array.prototype.slice.call(track.children);
  if (!viewport || !track || !originalSlides.length) return;

  function perView() {
    if (window.innerWidth < 576) return 1;
    if (window.innerWidth < 768) return 2;
    if (window.innerWidth < 992) return 3;
    if (window.innerWidth < 1200) return 4;
    return 5;
  }

  var currentIndex = 0;
  var slideGap = 20;
  var autoTimer = null;

  function clearClones() {
    Array.prototype.slice.call(track.querySelectorAll('[data-clone="true"]')).forEach(function (node) {
      node.remove();
    });
  }

  function buildLoop() {
    clearClones();

    var visible = Math.min(perView(), originalSlides.length);
    var head = originalSlides.slice(0, visible).map(function (node) {
      var clone = node.cloneNode(true);
      clone.setAttribute('data-clone', 'true');
      return clone;
    });
    var tail = originalSlides.slice(-visible).map(function (node) {
      var clone = node.cloneNode(true);
      clone.setAttribute('data-clone', 'true');
      return clone;
    });

    tail.forEach(function (clone) {
      track.insertBefore(clone, track.firstChild);
    });
    head.forEach(function (clone) {
      track.appendChild(clone);
    });

    currentIndex = visible;
    update(false);
  }

  function getStep() {
    var firstSlide = track.querySelector('.featured-categories-slide');
    if (!firstSlide) return 0;
    return firstSlide.getBoundingClientRect().width + slideGap;
  }

  function update(animate) {
    var step = getStep();
    track.style.transition = animate === false ? 'none' : 'transform 0.45s ease';
    track.style.transform = 'translateX(-' + (currentIndex * step) + 'px)';
  }

  function normalizeLoop() {
    var visible = Math.min(perView(), originalSlides.length);
    var maxIndex = originalSlides.length + visible - 1;

    if (currentIndex < visible) {
      currentIndex = originalSlides.length + currentIndex;
      update(false);
    } else if (currentIndex > maxIndex) {
      currentIndex = currentIndex - originalSlides.length;
      update(false);
    }
  }

  function next() {
    currentIndex += 1;
    update(true);
  }

  function prev() {
    currentIndex -= 1;
    update(true);
  }

  function stopAuto() {
    if (!autoTimer) return;
    clearInterval(autoTimer);
    autoTimer = null;
  }

  function startAuto() {
    stopAuto();
    autoTimer = setInterval(next, 3500);
  }

  nextBtn && nextBtn.addEventListener('click', function () {
    next();
    startAuto();
  });

  prevBtn && prevBtn.addEventListener('click', function () {
    prev();
    startAuto();
  });

  viewport.addEventListener('mouseenter', stopAuto);
  viewport.addEventListener('mouseleave', startAuto);

  track.addEventListener('transitionend', normalizeLoop);

  window.addEventListener('resize', function () {
    buildLoop();
  });

  buildLoop();
  startAuto();
})();
