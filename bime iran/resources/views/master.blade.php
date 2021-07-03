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
            <span> <img src="{{url('assets/img/logo.gif')}}" height="100px" style="padding: 5px"></span>سامانه هوشمند بررسی الکترونیک نسخ داروخانه های طرف قرارداد بیمه ایران
        </div>
    </div>
    <div class="row" style="border-bottom: 5px solid #094F95;border-top: 5px solid #094F95;background-color: white">
        <div id="content" class="col-xs-10" style="min-height: 100px;padding-bottom: 20px;background-color: white">@yield('content')</div>
        <div class="col-xs-2" >
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    منوی کاربری
                </div>
                <div class="panel-body" >
                    <?php $user=Auth::user(); ?>
                    @if($user->pic)
                        <img src="{{url('uploads/'.$user->pic)}}" width="90%" >
                    @else
                        <img src="{{url('assets/img/profile2.png')}}" height="100px" >
                    @endif
                    {{$user->fullname}}
                    <br>
                    <a
                    @if(Auth::user()->type=='admin')
                         href="  {{url('admin/edit-profile')}}">
                    @endif
                    @if(Auth::user()->type=='expert')
                         href="  {{url('expert/edit-profile')}}">
                    @endif
                    @if(Auth::user()->type=='center')
                         href="  {{url('center/edit-profile')}}">
                    @endif
                    <span  class="glyphicon glyphicon-cog" aria-hidden="true" ></span>
                    </a>
                    <a href="{{url('logout')}}">
                        <span style="color: red;" class="glyphicon glyphicon-off" aria-hidden="true" ></span>
                    </a>
                    <hr>
                    <ul class="sidemenu">
                        @if(Auth::user()->type=='admin')
                            <li><a href="{{url('admin')}}"><span class="glyphicon glyphicon-home"></span>صفحه اصلی</a></li>
                            <li><a href="{{action('AdminController@getInsurers')}}"><span class="glyphicon glyphicon-check"></span>مدیریت بیمه گذاران</a></li>
                            <li><a href="{{url('admin/insureds/all')}}"><span class=" glyphicon glyphicon-link"></span>مدیریت بیمه شدگان</a></li>
                            <li><a href="{{action('AdminController@getContracts')}}"><span class="glyphicon glyphicon-briefcase"></span>مدیریت قراردادها</a></li>
                            <li><a href="{{action('AdminController@getServiceCenters')}}"><span class="glyphicon glyphicon-tags"></span> مراکز ارائه خدمت</a></li>
                            <li><a href="{{action('AdminController@getCommits')}}"><span class="glyphicon glyphicon-tasks"></span> تعهدات و خدمات</a></li>
                            <li><a href="{{action('AdminController@getMedicines')}}"><span class="glyphicon glyphicon-tint"></span>مدیریت داروها</a></li>
                            <li><a href="{{url('admin/doctors')}}"><span class="glyphicon glyphicon-leaf"></span>مدیریت پزشکان</a></li>
                            <li><a href="{{url('admin/users')}}"><span class="glyphicon glyphicon-user"></span>مدیریت کاربران</a></li>
                            <li><a href="{{url('admin/reports/no/no/'.jdate('Y','','','','en'))}}"><span class="glyphicon glyphicon-signal"></span>گزارشات</a></li>
                            <li><a href="{{url('admin/setting')}}"><span class="glyphicon glyphicon-wrench"></span>تنظیمات سایت</a></li>

                        @endif
                        @if(Auth::user()->type=='center')
                            <li><a href="{{url('center')}}"><span class="glyphicon glyphicon-home"></span>صفحه اصلی</a></li>
                            <li><a href="{{action('CenterController@getNewPresc')}}"><span class="glyphicon glyphicon-list-alt"></span> نسخه جدید</a></li>
                            <li><a href="{{url('center/prescs')}}"><span class="glyphicon glyphicon-th-list"></span>مدیریت نسخه ها</a></li>


                        @endif
                        @if(Auth::user()->type=='expert')
                                <li><a href="{{url('expert')}}"><span class="glyphicon glyphicon-home"></span> صفحه اصلی</a></li>
                            <li><a href="{{url('expert/prescs')}}"><span class="glyphicon glyphicon-th-list"></span> مدیریت نسخه ها</a></li>

                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
<br>

<p class="bold">کلیه حقوق این سامانه متعلق به بیمه ایران میباشد</p>
<p class="bold">طراحی و توسعه شرکت مهندسی بازرگانی ریواس سیستم</p>

  @yield('footer')
    <script src="{{url('assets/js/main.js')}}"></script>
<script src="{{url('assets/js/bootstrap.js')}}"></script>
  <script>
    $('.select2').select2({ dir: "rtl"});
      $('.search').select2({ placeholder:'جست و جو' , dir: "rtl"});
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