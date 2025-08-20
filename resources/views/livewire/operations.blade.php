<div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @script
    <script wire:ignore>

        $wire.on('alert',event => {
            swal({
                title : event[0].title,
                text : event[0].message,
                icon : event[0].type,
                position: "top-end",
                button: false,
                timer: 10000
            });
        });

        $wire.on('closeModal',event => {
            $('.modal').modal('hide');
        });

        $wire.on('openModal',event => {
            $('.modal').modal('hide');
            $(event[0]).modal('show');
        });


    </script>
    @endscript
</div>
