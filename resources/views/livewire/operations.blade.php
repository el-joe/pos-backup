<div>
    @script
    <script wire:ignore>

        $wire.on('alert',event => {
            swal({
                title : event[0].title,
                text : event[0].message,
                icon : event[0].type,
                position: "top-end",
                button: false,
                timer: 1500
            });
        });

        $wire.on('closeModal',event => {
            $('.modal').modal('hide');
        });

    </script>
    @endscript
</div>
