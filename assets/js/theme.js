(function () {
  const storageKey = 'a-amusement-theme';

  function systemDark() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function resolvedTheme(theme) {
    if (theme === 'system') {
      return systemDark() ? 'dark' : 'light';
    }
    return theme === 'dark' ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    document.documentElement.dataset.theme = theme;
    document.documentElement.style.colorScheme = resolvedTheme(theme);
  }

  function getStoredTheme() {
    const fromHtml = document.documentElement.dataset.theme;
    if (fromHtml && fromHtml !== '') {
      return fromHtml;
    }
    return localStorage.getItem(storageKey) || 'system';
  }

  function setTheme(theme, persist) {
    applyTheme(theme);
    if (persist !== false) {
      localStorage.setItem(storageKey, theme);
    }
  }

  function cycleTheme(current) {
    const order = ['light', 'dark', 'system'];
    const idx = order.indexOf(current);
    return order[(idx + 1) % order.length];
  }

  document.addEventListener('DOMContentLoaded', () => {
    const initial = getStoredTheme();
    applyTheme(initial);

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (getStoredTheme() === 'system') {
        applyTheme('system');
      }
    });

    document.querySelectorAll('.theme-toggle').forEach((btn) => {
      btn.addEventListener('click', () => {
        const next = cycleTheme(getStoredTheme());
        setTheme(next);
        if (document.body.dataset.loggedIn === '1') {
          fetch('/api/theme.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ theme: next }),
          }).catch(() => {});
        }
      });
    });

    document.querySelectorAll('input[name="theme"]').forEach((input) => {
      input.addEventListener('change', () => {
        if (input.checked) {
          setTheme(input.value);
        }
      });
    });
  });

  window.AAmusementTheme = { setTheme, getStoredTheme };
})();
