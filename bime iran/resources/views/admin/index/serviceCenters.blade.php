@extends('master')
@section('content')
    <ol class="breadcrumb" >

        <li class="active bold"> مدیریت مراکز خدماتی</li>
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
            @foreach($centers as $center)
                <option value="{{url('admin/show-center/'.$center->id)}}">{{$center->name}} - {{$center->sahebe_emtiaz}}</option>
            @endforeach

        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>

    <a href="{{action('AdminController@getNewCenter')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد مرکز جدید</button></a>
    <br><br>
@if($centers==Null)

    <p>sdf</p>
    @else

    <table class="table table-bordered  ">
        <tr class="info bold">
            <td>نام مرکز</td>
            <td>صاحب امتیاز</td>
            <td>شیفت کاری</td>
            <td>تلفن</td>
        </tr>
        @foreach($centers as $center)
            <tr>
                <td><a href="{{url('admin/show-center/'.$center->id)}}">{{$center->name}}</a></td>
                <td>{{$center->sahebe_emtiaz}}</td>
                <td>{{$center->shift}}</td>
                <td>{{$center->phone}}</td>
            </tr>
        @endforeach
    </table>

@endif


@stop