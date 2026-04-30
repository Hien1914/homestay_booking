/**
 * Universal Carousel/Slider Component
 * Supports infinite loop, auto-play, drag/touch, and responsive items per view
 */
class Carousel {
  constructor(config = {}) {
    this.container = config.container;
    this.trackSelector = config.trackSelector || '[data-carousel-track]';
    this.slideSelector = config.slideSelector || '.carousel-slide';
    this.dotsSelector = config.dotsSelector || '[data-carousel-dots]';
    this.prevBtnSelector = config.prevBtnSelector || '[data-carousel-prev]';
    this.nextBtnSelector = config.nextBtnSelector || '[data-carousel-next]';
    this.viewportSelector = config.viewportSelector || '[data-carousel-viewport]';
    this.autoPlaySpeed = config.autoPlaySpeed || 5000;
    this.enableDrag = config.enableDrag !== undefined ? config.enableDrag : true;
    this.showNavButtons = config.showNavButtons !== undefined ? config.showNavButtons : true;
    this.responsive = config.responsive || {
      mobile: { width: 768, itemsPerView: 2 },
      tablet: { width: 1024, itemsPerView: 3 },
      desktop: { width: Infinity, itemsPerView: 4 }
    };
    this.gap = config.gap || 20;

    if (!this.container) return;

    this.track = this.container.querySelector(this.trackSelector);
    this.viewport = this.container.querySelector(this.viewportSelector);
    this.slides = Array.from(this.container.querySelectorAll(this.slideSelector));
    this.prevBtn = this.container.querySelector(this.prevBtnSelector);
    this.nextBtn = this.container.querySelector(this.nextBtnSelector);
    this.dotsContainer = this.container.querySelector(this.dotsSelector);

    if (!this.track || !this.slides.length || !this.viewport) return;

    this.currentIndex = 0;
    this.itemsPerView = this.getItemsPerView();
    this.autoTimer = null;
    this.isDragging = false;
    this.dragStartX = 0;
    this.dragDeltaX = 0;

    this.init();
  }

  getItemsPerView() {
    const sortedBreakpoints = Object.values(this.responsive).sort((a, b) => a.width - b.width);
    for (const bp of sortedBreakpoints) {
      if (window.innerWidth < bp.width) return bp.itemsPerView;
    }
    return sortedBreakpoints[sortedBreakpoints.length - 1].itemsPerView;
  }

  buildDots() {
    if (!this.dotsContainer) return;

    const totalPages = this.getTotalPages();
    this.dotsContainer.innerHTML = '';

    for (let i = 0; i < totalPages; i++) {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = `carousel-dot${i === this.currentIndex ? ' is-active' : ''}`;
      dot.setAttribute('aria-label', `Tới slide ${i + 1}`);
      dot.addEventListener('click', () => this.goToSlide(i));
      this.dotsContainer.appendChild(dot);
    }
  }

  updateDots() {
    if (!this.dotsContainer) return;
    this.dotsContainer.querySelectorAll('.carousel-dot').forEach((dot, i) => {
      dot.classList.toggle('is-active', i === this.currentIndex);
    });
  }

  getTotalPages() {
    return Math.max(1, this.slides.length - this.itemsPerView + 1);
  }

  getMaxIndex() {
    return Math.max(0, this.slides.length - this.itemsPerView);
  }

  updateCarousel(animate = true) {
    this.itemsPerView = this.getItemsPerView();
    const maxIndex = this.getMaxIndex();
    this.currentIndex = Math.max(0, Math.min(this.currentIndex, maxIndex));

    const slideWidth = this.slides[0]?.offsetWidth || 0;
    const offset = this.currentIndex * (slideWidth + this.gap);

    this.track.style.transition = animate ? 'transform 0.45s ease' : 'none';
    this.track.style.transform = `translateX(-${offset}px)`;

    this.updateDots();
  }

  next() {
    const maxIndex = this.getMaxIndex();
    this.currentIndex = this.currentIndex >= maxIndex ? 0 : this.currentIndex + 1;
    this.updateCarousel(true);
  }

  prev() {
    const maxIndex = this.getMaxIndex();
    this.currentIndex = this.currentIndex <= 0 ? maxIndex : this.currentIndex - 1;
    this.updateCarousel(true);
  }

  goToSlide(index) {
    this.currentIndex = index;
    this.updateCarousel(true);
    this.startAutoPlay();
  }

  startAutoPlay() {
    this.stopAutoPlay();
    if (this.autoPlaySpeed > 0) {
      this.autoTimer = setInterval(() => this.next(), this.autoPlaySpeed);
    }
  }

  stopAutoPlay() {
    if (this.autoTimer) {
      clearInterval(this.autoTimer);
      this.autoTimer = null;
    }
  }

  onDragStart(clientX) {
    if (!this.enableDrag) return;
    this.isDragging = true;
    this.dragStartX = clientX;
    this.dragDeltaX = 0;
    this.stopAutoPlay();
    this.track.style.transition = 'none';
  }

  onDragMove(clientX) {
    if (!this.isDragging || !this.enableDrag) return;
    this.dragDeltaX = clientX - this.dragStartX;
    const slideWidth = this.slides[0]?.offsetWidth || 0;
    const offset = this.currentIndex * (slideWidth + this.gap);
    this.track.style.transform = `translateX(-${offset - this.dragDeltaX}px)`;
  }

  onDragEnd() {
    if (!this.isDragging || !this.enableDrag) return;
    this.isDragging = false;

    if (this.dragDeltaX < -50) this.next();
    else if (this.dragDeltaX > 50) this.prev();
    else this.updateCarousel(true);

    this.startAutoPlay();
  }

  init() {
    this.buildDots();
    this.updateCarousel(false);
    this.startAutoPlay();

    // Buttons
    if (this.showNavButtons) {
      this.prevBtn?.addEventListener('click', () => {
        this.prev();
        this.startAutoPlay();
      });
      this.nextBtn?.addEventListener('click', () => {
        this.next();
        this.startAutoPlay();
      });
    }

    // Mouse
    this.viewport?.addEventListener('mouseenter', () => this.stopAutoPlay());
    this.viewport?.addEventListener('mouseleave', () => this.startAutoPlay());
    this.viewport?.addEventListener('mousedown', (e) => this.onDragStart(e.clientX));
    window.addEventListener('mousemove', (e) => this.onDragMove(e.clientX));
    window.addEventListener('mouseup', () => this.onDragEnd());

    // Touch
    this.viewport?.addEventListener('touchstart', (e) => {
      this.onDragStart(e.touches[0].clientX);
    }, { passive: true });
    this.viewport?.addEventListener('touchmove', (e) => {
      this.onDragMove(e.touches[0].clientX);
    }, { passive: true });
    this.viewport?.addEventListener('touchend', () => this.onDragEnd());

    // Resize
    window.addEventListener('resize', () => this.updateCarousel(false));
  }
}

// Export for use in HTML/blade
window.Carousel = Carousel;
