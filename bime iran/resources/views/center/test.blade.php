@extends('master')
@section('content')
    <link rel="stylesheet" href="../assets/css/persian-datepicker-dark.css"/>

    <script src="../assets/js/persian-date.js"></script>
    <script src="../assets/js/persian-datepicker.js"></script>
    <script type="text/javascript">
        /*
         Default Functionality
         */
        $(document).ready(function () {
            $("#persianDigit").persianDatepicker();
        });
    </script>

    <div class="col-xs-12">
                <div id="persianDigit" class="col-xs-6"></div>
    </div>
    <div hidden> <script src="http://www.yr.no/place/Iran/Gilan/Rasht/external_box_stripe.js"></script><noscript><a href="http://www.yr.no/place/Iran/Gilan/Rasht/">yr.no: Forecast for Rasht</a></noscript>
    </div>

@stop

