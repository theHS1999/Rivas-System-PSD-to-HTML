@extends('master')
@section('content')

    <ol class="breadcrumb" >
        <li class="active bold">ویرایش بیمه شده</li>
        <li ><a href="{{url('admin/insureds')}}">مدیریت افراد</a></li>

        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" >
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

            پر کردن تمامی فیلدها الزامی است!
        </div>
        @include('errors.form')
        {!! Form::open(['action'=>['AdminController@postEditInsured',$insured->id]]) !!}

        <div class="form-group col-xs-4 ">
            {!! Form::label('insurer_id','شرکت بیمه گذار') !!}
            <select name="insurer_id" class="form-control select2" >
                <option value="0">نامشخص</option>
                @foreach($insurers as $insurer)
                    <option value="{{$insurer->id}}" @if($insured->insurer_id==$insurer->id) selected @endif>{{$insurer->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-xs-4 ">
            {!! Form::label('contract_id','شماره قرارداد') !!}
            <select  name="contract_id" class="form-control select2">
                <option value="0">نامشخص</option>
                @foreach($contracts as $contract)
                    <option value="{{$contract->id}}" {{$insured->contract_id==$contract->id ? 'selected' : ''}}>{{$contract->contract_num}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('personal_code','کد پرسنلی') !!}
            {!!Form::text('personal_code',$insured->personal_code,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('melli_code','کد ملی') !!}
            {!!Form::text('melli_code',$insured->melli_code,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('fname','نام') !!}
            {!!Form::text('fname',$insured->fname,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('lname','نام خانوادگی') !!}
            {!!Form::text('lname',$insured->lname,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('father_name','نام پدر') !!}
            {!!Form::text('father_name',$insured->father_name,['class'=>'form-control','requird'])!!}
        </div>
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
                <input id="date_input" type="text" name="birth_date" class="form-control" style="text-align: right" value="{{$insured->birth_date}}">
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
            {!!Form::text('birth_cert_num',$insured->birth_cert_num,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('gender','جنسیت') !!}
            <select name="gender" class="form-control">
                <option value="مرد" {{$insured->gender=='مرد' ? 'selected' : ''}}>مرد</option>
                <option value="زن" {{$insured->gender=='زن' ? 'selected' : ''}}>زن</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('marrige_status','وضعیت تاهل') !!}
            <select name="marrige_status" class="form-control">
                <option value="مجرد" {{$insured->marrige_status=='مجرد' ? 'selected' : ''}}>مجرد</option>
                <option value="متاهل" {{$insured->marrige_status=='متاهل' ? 'selected' : ''}}>متاهل</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('employed_date','تاریخ استخدام') !!}
            {!!Form::text('employed_date',$insured->employed_date,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('janbaz_percent','درصد جانبازی') !!}
            {!!Form::text('janbaz_percent',$insured->janbaz_percent,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('status','بیمار خاص') !!}
            <select name="status" class="form-control">
                <option value="معمولی" {{$insured->status=='معمولی' ? 'selected' : ''}}>خیر</option>
                <option value="خاص" {{$insured->status=='خاص' ? 'selected' : ''}}>بله</option>
            </select>
        </div>


        <div class="form-group col-xs-4">
            {!! Form::label('base_insure','بیمه پایه') !!}
            <select name="base_insure" class="form-control" >
                <option value="تأمین اجتماعی" {{$insured->base_insure=='تأمین اجتماعی' ? 'selected' : ''}}>تأمین اجتماعی</option>
                <option value="بیمه ایران" {{$insured->base_insure=='بیمه ایران' ? 'selected' : ''}}>بیمه ایران</option>
                <option value="فاقد بیمه نامه" {{$insured->base_insure=='فاقد بیمه نامه' ? 'selected' : ''}}> فاقد بیمه نامه</option>
                <option value="سایر" {{$insured->base_insure=='سایر' ? 'selected' : ''}}>سایر</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('insure_num','شماره دفترجه') !!}
            {!!Form::text('insure_num',$insured->insure_num,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('group','زیرگروه') !!}
            <select name="group" class="form-control">
                <option value="شاغلین" {{$insured->group=='شاغلین' ? 'selected' : ''}}>شاغلین</option>
                <option value="بازنشستگان" {{$insured->group=='بازنشستگان' ? 'selected' : ''}}>بازنشستگان</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('bank','بانک') !!}
            <select name="bank" class="form-control">
                <option value="ملی" {{$insured->bank=='ملی' ? 'selected' : ''}}>ملی</option>
                <option value="صادرات" {{$insured->bank=='صادرات' ? 'selected' : ''}}>صادرات</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('account','شماره حساب') !!}
            {!!Form::text('account',$insured->account,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('phone','شماره تماس') !!}
            {!!Form::text('phone',$insured->phone,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('mobile','شماره همراه') !!}
            {!!Form::text('mobile',$insured->mobile,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary">
                ویرایش
            </button>
        </div>

        {!!Form::close()!!}
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