@extends('master')
@section('content')
    <style>
        .input-group-addon:first-child{
            border-right:none;
            border-radius: 0px;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }
    </style>
    <ol class="breadcrumb" >
        <li class="active bold">ویرایش قرارداد </li>
        <li><a href="{{url('admin/contracts')}}"> مدیریت قراردادها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-sm-10 col-sm-offset-1" >
        {!! Form::open() !!}
<? $contarct=0; ?>
        <div class="form-group col-xs-4">
            {!! Form::label('insurer_id','شرکت بیمه گذار') !!}
            <select name="insurer_id" class="form-control select2 col-xs-12" style=" width: 100%;">
                @foreach($insurers as $insurer)
                    <option value='{{$insurer->id}}' {{$contract->insurer_id==$insurer->id ? 'selected' : ''}}>{{$insurer->name}}</option>
                @endforeach
            </select>

        </div>

        <div class="form-group col-xs-4">
            {!! Form::label('contract_num','شماره قرارداد') !!}
            {!!Form::text('contract_num',$contract->contract_num,['class'=>'form-control','id'=>'test'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('type','نوع قرارداد') !!}
            {!!Form::text('type',$contract->type,['class'=>'form-control'])!!}
        </div>
        <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
        <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
        <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
        <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
        <div class="form-group col-xs-4" style="direction: ltr">
            <label>تاریخ اخذ قرارداد</label>
            <div class="input-group col-xs-12" style="padding: 0px">
            <span class="input-group-btn">
                <button id="date_btn" class="btn btn-default" type="button" style="">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </button>
            </span>
                <input id="date_input" type="text" name="start_date" class="form-control" style="text-align: right" value="{{$contract->start_date}}">
            </div>
        </div>
        <div class="form-group col-xs-4" style="direction: ltr">
            <label>تاریخ پایان قرارداد</label>
            <div class="input-group col-xs-12" style="padding: 0px">
            <span class="input-group-btn">
                <button id="date_btn1" class="btn btn-default" type="button" style="">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </button>
            </span>
                <input id="date_input1" type="text" name="finish_date" class="form-control" style="text-align: right" value="{{$contract->finish_date}}">
            </div>
        </div>
        <script>
            Calendar.setup({
                inputField: 'date_input',
                button: 'date_btn',
                ifFormat: '%Y-%m-%d',
                dateType: 'jalali',
            });
            Calendar.setup({
                inputField: 'date_input1',
                button: 'date_btn1',
                ifFormat: '%Y-%m-%d',
                dateType: 'jalali',
            });
        </script>

        <div class="form-group col-xs-4">
            {!! Form::label('others','تفاوت و سایر بر عهده') !!}
            <select name="others" class="form-control" >
                <option value="بیمه شده" {{$contract->others=='بیمه شده' ? 'selected' : ''}}>بیمه شده</option>
                <option value="بیمه گذار" {{$contract->others=='بیمه گذار' ? 'selected' : ''}}>بیمه گر پایه</option>
                <option value="بیمه ایران" {{$contract->others=='بیمه ایران' ? 'selected' : ''}}>بیمه ایران</option>
            </select>
        </div>



        <div class="form-group col-xs-12">
            {!! Form::label('commitsAndServices','تعهدات و سرویس ها') !!}


            <br/>
            <br/>
            <input type="checkbox" name="commit[0]" value="0" checked hidden>


            <?php $id=0; ?>
            <div class="table-responsive ">
                <table class="table table-bordered ">
                    <tr class="info bold">
                        <td>تعهدات</td>
                        <td>خدمات</td>
                        <td>
                            توضیحات
                        </td>




                    </tr>

                    @foreach($commits as $commit)
                        <?php $c=new \App\Commit; ?>
                        @foreach($contractCommits as $contractCommit)
                            @if($contractCommit->commit_id==$commit->id)
                                <?php
                                $c=$contractCommit;
                                ?>
                            @endif
                        @endforeach
                        <tr>
                            <td class="col-xs-3">
                                <label class="checkbox-inline" style="direction: ltr">
                                    <input type="checkbox" name="commit[{{$commit->id}}]" value="{{$commit->id}}"
                                     {{ $c!=null && $c->commit_id==$commit->id ? 'checked' : '' }}  > {{$commit->name}}
                                </label>
                            </td>

                            <td class="col-xs-3" style="direction: ltr">
                                @foreach($services as $service)
                                    @if($service->commit_id==$commit->id)
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="service[{{$commit->id}}][{{$service->id}}]" value="{{$service->id}}" @foreach($contractServices as $contractService) {{$contractService->service_id==$service->id ? 'checked' : '' }} @endforeach> {{$service->name}}
                                        </label>
                                        <br>

                                    @endif
                                @endforeach
                            </td>


                            <td style="padding-bottom: 10px">

                                <div class="form-group col-xs-4">

                                    {!! Form::label('franshiz_control','کنترل فرانشیز',['class'=>'col-sm-12']) !!}
                                    <select onchange="franshiz({{$commit->id}})" id="control{{$commit->id}}" class="form-control" name="franshiz_control[{{$commit->id}}]">
                                        <option value="ندارد" {{ $c!=null && $c->farnshiz_control=='ندارد' ? 'selected' : '' }}>ندارد</option>
                                        <option value="دارد" {{ $c!=null && $c->farnshiz_control=='دارد' ? 'selected' : '' }}>دارد</option>
                                    </select>


                                </div>
                                <div class="form-group col-xs-4">
                                    {!! Form::label('max_commit','حداکثر تعهد',['class'=>'col-sm-12']) !!}
                                    <input type="text" class="form-control" name="max_commit[{{$commit->id}}]" value="{{ $c!=null ? $c->max_commit : '' }}">

                                </div>
                                <div class="form-group col-xs-4">
                                    {!! Form::label('unit','واحد',['class'=>'col-sm-12']) !!}
                                    <input type="text" class="form-control" name="unit[{{$commit->id}}]" value="{{ $c!=null ? $c->unit : '' }}">

                                </div>

                                <div id="franshiz{{$commit->id}}" @if($c->commit_id!=$commit->id) style="display:none" @endif>
                                    <div style="border-bottom: 1px solid gray" class="col-xs-12">فرانشیز</div>

                                    <div class="form-group col-xs-4">
                                        {!! Form::label('non_depend_f','غیر تحت تکفل',['class'=>'col-sm-12']) !!}
                                        <div class="input-group" style="direction: ltr">
                                            <span class="input-group-addon" >درصد</span>
                                            <input type="text" class="form-control" name="non_depend_f[{{$commit->id}}]" value="{{ $c!=null ? $c->non_depend_f : '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-xs-4">
                                        {!! Form::label('depend_f','تحت تکفل',['class'=>'col-sm-12']) !!}
                                        <div class="input-group" style="direction: ltr">
                                            <span class="input-group-addon" >درصد</span>
                                            <input type="text" class="form-control" name="depend_f[{{$commit->id}}]" value="{{ $c!=null ? $c->depend_f : '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-xs-4" >
                                        {!! Form::label('insured_f','بیمه شده',['class'=>'col-sm-12']) !!}
                                        <div class="input-group" style="direction: ltr">
                                            <span class="input-group-addon" >درصد</span>
                                            <input type="text" class="form-control" name="insured_f[{{$commit->id}}]" value="{{ $c!=null ? $c->insured_f : '' }}">
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                        <?php $id++; ?>

                    @endforeach
                </table>
            </div>
        </div>

        <div class="col-xs-12">
            <input name="specs" type="text" value="0" id="ee" hidden>
            <table class="table table-bordered " id="expert">
                <tr class="info bold">
                    <td>نام تخصص</td>
                    <td>سقف مجاز</td>
                    <td>حذف</td>
                </tr>
            </table>
            <br>
            <p class="add_row btn btn-info "  style="float: right;">افزودن دارو با کاربرد تخصصی</p>
        </div>
        <div class="form-group  col-xs-12">
            <button type="submit" class="btn btn-primary" >
                ثبت قرارداد
            </button>
        </div>



        {!! Form::close() !!}
    </div>

@endsection
@section('footer')

    <script src="{{url('assets/js/contract.js')}}"></script>
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