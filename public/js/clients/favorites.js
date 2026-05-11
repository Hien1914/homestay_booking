(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    var csrfValue = csrfToken ? csrfToken.getAttribute('content') : '';
    var totalEl = document.getElementById('favourite-total');

    document.querySelectorAll('[data-favourite-toggle]').forEach(function (btn) {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            var endpoint = btn.getAttribute('data-endpoint');
            if (!endpoint || btn.dataset.loading === '1') return;
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
                if (data.active === false) {
                    var card = btn.closest('[data-favourite-card]');
                    if (card) card.remove();
                    if (totalEl) {
                        var next = Math.max(0, (parseInt(totalEl.textContent || '0', 10) || 0) - 1);
                        totalEl.textContent = String(next);
                    }
                    if (!document.querySelector('[data-favourite-card]')) {
                        window.location.reload();
                    }
                }
            } finally {
                delete btn.dataset.loading;
            }
        });
    });
})();
