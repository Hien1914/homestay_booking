// Nav menu toggle (mobile drawer)
  (function () {
    var toggle = document.getElementById('nav-toggle');
    var menu = document.getElementById('nav-menu');
    var backdrop = document.getElementById('nav-backdrop');
    var closeBtn = document.getElementById('nav-close');
    
    if (!toggle || !menu) return;

    function setOpen(open) {
      menu.classList.toggle('is-open', open);
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (backdrop) {
        backdrop.hidden = !open;
        backdrop.setAttribute('aria-hidden', !open);
      }
    }

    toggle.addEventListener('click', function () {
      setOpen(!menu.classList.contains('is-open'));
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        setOpen(false);
      });
    }

    if (backdrop) {
      backdrop.addEventListener('click', function () {
        setOpen(false);
      });
    }

    // Handle submenu toggle on mobile
    var navItems = menu.querySelectorAll('.nav-item');
    navItems.forEach(function (item) {
      var link = item.querySelector('a');
      var submenu = item.querySelector('.nav-submenu');

      if (submenu && link) {
        link.addEventListener('click', function (e) {
          // On mobile, prevent default and toggle submenu
          if (window.matchMedia('(max-width: 992px)').matches) {
            e.preventDefault();
            item.classList.toggle('is-open');
          }
        });
      }
    });

    // Close menu on link click only if it doesn't have submenu
    menu.querySelectorAll('a').forEach(function (el) {
      var item = el.closest('.nav-item');
      var submenu = item ? item.querySelector('.nav-submenu') : null;

      if (!submenu) {
        el.addEventListener('click', function () {
          if (window.matchMedia('(max-width: 992px)').matches) {
            setOpen(false);
          }
        });
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setOpen(false);
    });
  })();

  // User dropdown toggle
  (function () {
    var userMenu = document.getElementById('nav-user-menu');
    var userBtn = document.getElementById('nav-user-btn');

    if (!userMenu || !userBtn) return;

    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      userMenu.classList.toggle('is-open');
    });

    document.addEventListener('click', function (e) {
      if (!userMenu.contains(e.target)) {
        userMenu.classList.remove('is-open');
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        userMenu.classList.remove('is-open');
      }
    });
  })();
