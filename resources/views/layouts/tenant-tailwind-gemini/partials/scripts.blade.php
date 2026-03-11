<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/min/moment.min.js"></script>
<script src="{{ asset('adminBoard/plugins/components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
	window.app = window.app || {
		color: {
			theme: '#2563eb'
		}
	};

	window.geminiUi = window.geminiUi || {
		applyPanelAwareLinks() {
			const currentUrl = new URL(window.location.href);
			const panel = currentUrl.searchParams.get('panel');

			if (!panel) {
				return;
			}

			document.querySelectorAll('a[href]').forEach(anchor => {
				const href = anchor.getAttribute('href');

				if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
					return;
				}

				try {
					const url = new URL(href, window.location.origin);

					if (url.origin !== window.location.origin || url.searchParams.has('panel')) {
						return;
					}

					url.searchParams.set('panel', panel);
					anchor.setAttribute('href', url.pathname + url.search + url.hash);
				} catch (e) {
				}
			});
		},
		initBootstrapUi() {
			if (!window.bootstrap) {
				return;
			}

			document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => {
				window.bootstrap.Tooltip.getOrCreateInstance(element);
			});

			document.querySelectorAll('[data-bs-toggle="popover"]').forEach(element => {
				window.bootstrap.Popover.getOrCreateInstance(element);
			});
		},
		boot() {
			this.applyPanelAwareLinks();
			this.initBootstrapUi();
			if (typeof window.renderSelect2 === 'function') {
				window.renderSelect2();
			}
			if (typeof window.renderDateRangePicker === 'function') {
				window.renderDateRangePicker();
			}
		}
	};

	document.addEventListener('livewire:navigated', () => {
		requestAnimationFrame(() => window.geminiUi.boot());
	});

	document.addEventListener('livewire:initialized', () => {
		window.geminiUi.boot();

		if (window.__geminiMorphHookRegistered || !window.Livewire || typeof window.Livewire.hook !== 'function') {
			return;
		}

		window.__geminiMorphHookRegistered = true;
		window.Livewire.hook('morph.updated', () => {
			window.geminiUi.boot();
		});
	});
</script>
