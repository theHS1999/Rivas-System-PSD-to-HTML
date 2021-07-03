@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold"> پزشکان</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    @include('errors.form')
    <a class="btn btn-primary" href="{{'new-doctor'}}">افزودن پزشک جدید</a>
    <a class="btn btn-primary" href="{{'expertises'}}">مدیریت تخصص ها</a>
    <br>
    <br>
    <table class="table table-bordered ">
        <tr class="info ">
            <td>نام پزشک</td>
            <td>کد نظام پزشکی</td>
            <td>تخصص</td>
            <td>وضعیت</td>
            <td>عملیات</td>
        </tr>
        @foreach($doctors as $doctor)
            <tr >
                <td>{{$doctor->fname}} {{$doctor->lname}}</td>
                <td>{{$doctor->medical_code}}</td>
                <td>
                    {{\App\Expertise::find($doctor->expertise)->name}}
                </td>
                <td>{!! $doctor->status==1 ? '<span class="label label-success">فعال</span>' : '<span class="label label-danger">غیر فعال</span>' !!}</td>
                <td><a class="btn btn-info btn-xs" href="{{url('admin/edit-doctor/'.$doctor->id)}}"><span class="glyphicon glyphicon-edit"></span>ویرایش</a></td>
            </tr>
            @endforeach
    </table>
@stop