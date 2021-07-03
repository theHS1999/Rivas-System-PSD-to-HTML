@extends('master')

@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">مدیریت بیمه گذاران و بیمه شدگان</li>
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
            @foreach($insurers as $insurer)
                <option value="{{url('admin/show-insurer/'.$insurer->id)}}">{{$insurer->name}}</option>
            @endforeach

        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>

    @include('errors.form')
    <div class="row">
    <a href="{{action('AdminController@getNewInsurer')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد بیمه گذار جدید</button></a>
    <a href="{{url('admin/insureds/all')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-link"></span> مدیریت بیمه شدگان</button></a>
        <br><br>
    </div>
    <div class="table-responsive" >
    <table class="table table-bordered ">

        <tr class="info" style="font-weight: bold;font-size: 13px">
            <td>نام بیمه گذار</td>

            <td>شماره تلفن</td>
            <td>همراه</td>


            <td>پست الکترونیکی</td>
            <td>مسئول واحد درمان</td>
            <td>
                عملیات
            </td>
        </tr>
        @foreach($insurers as $insurer)
            <tr>
                <td><a href="{{url('admin/show-insurer/'.$insurer->id)}}">{{$insurer->name}}</a> </td>

                <td>{{$insurer->phone}}</td>
                <td>{{$insurer->mobile}}</td>


                <td>{{$insurer->mail}}</td>
                <td>{{$insurer->treatment_name}} </td>
                <td>
                    <a href="{{action('AdminController@getAddToInsurer',['insurer_id'=>$insurer->id,'insured_id'=>'no'])}}"><button class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus-sign"></span>افزودن فرد</button></a>

                </td>
            </tr>


        @endforeach
    </table>
    </div>
@endsection