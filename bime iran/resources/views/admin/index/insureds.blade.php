@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold"> مدیریت بیمه گذاران و بیمه شدگان</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
        {!! Form::open(['url' => 'admin/search','class'=>'pull-left col-xs-4 s']) !!}
        <style>
            .select2-container--default .select2-selection--single{
                border-bottom-left-radius: 0px;
                border-top-left-radius: 0px;
                text-align: right;
            }
        </style>

        <select class="search" name="search" style="width: 70%">
            <option value=""></option>
            @foreach($insureds as $insured)
                <option value="{{url('admin/show-insured/'.$insured->id)}}">{{$insured->fname}} {{$insured->lname}} - {{$insured->melli_code}}</option>
            @endforeach

        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>

<div class="row">
    <a href="{{action('AdminController@getNewInsured')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد بیمه شده جدید</button></a>
    <a href="{{action('AdminController@getNewPeople')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-link"></span>ایجاد فرد  وابسته جدید</button></a>

</div>

    <br>
    <div class="row">
        <div class="btn-group" role="group" >
            <a href="{{url('admin/insureds/depend')}}"  class="btn btn-default {{$type=='depend' ? 'active' : ''}}">افراد وابسته</a>
            <a href="{{url('admin/insureds/main')}}" class="btn btn-default {{$type=='main' ? 'active' : ''}}">بیمه شدگان</a>
            <a href="{{url('admin/insureds/all')}}" class="btn btn-default {{$type=='all' ? 'active' : ''}}">همه</a>
        </div>
    </div>

    <br/>
    <div class="table-responsive">
    <table class="table table-bordered">
        <tr class="info bold">
        <td>نام</td>
        <td>نام پدر</td>
        <td>کد ملی</td>
        <td>شماره شناسنامه</td>
        <td>عملیات</td>
        </tr>

        @foreach($insureds as $insured)
            <tr>
                <td><a href="{{url('admin/show-insured/'.$insured->id)}}">{{$insured->fname}} {{$insured->lname}}</a></td>
                <td>{{$insured->father_name}}</td>
                <td>{{$insured->melli_code}}</td>
                <td>{{$insured->melli_code}}</td>
                <td>
                    @if($insured->type=='main')
                    <a class="btn btn-info btn-xs" href="{{action('AdminController@getNewPeople',$insured->id)}}">
                        <span class="glyphicon glyphicon-plus-sign"></span> افزودن فرد وابسته
                    </a>
                    <a class="btn btn-info btn-xs" href="{{action('AdminController@getAddToInsurer',['insurer_id'=>'no','$insured_id'=>$insured->id])}}">
                        <span class="glyphicon glyphicon-import"></span> افزودن به بیمه گذار
                    </a>
                    <a class="btn btn-info btn-xs" href="{{url('admin/add-to-contract/no/'.$insured->id)}}">
                        <span class="glyphicon glyphicon-arrow-left"></span> افزودن به قرارداد
                    </a>
                    @endif
                    @if($insured->type=='depend')
                        <a href="{{url('admin/add-to-insured/')}}"></a>
                    @endif
                </td>
            </tr>


        @endforeach
    </table>
    </div>
@endsection