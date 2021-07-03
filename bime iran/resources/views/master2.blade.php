<!DOCTYPE html>
<html>
<head id="head">

    <title>سامانه هوشمند بررسی الکترونیک نسخ داروخانه های طرف قرارداد بیمه ایران</title>
    <meta charset="UTF-8">
    <script src="{{url('assets/js/jquery.js')}}"></script>
    <script  src="{{url('assets/js/select2.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style.css')}}">
    <link rel="icon" href="{{url('assets/img/logo1.gif')}}">
    @yield('header')
</head>
<body >
<div class="row" style="background: transparent;">


    <div class="col-xs-12" style="background: transparent;text-align: right;font-size: 25px;color:balck;font-family:yekan;font-weight: bold;height:100%;line-height: 90px;">
        <span> <img src="{{url('assets/img/logo.gif')}}" height="100px" style="padding: 5px"></span> سامانه هوشمند بررسی الکترونیک نسخ داروخانه طرف قرارداد بیمه ایران
    </div>

</div>

<div class="row" style="border-bottom: 5px solid #094F95;border-top: 5px solid #094F95;background-color: white">
    <div id="content" class="col-xs-12" style="min-height: 100px;padding-bottom: 20px;background-color: white">@yield('content')</div>


</div>

<p>کلیه حقوق این سامانه متعلق به بیمه ایران میباشد</p>
<p>طراحی و توسعه شرکت مهندسی بازرگانی ریواس سیستم</p>

@yield('footer')
<script src="{{url('assets/js/jquery.navgoco.js')}}"></script>
<script src="{{url('assets/js/main.js')}}"></script>
<script src="{{url('assets/js/bootstrap.js')}}"></script>
<script>

    $('.select2').select2({ dir: "rtl"});
    var w = window,
            d = document,
            e = d.documentElement,
            g = d.getElementsByTagName('body')[0],
            y = w.innerHeight|| e.clientHeight|| g.clientHeight;
    document.getElementById('content').style.minHeight=y-(25*y/100)+'px';

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
</body>


</html>