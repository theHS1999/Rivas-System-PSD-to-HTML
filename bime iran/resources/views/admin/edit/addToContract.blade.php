
@extends('master')

@section('content')
    <ol class="breadcrumb" >

        <li class="active bold">افزودن فرد به قرارداد</li>
        <li ><a href="{{url('admin/insurers')}}"> مدیریت بیمه گذاران و بیمه شدگان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>


    <div class="col-sm-8 col-sm-offset-2" >
        {!! Form::open() !!}
        <div class="form-group col-xs-6">
            {!! Form::label('contract_id','شماره قرداد') !!}
            <select name="contract_id" class="form-control" >
                @foreach($contracts as $contract)
                    <option value="{{$contract->id}}" @if($contract->id==$contract_id) selected @endif>{{$contract->contract_num}}</option>
                    @endforeach

            </select>
        </div>

        <div class="form-group col-xs-6">
            {!! Form::label('insured_id','کد ملی بیمه شده') !!}
            <select name="insured_id" class="form-control" >
                @foreach($insureds as $insured)
                    <option value="{{$insured->id}}" @if($insured->id==$insured_id) selected @endif>{{$insured->melli_code}} - {{$insured->fname}} {{$insured->lname}} </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary">
                ثبت بیمه شده
            </button>
        </div>
        {!! Form::close() !!}
    </div>
@stop