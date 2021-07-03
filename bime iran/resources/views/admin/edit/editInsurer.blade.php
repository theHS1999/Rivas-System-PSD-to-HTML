@extends('master')

@section('content')

    <ol class="breadcrumb" >
        <li class="active bold">ویرایش بیمه گذار</li>
        <li ><a href="{{url('admin/insurers')}}"> مدیریت بیمه گذاران و بیمه شدگان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    <div class="col-sm-8 col-sm-offset-2" >
        @include('errors.form')
        {!! Form::open(['action'=>['AdminController@postEditInsurer',$insurer->id]]) !!}
        <div class="row">
            <div class="form-group col-xs-6 ">
                <label>نام </label>

                {!!Form::text('name',$insurer->name,['class'=>'form-control ','requird'])!!}

            </div>
            <div class="form-group col-xs-6">
                <label>آدرس </label>


                {!!Form::text('address',$insurer->address,['class'=>'form-control','requird'])!!}

            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-6">
                <label> تلفن ثابت </label>


                {!!Form::text('phone',$insurer->phone,['class'=>'form-control','requird'])!!}
            </div>
            <div class="form-group col-xs-6">
                <label> شماره موبایل </label>

                {!!Form::text('mobile',$insurer->mobile,['class'=>'form-control','requird'])!!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-6">
                <label> ایمیل </label>
                {!!Form::text('mail',$insurer->mail,['class'=>'form-control','requird'])!!}
            </div>
            <div class="form-group col-xs-6">
                {!! Form::label('fax','فکس') !!}
                {!!Form::text('fax',$insurer->fax,['class'=>'form-control'])!!}
            </div>

        </div>
        <div class="row">
            <div class="form-group col-xs-6">
                {!! Form::label('website','وبسایت') !!}
                {!!Form::text('website',$insurer->website,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-6" >

                <label> مسئول واحد درمانی </label>


                {!!Form::text('treatment_name',$insurer->treatment_name,['class'=>'form-control','requird'])!!}
            </div>

        </div>
        <div class="form-group col-xs-12">

            <button type="submit" class="btn btn-primary">
                ویرایش
            </button>

        </div>
        {!! Form::close() !!}

    </div>
    <link rel="stylesheet" href="{{url('assets/css/formValidation.min.css')}}">
    <script src="{{url('assets/js/formValidation.min.js')}}"></script>
    <script src="{{url('assets/js/fr/bootstrap.min.js')}}"></script>
    <script src="{{url('assets/js/fa_IR.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('form').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                locale: 'fa_IR'

            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('.select1').select2({
            });
        });
    </script>


@endsection
