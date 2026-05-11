document.addEventListener('DOMContentLoaded', function() {
        new Carousel({
          container: document.getElementById('featured-slider'),
          autoPlaySpeed: 5000,
          responsive: {
            mobile: { width: 768, itemsPerView: 2 },
            tablet: { width: 1024, itemsPerView: 3 },
            desktop: { width: Infinity, itemsPerView: 4 }
          },
          gap: 20,
          trackSelector: '[data-carousel-track]',
          slideSelector: '.carousel-slide',
          dotsSelector: '[data-carousel-dots]',
          prevBtnSelector: '[data-carousel-prev]',
          nextBtnSelector: '[data-carousel-next]',
          viewportSelector: '[data-carousel-viewport]',
          enableDrag: false
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        document.querySelectorAll('[data-favourite-toggle]').forEach((btn) => {
          btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const endpoint = btn.getAttribute('data-endpoint');
            if (!endpoint) {
              const loginUrl = btn.getAttribute('data-login-url');
              if (loginUrl) window.location.href = loginUrl;
              return;
            }

            if (btn.dataset.loading === '1') return;
            btn.dataset.loading = '1';

            try {
              const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': csrfToken || '',
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json'
                }
              });

              if (!res.ok) return;
              const data = await res.json();
              const active = !!data.active;
              btn.classList.toggle('is-active', active);
              btn.setAttribute('aria-label', active ? 'Bỏ yêu thích' : 'Thêm vào yêu thích');
            } finally {
              delete btn.dataset.loading;
            }
          });
        });
      });
