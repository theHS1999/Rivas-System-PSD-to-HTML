@extends('master')
@section('content')
    <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
    <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
    <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
    <ol class="breadcrumb" >
        <li class="active bold">نمایش اطلاعات بیمه گذار {{$insurer->name}}</li>
        <li><a href="{{url('admin/insurers')}}">مدیریت بیمه گذاران و بیمه شدگان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
<div class=" row">
    @include('errors.form')
    <div class="col-sm-7">

        <div class="panel panel-primary " >

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-th-list"></span> لیست بیمه شدگان</h3>
            </div>
            <div class="panel-body" style="height: 380px;overflow-y: scroll">

                <a class="btn btn-primary btn-xs" href="{{url('admin/new-insured/'.$insurer->id.'/no')}}">
                    <span class="glyphicon glyphicon-plus"></span>
                    افزودن بیمه شده جدید
                </a>
                <a class="btn btn-primary btn-xs" href="{{url('admin/add-to-insurer/'.$insurer->id.'/no')}}">
                    <span class="glyphicon glyphicon-plus-sign"></span>
                    افزودن بیمه شده به بیمه گذار
                </a>
                <br><br>
                <table class="table table-bordered" >
                    <tr class="info">
                        <td >نام بیمه شده</td>
                        <td >شماره ملی</td>
                        <td >شماره قرارداد</td>
                        <td>عملیات</td>

                    </tr>
                    @foreach($insureds as $insured)
                        <tr>
                            <td ><a href="{{url('admin/show-insured/'.$insured->id)}}">{{$insured->fname.' '.$insured->lname }}</a></td>
                            <td >{{$insured->melli_code}}</td>
                            <td >@if($insured->contract_id!=0)
                                    <a href="{{url('admin/show-contract/'.$insured->contract_id)}}">{{App\Contract::find($insured->contract_id)->contract_num}}</a>
                                @else
                                     بدون قرارداد
                                    @endif</td>
                            <td>
                                <a href="{{url('admin/show-insured/'.$insured->id)}}" data-toggle="tooltip" data-placement="top" title=" نمایش جزئیات"><span class="glyphicon glyphicon-list-alt text-info"></span></a>
                                <a data-toggle="modal" data-target="#myModal{{$insured->id}}"><span class="glyphicon glyphicon-remove text-danger"  data-toggle="tooltip" data-placement="top" title="حذف از بیمه گذار"></span></a>
                                <div class="modal fade" id="myModal{{$insured->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"> توجه
                                                    !</h4>
                                            </div>
                                            <div class="modal-body">
                                                آیا از حذف مطمئن هستید؟
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">خیر</button>
                                                <a href="{{url('admin/remove-from-insurer/'.$insured->id)}}" type="button" class="btn btn-danger">حذف</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach


                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-5 " >

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>اطلاعات بیمه گذار</h3>
            </div>
            <div class="panel-body" style="height: 380px;overflow-y: scroll; ">
                <a href="{{url('admin/edit-insurer/'.$insurer->id)}}" class="btn btn-primary btn-xs"> <span class="glyphicon glyphicon-edit"></span>  ویرایش</a>
               <br>
                <br>
                <table class="table table-bordered">
                    <tr>
                        <td class="info col-xs-5">نام بیمه گذار</td>
                        <td>{{$insurer->name}}</td>
                    </tr>
                    <tr>
                        <td class="info">آدرس</td>
                        <td>{{$insurer->address}}</td>
                    </tr>
                    <tr>
                        <td class="info">شماره تلفن</td>
                        <td>{{$insurer->phone}}</td>
                    </tr>
                    <tr>
                        <td class="info">همراه</td>
                        <td>{{$insurer->mobile}}</td>
                    </tr>
                    <tr>
                        <td class="info">فکس</td>
                        <td>{{$insurer->fax}}</td>
                    </tr>
                    <tr>
                        <td class="info">وب سایت</td>
                        <td>{{$insurer->website}}</td>
                    </tr>
                    <tr>
                        <td class="info">پست الکترونیکی</td>
                        <td>{{$insurer->mail}}</td>
                    </tr>
                    <tr>
                        <td class="info">مسئول واحد درمانی</td>
                        <td>{{$insurer->treatment_name}}</td>
                    </tr>
                </table>
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
    <div class="col-sm-12" >
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-th-list"></span> لیست قردادها</h3>
            </div>
            <div class="panel-body">
                <a class="btn btn-primary btn-xs" href="{{url('admin/new-contract')}}">
                    <span class="glyphicon glyphicon-briefcase"></span>
                    ایجاد قرارداد جدید
                </a>
                <br><br>
                <table class="table table-bordered">
                    <tr class="info bold">
                        <td>شماره قرارداد</td>
                        <td>بیمه گزار</td>
                        <td>تاریخ شروع قرارداد</td>
                        <td>تاریخ پایان قرارداد</td>
                        <td>عملیات</td>

                    </tr>
                    @foreach($contracts as $contract)
                        <tr>
                            <td><a href="{{url('admin/show-contract/'.$contract->id)}}">
                                {{$contract->contract_num}} </a></td>
                            <td>
                                {{\App\Insurer::find($contract->insurer_id)->name}}
                            </td>
                            <td>
                                {{$contract->start_date}}
                            </td> <td>
                                {{$contract->finish_date}}
                            </td>
                            <td>
                                <a href="{{url('admin/show-contract/'.$contract->id)}}" class="btn btn-info btn-xs" ><span class="glyphicon glyphicon-list "></span>نمایش جزئیات</a>
                                <a href="{{url('admin/add-to-contract/'.$contract->id.'/no')}}" class="btn btn-info btn-xs" ><span class="glyphicon glyphicon-plus-sign "></span>افزودن فرد به قرارداد</a>

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