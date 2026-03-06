// ========== DARK MODE ==========
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");
const html = document.documentElement;

const savedTheme = localStorage.getItem("theme");
const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

if (savedTheme === "dark" || (!savedTheme && systemDark)) {
    html.setAttribute("data-theme", "dark");
    themeIcon.className = "fas fa-sun";
}

themeToggle.addEventListener("click", () => {
    const isDark = html.getAttribute("data-theme") === "dark";
    html.setAttribute("data-theme", isDark ? "light" : "dark");
    themeIcon.className = isDark ? "fas fa-moon" : "fas fa-sun";
    localStorage.setItem("theme", isDark ? "light" : "dark");
});

// ========== LOADING STATE ==========
const form = document.getElementById("loginForm");
const submitBtn = document.getElementById("submitBtn");

form?.addEventListener("submit", (e) => {
    submitBtn.classList.add("btn-loading");
    submitBtn.disabled = true;
});
