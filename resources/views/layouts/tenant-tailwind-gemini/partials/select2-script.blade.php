<style>
    .gemini-select2.select2-container,
    .select2-container {
        width: 100% !important;
    }

    .gemini-select2 .select2-selection,
    .select2-container .select2-selection {
        min-height: 2.75rem !important;
        border: 1px solid rgb(203 213 225 / 1) !important;
        border-radius: 1rem !important;
        background: #fff !important;
        box-shadow: none !important;
    }

    .dark .gemini-select2 .select2-selection,
    .dark .select2-container .select2-selection,
    [data-bs-theme=dark] .gemini-select2 .select2-selection {
        border-color: rgb(51 65 85 / 1) !important;
        background: #020617 !important;
    }

    .gemini-select2 .select2-selection__rendered,
    .select2-container .select2-selection__rendered {
        min-height: 2.75rem;
        padding-inline: 1rem !important;
        line-height: 2.75rem !important;
        color: #0f172a !important;
    }

    .dark .gemini-select2 .select2-selection__rendered,
    .dark .select2-container .select2-selection__rendered,
    [data-bs-theme=dark] .gemini-select2 .select2-selection__rendered {
        color: #e2e8f0 !important;
    }

    .gemini-select2 .select2-selection__arrow,
    .select2-container .select2-selection__arrow {
        height: 2.75rem !important;
        right: 0.85rem !important;
    }

    .select2-dropdown.gemini-select2-dropdown,
    .select2-dropdown {
        border: 1px solid rgb(203 213 225 / 1) !important;
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
    }

    .dark .select2-dropdown.gemini-select2-dropdown,
    [data-bs-theme=dark] .select2-dropdown.gemini-select2-dropdown {
        border-color: rgb(51 65 85 / 1) !important;
        background: #020617 !important;
        box-shadow: 0 18px 45px rgba(2, 6, 23, 0.45);
    }

    .gemini-select2-dropdown .select2-results__option {
        padding: 0.7rem 0.9rem;
    }

    .gemini-select2-dropdown .select2-results__option--highlighted {
        background: rgb(37 99 235 / 1) !important;
        color: #fff !important;
    }
</style>
<script>
    if (window.__geminiSelect2ScriptLoaded) {
        // Prevent duplicate listeners when this partial is included in both layout and page.
    } else {
        window.__geminiSelect2ScriptLoaded = true;

    window.formatGeminiSelect2Option = window.formatGeminiSelect2Option || function (option) {
        if (!option.id) {
            return $('<span>').text(option.text);
        }

        const element = $(option.element);
        const image = element.data('image');
        const content = element.data('content');

        if (image) {
            return $(
                `<div class="d-flex align-items-center gap-3">
                    <img src="${image}" class="rounded-circle border" width="38" height="38" style="object-fit:cover">
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">${option.text}</span>
                    </div>
                </div>`
            );
        }

        if (content) {
            return $(
                `<div class="d-flex align-items-center gap-3">
                    <div class="content-wrapper">${content}</div>
                    <div class="d-flex flex-column"><span class="fw-semibold">${option.text}</span></div>
                </div>`
            );
        }

        return $('<div class="fw-medium">').text(option.text);
    };

    window.renderSelect2 = window.renderSelect2 || function () {
        if (!window.jQuery || !jQuery.fn.select2) {
            return;
        }

        $('.select2').each(function () {
            const $element = $(this);

            if ($element.hasClass('select2-hidden-accessible')) {
                $element.select2('destroy');
            }
        });

        $('.select2').each(function () {
            const $element = $(this);
            const $parentModal = $element.closest('.modal');
            const options = {
                width: '100%',
                templateResult: window.formatGeminiSelect2Option,
                templateSelection: window.formatGeminiSelect2Option,
            };

            if ($parentModal.length) {
                options.dropdownParent = $parentModal;
            }

            $element.select2(options);
        });

        $('.select2').off('select2:select.gemini select2:unselect.gemini').on('select2:select.gemini select2:unselect.gemini', function (event) {
            const element = $(event.currentTarget);
            const field = element.attr('name');

            if (field && window.Livewire && typeof window.Livewire.find === 'function') {
                const componentRoot = element.closest('[wire\\:id]');
                const componentId = componentRoot.attr('wire:id');

                if (componentId) {
                    const component = window.Livewire.find(componentId);
                    component?.set(field, element.val());
                }
            }
        });
    };

    document.addEventListener('DOMContentLoaded', () => window.renderSelect2?.());
    document.addEventListener('livewire:navigated', () => window.renderSelect2?.());
    document.addEventListener('livewire:initialized', () => {
        window.renderSelect2?.();

        if (window.__geminiSelect2HookRegistered || !window.Livewire || typeof window.Livewire.hook !== 'function') {
            return;
        }

        window.__geminiSelect2HookRegistered = true;
        window.Livewire.hook('morph.updated', () => {
            window.renderSelect2?.();
        });
    });
    }
</script>
