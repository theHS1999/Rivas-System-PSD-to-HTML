@extends('master')
@section('content')
    <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
    <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
    <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
    <ol class="breadcrumb" >
        <li class="active bold"> مرکز خدماتی
            {{$center->name}}</li>
        <li ><a href="{{url('admin/service-centers')}}">مدیریت مراکز خدماتی</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-xs-12">
        @include('errors.form')
        <div class="col-sm-12">
            <div class="panel panel-primary " >

                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> اطلاعات مرکز خدماتی
                    </h3>
                </div>
                <div class="panel-body">
                    <a class="btn btn-primary btn-xs" href="{{url('admin/edit-center/'.$center->id)}}">
                        <span class="glyphicon glyphicon-edit"></span>
                        ویرایش
                    </a>
                    <br>
                    <br>
                    <div class="col-xs-4">
                        <table class="table table-bordered">
                            <tr>
                                <td class="info ">وب سایت</td>
                                <td>{{$center->website}}</td>
                            </tr>
                            <tr>
                                <td class="info ">شیفت</td>
                                <td>{{$center->shift}}</td>
                            </tr>
                            <tr>
                                <td class="info ">بیمه های طرف قرارداد</td>
                                <td>{{$center->insures_under_contract}}</td>
                            </tr>
                            <tr>
                                <td class="info ">حساب بانکی</td>
                                <td>{{$center->bank}} - {{$center->account_num}}</td>
                            </tr>
                        </table>
                    </div><div class="col-xs-4">
                        <table class="table table-bordered">
                            <tr>
                                <td class="info ">تلفن</td>
                                <td>{{$center->phone}}</td>
                            </tr>
                            <tr>
                                <td class="info ">موبایل</td>
                                <td>{{$center->mobile}}</td>
                            </tr>
                            <tr>
                                <td class="info ">فکس</td>
                                <td>{{$center->fax}}</td>
                            </tr>
                            <tr>
                                <td class="info ">آدرس</td>
                                <td>{{$center->address}}</td>
                            </tr>
                        </table>
                    </div><div class="col-xs-4">
                        <table class="table table-bordered">
                            <tr>
                                <td class="info ">نام مرکز</td>
                                <td>{{$center->name}}</td>
                            </tr>
                            <tr>
                                <td class="info ">صاحب امتیاز</td>
                                <td>{{$center->sahebe_emtiaz}}</td>
                            </tr>
                            <tr>
                                <td class="info ">کد نظام پزشکی</td>
                                <td>{{$center->medical_code}}</td>
                            </tr>
                            <tr>
                                <td class="info ">مسئول فنی</td>
                                <td>{{$center->technical_user}}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="panel panel-primary " >

                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-signal"></span>گزارشات</h3>
                </div>
                <div class="panel-body">
                    <div class="col-xs-9">
                        <div class="col-xs-6 text-right">
                            <p class="bold text-center">گزارش مبالغ</p>
                            <hr>
                            <p><span class="bold"> کل مبالغ نسخ: </span>{{$allp}} ریال</p>
                            <p><span class="bold">مبالغ نسخ ثبت شده : </span> {{$registredp}} ریال</p>
                            <p><span class="bold">مبالغ نسخ ارسال شده : </span> {{$send_to_expertp}} ریال</p>
                            <p><span class="bold">مبالغ نسخ تیکت دستی: </span> {{$ticketp}} ریال</p>
                        </div>
                        <div class="col-xs-6 text-right">
                            <p  class="bold text-center">گزارشات کمی</p>
                            <hr>
                            <p><span class="bold">تعداد کل نسخ: </span> {{$all}} </p>
                            <p><span class="bold">تعداد نسخ ثبت شده: </span> {{$registred}}</p>
                            <p><span class="bold">تعداد نسخ ارسالی: </span>{{$send_to_expert}}</p>
                            <p><span class="bold">تعداد نسخ تیکت دستی: </span> {{$ticket}}</p>
                        </div>

                    </div>
                    <div class="col-xs-3">
                        {!! Form::open() !!}
                        <div class="form-group col-xs-12">
                            <div class="input-group col-xs-12" style="padding: 0px">
                        <span class="input-group-btn">
                            <button id="start_btn" class="btn btn-default" type="button" style="">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </button>
                        </span>
                                <input id="start_date" type="text" name="start_date" class="form-control" placeholder="از تاریخ" value="{{$startdate }}" >
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="input-group col-xs-12" style="padding: 0px">
                        <span class="input-group-btn">
                            <button id="finish_btn" class="btn btn-default" type="button" style="">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </button>
                        </span>
                                <input id="finish_date" type="text" name="finish_date" class="form-control" placeholder="تا تاریخ"  value="{{ $finishdate }}" >
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <input type="submit" value="فیلتر" class="btn btn-primary">
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="panel panel-primary " >

                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span>نسخ</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr class="info bold">
                            <td>نام بیمار</td>
                            <td>تاریخ نسخه</td>
                            <td>وضعیت</td>
                            <td>عملیات</td>
                        </tr>
                        @foreach($prescs as $presc)
                            <tr>
                                <td>
                                    <?php $insured=App\Insured::find($presc->insured_id) ?>
                                        @if(count($insured)>0) <a href="{{url('admin/show-insured/'.$insured->id)}}" data-toggle="tooltip" data-placement="top" title="نمایش جزئیات بیمه شده">
                                        {{$insured->fname}} {{$insured->lname}}
                                    </a>@endif
                                </td>
                                <td>{{$presc->reception_date}}</td>
                                <td>
                                    @if($presc->status==1)
                                        تایید شده
                                    @endif
                                    @if($presc->status==2 || $presc->status==3)
                                        منتظر تایید
                                    @endif
                                </td>
                                <td>
                                    <a href="{{url('print/'.$presc->id)}}" data-toggle="tooltip" data-placement="top" title="نمایش جزئیات نسخه">
                                        <span class="glyphicon glyphicon-list"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script>
        Calendar.setup({
            inputField: 'start_date',
            button: 'start_btn',
            ifFormat: '%Y-%m-%d',
            dateType: 'jalali',
        });
        Calendar.setup({
            inputField: 'finish_date',
            button: 'finish_btn',
            ifFormat: '%Y-%m-%d',
            dateType: 'jalali',
        });
    </script>
@endsection