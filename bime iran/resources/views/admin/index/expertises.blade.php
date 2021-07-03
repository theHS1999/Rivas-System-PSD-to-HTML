@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active">مدیریت تخصص ها</li>
        <li ><a href="{{url('admin/doctors')}}">مدیریت پزشکان</a></li>
    <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
</ol>
    @include('errors.form')
<a class="btn btn-primary" href="{{'new-expertise'}}">ایجاد تخصص جدید</a>
<br>
<br>
<table class="table table-bordered ">
    <tr class="info ">
        <td>نام تخصص</td>
        <td>کد تخصص</td>
        <td>عملیات</td>
    </tr>
    @foreach($expertises as $expertise)
        <tr>
            <td>{{$expertise->name}}</td>
            <td>{{$expertise->code}}</td>
            <td><a class="btn btn-info btn-xs" href="{{url('admin/edit-expertise/'.$expertise->id)}}"><span class="glyphicon glyphicon-edit"></span>ویرایش</a></td>
        </tr>
    @endforeach
</table>
    @stop