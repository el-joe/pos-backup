<script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class', // Enabled
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"{{ app()->getLocale() == "ar" ? "Cairo" : "Plus Jakarta Sans"  }}"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa', 100: '#ccfbf1', 400: '#2dd4bf',
                            500: '#00d2b4', 600: '#0d9488', 900: '#134e4a',
                            accent: '#fbbf24', dark: '#0f172a',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
<script>
    const langBtn = document.getElementById('lang-menu-btn');
    const langDropdown = document.getElementById('lang-dropdown');
    const langChevron = document.getElementById('lang-chevron');

    langBtn.addEventListener('click', (e) => {
        // Stops the click from immediately triggering the "click outside" listener
        e.stopPropagation();

        // Toggle visibility
        langDropdown.classList.toggle('hidden');

        // Rotate the arrow icon
        langChevron.classList.toggle('rotate-180');
    });

    // Close menu when clicking anywhere else on the page
    document.addEventListener('click', (event) => {
        if (!langBtn.contains(event.target)) {
            langDropdown.classList.add('hidden');
            langChevron.classList.remove('rotate-180');
        }
    });
</script>

    <script>
        // Theme Logic
        const html = document.documentElement;
        const toggleBtns = [document.getElementById('theme-toggle'), document.getElementById('mobile-theme-toggle')];
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        function updateIcons(isDark) {
            toggleBtns.forEach(btn => {
                if(!btn) return;
                const icon = btn.querySelector('i');
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

        const savedTheme = localStorage.getItem('theme') ||
                       (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(savedTheme);

        toggleBtns.forEach(btn => {
            if(btn) {
                btn.addEventListener('click', () => {
                    const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    applyTheme(newTheme);
                });
            }
        });

        if(menuBtn) menuBtn.addEventListener('click', () => { menu.classList.toggle('hidden'); });

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('glass-nav', 'shadow-sm', 'py-3');
                navbar.classList.remove('py-4', 'bg-white/80', 'dark:bg-slate-900/90');
            } else {
                navbar.classList.remove('glass-nav', 'shadow-sm', 'py-3');
                navbar.classList.add('py-4', 'bg-white/80', 'dark:bg-slate-900/90');
            }
        });
    </script>
