@extends('master')

@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">مدیریت کاربران</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
        {!! Form::open(['url' => 'admin/search','class'=>'pull-left col-xs-4 s']) !!}
        <style>
            .select2-container--default .select2-selection--single{
                border-bottom-left-radius: 0px;
                border-top-left-radius: 0px;
                text-align: right;
            }
        </style>

        <select class="search" name="search" style="width: 80%">
            <option value=""></option>
            @foreach($users as $user)
                <option value="{{url('admin/show-user/'.$user->id)}}">{{$user->username}} - {{$user->fullname}}</option>
            @endforeach
        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>
    @include('errors.form')
    <div class="col-xs-12"><a class="btn btn-primary " href="{{url('admin/new-user')}}"><span class="glyphicon glyphicon-plus"></span>ایجاد کاربر جدید</a></div>
    <table class="table table-bordered">
        <tr class="info bold">
            <td>نام کاربری</td>
            <td>نوع</td>
            <td>نام اصلی</td>
            <td>ایمیل</td>
            <td>وضعیت</td>
            <td>عملیات</td>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{$user->username}}</td>
                <td>@if($user->type=='admin') ادمین  @elseif($user->type=='expert') کارشناس  @else مرکز خدماتی @endif</td>
                <td>{{$user->fullname}}</td>
                <td>{{$user->email}}</td>
                <td>@if($user->status==0) <div class="label label-success" >فعال</div> @else <div class="label label-danger">غیر فعال</div> @endif</td>
                <td>@if($user->status==0) <a class="btn btn-danger btn-xs" {{$user->type=='admin' ? 'disabled' : 'href='.url('admin/change-status/'.$user->id)}} ><span class="glyphicon glyphicon-remove" ></span>غیر فعال کردن</a> @else <a class="btn btn-success btn-xs"  href="{{url('admin/change-status/'.$user->id)}}"><span class="glyphicon glyphicon-ok"></span>فعال کردن</a> @endif
                    <a class="btn btn-info btn-xs" {{$user->type=='center' ? 'disabled' : 'href='.url('admin/edit-user/'.$user->id)}} ><span class="glyphicon glyphicon-edit"></span>ویرایش</a></td>
            </tr>
        @endforeach
    </table>
@endsection