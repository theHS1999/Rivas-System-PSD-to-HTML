<!DOCTYPE html>
<html>
<head id="head">

    <title></title>
    <meta charset="UTF-8">
    <script src="{{url('assets/js/jquery.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style.css')}}">
    <style>
        .information tr td {
            padding: 10px;
        }
    </style>
    <script>
       $(document).ready(function(){
            //
        });
    </script>
</head>
<body style="background: none">
<div class="row" style="border-bottom: 5px solid #094F95">
    <div class="col-xs-12" style="background: transparent;text-align: right;font-size: 25px;color:balck;font-family:yekan;font-weight: bold;height:100%;line-height: 90px;">
        <span> <img src="{{url('assets/img/logo.gif')}}" height="100px" style="padding: 5px"></span> سامانه مالی و مکانیزاسیون تأیید نسخ داروخانه
    </div>

</div>
<table class="information" style="width: 100%;">
    <tr>
        <td width="25%">
            <label>بیمار
                :</label>
            {{$insured->fname}} {{$insured->lname}} - کد ملی: {{$insured->melli_code}}
        </td>
        <td width="25%">
            <label> شرکت
                : </label>
            {{App\Insurer::find($insured->insurer_id)->name}}
        </td>
        <td width="25%">
            <label> وابستگی
                :</label>
            {!! $insured->type=='main' ? 'اصلی' : 'وابسته' !!}
        </td>
        <td width="25%">
                <label>وضعیت تکفل
                    :</label>

                {{$insured->sponser_status!='' ? $insured->sponser_status : '-'}}
        </td>
    </tr>
    <tr>
        <td width="25%">
            <label >
                پزشک معالج
                :</label>
            <?php $doc=\App\Doctor::find($pr->doctor); ?>
            {{ $doc->fname.' '.$doc->lname  }}
        </td>
        <td width="25%">
            <label >تخصص
                :</label>
            {{\App\Expertise::find($doc->expertise)->name}}
        </td>
        <td width="25%">
            <label>تاریخ نسخه
                :</label>
            {{$pr->presc_date}}
        </td>
        <td width="25%">
            <label>تاریخ پذیرش
                :</label>
            {{$pr->reception_date}}
           </td>

    </tr>
</table>
<hr>

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

                </tr>

                @foreach($prmeds as $prmed)
                    <tr>
                        <td>{{$prmed->medicine_name}}</td>
                        <td >{{$prmed->count}} ( واحد : {{$prmed->unit}} )</td>
                        <td >هر {{$prmed->m_order}}  {{$prmed->hour}}</td>
                        <td>{{$prmed->medicine_price}}</td>
                        <td>{{$prmed->open_market_price}}</td>
                        <td>{{$prmed->others_difference}}</td>
                        <td>{{$prmed->base_insure}}</td>
                        <td>{{$prmed->iran_pay}}</td>
                        <td>{{$prmed->franshiz}}</td>
                        <td>{{$prmed->total}}
                        </tr>
                @endforeach
            </table>

        </div>

        <hr>
        <div  class="row">
            <div class="col-xs-7 col-xs-offset-1">
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
                            {{$contract->others}}
                        </td>
                        <td>
                           {{$pr->total_others_difference}}
                        </td>
                        <td>
                           {{$pr->total_base_insure}}
                        </td>
                        <td>
                            {{$pr->iran_pay}}
                        </td>
                        <td>
                            {{$pr->total_franshiz}}
                        </td>
                        <td>
                           {{$pr->total}}
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
                        <td>{{$pr->total_specs}}</td>
                        <td>{{$pr->payable}}</td>
                        <td>{{$pr->left_over}}</td>
                        <td> {{$pr->total_insured}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-2 bold">

                <br>
                <br>
                جمع مبالغ فاکتور:
            </div>

        </div>
        <hr>
<button class="btn btn-success" onclick="window.print()"><span class="glyphicon glyphicon-print"></span>چاپ نسخه</button></div>




<style>
            .test{
                border-top-left-radius: 0px;
            }
        </style>
</body>


</html>