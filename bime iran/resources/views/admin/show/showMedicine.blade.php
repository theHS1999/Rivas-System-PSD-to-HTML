@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">  مدیریت داروها</li>
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
            @foreach($medicines as $medicine)
                <option value="{{url('admin/show-medicine/'.$medicine->id)}}">{{$medicine->name}}</option>
            @endforeach
        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>

    <a href="{{action('AdminController@getNewMedicine')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد دارو جدید</button></a>
    <a href="{{action('AdminController@getNewMedicine')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-upload"></span>بارگذاری فرم فارماکوپه</button></a>
    <br><br>


    <table class="table table-bordered" >
        <tr class="info bold">
            <td>نام دارو</td>
            <td>قیمت دارو</td>
            <td>نوع دارو</td>
            <td>سهم بیمه گر پایه</td>
            <td>سهم بیمه گر تکمیلی</td>
        </tr>

            <tr>
                <td style="font-family: tahoma;direction: ltr">{{$medicine1->name}}</td>
                <td>{{$medicine1->price}}</td>
                <td>{{$medicine1->type==1 ?  'فارماکوپه ای' : 'اقلام خارجی'}}</td>
                <td>{{$medicine1->first_insure_percent}} %</td>
                <td>@if($medicine1-> iran_percent==0)
                        {{'ذکر نشده'}}
                    @else
                        {{$medicine1-> iran_percent}} %
                    @endif</td>

            </tr>


    </table>


@endsection