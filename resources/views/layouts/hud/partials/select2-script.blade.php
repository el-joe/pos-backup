<style>
    /* ===== Select Box ===== */
.select2-container--bootstrap-5 .select2-selection--single {
    height: 48px;
    padding: 8px 14px;
    border: 1.5px solid var(--border-theme);
    border-radius: 8px;
    background-color: var(--bs-bg-theme);
    transition: all .2s ease-in-out;
}

/* Align content */
.select2-selection__rendered {
    display: flex;
    align-items: center;
    gap: 10px;
}
/* Placeholder */
.select2-container--bootstrap-5
.select2-selection__placeholder {
    color: #6c757d;
    font-style: italic;
    font-size: 0.95rem;
}

/* Dropdown container */
.select2-dropdown {
    border-radius: 10px;
    border: 1px solid #dee2e6;
    overflow: hidden;
}

/* Option */
.select2-results__option {
    padding: 12px 16px;
    font-size: 0.95rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color .15s ease;
}

/* Hover */
.select2-results__option--highlighted {
    background-color: rgba(13,110,253,.08) !important;
    color: #0d6efd;
}

/* Selected */
.select2-results__option--selected {
    background-color: rgba(13,110,253,.15);
    font-weight: 600;
}


</style>

<script>
    $(document).ready(function() {
        function formatOption(option) {
            if (!option.id) {
                return $(`<span class="text-muted">${option.text}</span>`);
            }

            let icon = $(option.element).data('icon');

            if (icon) {
                return $(`
                    <div class="d-flex align-items-center gap-3">
                        <img
                            src="${icon}"
                            class="rounded-circle border"
                            width="38"
                            height="38"
                            style="object-fit:cover"
                        >
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark">${option.text}</span>
                            <small class="text-muted">Branch</small>
                        </div>
                    </div>
                `);
            }

            return $(`
                <div class="fw-medium text-dark">
                    ${option.text}
                </div>
            `);
        }

        // تهيئة Select2 على العناصر الموجودة
        $(".select2").select2({
            theme: 'bootstrap-5',
            allowClear: true,
            templateResult: formatOption,
            templateSelection: formatOption,
            dropdownAutoWidth: true,
            width: '100%'
        });
    });

    $('.select2').on('select2:select select2:unselect', function(e) {
        const elem = $(e.currentTarget);
        var data = elem.select2("val");
        @this.set(elem.attr('name'), data);
    });
</script>
