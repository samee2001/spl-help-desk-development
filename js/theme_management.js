// --- Part 1: Immediate execution to prevent theme flicker ---
(function () {
  const THEME_KEY = "bsTheme";
  const savedTheme =
    localStorage.getItem(THEME_KEY) ||
    (window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light");
  document.documentElement.setAttribute("data-bs-theme", savedTheme);
})();

// --- Part 2: Update button icon and handle clicks after DOM is loaded ---
document.addEventListener("DOMContentLoaded", () => {
  const rootElement = document.documentElement;
  const themeToggleButton = document.getElementById("themeToggle");
  const THEME_KEY = "bsTheme";

  function updateToggleButton(theme) {
    if (themeToggleButton) {
      themeToggleButton.innerHTML =
        theme === "dark"
          ? '<i class="fas fa-sun"></i>'
          : '<i class="fas fa-moon"></i>';
    }
  }

  // Set initial icon state
  updateToggleButton(rootElement.getAttribute("data-bs-theme"));

  // Add click listener
  if (themeToggleButton) {
    themeToggleButton.addEventListener("click", () => {
      const newTheme =
        rootElement.getAttribute("data-bs-theme") === "light"
          ? "dark"
          : "light";
      rootElement.setAttribute("data-bs-theme", newTheme);
      localStorage.setItem(THEME_KEY, newTheme);
      updateToggleButton(newTheme);
    });
  }
});
