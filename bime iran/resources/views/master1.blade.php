<!DOCTYPE html>
<html>
<head id="head">
    <title>سامانه هوشمند بررسی الکترونیک نسخ داروخانه های طرف قرارداد بیمه ایران</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/animate.css')}}">
    <link rel="icon" href="{{ URL::asset('assets/img/logo.gif') }}">
</head>
<body >
@if(Session::has('flash_message'))
    <br>

    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{Session::get('flash_message')}}</div>
@endif


@yield('content')
</body>



<?php
Html::script('/js/query.js');
Html::script('/js/bootsrap.js');
Html::script('/css/select2.min.js');
?>
{{--<script src="{{url('assets/js/jquery.js')}}"></script>
<script src="{{url('assets/js/bootstrap.js')}}"></script>
<script  src="{{url('assets/js/select2.min.js')}}"></script>--}}
<script>

</script>
</html>