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
            @foreach($medicines1 as $medicine)
                <option value="{{url('admin/show-medicine/'.$medicine->id)}}">{{$medicine->name}}</option>
            @endforeach
        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>
    @include('errors.form')
    <a href="{{action('AdminController@getNewMedicine')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد دارو جدید</button></a>
    <a href="{{action('AdminController@getNewMedicine')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-upload"></span>بارگذاری فرم فارماکوپه</button></a>
    <a href="{{url('admin/specialuses')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-star"></span>مدیریت کاربری های خاص</button></a>
    <a href="{{url('admin/medicine-group-add')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-th"></span>تخصیص گروهی تخصص و کاربری خاص</button></a>
    <br><br>
    <div class="btn-group" role="group">
        <a href="{{url('admin/medicines/farma')}}"
           class="btn btn-default {{$status=='farma' ? 'active' : ''}} ">فارماکوپه ای</a>
        <a href="{{url('admin/medicines/foreign')}}"
           class="btn btn-default {{$status=='foreign' ? 'active' : ''}}">اقلام خارجی</a>
        <a href="{{url('admin/medicines/all')}}"
           class="btn btn-default {{$status=='all' ? 'active' : ''}}">همه داروها</a>

    </div>
    <br><br>
    <table class="table table-bordered" >
    <tr class="info bold">
        <td>نام دارو</td>
        <td>قیمت دارو</td>
        <td>نوع دارو</td>
        <td>سهم بیمه گر پایه</td>
        <td>سهم بیمه گر تکمیلی</td>
        <td>عملیات</td>
    </tr>
    @foreach($medicines as $medicine)
        <tr>
            <td style="font-family: tahoma;direction: ltr">{{$medicine->name}}</td>
            <td>{{$medicine->price}}</td>
            <td>{{$medicine->type==1 ?  'فارماکوپه ای' : 'اقلام خارجی'}}</td>
            <td>{{$medicine->first_insure_percent}} %</td>
            <td>@if($medicine-> iran_percent==0)
                    {{'ذکر نشده'}}
                @else
                {{$medicine-> iran_percent}} %
            @endif</td>
            <td><a class="btn btn-info btn-xs" href="{{url('admin/edit-medicine/'.$medicine->id)}}">ویرایش</a></td>
        </tr>
    @endforeach
        
</table>
<div style="direction: ltr">
	{!! $medicines->render() !!}
</div>

    @endsection