
@extends('master')

@section('content')
    <ol class="breadcrumb" >

        <li class="active bold">افزودن فرد به بیمه گزار</li>
        <li ><a href="{{url('admin/insurers')}}"> مدیریت بیمه گذاران و بیمه شدگان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>


    <div class="col-sm-8 col-sm-offset-2" >
        @include('errors.form')
        {!!Form::open()!!}

        <div class="form-group col-xs-6">

        {!! Form::label('insurer_id','بیمه گزار') !!}
        <select name="insurer_id" class="form-control select2" >
            @foreach($insurers as $insurer)
                <option value="{{$insurer->id}}" @if($insurer->id==$insurer_id) selected @endif>{{$insurer->name}}</option>
            @endforeach

        </select>
    </div>

    <div class="form-group col-xs-6 ">
        {!! Form::label('insured_id','بیمه شده') !!}
        <select name="insured_id" class="form-control select2" >
            @foreach($insureds as $insured)
                <option value="{{$insured->id}}" @if($insured->id==$insured_id) selected @endif>{{$insured->melli_code}} - {{$insured->fname}} {{$insured->lname}} </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-xs-12 ">
        <button type="submit" class="btn btn-primary">
            افزودن به بیمه گزار
        </button>
    </div>
    {!! Form::close() !!}
    </div>
@stop