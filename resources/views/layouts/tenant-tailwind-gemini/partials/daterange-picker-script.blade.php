<script>
    window.renderDateRangePicker = window.renderDateRangePicker || function () {
        if (!window.jQuery || !jQuery.fn.daterangepicker || !window.moment) {
            return;
        }

        $('.date_range').each(function () {
            const $element = $(this);
            const componentRoot = $element.closest('[wire\\:id]');
            const componentId = componentRoot.attr('wire:id');
            const component = componentId && window.Livewire && typeof window.Livewire.find === 'function'
                ? window.Livewire.find(componentId)
                : null;

            const startKey = $element.data('start_date_key');
            const endKey = $element.data('end_date_key');
            const startValue = component && startKey ? component.get(startKey) : null;
            const endValue = component && endKey ? component.get(endKey) : null;
            const startDate = startValue ? moment(startValue) : moment();
            const endDate = endValue ? moment(endValue) : moment();

            if ($element.data('daterangepicker')) {
                $element.data('daterangepicker').remove();
            }

            $element.daterangepicker({
                autoUpdateInput: false,
                startDate,
                endDate,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                },
            }, (start, end) => {
                $element.val(`${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
                component?.set(startKey, start.format('YYYY-MM-DD'));
                component?.set(endKey, end.format('YYYY-MM-DD'));
            });

            if (startValue && endValue) {
                $element.val(`${moment(startValue).format('DD/MM/YYYY')} - ${moment(endValue).format('DD/MM/YYYY')}`);
            } else {
                $element.val('');
            }
        });

        $('.date_range').off('cancel.daterangepicker.gemini').on('cancel.daterangepicker.gemini', function () {
            const $element = $(this);
            const componentRoot = $element.closest('[wire\\:id]');
            const componentId = componentRoot.attr('wire:id');
            const component = componentId && window.Livewire && typeof window.Livewire.find === 'function'
                ? window.Livewire.find(componentId)
                : null;

            component?.set($element.data('start_date_key'), '');
            component?.set($element.data('end_date_key'), '');
            $element.val('');
        });
    };

    document.addEventListener('DOMContentLoaded', () => window.renderDateRangePicker?.());
    document.addEventListener('livewire:navigated', () => window.renderDateRangePicker?.());
    document.addEventListener('livewire:initialized', () => {
        window.renderDateRangePicker?.();

        if (window.__geminiDateRangeHookRegistered || !window.Livewire || typeof window.Livewire.hook !== 'function') {
            return;
        }

        window.__geminiDateRangeHookRegistered = true;
        window.Livewire.hook('morph.updated', () => {
            window.renderDateRangePicker?.();
        });
    });
</script>
