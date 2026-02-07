<script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class', // Enabled
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
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
