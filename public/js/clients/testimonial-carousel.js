(function () {
        const slider = document.getElementById('testimonial-slider');
        if (!slider) return;

        const viewport = slider.querySelector('[data-tsm-viewport]');
        const track = slider.querySelector('[data-tsm-track]');
        const slides = Array.from(slider.querySelectorAll('.tsm-slide'));
        const prevBtn = slider.querySelector('[data-tsm-prev]');
        const nextBtn = slider.querySelector('[data-tsm-next]');
        const dotsWrap = slider.querySelector('[data-tsm-dots]');
        if (!viewport || !track || slides.length === 0) return;

        const perView = () => (window.innerWidth < 768 ? 1 : 3);
        let index = 0;
        let autoTimer = null;
        let startX = 0;
        let dragDelta = 0;
        let dragging = false;

        const buildDots = () => {
          if (!dotsWrap) return;
          const pages = Math.max(1, slides.length - perView() + 1);
          dotsWrap.innerHTML = '';
          for (let i = 0; i < pages; i++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'tsm-dot' + (i === index ? ' is-active' : '');
            btn.setAttribute('aria-label', 'Tới đánh giá ' + (i + 1));
            btn.addEventListener('click', () => {
              index = i;
              update(true);
              startAuto();
            });
            dotsWrap.appendChild(btn);
          }
        };

        const updateDots = () => {
          if (!dotsWrap) return;
          dotsWrap.querySelectorAll('.tsm-dot').forEach((dot, i) => {
            dot.classList.toggle('is-active', i === index);
          });
        };

        const update = (animate = true) => {
          const max = Math.max(0, slides.length - perView());
          index = Math.max(0, Math.min(index, max));
          const step = slides[0].offsetWidth + 20;
          track.style.transition = animate ? 'transform .45s ease' : 'none';
          track.style.transform = `translateX(-${index * step}px)`;
          updateDots();
        };

        const next = () => {
          const max = Math.max(0, slides.length - perView());
          index = index >= max ? 0 : index + 1;
          update(true);
        };

        const prev = () => {
          const max = Math.max(0, slides.length - perView());
          index = index <= 0 ? max : index - 1;
          update(true);
        };

        const startAuto = () => {
          stopAuto();
          autoTimer = setInterval(next, 3600);
        };

        const stopAuto = () => {
          if (!autoTimer) return;
          clearInterval(autoTimer);
          autoTimer = null;
        };

        prevBtn?.addEventListener('click', () => { prev(); startAuto(); });
        nextBtn?.addEventListener('click', () => { next(); startAuto(); });

        viewport.addEventListener('mouseenter', stopAuto);
        viewport.addEventListener('mouseleave', startAuto);
        viewport.addEventListener('touchstart', (e) => {
          dragging = true;
          startX = e.touches[0].clientX;
          dragDelta = 0;
          stopAuto();
          track.style.transition = 'none';
        }, { passive: true });
        viewport.addEventListener('touchmove', (e) => {
          if (!dragging) return;
          dragDelta = e.touches[0].clientX - startX;
          const step = slides[0].offsetWidth + 20;
          track.style.transform = `translateX(-${index * step - dragDelta}px)`;
        }, { passive: true });
        viewport.addEventListener('touchend', () => {
          if (!dragging) return;
          dragging = false;
          if (dragDelta < -50) next();
          else if (dragDelta > 50) prev();
          else update(true);
          startAuto();
        });
        window.addEventListener('resize', () => {
          buildDots();
          update(false);
        });

        buildDots();
        update(false);
        startAuto();
      })();
