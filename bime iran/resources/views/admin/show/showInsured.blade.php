@extends('master')
@section('content')
    <link rel="stylesheet" href="{{url('jalalijscalendar/skins/calendar-blue2.css')}}">
    <script src="{{url('jalalijscalendar/jalali.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar.js')}}"></script>
    <script src="{{url('jalalijscalendar/calendar-setup.js')}}"></script>
    <script src="{{url('jalalijscalendar/lang/calendar-fa.js')}}"></script>
<ol class="breadcrumb" >
	<li class="active bold">
		نمایش اطلاعات بیمه شده - {{$insured->fname}} {{$insured->lname}}
	</li>
	<li>
		<a href="{{url('admin/insurers')}}">مدیریت بیمه گذاران و بیمه شدگان</a>
	</li>
	<li >
		<a href="{{url('admin')}}">صفحه اصلی</a>
	</li>
</ol>

<div class=" row">

	<div class="col-sm-12 ">
		@include('errors.form')
		<div class="panel panel-primary ">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>اطلاعات بیمه شده</h3>
			</div>
			<div class="panel-body">
				<a @if($insured->type=='main') href="{{url('admin/edit-insured/'.$insured->id)}}" @else href="{{url('admin/edit-people/'.$insured->id)}}" @endif class="btn btn-primary btn-xs"> <span class="glyphicon glyphicon-edit"></span> ویرایش</a>
				<br>
				<br>
				@if($insured->type=='main')
				<div class="col-xs-4">
					<table class="table table-bordered">
						<tr>
							<td class="info">شماره قرارداد</td>
							<td> @if($insured->contract_id!=0)
							<?php $contract = App\Contract::find($insured -> contract_id); ?>
							<a href="{{url('admin/show-contract/'.$contract->id)}}">{{$contract->contract_num}}</a> @else
							'نا مشخص'
							@endif </td>
						</tr>
						<tr>
							<td class="info col-xs-5">تلفن</td>
							<td>{{$insured->phone}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">موبایل</td>
							<td>{{$insured->mobile}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">بیمه گر پایه</td>
							<td>{{$insured->base_insure}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">شماره بیمه</td>
							<td>{{$insured->insure_num}} </td>
						</tr>

						<tr>
							<td class="info col-xs-5">نام بانک</td>
							<td>{{$insured->bank}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">شماره حساب</td>
							<td>{{$insured->account}} </td>
						</tr>

					</table>
				</div>
				<div class="col-xs-4">
					<table class="table table-bordered">
						<tr>
							<td class="info col-xs-5">نام بیمه گذار</td>
							<td>@if($insured->insurer_id!=0)
							<?php $insured1 = App\Insurer::find($insured -> insurer_id); ?>
							<a href="{{url('admin/show-insurer/'.$insured1->id)}}">{{$insured1->name}}</a> @else
							{{'نامشخص'}}
							@endif </td>
						</tr>
						<tr>
							<td class="info col-xs-5">تاریخ استخدام</td>
							<td>{{$insured->employed_date}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">کد پرسنلی</td>
							<td>{{$insured->personal_code}} </td>
						</tr>

						<tr>
							<td class="info col-xs-5">گروه</td>
							<td>{{$insured->group}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">وابستگی</td>
							<td>{!! $insured->type=='main' ? '-' : 'وابسته' !!}</td>
						</tr>

						<tr>
							<td class="info col-xs-5">خاص</td>
							<td>{!! $insured->status=='معمولی' ? 'بله' : 'خیر' !!}</td>
						</tr>
						<tr>
							<td class="info col-xs-5">در صد جانبازی</td>
							<td>{{$insured->janbaz_percent}} </td>
						</tr>

					</table>
				</div>
				<div class="col-xs-4">
					<table class="table table-bordered">
						<tr>
							<td class="info col-xs-5">نام - نام خانوادگی</td>
							<td>{{$insured->fname}} {{$insured->lname}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">نام پدر</td>
							<td>{{$insured->father_name}}</td>
						</tr>
						<tr>
							<td class="info col-xs-5">تاریخ تولد</td>
							<td>{{$insured->birth_date}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">شماره شناسنامه</td>
							<td>{{$insured->birth_cert_num}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">کد ملی</td>
							<td>{{$insured->melli_code}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">جنسیت</td>
							<td>{{$insured->gender}} </td>
						</tr>
						<tr>
							<td class="info col-xs-5">وضعیت تاهل</td>
							<td>{{$insured->marrige_status}} </td>
						</tr>
						@if($insured->insured_id!=0)
						<?php $main = App\Insured::find($insured -> insured_id); ?>
						<tr>
							<td class="info col-xs-5">شخص اصلی</td>
							<td><a href="{{url('admin/show-insured/'.$main->id)}}">{{$main->fname}} {{$main->lname}}</a></td>
						</tr>
						@endif
					</table>
				</div>
				@else
					<div class="col-xs-4">
						<table class="table table-bordered">
							<tr>
								<td class="info col-xs-5">نسبت</td>
								<td>{{$insured->relation}}</td>
							</tr>

							<tr>
								<td class="info col-xs-5">وضعیت تکفل</td>
								<td>{{$insured->sponser_status}}</td>
							</tr>
							<tr>
								<td class="info col-xs-5">بیمه گر پایه</td>
								<td>{{$insured->base_insure}} </td>
							</tr>
							<tr>
								<td class="info col-xs-5">شماره بیمه</td>
								<td>{{$insured->insure_num}} </td>
							</tr>
						</table>
					</div>
					<div class="col-xs-4">
						<table class="table table-bordered">
							<tr>
								<td class="info col-xs-5">کد ملی</td>
								<td>{{$insured->melli_code}} </td>
							</tr>
							<tr>
								<td class="info col-xs-5">جنسیت</td>
								<td>{{$insured->gender}} </td>
							</tr>
							<tr>
								<td class="info col-xs-5">وضعیت تاهل</td>
								<td>{{$insured->marrige_status}} </td>
							</tr>
							<?php $main = App\Insured::find($insured -> insured_id); ?>
							<tr>
								<td class="info col-xs-5">شخص اصلی</td>
								<td><a href="{{url('admin/show-insured/'.$main->id)}}">{{$main->fname}} {{$main->lname}}</a></td>
							</tr>
						</table>
					</div>
					<div class="col-xs-4">
						<table class="table table-bordered">
							<tr>
								<td class="info col-xs-5">نام - نام خانوادگی</td>
								<td>{{$insured->fname}} {{$insured->lname}} </td>
							</tr>
							<tr>
								<td class="info col-xs-5">نام پدر</td>
								<td>{{$insured->father_name}}</td>
							</tr>
							<tr>
								<td class="info col-xs-5">تاریخ تولد</td>
								<td>{{$insured->birth_date}} </td>
							</tr>
							<tr>
								<td class="info col-xs-5">شماره شناسنامه</td>
								<td>{{$insured->birth_cert_num}} </td>
							</tr>


						</table>
					</div>
				@endif

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
		<div class="@if($insured->type=='main') col-sm-6 @else col-sm-12 @endif">
			<div class="panel panel-primary" >
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-list"></span>نسخ بیمه شده</h3>
				</div>
				<div class="panel-body" style="height: 254px;overflow-y: scroll">
					<table class="table table-bordered">
						<tr class="info bold">
							<td>نام داروخانه</td>
							<td>تاریخ نسخه</td>
							<td>وضعیت</td>
							<td>عملیات</td>
						</tr>
						@foreach($prescs as $presc)
							<tr>
								<td>
									<?php $center=App\ServiceCenter::find($presc->serviceCenter_id) ?>
									<a href="{{url('admin/show-center/'.$center->id)}}" data-toggle="tooltip" data-placement="top" title="نمایش جزئیات داروخانه">
										{{$center->name}}
									</a>
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
										<span class="glyphicon glyphicon-list-alt"></span>
									</a>
								</td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
		@if($insured->type=='main')
		<div class="col-sm-6">
			<div class="panel panel-primary" >
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-link"></span>اطلاعات افراد وابسته</h3>
				</div>
				<div class="panel-body" style="height: 254px;overflow-y: scroll">
					<a href="{{url('admin/new-people/'.$insured->id)}}" class="btn btn-primary btn-xs"> <span class="glyphicon glyphicon-plus-sign"></span>افزودن فرد وابسته جدید</a>
					<a href="{{url('admin/add-to-insured/'.$insured->id.'/no')}}" class="btn btn-primary btn-xs"> <span class="glyphicon glyphicon-plus"></span>افزودن فرد وابسته</a>
					<br>
					<br>
					<table class="table table-bordered">
						<tr class="info bold">
							<td>نام  - نام خانوادگی</td>
							<td>نسبت</td>
							<td>وضعیت تکفل</td>
							<td>عملیات</td>
						</tr>
						@foreach($people as $p)
						<tr>
							<td><a href="{{url('admin/show-insured/'.$p->id)}}">{{$p->fname}} {{$p->lname}}</a></td>
							<td>{{$p->relation}}</td>
							<td>{{$p->sponser_status}}</td>
							<td>
								<a href="{{url('admin/show-insured/'.$p->id)}}" data-toggle="tooltip" data-placement="top" title="نمایش جزئیات">
									<span class="glyphicon glyphicon-list-alt text-primary"></span>
								</a>
								<a  data-toggle="modal" data-target="#myModal{{$p->id}}">
									<span class="glyphicon glyphicon-remove text-danger" data-toggle="tooltip" data-placement="top" title="حذف از خانواده"></span>
								</a>
								<div class="modal fade" id="myModal{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
												<a href="{{url('admin/remove-from-insured/'.$p->id)}}" type="button" class="btn btn-danger">حذف</a>
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
		@endif
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