const THEME_KEY = "theme";

function toggleDarkTheme() {
  setTheme(
    document.documentElement.getAttribute("data-bs-theme") === "dark"
      ? "light"
      : "dark"
  );
}

/**
 * Set theme for mazer
 * @param {"dark"|"light"} theme
 * @param {boolean} persist 
 */
function setTheme(theme, persist = false) {
  document.body.classList.add(theme);
  document.documentElement.setAttribute("data-bs-theme", theme);

  if (persist) {
    localStorage.setItem(THEME_KEY, theme);
  }
}

/**
 * Init theme from setTheme()
 */
function initTheme() {
  // **Paksa tema awal menjadi "light"**
  setTheme("light", true);

  // Cek apakah ada preferensi tema yang disimpan sebelumnya
  const storedTheme = localStorage.getItem(THEME_KEY);
  if (storedTheme) {
    setTheme(storedTheme);
  }

  // Jika browser mendukung prefers-color-scheme, tambahkan event listener
  if (window.matchMedia) {
    const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
    mediaQuery.addEventListener("change", (e) =>
      setTheme(e.matches ? "dark" : "light", true)
    );
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const toggler = document.getElementById("toggle-dark");
  const theme = localStorage.getItem(THEME_KEY);

  if (toggler) {
    toggler.checked = theme === "dark";

    toggler.addEventListener("input", (e) => {
      setTheme(e.target.checked ? "dark" : "light", true);
    });
  }
});

initTheme();
