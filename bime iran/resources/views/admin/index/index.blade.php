@extends('master')
@section('content')
    <ol class="breadcrumb" >

        <li class="active bold">
           صفحه اصلی
        </li>
    </ol>
   <!-- <a href="{{action('AdminController@getInsurers')}}"><button class="btn btn-default"> مدیریت بیمه گذاران و افراد</button></a>
    <a href="{{action('AdminController@getCommits')}}"><button class="btn btn-default"> مدیریت تعهدات و خدمات</button></a>
    <a href="{{action('AdminController@getContracts')}}"><button class="btn btn-default"> مدیریت قراردادها</button></a>
    <a href="{{action('AdminController@getServiceCenters')}}"><button class="btn btn-default">مدیریت مراکز ارائه خدمت</button></a>
    <a href="{{action('AdminController@getMedicines')}}"><button class="btn btn-default">مدیریت داروها</button></a>
-->


<div class="col-sm-8 col-sm-offset-2" style="border: 1px solid #7f7f7f;border-radius: 10px;direction: ltr;text-align: right">
    {!! \App\Setting::find(333)->value !!}
</div>
@stop
