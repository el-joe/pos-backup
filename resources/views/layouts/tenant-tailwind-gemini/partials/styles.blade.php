<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="{{ asset('template/vendors/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('adminBoard/plugins/components/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@vite(['resources/css/gemini-tailwind.css', 'resources/js/gemini-tailwind.js'])
<style>
	.gemini-legacy-page {
		color: #1f2937;
	}

	.dark .gemini-legacy-page {
		color: #e5e7eb;
	}

	.gemini-legacy-page .card,
	.gemini-legacy-page .modal-content,
	.gemini-legacy-page .dropdown-menu,
	.gemini-legacy-page .accordion-item,
	.gemini-legacy-page .list-group,
	.gemini-legacy-page .timeline,
	.gemini-legacy-page .fc,
	.gemini-legacy-page .select2-dropdown {
		border: 1px solid rgb(226 232 240 / 1);
		border-radius: 1.5rem;
		background: rgba(255, 255, 255, 0.96);
		box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
	}

	.dark .gemini-legacy-page .card,
	.dark .gemini-legacy-page .modal-content,
	.dark .gemini-legacy-page .dropdown-menu,
	.dark .gemini-legacy-page .accordion-item,
	.dark .gemini-legacy-page .list-group,
	.dark .gemini-legacy-page .timeline,
	.dark .gemini-legacy-page .fc,
	.dark .gemini-legacy-page .select2-dropdown {
		border-color: rgb(51 65 85 / 1);
		background: rgba(15, 23, 42, 0.92);
		box-shadow: 0 14px 40px rgba(2, 6, 23, 0.38);
	}

	.gemini-legacy-page .card-header,
	.gemini-legacy-page .modal-header,
	.gemini-legacy-page .dropdown-header,
	.gemini-legacy-page .accordion-header,
	.gemini-legacy-page .list-group-item,
	.gemini-legacy-page .nav-tabs,
	.gemini-legacy-page .border-bottom,
	.gemini-legacy-page .border-top {
		border-color: rgb(226 232 240 / 1) !important;
	}

	.dark .gemini-legacy-page .card-header,
	.dark .gemini-legacy-page .modal-header,
	.dark .gemini-legacy-page .dropdown-header,
	.dark .gemini-legacy-page .accordion-header,
	.dark .gemini-legacy-page .list-group-item,
	.dark .gemini-legacy-page .nav-tabs,
	.dark .gemini-legacy-page .border-bottom,
	.dark .gemini-legacy-page .border-top {
		border-color: rgb(51 65 85 / 1) !important;
	}

	.gemini-legacy-page .card-header,
	.gemini-legacy-page .card-footer,
	.gemini-legacy-page .modal-header,
	.gemini-legacy-page .modal-footer,
	.gemini-legacy-page .dropdown-header {
		background: transparent;
	}

	.gemini-legacy-page .card-title,
	.gemini-legacy-page h1,
	.gemini-legacy-page h2,
	.gemini-legacy-page h3,
	.gemini-legacy-page h4,
	.gemini-legacy-page h5,
	.gemini-legacy-page h6,
	.gemini-legacy-page .text-inverse,
	.gemini-legacy-page .modal-title,
	.gemini-legacy-page .dropdown-header {
		color: #0f172a !important;
	}

	.dark .gemini-legacy-page .card-title,
	.dark .gemini-legacy-page h1,
	.dark .gemini-legacy-page h2,
	.dark .gemini-legacy-page h3,
	.dark .gemini-legacy-page h4,
	.dark .gemini-legacy-page h5,
	.dark .gemini-legacy-page h6,
	.dark .gemini-legacy-page .text-inverse,
	.dark .gemini-legacy-page .modal-title,
	.dark .gemini-legacy-page .dropdown-header {
		color: #f8fafc !important;
	}

	.gemini-legacy-page .text-muted,
	.gemini-legacy-page .text-inverse.text-opacity-50,
	.gemini-legacy-page small,
	.gemini-legacy-page .small {
		color: #64748b !important;
	}

	.dark .gemini-legacy-page .text-muted,
	.dark .gemini-legacy-page .text-inverse.text-opacity-50,
	.dark .gemini-legacy-page small,
	.dark .gemini-legacy-page .small {
		color: #94a3b8 !important;
	}

	.gemini-legacy-page .btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 0.45rem;
		padding: 0.75rem 1rem;
		border-radius: 1rem;
		border-width: 1px;
		font-weight: 600;
		box-shadow: none;
		transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
	}

	.gemini-legacy-page .btn:hover {
		transform: translateY(-1px);
	}

	.gemini-legacy-page .btn-sm,
	.gemini-legacy-page .btn-group-sm > .btn {
		padding: 0.55rem 0.8rem;
		border-radius: 0.85rem;
		font-size: 0.82rem;
	}

	.gemini-legacy-page .btn-primary,
	.gemini-legacy-page .btn-theme,
	.gemini-legacy-page .bg-theme,
	.gemini-legacy-page .page-item.active .page-link,
	.gemini-legacy-page .nav-pills .nav-link.active,
	.gemini-legacy-page .nav-tabs .nav-link.active {
		border-color: #2563eb !important;
		background: #2563eb !important;
		color: #ffffff !important;
	}

	.gemini-legacy-page .btn-primary:hover,
	.gemini-legacy-page .btn-theme:hover,
	.gemini-legacy-page .btn-success:hover,
	.gemini-legacy-page .btn-danger:hover,
	.gemini-legacy-page .btn-info:hover,
	.gemini-legacy-page .btn-warning:hover {
		filter: brightness(0.96);
	}

	.gemini-legacy-page .btn-outline-primary,
	.gemini-legacy-page .btn-outline-theme,
	.gemini-legacy-page .text-theme,
	.gemini-legacy-page .nav-tabs .nav-link,
	.gemini-legacy-page .page-link {
		border-color: rgb(191 219 254 / 1);
		color: #2563eb !important;
	}

	.dark .gemini-legacy-page .btn-outline-primary,
	.dark .gemini-legacy-page .btn-outline-theme,
	.dark .gemini-legacy-page .text-theme,
	.dark .gemini-legacy-page .nav-tabs .nav-link,
	.dark .gemini-legacy-page .page-link {
		border-color: rgb(59 130 246 / 0.35);
		color: #60a5fa !important;
	}

	.gemini-legacy-page .btn-success,
	.gemini-legacy-page .bg-success {
		border-color: #059669 !important;
		background: #059669 !important;
		color: #ffffff !important;
	}

	.gemini-legacy-page .btn-outline-success {
		border-color: rgb(167 243 208 / 1) !important;
		background: transparent !important;
		color: #059669 !important;
	}

	.dark .gemini-legacy-page .btn-outline-success {
		border-color: rgb(16 185 129 / 0.35) !important;
		color: #34d399 !important;
	}

	.gemini-legacy-page .btn-danger,
	.gemini-legacy-page .bg-danger {
		border-color: #dc2626 !important;
		background: #dc2626 !important;
		color: #ffffff !important;
	}

	.gemini-legacy-page .btn-outline-danger {
		border-color: rgb(254 202 202 / 1) !important;
		background: transparent !important;
		color: #dc2626 !important;
	}

	.dark .gemini-legacy-page .btn-outline-danger {
		border-color: rgb(239 68 68 / 0.35) !important;
		color: #f87171 !important;
	}

	.gemini-legacy-page .btn-warning,
	.gemini-legacy-page .bg-warning {
		border-color: #d97706 !important;
		background: #f59e0b !important;
		color: #111827 !important;
	}

	.gemini-legacy-page .btn-outline-warning {
		border-color: rgb(253 230 138 / 1) !important;
		background: transparent !important;
		color: #d97706 !important;
	}

	.dark .gemini-legacy-page .btn-outline-warning {
		border-color: rgb(245 158 11 / 0.35) !important;
		color: #fbbf24 !important;
	}

	.gemini-legacy-page .btn-info,
	.gemini-legacy-page .bg-info {
		border-color: #0284c7 !important;
		background: #0ea5e9 !important;
		color: #ffffff !important;
	}

	.gemini-legacy-page .btn-outline-info {
		border-color: rgb(186 230 253 / 1) !important;
		background: transparent !important;
		color: #0284c7 !important;
	}

	.dark .gemini-legacy-page .btn-outline-info {
		border-color: rgb(14 165 233 / 0.35) !important;
		color: #38bdf8 !important;
	}

	.gemini-legacy-page .btn-secondary {
		border-color: rgb(203 213 225 / 1);
		background: #ffffff;
		color: #475569;
	}

	.dark .gemini-legacy-page .btn-secondary {
		border-color: rgb(71 85 105 / 1);
		background: #0f172a;
		color: #cbd5e1;
	}

	.gemini-legacy-page .form-label {
		display: block;
		margin-bottom: 0.55rem;
		font-size: 0.72rem;
		font-weight: 700;
		letter-spacing: 0.14em;
		text-transform: uppercase;
		color: #64748b;
	}

	.dark .gemini-legacy-page .form-label {
		color: #94a3b8;
	}

	.gemini-legacy-page .form-control,
	.gemini-legacy-page .form-select,
	.gemini-legacy-page textarea,
	.gemini-legacy-page input:not([type='checkbox']):not([type='radio']):not([type='range']) {
		min-height: 2.75rem;
		border: 1px solid rgb(203 213 225 / 1);
		border-radius: 1rem;
		background: #ffffff;
		color: #0f172a;
		box-shadow: none;
	}

	.dark .gemini-legacy-page .form-control,
	.dark .gemini-legacy-page .form-select,
	.dark .gemini-legacy-page textarea,
	.dark .gemini-legacy-page input:not([type='checkbox']):not([type='radio']):not([type='range']) {
		border-color: rgb(51 65 85 / 1);
		background: #020617;
		color: #e2e8f0;
	}

	.gemini-legacy-page .form-control:focus,
	.gemini-legacy-page .form-select:focus,
	.gemini-legacy-page textarea:focus,
	.gemini-legacy-page input:focus {
		border-color: #2563eb;
		box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
	}

	.gemini-legacy-page .input-group {
		display: flex;
		align-items: stretch;
		gap: 0.75rem;
	}

	.gemini-legacy-page .input-group > .form-control,
	.gemini-legacy-page .input-group > .form-select,
	.gemini-legacy-page .input-group > .select2,
	.gemini-legacy-page .input-group > .select2-container {
		flex: 1 1 auto;
	}

	.gemini-legacy-page .input-group-text {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 3rem;
		padding: 0 1rem;
		border: 1px solid rgb(203 213 225 / 1);
		border-radius: 1rem;
		background: rgb(248 250 252 / 1);
		color: #475569;
	}

	.dark .gemini-legacy-page .input-group-text {
		border-color: rgb(51 65 85 / 1);
		background: rgb(15 23 42 / 1);
		color: #cbd5e1;
	}

	.gemini-legacy-page .form-check.form-switch {
		display: flex;
		align-items: center;
		gap: 0.75rem;
		padding-left: 0;
		min-height: 2.75rem;
	}

	.gemini-legacy-page .form-check-input {
		margin: 0;
		width: 3rem;
		height: 1.65rem;
		border-radius: 999px;
		border: 1px solid rgb(148 163 184 / 1);
		background-color: #e2e8f0;
		box-shadow: none;
	}

	.gemini-legacy-page .form-check-input:checked {
		border-color: #2563eb;
		background-color: #2563eb;
	}

	.dark .gemini-legacy-page .form-check-input {
		border-color: rgb(71 85 105 / 1);
		background-color: rgb(30 41 59 / 1);
	}

	.gemini-legacy-page .table-responsive {
		overflow-x: auto;
	}

	.gemini-legacy-page .table {
		margin-bottom: 0;
		color: inherit;
	}

	.gemini-legacy-page .table > :not(caption) > * > * {
		border-bottom-color: rgb(226 232 240 / 1);
		padding: 0.95rem 1rem;
		vertical-align: middle;
		background: transparent;
	}

	.dark .gemini-legacy-page .table > :not(caption) > * > * {
		border-bottom-color: rgb(51 65 85 / 1);
	}

	.gemini-legacy-page .table thead th {
		background: rgb(248 250 252 / 1);
		color: #64748b;
		font-size: 0.72rem;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	.dark .gemini-legacy-page .table thead th {
		background: rgb(15 23 42 / 1);
		color: #94a3b8;
	}

	.gemini-legacy-page .table-hover tbody tr:hover > * {
		background: rgb(248 250 252 / 1);
	}

	.dark .gemini-legacy-page .table-hover tbody tr:hover > * {
		background: rgb(15 23 42 / 0.85);
	}

	.gemini-legacy-page .table-dark,
	.gemini-legacy-page .table-dark > :not(caption) > * > * {
		--bs-table-bg: transparent;
		--bs-table-color: inherit;
		--bs-table-striped-bg: rgb(248 250 252 / 0.88);
		--bs-table-striped-color: inherit;
		--bs-table-hover-bg: rgb(241 245 249 / 1);
		--bs-table-hover-color: inherit;
		background: transparent !important;
		color: inherit !important;
	}

	.dark .gemini-legacy-page .table-dark,
	.dark .gemini-legacy-page .table-dark > :not(caption) > * > * {
		--bs-table-striped-bg: rgb(15 23 42 / 0.78);
		--bs-table-hover-bg: rgb(30 41 59 / 0.9);
	}

	.gemini-legacy-page .table-light,
	.gemini-legacy-page .table-primary,
	.gemini-legacy-page .table-secondary,
	.gemini-legacy-page .table-success,
	.gemini-legacy-page .table-warning,
	.gemini-legacy-page .table-info,
	.gemini-legacy-page .bg-light,
	.gemini-legacy-page .bg-primary-subtle,
	.gemini-legacy-page .bg-danger-subtle,
	.gemini-legacy-page .bg-success-subtle,
	.gemini-legacy-page .bg-warning-subtle,
	.gemini-legacy-page .bg-opacity-25 {
		background: rgb(248 250 252 / 1) !important;
		color: #0f172a !important;
	}

	.dark .gemini-legacy-page .table-light,
	.dark .gemini-legacy-page .table-primary,
	.dark .gemini-legacy-page .table-secondary,
	.dark .gemini-legacy-page .table-success,
	.dark .gemini-legacy-page .table-warning,
	.dark .gemini-legacy-page .table-info,
	.dark .gemini-legacy-page .bg-light,
	.dark .gemini-legacy-page .bg-primary-subtle,
	.dark .gemini-legacy-page .bg-danger-subtle,
	.dark .gemini-legacy-page .bg-success-subtle,
	.dark .gemini-legacy-page .bg-warning-subtle,
	.dark .gemini-legacy-page .bg-opacity-25 {
		background: rgb(15 23 42 / 1) !important;
		color: #e2e8f0 !important;
	}

	.gemini-legacy-page .table-secondary.text-dark,
	.gemini-legacy-page .table-primary.text-dark {
		color: #475569 !important;
	}

	.dark .gemini-legacy-page .table-secondary.text-dark,
	.dark .gemini-legacy-page .table-primary.text-dark {
		color: #cbd5e1 !important;
	}

	.gemini-legacy-page .row > [class*='col-'] > .card.shadow-sm:not(.modal-content) {
		height: 100%;
	}

	.gemini-legacy-page .card-body > .row.g-3 > [class*='col-'] > .card,
	.gemini-legacy-page .row.g-3 > [class*='col-'] > .border.rounded,
	.gemini-legacy-page .row.g-3 > [class*='col-'] > .border.rounded.p-3 {
		border: 1px solid rgb(226 232 240 / 1) !important;
		border-radius: 1.25rem !important;
		background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.92) 100%) !important;
		box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06) !important;
	}

	.dark .gemini-legacy-page .card-body > .row.g-3 > [class*='col-'] > .card,
	.dark .gemini-legacy-page .row.g-3 > [class*='col-'] > .border.rounded,
	.dark .gemini-legacy-page .row.g-3 > [class*='col-'] > .border.rounded.p-3 {
		border-color: rgb(51 65 85 / 1) !important;
		background: linear-gradient(180deg, rgba(15,23,42,0.96) 0%, rgba(2,6,23,0.98) 100%) !important;
		box-shadow: 0 16px 35px rgba(2, 6, 23, 0.36) !important;
	}

	.gemini-legacy-page .page-header {
		margin-bottom: 1.5rem;
		font-size: 1.8rem;
		font-weight: 800;
		letter-spacing: -0.03em;
		color: #0f172a;
	}

	.dark .gemini-legacy-page .page-header {
		color: #f8fafc;
	}

	.gemini-legacy-page .table tbody tr.fw-bold > td,
	.gemini-legacy-page .table tbody tr.fw-semibold > td,
	.gemini-legacy-page .table tfoot tr > td,
	.gemini-legacy-page .table tfoot tr > th {
		font-weight: 700;
	}

	.gemini-legacy-page .table-sm > :not(caption) > * > * {
		padding: 0.7rem 0.85rem;
	}

	.gemini-legacy-page .table a {
		color: #2563eb;
		font-weight: 600;
		text-decoration: none;
	}

	.dark .gemini-legacy-page .table a {
		color: #60a5fa;
	}

	.gemini-legacy-page .table a:hover {
		text-decoration: underline;
	}

	.gemini-legacy-page .alert {
		border-radius: 1rem;
		border-width: 1px;
	}

	.gemini-legacy-page .badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 0.35rem;
		padding: 0.45rem 0.7rem;
		border-radius: 999px;
		font-weight: 700;
	}

	.gemini-legacy-page .badge.bg-success,
	.gemini-legacy-page .badge-success {
		background: rgb(220 252 231 / 1) !important;
		color: #166534 !important;
	}

	.gemini-legacy-page .badge.bg-danger,
	.gemini-legacy-page .badge-danger {
		background: rgb(254 226 226 / 1) !important;
		color: #991b1b !important;
	}

	.gemini-legacy-page .badge.bg-warning,
	.gemini-legacy-page .badge-warning {
		background: rgb(254 243 199 / 1) !important;
		color: #92400e !important;
	}

	.gemini-legacy-page .badge.bg-info,
	.gemini-legacy-page .badge-info,
	.gemini-legacy-page .badge.bg-primary,
	.gemini-legacy-page .badge-primary {
		background: rgb(219 234 254 / 1) !important;
		color: #1d4ed8 !important;
	}

	.gemini-legacy-page .badge.bg-secondary,
	.gemini-legacy-page .badge-secondary {
		background: rgb(226 232 240 / 1) !important;
		color: #334155 !important;
	}

	.dark .gemini-legacy-page .badge.bg-success,
	.dark .gemini-legacy-page .badge-success {
		background: rgb(20 83 45 / 0.4) !important;
		color: #86efac !important;
	}

	.dark .gemini-legacy-page .badge.bg-danger,
	.dark .gemini-legacy-page .badge-danger {
		background: rgb(127 29 29 / 0.45) !important;
		color: #fca5a5 !important;
	}

	.dark .gemini-legacy-page .badge.bg-warning,
	.dark .gemini-legacy-page .badge-warning {
		background: rgb(120 53 15 / 0.45) !important;
		color: #fcd34d !important;
	}

	.dark .gemini-legacy-page .badge.bg-info,
	.dark .gemini-legacy-page .badge-info,
	.dark .gemini-legacy-page .badge.bg-primary,
	.dark .gemini-legacy-page .badge-primary {
		background: rgb(30 64 175 / 0.35) !important;
		color: #93c5fd !important;
	}

	.dark .gemini-legacy-page .badge.bg-secondary,
	.dark .gemini-legacy-page .badge-secondary {
		background: rgb(51 65 85 / 1) !important;
		color: #cbd5e1 !important;
	}

	.gemini-legacy-page .pagination {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: center;
		gap: 0.4rem;
	}

	.gemini-legacy-page .page-link {
		border-radius: 0.9rem;
		background: transparent;
	}

	.gemini-legacy-page .nav-tabs {
		gap: 0.5rem;
		border-bottom: 0;
	}

	.gemini-legacy-page .nav-tabs .nav-link {
		border-radius: 999px;
		border: 1px solid rgb(203 213 225 / 1);
		background: #ffffff;
	}

	.dark .gemini-legacy-page .nav-tabs .nav-link {
		border-color: rgb(51 65 85 / 1);
		background: rgb(15 23 42 / 1);
	}

	.gemini-legacy-page .modal-backdrop.show {
		opacity: 0.45;
	}

	.gemini-legacy-page .dropdown-item,
	.gemini-legacy-page .list-group-item {
		border-radius: 0.9rem;
	}

	.gemini-legacy-page .dropdown-item:hover,
	.gemini-legacy-page .list-group-item:hover {
		background: rgb(248 250 252 / 1);
	}

	.dark .gemini-legacy-page .dropdown-item:hover,
	.dark .gemini-legacy-page .list-group-item:hover {
		background: rgb(15 23 42 / 1);
	}

	.gemini-legacy-page .select2-container {
		width: 100% !important;
	}

	.gemini-legacy-page .select2-container--default .select2-selection--single,
	.gemini-legacy-page .select2-container--default .select2-selection--multiple {
		min-height: 2.75rem;
		border: 1px solid rgb(203 213 225 / 1) !important;
		border-radius: 1rem !important;
		background: #ffffff !important;
	}

	.dark .gemini-legacy-page .select2-container--default .select2-selection--single,
	.dark .gemini-legacy-page .select2-container--default .select2-selection--multiple {
		border-color: rgb(51 65 85 / 1) !important;
		background: #020617 !important;
	}

	.gemini-legacy-page .select2-container--default .select2-selection__rendered {
		line-height: 2.75rem !important;
		padding-inline: 1rem !important;
		color: #0f172a !important;
	}

	.dark .gemini-legacy-page .select2-container--default .select2-selection__rendered {
		color: #e2e8f0 !important;
	}

	.gemini-legacy-page .select2-container--default .select2-selection__arrow {
		height: 2.75rem !important;
		right: 0.85rem !important;
	}

	.gemini-legacy-page .modal-header.bg-primary,
	.gemini-legacy-page .modal-header.bg-success,
	.gemini-legacy-page .modal-header.bg-danger,
	.gemini-legacy-page .modal-header.bg-info,
	.gemini-legacy-page .modal-header.bg-warning {
		background: transparent !important;
		color: inherit !important;
	}

	.gemini-legacy-page .modal-body,
	.gemini-legacy-page .modal-footer {
		padding: 1.4rem 1.5rem;
	}

	.gemini-legacy-page .btn-close {
		opacity: 0.7;
	}

	.dark .gemini-legacy-page .btn-close {
		filter: invert(1);
	}

	.gemini-legacy-page .border-end {
		border-color: rgb(226 232 240 / 1) !important;
	}

	.dark .gemini-legacy-page .border-end {
		border-color: rgb(51 65 85 / 1) !important;
	}

	.gemini-legacy-page .card-arrow,
	.gemini-legacy-page .app-theme-panel,
	.gemini-legacy-page .theme-panel,
	.gemini-legacy-page .app-theme-toggle-btn {
		display: none !important;
	}
</style>
