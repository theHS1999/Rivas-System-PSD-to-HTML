@extends('master2')
@section('content')

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


    {!! Form::open() !!}
    <div class="row bold">

        <div class="col-sm-3" style="float: right;">
            <select name="person" class="form-control select" id="insured" onchange="company()" style=" ">
                <option></option>
                @foreach($insureds as $insured)
                    <option value='{{$insured->id}}' @if($insured->id==$pr->insured_id)
                    {!! $person=$insured !!}
                    selected @endif
                    >{{$insured->fname}} {{$insured->lname}} - کد ملی: {{$insured->melli_code}}</option>
                @endforeach
            </select>
        </div>
        <div id="company" class="col-sm-3 text-right" style="float: right;line-height: 30px">
            شرکت
            :</div>
        <div id="type" class="col-sm-3 text-right" style="float: right;line-height: 30px">
            وابستگی
            :</div>
        <div id="sponser" class="col-sm-3 text-right" style="float: right;line-height: 30px">
            وضعیت تکفل
            :</div>
    </div>

    <div class="row">


        <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
        <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
        <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
        <div class="form-group col-xs-3" style="direction: ltr">
            <label>:
                تاریخ نسخه</label>
            <div class="input-group col-xs-12" style="padding: 0px">
            <span class="input-group-btn">
                <button id="date_btn" class="btn btn-default" type="button" style="">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </button>
            </span>
                <input id="date_input" type="text" name="presc_date" class="form-control" style="text-align: right" value="{{$pr->presc_date}}">
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
        <div class="form-group col-xs-6">
            <label >
                پزشک معالج
                :</label>
            <select name="doctor_type"  class="form-control select2">
                <option value="گوش و گلو و بيني و جراحي سر و گردن">گوش و گلو و بيني و جراحي سر و گردن</option>
                <option value="گوش، حلق، بيني و حنجره">گوش، حلق، بيني و حنجره</option>
                <option value="آسيب شناسي (پاتولوژي)">آسيب شناسي (پاتولوژي)</option>
                <option value="آسيب شناسي فک و دهان">آسيب شناسي فک و دهان</option>
                <option value="آلرژي، آسم و ايمونولوژي">آلرژي، آسم و ايمونولوژي</option>
                <option value="آلرژي، آسم و ايمونولوژي کودکان">آلرژي، آسم و ايمونولوژي کودکان</option>
                <option value="اپيدميولوژي">اپيدميولوژي</option>
                <option value="اتولوژي و اتونورولوژي">اتولوژي و اتونورولوژي</option>
                <option value="اختلالات حرکتي">اختلالات حرکتي</option>
                <option value="ارتز و پروتز (ارتوپدي فني)">ارتز و پروتز (ارتوپدي فني)</option>
                <option value="ارتودانتيکس (دندانپزشک)">ارتودانتيکس (دندانپزشک)</option>
                <option value="ارتوسرجري">ارتوسرجري</option>
                <option value="استرابيسم و اکولوپلاستيک">استرابيسم و اکولوپلاستيک</option>
                <option value="اعصاب و روان">اعصاب و روان</option>
                <option value="الکتروفيزيولژي باليني قلب">الکتروفيزيولژي باليني قلب</option>
                <option value="ام اس">ام اس</option>
                <option value="اندواورلوژي">اندواورلوژي</option>
                <option value="اندودانتيکس (دندانپزشک)">اندودانتيکس (دندانپزشک)</option>
                <option value="انگل شناسي">انگل شناسي</option>
                <option value="انکولوژي زنان (سرطان)">انکولوژي زنان (سرطان)</option>
                <option value="اوروانکولوژي">اوروانکولوژي</option>
                <option value="اورولوژي (جراحي واريکوسل و پروستات)">اورولوژي (جراحي واريکوسل و پروستات)</option>
                <option value="اورولوژي کودکان">اورولوژي کودکان</option>
                <option value="ايمني شناسي آزمايشگاهي">ايمني شناسي آزمايشگاهي</option>
                <option value="ايمونولوژي و آلرژي باليني">ايمونولوژي و آلرژي باليني</option>
                <option value="ايمونولوژي و آلرژي باليني کودکان">ايمونولوژي و آلرژي باليني کودکان</option>
                <option value="اينترونشنال کارديولوژي">اينترونشنال کارديولوژي</option>
                <option value="اکوکارديوگراف">اکوکارديوگرافي</option>
                <option value="بيماريهاي خون وسرطان کودکان">بيماريهاي خون وسرطان کودکان</option>
                <option value="بيماريهاي دهان و دندان">بيماريهاي دهان و دندان</option>
                <option value="بيماريهاي روماتيسمي">بيماريهاي روماتيسمي</option>
                <option value="بيماريهاي ريه">بيماريهاي ريه</option>
                <option value="بيماريهاي عفوني و گرمسيري">بيماريهاي عفوني و گرمسيري</option>
                <option value="بيماريهاي عفوني کودکان">بيماريهاي عفوني کودکان</option>
                <option value="بيماريهاي قلب وعروق (اينتروينشنال و کارديولوژي)">بيماريهاي قلب وعروق (اينتروينشنال و کارديولوژي)</option>
                <option value="بيماريهاي گوارش وکبد">بيماريهاي گوارش وکبد</option>
                <option value="بيماريهاي کلیه بالغين (نفرولوژي)">بيماريهاي کلیه بالغين (نفرولوژي)</option>
                <option value="بيماريهاي کلیه کودکان (نفرولوژي)">بيماريهاي کلیه کودکان (نفرولوژي)</option>
                <option value="بينايي سنجي">بينايي سنجي</option>
                <option value="بيهوشي">بيهوشي</option>
                <option value="پرتو درماني">پرتو درماني</option>
                <option value="پروتزهاي دنداني (ثابت و متحرک)">پروتزهاي دنداني (ثابت و متحرک)</option>
                <option value="پزشکي اجتماعي">پزشکي اجتماعي</option>
                <option value="پزشکي عمومي">پزشکي عمومي</option>
                <option value="پزشکي فيزيکي و توانبخشي">پزشکي فيزيکي و توانبخشي</option>
                <option value="پزشکي هسته اي">پزشکي هسته اي</option>
                <option value="پوست و مو">پوست و مو</option>
                <option value="پيوند کليه">پيوند کليه</option>
                <option value="تغذيه و رژيم درماني">تغذيه و رژيم درماني</option>
                <option value="جراح استخوان و مفاصل (ارتوپد)">جراح استخوان و مفاصل (ارتوپد)</option>
                <option value="جراح استخوان و مفاصل (ارتوپد) کودکان">جراح استخوان و مفاصل (ارتوپد) کودکان</option>
                <option value="جراح پلاستيک">جراح پلاستيک</option>
                <option value="جراح عمومي">جراح عمومي</option>
                <option value="جراح قلب و عروق">جراح قلب و عروق</option>
                <option value="جراح لثه">جراح لثه</option>
                <option value="جراح لگن و مفصل ران (هيپ)">جراح لگن و مفصل ران (هيپ)</option>
                <option value="جراح مغز و اعصاب">جراح مغز و اعصاب</option>
                <option value="جراح کليه - مجاري ادرار و تناسلي (اورولوژيست)">جراح کليه - مجاري ادرار و تناسلي (اورولوژيست)</option>
                <option value="جراح کليه، مجاري ادرار و تناسلي(اورولوژي کودکان)">جراح کليه، مجاري ادرار و تناسلي(اورولوژي کودکان)</option>
                <option value="جراح کودکان">جراح کودکان</option>
                <option value="جراحي آرتروسکوپي و طب ورزشي">جراحي آرتروسکوپي و طب ورزشي</option>
                <option value="جراحي استخوان و مفاصل ( جراحي دست )">جراحي استخوان و مفاصل ( جراحي دست )</option>
                <option value="جراحي استخوان و مفاصل (ستون فقرات)">جراحي استخوان و مفاصل (ستون فقرات)</option>
                <option value="جراحي اعصاب ستون فقرات">جراحي اعصاب ستون فقرات</option>
                <option value="جراحي پا و مچ پا">جراحي پا و مچ پا</option>
                <option value="جراحي پستان">جراحي پستان</option>
                <option value="جراحي پلاستيک صورت">جراحي پلاستيک صورت</option>
                <option value="جراحي پلاستيک و ترميمي چشم (اکولوپلاستي)">جراحي پلاستيک و ترميمي چشم (اکولوپلاستي)</option>
                <option value="جراحي پلاستيک و ترميمي و سوختگي">جراحي پلاستيک و ترميمي و سوختگي</option>
                <option value="جراحي درون بين ( اندوسکوپيک )">جراحي درون بين ( اندوسکوپيک )</option>
                <option value="جراحي دهان وفک و صورت">جراحي دهان وفک و صورت</option>
                <option value="جراحي روده بزرگ و مقعد (کولورکتال)">جراحي روده بزرگ و مقعد (کولورکتال)</option>
                <option value="جراحي زانو">جراحي زانو</option>
                <option value="جراحي سرطان">جراحي سرطان</option>
                <option value="جراحي شانه">جراحي شانه</option>
                <option value="جراحي قفسه صدري سينه (توراکس)">جراحي قفسه صدري سينه (توراکس)</option>
                <option value="جراحي کليه ( اندويورولوژي و لاپاراسکوپي )">جراحي کليه ( اندويورولوژي و لاپاراسکوپي )</option>
                <option value="چشم پزشک">چشم پزشک</option>
                <option value="چشم پزشک (شبکيه)">چشم پزشک (شبکيه)</option>
                <option value="چشم پزشک (گلوکوم)">چشم پزشک (گلوکوم)</option>
                <option value="چشم کودکان و استرابيسم">چشم کودکان و استرابيسم</option>
                <option value="خون و سرطان بالغين (انکولوژي)">خون و سرطان بالغين (انکولوژي)</option>
                <option value="خون و سرطان کودکان (انکولوژي)">خون و سرطان کودکان (انکولوژي)</option>
                <option value="داخلي">داخلي</option>
                <option value="داروسازي">داروسازي</option>
                <option value="داروشناسي (فارماکولوژي)">داروشناسي (فارماکولوژي)</option>
                <option value="درد">درد</option>
                <option value="درمان ريشه<">درمان ريشه</option>
                <option value="درمان لثه (پريودانتيکس)">درمان لثه (پريودانتيکس)</option>
                <option value="دندانپزشک">دندانپزشک</option>
                <option value="دندانپزشک کودکان">دندانپزشک کودکان</option>
                <option value="دندانپزشکي ترميمي">دندانپزشکي ترميمي</option>
                <option value="راديولوژي دهان و فک وصورت">راديولوژي دهان و فک وصورت</option>
                <option value="راديولوژي و سونوگرافي">راديولوژي و سونوگرافي</option>
                <option value="رشد و تکامل کودکان">رشد و تکامل کودکان</option>
                <option value="روان درماني و روانشناس باليني">روان درماني و روانشناس باليني</option>
                <option value="روانپزشک (روانشناس)">روانپزشک (روانشناس)</option>
                <option value="روانپزشک (روانشناس) کودکان و نوجوانان">روانپزشک (روانشناس) کودکان و نوجوانان</option>
                <option value="روماتولوژيست">روماتولوژيست</option>
                <option value="ريه کودکان">ريه کودکان</option>
                <option value="زنان و زايمان">زنان و زايمان</option>
                <option value="زنان و زايمان (آي.وي.اف و لاپاراسکوپي)">زنان و زايمان (آي.وي.اف و لاپاراسکوپي)</option>
                <option value="زنان و زايمان (پريناتولوژي)">زنان و زايمان (پريناتولوژي)</option>
                <option value="زنان و زايمان (نازايي و آي.وي.اف)">زنان و زايمان (نازايي و آي.وي.اف)</option>
                <option value="ژنتيک پزشکي">ژنتيک پزشکي</option>
                <option value="سالمند شناسي (ژرياتريک)">سالمند شناسي (ژرياتريک)</option>
                <option value="سرطان دستگاه ادراری و تناسلی (اروانکولوژی)">سرطان دستگاه ادراری و تناسلی (اروانکولوژی)</option>
                <option value="سيتوپاتولوژي">سيتوپاتولوژي</option>
                <option value="سکمان قدامي چشم">سکمان قدامي چشم</option>
                <option value="شنوايي سنجي">شنوايي سنجي</option>
                <option value="صرع">صرع</option>
                <option value="ضايعات پوستي (درماتوپاتولوژي)">ضايعات پوستي (درماتوپاتولوژي)</option>
                <option value="طب اورژانس">طب اورژانس</option>
                <option value="طب اورژانس">طب سنتي</option>
                <option value="طب کار">طب کار</option>
                <option value="طب کودکان و پيرامون تولد">طب کودکان و پيرامون تولد</option>
                <option value="علوم آزمايشگاهي">علوم آزمايشگاهي</option>
                <option value="غدد درون ريز و متابوليسم (اندوکرينولوژي)">غدد درون ريز و متابوليسم (اندوکرينولوژي)</option>
                <option value="غدد کودکان">غدد کودکان</option>
                <option value="فيزيوتراپي">فيزيوتراپي</option>
                <option value="قارچ شناسي آزمايشگاهي">قارچ شناسي آزمايشگاهي</option>
                <option value="قرنيه">قرنيه</option>
                <option value="قلب و عروق">قلب و عروق</option>
                <option value="قلب کودکان">قلب کودکان</option>
                <option value="گفتار درماني">گفتار درماني</option>
                <option value="گفتار درماني">c</option>
                <option value="گوارش و کبد کودکان">گوارش و کبد کودکان</option>
                <option value="مامايي">مامايي</option>
                <option value="متابوليک ارثي">متابوليک ارثي</option>
                <option value="مراقبت هاي ويژه (آي سي يو)">مراقبت هاي ويژه (آي سي يو)</option>
                <option value="مشاوره">مشاوره</option>
                <option value="مغز و اعصاب (نورولوژي)">مغز و اعصاب (نورولوژي)</option>
                <option value="مغز و اعصاب کودکان">مغز و اعصاب کودکان</option>
                <option  value="ميکروب شناسي">ميکروب شناسي</option>
                <option value="نازايي و آي وي اف">نازايي و آي وي اف</option>
                <option value="ويتره ورتين سگمان خلفي">ويتره ورتين سگمان خلفي</option>
                <option value="ويروس شناسي">ويروس شناسي</option>
                <option value="کار درماني ( ارگوتراپيست )">کار درماني ( ارگوتراپيست )</option>
                <option value="کايروپراکتيک">کايروپراکتيک</option>
                <option value="کلينيکال انکولوژي(سرطان شناسي و پرتودرماني)">کلينيکال انکولوژي(سرطان شناسي و پرتودرماني)</option>
                <option value="کودکان">کودکان</option></select>

        </div>
        <div class="form-group col-xs-3">
            <label >نام دکتر
                :</label>
            <input type="text" class="form-control" name="doctor_expertise" value="{{$pr->doctor_expertise}}">
        </div>

    </div>

    <div class="row">
        <div class="col-xs-10 col-xs-offset-1" id="prescs" >
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6"  ><div class="bold">موجودی داروی بیمار :</div>
            <div class="col-sm-12" id="meds1"></div>

        </div>
        <div class="col-sm-4" >
            <div class="bold"> داروهای تخصصی:</div>
            <input id="is_drug" type="text" value="1" name="is_drug" hidden>
            <div class="col-sm-12" >
                <table class="table table-bordered" id="drug-list">
                    @if(count($prescspecs)>0)
                        <tr class="active"> <td >داروها</td> <td>مقدار مجاز</td> </tr>
                        @foreach($prescspecs as $prescspec)
                            <tr>
                                <td style="font-size: 12px;font-family: tahoma;direction:ltr">
                                    <input type="text" name="specslist[]" value="{{$prescspec->medicines}}" hidden>{!! $prescspec->medicines !!}
                                </td>
                                <td>
                                    <input class="form-control" name="seil[]" type="text" value="{{$prescspec->value}}">
                                    <input name="spec[]" name="" type="text" value="{{$prescspec->spec_id}}" hidden>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>

        </div>


        <div class="col-sm-2">
            <!-- Button trigger modal -->
            <button type="button" class="taeine_daro btn btn-info btn-xs"  style="margin-top: 5px;float: right">
                <span class="glyphicon glyphicon-tags"></span>  تعیین داروی تخصصی
            </button>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">انتخاب دارو</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr class="info bold">
                                    <td class="col-sm-3">
                                        نام تخصص
                                    </td>
                                    <td>
                                        انتخاب داروها
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control" id="specs">

                                        </select>
                                    </td>
                                    <td id="drugs" style="direction: ltr">

                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                            <button type="button" class="btn btn-primary" onclick="add_drugs()">اضافه کردن به لیست</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
            @foreach($prmeds as $prmed)
                <tr>
                    <td > <input name="number[{{$i}}]" type="text" value="{{$i}}" hidden="hidden" />
                        <select name="medicine[{{$i}}]" class="form-control select1" id="medicine{{$i}}" onchange="myFunction({{$i}})" style="font-family: Tahoma">
                            <option style="font-family: Tahoma"></option>
                            @foreach($medicines as $medicine)
                                <option value='{{$medicine->id}}'
                                        @if($prmed->medicine_id==$medicine->id) selected @endif
                                >{{$medicine->name}}</option>
                            @endforeach
                        </select>

                    </td>
                    <td ><input style="border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;border-bottom: none" value="{{$prmed->count}}" name="count[{{$i}}]" class="form-control" id="count{{$i}}" onchange="myChange({{$i}})" >
                        <div class="input-group " style="direction: ltr"><input value="{{$prmed->unit}}" name="unit[{{$i}}]" class="form-control myinput" id="unit{{$i}}"  ><div  class="input-group-addon" style="border-top-right-radius: 0px" >واحد</div></div></td>
                    <td ><div class="input-group " style="direction: ltr">
                            <input  value="{{$prmed->m_order}}" name="order_per_hour[{{$i}}]" class="form-control" id="order_per_hour{{$i}}" style="border-bottom: none;border-bottom-left-radius:0px" >
                            <select  name="hour[{{$i}}]" class="form-control"  style="border-bottom-left-radius: 4px" >

                                <option value='ساعت' @if($prmed->hour=='ساعت') selected @endif>ساعت</option>
                                <option value='روز' @if($prmed->hour=='روز') selected @endif>روز</option>

                            </select>
                            <div  class="input-group-addon" >هر</div>
                        </div>
                    </td>
                    <td><input value="{{$prmed->medicine_price}}" name="price[{{$i}}]" class="form-control" id="price{{$i}}" ></td>
                    <td><input value="{{$prmed->open_market_price}}" name="open_market[{{$i}}]" class="form-control" id="open_market{{$i}}" onchange="open_market({{$i}})" ></td>
                    <td><input value="{{$prmed->others_difference}}" name="others[{{$i}}]" class="form-control" id="others{{$i}}" onchange="others(1)"></td>

                    <td >
                        <div  class="input-group-addon myaddon" id="fp{{$i}}">%{{$prmed->base_insure!==0 & $prmed->total!=0 ? round($prmed->base_insure/$prmed->total*100) : 0 }}</div>
                        <input value="{{$prmed->base_insure}}" name="first_insure_percent[{{$i}}]" class="form-control myinput" id="first_insure_percent{{$i}}" >


                    </td>
                    <td >   <div  class="input-group-addon myaddon" id="ip{{$i}}">%{{$prmed->iran_pay!==0 & $prmed->total!=0 ? round($prmed->iran_pay/$prmed->total*100) : 0}}</div>
                        <input value="{{$prmed->iran_pay}}" name="iran_percent[{{$i}}]" class="form-control myinput" id="iran_percent{{$i}}" >


                    </td>
                    <td >   <div  class="input-group-addon myaddon" id="pp{{$i}}">%{{$prmed->franshiz!==0 & $prmed->total!=0 ? round($prmed->franshiz/$prmed->total*100) : 0}}</div>
                        <input value="{{$prmed->franshiz}}" name="franshiz[{{$i}}]" class="form-control myinput" id="franshiz{{$i}}" >


                    </td>
                    <td> <input value="{{$prmed->total}}" name="total[{{$i}}]" class="form-control" id="total{{$i}}" >
                    <td> @if($i>1) <a class='btn btn-danger btn-xs remove_field' ><span class="glyphicon glyphicon-remove-sign"></span>حذف</a> @endif </td>

                </tr>

                <?php
                $daroo= ['id'=>$i , 'selected'=>1]  ;
                array_push($darooha,$daroo);
                $i++;?>

            @endforeach


        </table>

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
                        <input type="text" class="form-control" id="presc_others" name="presc_others" value="{{$pr->total_others_difference}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_first" name="presc_first" value="{{$pr->total_base_insure}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_iran" name="presc_iran" value="{{$pr->iran_pay}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_insured" name="presc_insured" value="{{$pr->total_franshiz}}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="presc_total" name="presc_total" value="{{$pr->total}}">
                    </td>

                </tr>
            </table>
        </div>
        <div class="col-xs-2 bold">
            <br>
            <br>
            <br>
            جمع مبالغ فاکتور:
        </div>

    </div>
    <hr>


    <div class="row">

        <div class="form-group col-sm-12" style="text-align: center">
            <a class="btn btn-success" href="{{url('print/'.$pr->id)}}" target="_blank"><span class="glyphicon glyphicon-print"></span>چاپ نسخه</a></div>

    </div>





    {!! Form::close() !!}


@stop
@section('footer')
    <script type="text/javascript">
        var medicines=<?php echo json_encode($medicines, JSON_PRETTY_PRINT) ?>;
        var insureds = <?php echo json_encode($insureds, JSON_PRETTY_PRINT) ?>;
        var insurers = <?php echo json_encode($insurers, JSON_PRETTY_PRINT) ?>;
        var prescs = <?php echo json_encode($prescs, JSON_PRETTY_PRINT) ?>;
        var prescMed = <?php echo json_encode($prescMed, JSON_PRETTY_PRINT) ?>;
        var contractcommits = <?php echo json_encode($contractcommits, JSON_PRETTY_PRINT) ?>;
        var commits= <?php echo json_encode($commits, JSON_PRETTY_PRINT) ?>;
        var setting= <?php echo json_encode($setting, JSON_PRETTY_PRINT) ?>;
        var contract_f=30;
        var permision=1;
        var hike=<?php echo $i;?>;
        var specs=<?php echo json_encode($specs, JSON_PRETTY_PRINT) ?>;
        var daroha=<?php echo json_encode($darooha, JSON_PRETTY_PRINT) ?>;
        var contracts=<?php echo json_encode($contracts, JSON_PRETTY_PRINT) ?>;
        var drug_list=<?php echo count($prescspecs);?>;;
    </script>
    <script src="{{url('assets/js/jquery.js')}}"></script>
    <script  src="{{url('assets/js/select2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder:'نام بیمار',
                dir: "rtl"
            });
            $('.select1').select2({
                placeholder:'دارو',
            });
        });
    </script>
    <script src="{{url('assets/js/presc.js')}}"></script>
    <script> company({{$pr->insured_id}});

    </script>
    <style>
        .test{
            border-top-left-radius: 0px;
        }
    </style>
@stop