@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold"> نمایش قرارداد   شماره
            {{$contract->contract_num}}</li>
        <li><a href="{{url('admin/contracts')}}">مدیریت قراردادها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    @include('errors.form')

    <div class="col-sm-6">

        <div class="panel panel-primary " >

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-th-list"></span> لیست بیمه شدگان</h3>
            </div>
            <div class="panel-body" style="height: 310px;overflow-y: scroll">

                <a class="btn btn-primary btn-xs" href="{{url('admin/new-insured/'.$insurer->id.'/'.$contract->id)}}">
                    <span class="glyphicon glyphicon-plus"></span>
                    افزودن بیمه شده جدید
                </a>
                <a class="btn btn-primary btn-xs" href="{{url('admin/add-to-contract/'.$contract->id.'/no')}}">
                    <span class="glyphicon glyphicon-plus-sign"></span>
                    افزودن بیمه شده به قرارداد
                </a>
                <br><br>
                <table class="table table-bordered" >
                    <tr class="info">
                        <td >نام بیمه شده</td>
                        <td >شماره ملی</td>

                        <td>عملیات</td>

                    </tr>
                    @foreach($insureds as $insured)
                        <tr>
                            <td ><a href="{{url('admin/show-insured/'.$insured->id)}}">{{$insured->fname.' '.$insured->lname }}</a></td>
                            <td >{{$insured->melli_code}}</td>

                            <td>
                                <a href="{{url('admin/show-insured/'.$insured->id)}}" data-toggle="tooltip" data-placement="top" title=" نمایش جزئیات"><span class="glyphicon glyphicon-list-alt text-info"></span></a>
                                <a  data-toggle="modal" data-target="#myModal{{$insured->id}}" ><span class="glyphicon glyphicon-remove text-danger"   data-toggle="tooltip" data-placement="top" title="حذف از قرارداد" ></span></a>
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
                                                <a href="{{url('admin/remove-from-contract/'.$insured->id)}}" type="button" class="btn btn-danger">حذف</a>
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
    <div class="col-sm-6">
        <div class="panel panel-primary " >
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>اطلاعات قرارداد</h3>
            </div>
            <div class="panel-body" style="height: 310px;overflow-y: scroll">

                    <a class="btn btn-primary btn-xs" href="{{url('admin/edit-contract/'.$contract->id)}}"><span class="glyphicon glyphicon-edit"></span>ویرایش قرارداد</a>
                <br><br>
                    <table class="table table-bordered">
                        <tr>
                            <td class="info bold">شماره قرارداد</td>
                            <td>{{$contract->contract_num}}</td>
                        </tr>
                        <tr>
                            <td class="info bold">بیمه گذار</td>
                            <td><a href="{{url('admin/show-insurer/'.$insurer->id)}}">{{ $insurer->name}}</a> </td>
                        </tr>
                        <tr>
                            <td class="info bold">تاریخ اخذ قرارداد
                            </td>
                            <td>{{$contract->start_date}}</td>
                        </tr>
                        <tr>
                            <td class="info bold">تاریخ پایان قرارداد
                            </td>
                            <td>{{$contract->finish_date}}</td>
                        </tr>
                        <tr>
                            <td class="info bold">نوع قرارداد</td>
                            <td>{{$contract->type}}</td>
                        </tr>
                        <tr>
                            <td class="info bold">
                                تفاوت و سایر بر عهده</td>
                            <td>{{$contract->others}}</td>
                        </tr>
                    </table>



            </div>
        </div>
    </div>

        <div class="col-xs-12">
        <div class="panel panel-primary " >

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-tasks"></span>تعهدات و خدمات قرارداد</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr class="info bold">
                        <td width="20%">تعهدات</td>
                        <td width="10%">سرویس ها</td>
                        <td>توضیحات</td>
                    </tr>
                    @foreach($commits as $commit)
                        <tr>
                        <td>{{App\Commit::find($commit->commit_id)->name}}</td>
                        <td>
                            <ul class="text-right" style="list-style: square;padding-right: 18px">
                            @foreach($services as $service)
                                @if($service->contractCommit_id==$commit->id)
                                    <li>
                                        {{App\Service::find($service->service_id)->name}}
                                    </li>
                                @endif
                            @endforeach
                            </ul>
                        </td>
                        <td>
                            <div class="col-xs-4">
                                <table class="table table-bordered col-xs-12">
                                    <tr>
                                        <td class="info ">واحد</td>
                                        <td>{{$commit->unit}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-4">
                                <table class="table table-bordered col-xs-12">
                                    <tr>
                                        <td class="info ">حداکثر تعهد</td>
                                        <td>{{$commit->max_commit}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-4">
                                <table class="table table-bordered col-xs-12">
                                    <tr>
                                        <td class="info ">کنترل فرانشیز</td>
                                        <td>{{$commit->farnshiz_control}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="border-bottom: 1px solid gray" class="col-xs-12">فرانشیز</div>
                            <div class="col-xs-4">
                                <table class="table table-bordered col-xs-12">
                                    <tr>
                                        <td class="info ">غیر تحت تکفل</td>
                                        <td>{{$commit->insured_f}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-4">
                                <table class="table table-bordered col-xs-12">
                                    <tr>
                                        <td class="info ">تحت تکفل</td>
                                        <td>{{$commit->depend_f}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-4">
                                <table class="table table-bordered col-xs-12">
                                    <tr>
                                        <td class="info ">بیمه شده</td>
                                        <td>{{$commit->non_depend_f}}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>

    </div>
@stop