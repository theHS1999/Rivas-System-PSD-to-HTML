@extends('master2')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>

        .form-control{
            font-size: 13px;
            padding: 6px 1px;
        }
        input{
            text-align: center;

        }
        .table > tbody > tr > td{
            padding: 2px;
            vertical-align: middle;
        }
        .test{
            border-bottom-left-radius: 4px;
        }
    </style>
    <ol class="breadcrumb" >
        <li class="active bold">ویرایش نسخه</li>
        <li ><a href="{{url('center/prescs')}}">مدیریت نسخه ها</a></li>
        <li ><a href="{{url('center')}}">صفحه اصلی</a></li>
    </ol>


    {!! Form::open(['autocomplete'=>'off']) !!}
   <!-- <div class="row bold ">
        <div class="col-xs-4 " style="float:right " >
            <input type="text" class="form-control " id="insured" onkeydown="down()" placeholder="نام بیمار - کد ملی" value="{{$insured->fname.' '.$insured->lname.' - کدملی :'.$insured->melli_code}}">
            <input type="text" id="insured_id" name="person" value="{{$insured->id}}" hidden>
            <div class="dropdown" id="dropsearch">
            <span class=" dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            </span>
                <ul class="dropdown-menu search-result" aria-labelledby="dropdownMenu1" id="result" style="width: 100%;">
                </ul>
            </div>

        </div>
        <div class="form-group col-xs-4 bold">
            <input id="doctor" type="text" class="form-control" style="" placeholder=" پزشک معالج " onkeydown="find_doc()" value="{{$doctor->fname.' '.$doctor->lname.' - کد نظام پزشکی :'.$doctor->medical_code}}">
            <input type="text" id="doctor_id" name="doctor" value="{{$presc->doctor}}" hidden>
            <div class="dropdown " id="dropdoc">
                <span class=" dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                </span>
                <ul class="dropdown-menu search-result" id="doctor-result" style="width: 100%;">
                </ul>
            </div>
        </div>

        <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
        <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
        <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
        <div class="form-group col-xs-4" style="direction: ltr">

            <div class="input-group col-xs-12" style="padding: 0px">
            <span class="input-group-btn">
                <button id="date_btn" class="btn btn-default" type="button" style="">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </button>
            </span>
                <input id="date_input" type="text" name="presc_date" class="form-control" style="text-align: center" placeholder="تاریخ نسخه" value="{{$presc->presc_date}}">
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


    </div>
    <div class="row bold text-right">
        <div id="company" class="col-sm-3 " style="float: right;line-height: 35px">
            شرکت
            : {{$insurer->name}}</div>
        <div id="type" class="col-sm-3 " style="float: right;line-height: 35px">
            وابستگی
            : {{$insured->type=='main' ? 'اصلی' : 'وابسته'}}</div>
        <div id="sponser" class="col-sm-3 " style="float: right;line-height: 35px">
            وضعیت تکفل
            : {{$insured->sponser_status!='' ? $insured->sponser_status : '-'}}</div>

        <div id="expertise" class=" col-xs-3  bold" style="float: right;line-height: 35px">تخصص پزشک
            : {{$doctorexp->name}}</div>

    </div>

    <hr>
    <div class="row">
        <div class="col-sm-6">موجودی داروی بیمار :
            <div class="col-sm-12" id="meds1"></div>
        </div>
        <div class="col-sm-4">کاربری های خاص :
            <div class="col-sm-12 text-center" id="contarct_specs">
            </div>
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                نسخ بیمار
            </button>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">نسخ بیمار</h4>
                        </div>
                        <div class="modal-body" >
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr class="info bold">
                                    <td>#</td>
                                    <td>نسخه</td>
                                    <td>تاریخ نسخه</td>
                                </tr>
                                </thead>
                                <tbody id="prescs">

                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>-->
    <div class="row bold ">
        <div class="col-xs-4 " style="float:right " >
            <input type="text" class="form-control " id="insured" onkeydown="down()" placeholder="نام بیمار - کد ملی">
            <input type="text" id="insured_id" name="person" value="{{$presc->insured_id}}" hidden>
            <div class="dropdown" id="dropsearch">
            <span class=" dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            </span>
                <ul class="dropdown-menu search-result" aria-labelledby="dropdownMenu1" id="result" style="width: 100%;">
                </ul>
            </div>

        </div>
        <div class="form-group col-xs-4 bold">
            <input id="doctor" type="text" class="form-control" style="" placeholder=" پزشک معالج " onkeydown="find_doc()">
            <input type="text" id="doctor_id" name="doctor" value="{{$presc->doctor}}" hidden>
            <div class="dropdown " id="dropdoc">
                <span class=" dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                </span>
                <ul class="dropdown-menu search-result" id="doctor-result" style="width: 100%;">
                </ul>
            </div>
        </div>

        <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
        <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
        <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
        <div class="form-group col-xs-4" style="direction: ltr">

            <div class="input-group col-xs-12" style="padding: 0px">
            <span class="input-group-btn">
                <button id="date_btn" class="btn btn-default" type="button" style="">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </button>
            </span>
                <input id="date_input" type="text" name="presc_date" class="form-control" style="text-align: center" placeholder="تاریخ نسخه">
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


    </div>
    <div class="row bold text-right">
        <div id="company" class="col-sm-3 " style="float: right;line-height: 35px">
            شرکت
            :</div>
        <div id="type" class="col-sm-3 " style="float: right;line-height: 35px">
            وابستگی
            :</div>
        <div id="sponser" class="col-sm-3 " style="float: right;line-height: 35px">
            وضعیت تکفل
            :</div>

        <div id="expertise" class=" col-xs-3  bold" style="float: right;line-height: 35px">تخصص پزشک  :</div>

    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6">موجودی داروی بیمار :
            <div class="col-sm-12" id="meds1"></div>
        </div>
        <div class="col-sm-4">کاربری های خاص :
            <input type="text" name="spec_en" id="spec_en" value="{{count($contract_specs)>0 ? '1' : '0'}}" hidden>
            <div class="col-sm-12 text-center" id="contarct_specs">
                @foreach($contract_specs as $contract_spec)
                    <label style="padding: 0px 5px">{{$contract_spec->name}} - اعتبار باقیمانده :<span class={{$contract_spec->id}}>{{$contract_spec->value}}</span><input type="radio" name="insured_spec" value="{{$contract_spec->id}}" class="text-center hi" {{$presc->spec_id==$contract_spec->id ? 'checked' : ''}}> </label>
                @endforeach
            </div>
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                نسخ بیمار
            </button>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">نسخ بیمار</h4>
                        </div>
                        <div class="modal-body" >
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr class="info bold">
                                    <td>#</td>
                                    <td>نسخه</td>
                                    <td>تاریخ نسخه</td>
                                </tr>
                                </thead>
                                <tbody id="prescs">

                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<div class="row">
<div class="border-respomsive">
    <table id="table" class="table table-bordered" style="font-size: 12px">
        <tr class="info bold">
            <td class="col-sm-2">نام دارو</td>
            <td class="col-sm-1">تعداد</td>
            <td class="col-sm-1">دستور مصرف</td>
            <td class="col-sm-1">قیمت دارو</td>
            <td class="col-sm-1">قیمت بازار آزاد</td>
            <td class="col-sm-1">تفاوت و سایر</td>
            <td class="col-sm-1">سهم بیمه گر پایه</td>
            <td class="col-sm-1">سهم بیمه ایران</td>
            <td class="col-sm-1">سهم بیمار</td>
            <td class="col-sm-1">مبلغ کل</td>
            <td class="col-sm-1">حذف</td>
        </tr>
        <?php $i=1;
        $darooha=[];
        ?>
        @foreach($prescmeds as $prescmed)
            <tr>
                <td>
                    <input name="number[{{$i}}]" type="text" value="1" hidden/>
                    <input name="med_spec[{{$i}}]" id="med_spec{{$i}}" type="text" value="0" hidden/>
                    <input id="medicineid{{$i}}" name="medicine[{{$i}}]" type="text" value="{{$prescmed->medicine_id}}" hidden/>
                    <input type="text" name="medicine_name[{{$i}}]"  class="form-control" id="medicine{{$i}}" value="{{$prescmed->medicine_name}}" onkeydown="find_med({{$i}})" >
                    <div class="dropdown" id="dropsearch{{$i}}">
                    <span class=" dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    </span>
                        <ul class="dropdown-menu search-result" id="med_find_result{{$i}}"  style="width: 100%;">
                        </ul>
                    </div>
                    <div id="medicine_spec{{$i}}">

                    </div>
                </td>
                <td ><input style="border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;border-bottom: none" value="{{$prescmed->count}}" name="count[{{$i}}]" class="form-control" id="count{{$i}}" onchange="myChange({{$i}})" >
                    <div class="input-group " style="direction: ltr"><input value="{{$prescmed->unit}}" name="unit[{{$i}}]" class="form-control myinput" id="unit{{$i}}"  ><div  class="input-group-addon" style="border-top-right-radius: 0px" >واحد</div></div></td>
                <td ><div class="input-group " style="direction: ltr">
                        <input  value="{{$prescmed->m_order}}" name="order_per_hour[{{$i}}]" class="form-control" id="order_per_hour{{$i}}" style="border-bottom: none;border-bottom-left-radius:0px" >
                        <select  name="hour[{{$i}}]" class="form-control"  style="border-bottom-left-radius: 4px" >

                            <option value='ساعت' @if($prescmed->hour=='ساعت') selected @endif>ساعت</option>
                            <option value='روز' @if($prescmed->hour=='روز') selected @endif>روز</option>

                        </select>
                        <div  class="input-group-addon" >هر</div>
                    </div>
                </td>
                <td><input value="{{$prescmed->medicine_price}}" name="price[{{$i}}]" class="form-control" id="price{{$i}}" ></td>
                <td><input value="{{$prescmed->open_market_price}}" name="open_market[{{$i}}]" class="form-control" id="open_market{{$i}}" onchange="open_market({{$i}})" ></td>
                <td><input value="{{$prescmed->others_difference}}" name="others[{{$i}}]" class="form-control" id="others{{$i}}" onchange="others({{$i}})" onchange="calculate_sum()"></td>

                <td >
                        <div  class="input-group-addon myaddon" id="fp{{$i}}">%{{$prescmed->base_insure!==0 & $prescmed->total!=0 ? round($prescmed->base_insure/$prescmed->total*100) : 0 }}</div>
                        <input value="{{$prescmed->base_insure}}" name="first_insure_percent[{{$i}}]" class="form-control myinput" id="first_insure_percent{{$i}}" onchange="calculate_sum()">


                </td>
                <td >   <div  class="input-group-addon myaddon" id="ip{{$i}}">%{{$prescmed->iran_pay!==0 & $prescmed->total!=0 ? round($prescmed->iran_pay/$prescmed->total*100) : 0}}</div>
                        <input value="{{$prescmed->iran_pay}}" name="iran_percent[{{$i}}]" class="form-control myinput" id="iran_percent{{$i}}" onchange="calculate_sum()" >


                </td>
                <td >   <div  class="input-group-addon myaddon" id="pp{{$i}}">%{{$prescmed->franshiz!==0 & $prescmed->total!=0 ? round($prescmed->franshiz/$prescmed->total*100) : 0}}</div>
                        <input value="{{$prescmed->franshiz}}" name="franshiz[{{$i}}]" class="form-control myinput" id="franshiz{{$i}}" onchange="calculate_sum()">


                </td>
                <td> <input value="{{$prescmed->total}}" name="total[{{$i}}]" class="form-control" id="total{{$i}}" onchange="calculate_sum()" >
                <td> @if($i>1) <a class='btn btn-danger btn-xs remove_field' ><span class="glyphicon glyphicon-remove-sign"></span>حذف</a> @endif </td>

            </tr>

            <?php
            $daroo= ['id'=>$i , 'selected'=>1]  ;
               array_push($darooha,$daroo);
            $i++;?>

        @endforeach


    </table>

</div>
</div>
   <div class="row">
        <div class="col-sm-12">
            <a class="add_row btn btn-info btn-xs" style="margin-top: 5px;float: right" >
                <span class="glyphicon glyphicon-plus-sign"></span>فیلد اضافه</a>
        </div>
    </div>
    <hr>
    <div  class="row">
        <div class="col-xs-6 col-xs-offset-1">
            <table class="table table-bordered " >
                <tr class="active bold">
                    <td class="col-xs-1">تفاوت و سایر بر عهده</td>
                    <td class="col-xs-1">تفاوت و سایر</td>
                    <td class="col-xs-1">سهم بیمه گر پایه</td>
                    <td class="col-xs-1">سهم بیمه ایران</td>
                    <td class="col-xs-1">سهم بیمار</td>
                    <td class="col-xs-1">جمع کل</td>

                </tr>
                <tr>
                    <td class="active" id="ohde">

                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_others" name="presc_others" value="{{$presc->total_others_difference}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_first" name="presc_first" value="{{$presc->total_base_insure}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_iran" name="presc_iran" value="{{$presc->iran_pay}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_insured" name="presc_insured" value="{{$presc->total_franshiz}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_total" name="presc_total" value="{{$presc->total}}">
                    </td>

                </tr>
            </table> <br>
            <table class="table table-bordered " >
                <tr class="active bold">
                    <td class="col-xs-1 bold active">مبلغ کل دارو ها با کاربری خاص</td>
                    <td class="col-xs-1 bold active">مبلغ قابل پرداخت از قرارداد</td>
                    <td class="col-xs-1 bold active">باقیمانده از مبلغ کل دارو ها با کاربری خاص</td>
                    <td class="col-xs-1 bold info">کل مبلغ قابل پرداخت توسط بیمار</td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" id="total_specs" name="total_specs" value="{{$presc->total_specs}}" readonly></td>
                    <td><input type="text" class="form-control" id="payable" name="payable" value="{{$presc->payable}}" readonly></td>
                    <td><input type="text" class="form-control" id="left_over" name="left_over" value="{{$presc->left_over}}" readonly></td>
                    <td><input type="text" class="form-control" id="total_insured" name="total_insured" value="{{$presc->total_insured}}" readonly></td>
                </tr>
            </table>
        </div>



        <div class="col-xs-1 bold">

            جمع مبالغ :
        </div>

        </div>


    </div>
    <hr>


    <div class="row">

        <div class="form-group col-sm-12" style="text-align: center">
            <button  type="submit" name="submit" value="ثبت نسخه" class="btn btn-primary" ><span class="glyphicon glyphicon-ok"></span>ثبت نسخه</button>
            <button  type="submit" name="submit" value="رد نسخه" class="btn btn-danger" ><span class="glyphicon glyphicon-remove"></span>رد نسخه</button>
        </div>

    </div>





    {!! Form::close() !!}


@stop
@section('footer')
    <script type="text/javascript">

        var contract_f=30;
        var permision=1;
        var hike=<?php echo $i?>;
        var daroha=<?php echo json_encode($darooha, JSON_PRETTY_PRINT) ?>;


    </script>
    <script src="{{url('assets/js/presc1.js')}}"></script>
    <script>
        selectInsuredexp(<?php echo $insured->id?>);
        selectDoc(<?php echo $presc->doctor?>);
daroha.forEach(function(d){
    getspecs($('#medicineid'+ d.id).val(),$('#doctor_id').val(), d.id)
})
    </script>

    <style>
        .test{
            border-top-left-radius: 0px;
        }
    </style>
@stop