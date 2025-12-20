<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    function renderDateRangePicker(){
        $('.date_range').each(function(){
            var $el = $(this);
            var start = @this.get($el.data('start_date_key')) ? moment(@this.get($el.data('start_date_key'))) : null;
            var end = @this.get($el.data('end_date_key')) ? moment(@this.get($el.data('end_date_key'))) : null;

            // Destroy existing instance if present
            if($el.data('daterangepicker')) {
                $el.data('daterangepicker').remove();
            }

            $el.daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY'
                },
                startDate: start || moment(),
                endDate: end || moment(),
            }, function(start, end, label) {
                // Update input value
                $el.val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

                // Update Livewire properties
                @this.set($el.data('start_date_key'), start.format('YYYY-MM-DD'));
                @this.set($el.data('end_date_key'), end.format('YYYY-MM-DD'));
            });

            // Set initial value if dates exist
            if(start && end){
                $el.val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            }
        });

        // Clear handler
        $('.date_range').off('cancel.daterangepicker').on('cancel.daterangepicker', function(ev, picker) {
            var $el = $(this);
            @this.set($el.data('start_date_key'), '');
            @this.set($el.data('end_date_key'), '');
            $el.val('');
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderDateRangePicker();
    });

    // Re-initialize after Livewire updates
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', () => {
            renderDateRangePicker();
        });
    });
</script>
