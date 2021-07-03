@extends('master')
@section('content') <ol class="breadcrumb" >
    <li class="active bold">فرد  وابسته جدید</li>
    <li ><a href="{{url('admin/insureds')}}">مدیریت بیمه شدگان</a></li>
    <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
</ol>
<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" >
    <div class="alert alert-info" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

        پر کردن تمامی فیلدها الزامی است!
    </div>
        {!!Form::open()!!}
<div class="row">
    <div class="form-group col-xs-4">
        {!! Form::label('relation','نسبت با اصلی') !!}
        <select name="relation" class="form-control" >
            <option value="پدر">پدر</option>
            <option value="مادر">مادر</option>
            <option value="خواهر">خواهر</option>
            <option value="برادر">برادر</option>
            <option value="فرزند">فرزند</option>

        </select>
    </div>
    <div class="form-group col-xs-4">
        <label>شخص اصلی</label>

        <select name="insured_id" class="form-control col-xs-12 select1" >
            @foreach($insureds as $insured)
                <option @if($insured->id==$id) selected="selected" @endif value="{{$insured->id}}">{{$insured->fname}} {{$insured->lname}} - کد ملی : {{$insured->melli_code}}</option>
            @endforeach

        </select>
    </div>
    <div class="form-group col-xs-4">

        {!! Form::label('fname','نام') !!}
        {!!Form::text('fname',null,['class'=>'form-control','required'])!!}
    </div>
</div>
<div class="row">
    <div class="form-group col-xs-4">
        {!! Form::label('lname','نام خانوادگی') !!}
        {!!Form::text('lname',null,['class'=>'form-control','required'])!!}
    </div>
    <div class="form-group col-xs-4">
        {!! Form::label('father_name','نام پدر') !!}
        {!!Form::text('father_name',null,['class'=>'form-control','required'])!!}
    </div>
    <div class="form-group col-xs-4">

        {!! Form::label('melli_code','کد ملی') !!}
        {!!Form::text('melli_code',null,['class'=>'form-control','required'])!!}
    </div>
</div>
<div class="row">
    <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
    <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
    <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
    <div class="form-group col-xs-4" style="direction: ltr">
        <label>تاریخ تولد</label>
        <div class="input-group col-xs-12" style="padding: 0px">
                <span class="input-group-btn">
                    <button id="date_btn" class="btn btn-default" type="button" style="">
                        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                    </button>
                </span>
            <input id="date_input" type="text" name="birth_date" class="form-control" style="text-align: right" >
        </div>
    </div>
    <script>
        Calendar.setup({
            inputField: 'date_input',
            button: 'date_btn',
            ifFormat: '%Y-%m-%d',
            dateType: 'jalali',
        });
    </script>
    <div class="form-group col-xs-4">
        {!! Form::label('birth_cert_num','شماره شناسنامه') !!}
        {!!Form::text('birth_cert_num',null,['class'=>'form-control','required'])!!}
    </div>
    <div class="form-group col-xs-4">
        {!! Form::label('gender','جنسیت') !!}
        <select name="gender" class="form-control" >
            <option value="مرد">مرد</option>
            <option value="زن">زن</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-xs-4">
        {!! Form::label('sponser_status','وضعیت تکفل') !!}
        <select name="sponser_status" class="form-control">
            <option value="تحت تکفل">تحت تکفل</option>
            <option value="غیر تحت تکفل">غیر تحت تکفل</option>
        </select>
    </div>
    <div class="form-group col-xs-4">
        {!! Form::label('marrige_status','وضعیت تاهل') !!}
        <select name="marrige_status" class="form-control">
            <option value="مجرد">مجرد</option>
            <option value="متاهل">متاهل</option>
        </select>
    </div>
    <div class="form-group col-xs-4">
        {!! Form::label('base_insure','بیمه گر پایه') !!}
        <select name="base_insure" class="form-control">
            <option value="تأمین اجتماعی">تأمین اجتماعی</option>
            <option value="بیمه ایران">بیمه ایران</option>
            <option value=" فاقد بیمه نامه"> فاقد بیمه نامه</option>
            <option value="سایر">سایر</option>
        </select>
    </div>
</div>
<div class="row">  <div class="form-group col-xs-4">
        {!! Form::label('insure_num','شماره دفترجه') !!}
        {!!Form::text('insure_num',null,['class'=>'form-control','required'])!!}
    </div>
</div>
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary">
                ثبت فرد وابسته
            </button>
        </div>

        {!!Form::close()!!}
    </div>
@endsection
@section('footer')
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