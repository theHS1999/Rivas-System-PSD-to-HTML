@extends('master')
@section('content')
    <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
    <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
    <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
    <!DOCTYPE html>

        <link rel="stylesheet"
              href="{{url('assets/css/chartist.min.css')}}">

    <script src="{{url('assets/js/chartist.min.js')}}"></script>
<style>
    .ct-label{
        font-size: 18px;
        font-weight: bold;
        color: white;
    }
    .ct-label.ct-horizontal.ct-end,.ct-label.ct-vertical.ct-start{
        font-size: 8px;
        font-weight: bold;
        color: black;
    }

    .ct-series-a .ct-line,
    .ct-series-a .ct-point {
        stroke: blue;
    }
    .ct-series-b .ct-line,
    .ct-series-b .ct-point {
        stroke: red;
    }
    .ct-series-c .ct-line,
    .ct-series-c .ct-point {
        stroke: purple;
    }

    .ct-series-a .ct-slice-pie {
        fill: #5cb85c;
    }
    .ct-series-b .ct-slice-pie {
        fill: #337ab7;
    }
    .ct-series-c .ct-slice-pie {
        fill: #ec971f;
    }


</style>

    <ol class="breadcrumb" >
        <li class="active">گزارشات</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی   </a></li>
    </ol>

    <div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">گزارشات نسخ</div>
            <div class="panel-body text-right">
                {!! Form::open() !!}
                <div class="row">
                <div class="form-group col-xs-5">
                    <div class="input-group col-xs-12" style="padding: 0px">
                        <span class="input-group-btn">
                            <button id="start_btn" class="btn btn-default" type="button" style="">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </button>
                        </span>
                        <input id="start_date" type="text" name="start_date" class="form-control" placeholder="از تاریخ" @if(Session::has('startdate')) value="{{ Session::get('startdate') }}" @endif style="text-align: right">
                    </div>
                </div>
                <div class="form-group col-xs-5">
                    <div class="input-group col-xs-12" style="padding: 0px">
                        <span class="input-group-btn">
                            <button id="finish_btn" class="btn btn-default" type="button" style="">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </button>
                        </span>
                        <input id="finish_date" type="text" name="finish_date" class="form-control" placeholder="تا تاریخ" @if(Session::has('finishdate')) value="{{ Session::get('finishdate') }}" @endif style="text-align: right">
                    </div>
                </div>
                <div class="form-group col-xs-2">
                    <input type="submit" value="فیلتر" class="btn btn-primary">
                </div>
                </div>
                {!! Form::close() !!}
                <p ><span class="bold">تعداد کل نسخ : </span> {{$prescs_count}}</p>
                <p><span class="bold"> کل مبالغ نسخ: </span>{{$prescs_total}} ریال</p>
                <p><span class="bold"> مجموع پرداختی بیمه ایران: </span>{{$prescs_iran_pay}} ریال</p>
                <p><span class="bold"> مجموع پرداخت های کاربری های خاص: </span>{{$prescs_payable}} ریال</p>
            </div>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">گزارشات کلی سامانه</div>
            <div class="panel-body text-right">

                <p><span class="bold"> تعداد کل مراکز: </span>{{$centers_count}}</p>
                <p><span class="bold"> تعداد کل بیمه گذاران: </span>{{$insurers_count}} </p>
                <p><span class="bold"> تعداد کل بیمه شدگان: </span>{{$insureds_count}} </p>
                <p><span class="bold"> تعداد کل قرارداد ها: </span>{{$contracts_count}} </p>
                <p><span class="bold"> تعداد کل داروها: </span>{{$medicines_count}} </p>
                <p><span class="bold"> تعداد کل پزشکان:</span> {{$doctors_count}} </p>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">گزارشات ماهانه</div>
            <div class="panel-body text-right">
                <div class="col-xs-8">
                    <form class="form-inline">
                        <label class="control-label">انتخاب سال : </label>
                        <select class="form-control">
                            @for($i=1395;$i<=$year;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary">نمایش نمودار</button>
                    </form>
                    <br>



                </div>
                <div class="col-xs-7">

                    <div class="ct-chart ct-perfect-fourth"></div>

                </div>
                <div class="col-xs-5">
                    <div class="col-xs-8 col-xs-offset-2"><div class="ct-chart1 ct-perfect-fourth"></div></div>
                    <ul class="col-xs-12" style="list-style: square;padding-right: 10px">
                        <p class="bold">راهنمای نمودار:</p>
                        <li style="color: blue">کل مبالغ نسخ</li>
                        <li style="color: red">مجموع پرداختی بیمه ایران</li>
                        <li style="color: purple">مجموع پرداخت های کاربری های خاص</li>
                        <li style="color: #5cb85c"> نسخه های ثبت شده</li>
                        <li style="color: #337ab7">نسخه های ارسالی به کارشناس</li>
                        <li style="color: #ec971f">تیکت های دستی</li>
                    </ul></div>

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
    <div>

        <script>
            new Chartist.Line('.ct-chart', {
                labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد','شهریور', 'مهر', 'آبان', 'آذر', 'دی','بهمن','اسفند',''],
                series: [
                    [
                        {{$total[0]==0 ? 'null' : $total[0]}},
                        {{$total[1]==0 ? 'null' : $total[1]}},
                        {{$total[2]==0 ? 'null' : $total[2]}},
                        {{$total[3]==0 ? 'null' : $total[3]}},
                        {{$total[4]==0 ? 'null' : $total[4]}},
                        {{$total[5]==0 ? 'null' : $total[5]}},
                        {{$total[6]==0 ? 'null' : $total[6]}},
                        {{$total[7]==0 ? 'null' : $total[7]}},
                        {{$total[8]==0 ? 'null' : $total[8]}},
                        {{$total[9]==0 ? 'null' : $total[9]}},
                        {{$total[10]==0 ? 'null' : $total[10]}},
                        {{$total[11]==0 ? 'null' : $total[11]}}
                    ],
                    [
                        {{$iran_pay[0]==0 ? 'null' : $iran_pay[0]}},
                        {{$iran_pay[1]==0 ? 'null' : $iran_pay[1]}},
                        {{$iran_pay[2]==0 ? 'null' : $iran_pay[2]}},
                        {{$iran_pay[3]==0 ? 'null' : $iran_pay[3]}},
                        {{$iran_pay[4]==0 ? 'null' : $iran_pay[4]}},
                        {{$iran_pay[5]==0 ? 'null' : $iran_pay[5]}},
                        {{$iran_pay[6]==0 ? 'null' : $iran_pay[6]}},
                        {{$iran_pay[7]==0 ? 'null' : $iran_pay[7]}},
                        {{$iran_pay[8]==0 ? 'null' : $iran_pay[8]}},
                        {{$iran_pay[9]==0 ? 'null' : $iran_pay[9]}},
                        {{$iran_pay[10]==0 ? 'null' : $iran_pay[10]}},
                        {{$iran_pay[11]==0 ? 'null' : $iran_pay[11]}}


                    ],
                    [
                        {{$payable[0]==0 ? 'null' : $payable[0]}},
                        {{$payable[1]==0 ? 'null' : $payable[1]}},
                        {{$payable[2]==0 ? 'null' : $payable[2]}},
                        {{$payable[3]==0 ? 'null' : $payable[3]}},
                        {{$payable[4]==0 ? 'null' : $payable[4]}},
                        {{$payable[5]==0 ? 'null' : $payable[5]}},
                        {{$payable[6]==0 ? 'null' : $payable[6]}},
                        {{$payable[7]==0 ? 'null' : $payable[7]}},
                        {{$payable[8]==0 ? 'null' : $payable[8]}},
                        {{$payable[9]==0 ? 'null' : $payable[9]}},
                        {{$payable[10]==0 ? 'null' : $payable[10]}},
                        {{$payable[11]==0 ? 'null' : $payable[11]}}
                    ]
                ]
            }, {
                lineSmooth: Chartist.Interpolation.simple({
                    divisor: 2
                }),
                fullWidth: true,
                chartPadding: {
                    right: 20
                },
                low: 0,
                fullWidth: true,
                chartPadding: {
                    right: 0
                }
            });
            var data = {
                series: [{{$registred}}, {{$send_to_expert}}, {{$ticket}}]
            };

            var sum = function(a, b) { return a + b };

            new Chartist.Pie('.ct-chart1', data, {
                labelInterpolationFnc: function(value) {
                    return Math.round(value / data.series.reduce(sum) * 100) + '%';
                }
            });
        </script>
    </div>
@endsection