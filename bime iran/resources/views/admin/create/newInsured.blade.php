@extends('master')
@section('content')

    <ol class="breadcrumb" >
        <li class="active bold"> بیمه شده جدید</li>
        <li ><a href="{{url('admin/insureds')}}">مدیریت بیمه شدگان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1" >
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

            پر کردن تمامی فیلدها الزامی است!
        </div>
        @include('errors.form')
        {!! Form::open() !!}
        <div class="row">
            <div class="form-group col-xs-4 ">
                {!! Form::label('insurer_id','شرکت بیمه گذار') !!}
                <select name="insurer_id" class="form-control select2" >
                    <option value="0">نامشخص</option>
                    @foreach($insurers as $insurer)
                        <option value="{{$insurer->id}}" @if($insurer1==$insurer->id) selected @endif>{{$insurer->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xs-4 ">
                {!! Form::label('contract_id','شماره قرارداد') !!}
                <select  name="contract_id" class="form-control select2">
                    <option value="0">نامشخص</option>
                    @foreach($contracts as $contract)
                        <option value="{{$contract->id}}" {{$contract1==$contract->id ? 'selected' : ''}}>{{$contract->contract_num}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('personal_code','کد پرسنلی') !!}
                {!!Form::text('personal_code',null,['class'=>'form-control','required'])!!}
            </div></div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('melli_code','کد ملی') !!}
                {!!Form::text('melli_code',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('fname','نام') !!}
                {!!Form::text('fname',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('lname','نام خانوادگی') !!}
                {!!Form::text('lname',null,['class'=>'form-control'])!!}
            </div></div>
        <div class="row">
            <div class="form-group col-xs-4">
                {!! Form::label('father_name','نام پدر') !!}
                {!!Form::text('father_name',null,['class'=>'form-control'])!!}
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
            </div></div>
        <div class="row"> <div class="form-group col-xs-4">
                {!! Form::label('gender','جنسیت') !!}
                <select name="gender" class="form-control">
                    <option value="مرد">مرد</option>
                    <option value="زن">زن</option>
                </select>
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('marrige_status','وضعیت تاهل') !!}
                <select name="marrige_status" class="form-control">
                    <option value="مجرد">مجرد</option>
                    <option value="متاهل">متاهل</option>
                </select>
            </div>
            <div class="form-group col-xs-4" style="direction: ltr">
                <label>تاریخ استخدام</label>
                <div class="input-group col-xs-12" style="padding: 0px">
            <span class="input-group-btn">
                 <button id="employed_date1" class="btn btn-default" type="button" style="">
                     <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                 </button>
            </span>
                    <input id="employed_date" type="text" name="employed_date" class="form-control" style="text-align: right">
                </div>
            </div>

            <script>
                Calendar.setup({
                    inputField: 'employed_date',
                    button: 'employed_date1',
                    ifFormat: '%Y-%m-%d',
                    dateType: 'jalali',
                });
            </script></div>
        <div class="row"> <div class="form-group col-xs-4">
                {!! Form::label('janbaz_percent','درصد جانبازی') !!}
                {!!Form::text('janbaz_percent',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('status','بیمار خاص') !!}
                <div class="form-control col-xs-12" style="direction: ltr">
                    <label class="radio-inline col-xs-2 col-xs-offset-7">
                        <input type="radio" name="status" id="inlineRadio2" value="معمولی" checked> خیر
                    </label>

                    <label class="radio-inline col-xs-2">
                        <input type="radio" name="status" id="inlineRadio1" value="خاص"> بله
                    </label>
                </div>

            </div>


            <div class="form-group col-xs-4">
                {!! Form::label('base_insure','بیمه پایه') !!}
                <select name="base_insure" class="form-control" >
                    <option value="تأمین اجتماعی">تأمین اجتماعی</option>
                    <option value="بیمه ایران">بیمه ایران</option>
                    <option value=" فاقد بیمه نامه"> فاقد بیمه نامه</option>
                    <option value="سایر">سایر</option>
                </select>
            </div></div>
        <div class="row"> <div class="form-group col-xs-4">
                {!! Form::label('insure_num','شماره دفترجه') !!}
                {!!Form::text('insure_num',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('group','زیرگروه') !!}
                <select name="group" class="form-control">
                    <option value="شاغلین">شاغلین</option>
                    <option value="بازنشستگان">بازنشستگان</option>
                </select>
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('bank','بانک') !!}
                <select name="bank" class="form-control">
                    <option value="ملی">ملی</option>
                    <option value="صادرات">صادرات</option>
                </select>
            </div></div>
        <div class="row"><div class="form-group col-xs-4">
                {!! Form::label('account','شماره حساب') !!}
                {!!Form::text('account',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('phone','شماره تماس') !!}
                {!!Form::text('phone',null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group col-xs-4">
                {!! Form::label('mobile','شماره همراه') !!}
                {!!Form::text('mobile',null,['class'=>'form-control'])!!}
            </div></div>






        <div class="form-group col-xs-12">
                <button type="submit" class="btn btn-primary">
                    ثبت بیمه شده
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
                locale: 'fa_IR',
                fields: {

                   melli_code: {
                        validators: {
                            notEmpty: {}
                        }
                    },
                   fname: {
                        validators: {
                            notEmpty: {}
                        }
                    },
                   lname: {
                        validators: {
                            notEmpty: {}
                        }
                    },
                   father_name: {
                        validators: {
                            notEmpty: {}
                        }
                    },
            insure_num: {
                        validators: {
                            notEmpty: {}
                        }
                    },
                    account: {
                        validators: {
                            notEmpty: {}
                        }
                    },

                    phone: {
                        validators: {
                            notEmpty: {

                            },
                            phone: {
                                country: 'CN',
                                message: 'شماره تلفن معتبر نیست ( مثال: 01234567899 )'
                            }
                        }
                    },
                    mobile: {
                        validators: {

                            notEmpty: {

                            },
                            phone: {
                                country: 'CN',
                                message: 'شماره موبایل معتبر نیست (مثال: 09112223344)'
                            }
                        }
                    },

                }
            });
        });
    </script>
@endsection