@extends('master2')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .form-control{
        text-align: center;
    }
    .table > tbody > tr > td{
        padding: 2px;
         vertical-align: middle;
    }
   .test{
       border-bottom-left-radius: 4px;
   }
    label{
        float: none;
    }
</style>
<ol class="breadcrumb" >
    <li class="active">نسخه جدید</li>
    <li ><a href="{{url('center')}}">صفحه اصلی</a></li>
</ol>
{!! Form::open(['autocomplete'=>'off']) !!}
<div class="row bold ">
    <div class="col-xs-4 " style="float:right " >
        <input type="text" class="form-control " id="insured" onkeydown="down()" placeholder="نام بیمار - کد ملی">
        <input type="text" id="insured_id" name="person" value="0" hidden>
        <div class="dropdown" id="dropsearch">
            <span class=" dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            </span>
            <ul class="dropdown-menu search-result" aria-labelledby="dropdownMenu1" id="result" style="width: 100%;">
            </ul>
        </div>

    </div>
    <div class="form-group col-xs-4 bold">
        <input id="doctor" type="text" class="form-control" style="" placeholder=" پزشک معالج " onkeydown="find_doc()">
        <input type="text" id="doctor_id" name="doctor" value="0" hidden>
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
        <input type="text" name="spec_en" id="spec_en" value="0" hidden>
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

</div>
<div class="row">
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
            <tr>
                <td>
                    <input name="number[1]" type="text" value="1" hidden/>
                    <input name="med_spec[1]" id="med_spec1" type="text" value="0" hidden/>
                    <input id="medicineid1" name="medicine[1]" type="text" value="0" hidden/>
                    <input type="text"  class="form-control" name="medicine_name[1]" id="medicine1" onkeydown="find_med(1)" >
                    <div class="dropdown" id="dropsearch1">
                    <span class=" dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    </span>
                        <ul class="dropdown-menu search-result" id="med_find_result1"  style="width: 100%;">
                        </ul>
                    </div>
                    <div id="medicine_spec1">

                    </div>
                </td>
                <td ><input name="count[1]" class="form-control" id="count1" style="border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;border-bottom: none" onchange="myChange(1)" >
                	<div class="input-group " style="direction: ltr"><input name="unit[1]" class="form-control myinput" id="unit1"  >
                        <div  class="input-group-addon" style="border-top-right-radius: 0px">واحد</div></div></td>
                <td ><div class="input-group " style="direction: ltr">
                        <input name="order_per_hour[1]" class="form-control" id="order_per_hour1" style="border-bottom: none;border-bottom-left-radius:0px" >
                        <select name="hour[1]" class="form-control"  style="border-bottom-left-radius: 4px" >

                            <option value='ساعت'>ساعت</option>
                            <option value='روز'>روز</option>

                        </select>
                        <div  class="input-group-addon" >هر</div>
                    </div>
                    </td>
                <td><input name="price[1]" class="form-control" id="price1" ></td>
                <td><input name="open_market[1]" class="form-control" id="open_market1" onchange="open_market(1)" ></td>
                <td><input name="others[1]" class="form-control" id="others1" onchange="others(1)"></td>

                <td >
                        <div  class="input-group-addon myaddon " id="fp1">%0</div>
                        <input name="first_insure_percent[1]" class="form-control myinput" id="first_insure_percent1" onchange="calculate_sum()">
                </td>
                <td ><div  class="input-group-addon myaddon" id="ip1">%0</div>
                        <input name="iran_percent[1]" class="form-control myinput" id="iran_percent1" onchange="calculate_sum()">
                </td>
                <td ><div  class="input-group-addon myaddon" id="pp1">%0</div>
                        <input name="franshiz[1]" class="form-control myinput" id="franshiz1" onchange="calculate_sum()">
                </td>
                <td> <input name="total[1]" class="form-control" id="total1" onchange="calculate_sum()">
                <td>-</td>
            </tr>
        </table>
</div>
<div class="row">
    <div class=" col-sm-12">
        <a class="add_row btn btn-info btn-xs" style="float: right;margin-top: 5px" > <span class="glyphicon glyphicon-plus-sign"></span>افزودن ردیف</a>
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
                <input type="text" class="form-control" id="presc_others" name="presc_others" value="0">
            </td>
            <td>
                <input type="text" class="form-control" id="presc_first" name="presc_first" value="0">
            </td>
            <td>
                <input type="text" class="form-control" id="presc_iran" name="presc_iran" value="0">
            </td>
            <td>
                <input type="text" class="form-control" id="presc_insured" name="presc_insured" value="0">
            </td>
            <td>
                <input type="text" class="form-control" id="presc_total" name="presc_total" value="0">
            </td>

        </tr>
    </table>
        <br>
    <table class="table table-bordered " >
            <tr class="active bold">
                <td class="col-xs-1 bold active">مبلغ کل دارو ها با کاربری خاص</td>
                <td class="col-xs-1 bold active">مبلغ قابل پرداخت از قرارداد</td>
                <td class="col-xs-1 bold active">باقیمانده از مبلغ کل دارو ها با کاربری خاص</td>
                <td class="col-xs-1 bold info">کل مبلغ قابل پرداخت توسط بیمار</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" id="total_specs" name="total_specs" value="0" readonly></td>
                <td><input type="text" class="form-control" id="payable" name="payable" value="0" readonly></td>
                <td><input type="text" class="form-control" id="left_over" name="left_over" value="0" readonly></td>
                <td><input type="text" class="form-control" id="total_insured" name="total_insured" value="0" readonly></td>
            </tr>
        </table>
    </div>



    <div class="col-xs-1 bold">

        جمع مبالغ :
    </div>

</div>

<hr>
<div class="row">
    <label class="checkbox-inline" style="direction: ltr;float: none">
        <input type="checkbox" id="check" > نسخه از طرف بیمه گر پایه مورد تایید می باشد<span class="glyphicon glyphicon-exclamation-sign text-danger "></span>
    </label>
    <br>
    <br>

</div>
<div class="row">
    <div class="form-group col-sm-5" style="text-align: left">
        <button id="send"  type="submit" name="submit" value="ثبت نسخه" class="btn btn-success" ><span class="glyphicon glyphicon-ok"></span>ثبت نسخه</button>
    </div>
    <div class="form-group col-sm-2" style="text-align: center">
        <button id="expert"  type="submit" name="submit" value="ارسال به کارشناس" class="btn btn-primary expire" ><span class="glyphicon glyphicon-circle-arrow-left"></span>ارسال به کارشناس</button>
    </div>
    <div class="form-group col-sm-5" style="text-align: right">
        <button id="ticket"  type="submit" name="submit" value="تیکت دستی" class="btn btn-warning expire" ><span class="glyphicon glyphicon-list-alt"></span>تیکت دستی</button>
    </div>
</div>
<br>
    {!! Form::close() !!}


@stop
@section('footer')
    <script type="text/javascript">
        var setting= <?php echo json_encode($setting, JSON_PRETTY_PRINT) ?>;
        var contract_f=30;
        var permision=1;
        var hike=2;
        var daroha=[{id: 1,selected: 0}];
        var drug_list=0;
    </script>
    <script src="{{url('assets/js/presc.js')}}"></script>
    <script src="{{url('assets/js/search.js')}}"></script>
<style>
    .test{
        border-top-left-radius: 0px;
    }

</style>
@stop
