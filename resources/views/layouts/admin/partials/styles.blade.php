<!-- ===== Bootstrap CSS ===== -->
<link href="{{ asset('adminBoard/cubic-html') }}/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- ===== Plugin CSS ===== -->
<link href="{{ asset('adminBoard') }}/plugins/components/chartist-js/dist/chartist.min.css" rel="stylesheet">
<link href="{{ asset('adminBoard') }}/plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
<link href='{{ asset('adminBoard') }}/plugins/components/fullcalendar/fullcalendar.css' rel='stylesheet'>
<!-- ===== Animation CSS ===== -->
<link href="{{ asset('adminBoard/cubic-html') }}/css/animate.css" rel="stylesheet">
<!-- ===== Custom CSS ===== -->
<link href="{{ asset('adminBoard/cubic-html') }}/css/style.css" rel="stylesheet">
<!-- ===== Color CSS ===== -->
<link href="{{ asset('adminBoard/cubic-html') }}/css/colors/default.css" id="theme" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
    /* Hide default checkbox */
    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Custom checkmark box */
    .custom-checkbox .checkmark {
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 2px solid #007bff;
        /* Bootstrap blue */
        display: inline-block;
        vertical-align: middle;
        margin-right: 6px;
        border-radius: 4px;
        transition: 0.2s;
    }

    /* When checked → fill blue */
    .custom-checkbox input:checked~.checkmark {
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Checkmark tick */
    .custom-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .custom-checkbox input:checked~.checkmark:after {
        display: block;
    }

    /* Tick style */
    .custom-checkbox .checkmark:after {
        left: 6px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        position: relative;
    }

</style>
