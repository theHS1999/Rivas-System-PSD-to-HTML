@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active"> مدیریت بیمه گذاران و افراد</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <h3>
        مدیریت بیمه گذاران و افراد
    </h3>
    <hr>
<a href="{{action('ServicesController@getCreate')}}"><button class="btn">ایجاد خدمت جدید</button></a>
<br/>
@foreach($services as $service)
    {{$service}}
    @foreach($commits as $commit)
        @if($commit->service)
    @endforeach

@endforeach
@endsection