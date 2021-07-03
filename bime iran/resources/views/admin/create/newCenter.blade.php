@extends('master')
@section('content')
    <script>
        $(document).ready(function() {
            $("#insures_under_contract").select2({
                placeholder: "بیمه گر",
                dir:'rtl'
            });
        });
    </script>
    <ol class="breadcrumb" >
        <li class="active bold">مرکز جدید</li>
        <li ><a href="{{url('admin/service-centers')}}"> مدیریت مراکز خدماتی</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    {!! Form::open() !!}
    <div class="col-sm-8 col-sm-offset-2">
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('username','نام کاربری') !!}
                {!!Form::text('username',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('name','نام واقعی') !!}
                {!!Form::text('name',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('pic','تصویر') !!}
                <input type="file" name="image" id="" class="form-control">
            </div></div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('password','کلمه عبور جدید') !!}
                {!!Form::text('password',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('mail','ایمیل') !!}
                {!!Form::text('mail',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('technical_user','نام مسئول فنی') !!}
                {!!Form::text('technical_user',null,['class'=>'form-control','required'])!!}
            </div></div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('sahebe_emtiaz','صاحب امتیاز') !!}
                {!!Form::text('sahebe_emtiaz',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('medical_code','کد نظام پزشکی') !!}

                {!!Form::text('medical_code',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('address','آدرس') !!}
                {!!Form::text('address',null,['class'=>'form-control','required'])!!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('phone','شماره تماس') !!}
                {!!Form::text('phone',null,['class'=>'form-control','required'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('fax','فکس') !!}
                {!!Form::text('fax',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('mobile','موبایل') !!}
                {!!Form::text('mobile',null,['class'=>'form-control','required'])!!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('website','وب سایت') !!}
                {!!Form::text('website',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('shift','شیفت کاری') !!}
                <select name="shift" class="form-control" >
                    <option value="روزانه">روزانه</option>
                    <option value="شبانه روزی">شبانه روزی</option>

                </select>
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('bank','بانک') !!}
                <select name="bank" class="form-control" >
                    <option value="صادرات">صادرات</option>
                    <option value="ملت">ملت</option>

                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('account_num','شماره حساب') !!}
                {!!Form::text('account_num',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-8">
                {!! Form::label('insures_under_contract','بیمه های طرف قرارداد') !!}
                <select class="form-control" id="insures_under_contract" name="insures_under_contract[]"  multiple="multiple" style="width: 100%" required>
                    <option value="تامین اجتماعی"> تامین اجتماعی</option>
                    <option value="خدمات درمانی"> خدمات درمانی</option>
                    <option value="نیروهای مسلح"> نیروهای مسلح</option>
                </select>
            </div>

        </div>






        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary">
                ثبت مرکز جدبد
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
@stop


