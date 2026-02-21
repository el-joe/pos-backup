import './bootstrap';

const langBtn = document.getElementById('lang-menu-btn');
const langDropdown = document.getElementById('lang-dropdown');
const langChevron = document.getElementById('lang-chevron');

if (langBtn && langDropdown && langChevron) {
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langDropdown.classList.toggle('hidden');
        langChevron.classList.toggle('rotate-180');
    });

    document.addEventListener('click', (event) => {
        if (!langBtn.contains(event.target)) {
            langDropdown.classList.add('hidden');
            langChevron.classList.remove('rotate-180');
        }
    });
}

const html = document.documentElement;
const toggleBtns = [document.getElementById('theme-toggle'), document.getElementById('mobile-theme-toggle')];
const menuBtn = document.getElementById('mobile-menu-btn');
const menu = document.getElementById('mobile-menu');

function updateIcons(isDark) {
    toggleBtns.forEach((btn) => {
        if (!btn) return;

        const icon = btn.querySelector('i');
        if (!icon) return;

        if (isDark) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    });
}

function applyTheme(theme) {
    if (theme === 'dark') {
        html.classList.add('dark');
        updateIcons(true);
    } else {
        html.classList.remove('dark');
        updateIcons(false);
    }

    localStorage.setItem('theme', theme);
}

const savedTheme = localStorage.getItem('theme')
    || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

applyTheme(savedTheme);

toggleBtns.forEach((btn) => {
    if (!btn) return;

    btn.addEventListener('click', () => {
        const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        applyTheme(newTheme);
    });
});

if (menuBtn && menu) {
    menuBtn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
}

const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.classList.add('glass-nav', 'shadow-sm', 'py-3');
            navbar.classList.remove('py-4', 'bg-white/80', 'dark:bg-slate-900/90');
        } else {
            navbar.classList.remove('glass-nav', 'shadow-sm', 'py-3');
            navbar.classList.add('py-4', 'bg-white/80', 'dark:bg-slate-900/90');
        }
    });
}

window.toggleFaq = (element) => {
    const allFaqs = document.querySelectorAll('.faq-item');
    allFaqs.forEach((item) => {
        if (item !== element) {
            item.classList.remove('active');
        }
    });

    element.classList.toggle('active');
};
