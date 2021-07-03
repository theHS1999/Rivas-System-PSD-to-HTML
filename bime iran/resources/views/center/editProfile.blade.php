@extends('master')
@section('content')
    <script>
        $(document).ready(function() {
            $("#insures_under_contract").select2({
                placeholder: "بیمه گر",
                dir:'rtl'
            });
        });
    </script>
    <ol class="breadcrumb" >
        <li class="active bold">ویرایش اطلاعات کاربری</li>

        <li ><a href="{{url('center')}}">صفحه اصلی</a></li>
    </ol>

    {!! Form::open(['files'=>true]) !!}
    <div class="col-sm-10 col-sm-offset-1">
        <div class="row">
            <div class="col-xs-4">

                <div class="col-xs-12">
                    <label >عکس جدید</label>
                    <input type="file" class="form-control" name="image">
                </div>
                <div class="col-xs-12" >
                    @if($user->pic!=null)
                        <img src="{{url('uploads/'.$user->pic)}}" class="img-thumbnail"  alt=>
                    @endif
                </div>

            </div>
            <div class="col-xs-8">

                <div class="form-group col-xs-6">
                    {!! Form::label('name','نام واقعی') !!}
                    {!!Form::text('name',$center->name,['class'=>'form-control'])!!}
                </div>
                <div class="form-group col-xs-6">
                    {!! Form::label('password','کلمه عبور جدید') !!}
                    {!!Form::text('password',null,['class'=>'form-control'])!!}
                </div>
                <div class="form-group col-xs-6">
                    <label >تکرار رمز عبور جدید</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
                <div class="form-group col-xs-6">
                    {!! Form::label('mail','ایمیل') !!}
                    {!!Form::text('mail',$center->mail,['class'=>'form-control'])!!}
                </div>
                <div class="form-group col-xs-6">
                    {!! Form::label('sahebe_emtiaz','صاحب امتیاز') !!}
                    {!!Form::text('sahebe_emtiaz',$center->sahebe_emtiaz,['class'=>'form-control'])!!}
                </div>
                <div class="form-group col-xs-6">
                    {!! Form::label('technical_user','نام مسئول فنی') !!}
                    {!!Form::text('technical_user',$center->technical_user,['class'=>'form-control'])!!}
                </div>
            </div>

        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('medical_code','کد نظام پزشکی') !!}

            {!!Form::text('medical_code',$center->medical_code,['class'=>'form-control'])!!}
        </div>

        <div class="form-group col-xs-4">
            {!! Form::label('address','آدرس') !!}
            {!!Form::text('address',$center->address,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('phone','شماره تماس') !!}
            {!!Form::text('phone',$center->phone,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('fax','فکس') !!}
            {!!Form::text('fax',$center->fax,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('mobile','موبایل') !!}
            {!!Form::text('mobile',$center->mobile,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('website','وب سایت') !!}
            {!!Form::text('website',$center->website,['class'=>'form-control'])!!}
        </div>

        <div class="form-group col-xs-4">
            {!! Form::label('shift','شیفت کاری') !!}
            <select name="shift" class="form-control" >
                <option value="روزانه" {{$center->shift=='روزانه' ? 'selected' : ''}}>روزانه</option>
                <option value="شبانه روزی" {{$center->shift=='شبانه روزی' ? 'selected' : ''}}>شبانه روزی</option>

            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('bank','بانک') !!}
            <select name="bank" class="form-control" >
                <option value="صادرات" {{$center->bank=='صادرات' ? 'selected' : ''}}>صادرات</option>
                <option value="ملت" {{$center->bank=='ملت' ? 'selected' : ''}}>ملت</option>

            </select>
        </div>
        <div class="form-group col-xs-4">
            {!! Form::label('account_num','شماره حساب') !!}
            {!!Form::text('account_num',$center->account_num,['class'=>'form-control'])!!}
        </div>


        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary">
                ویرایش اطلاعات
            </button>
        </div>

        {!! Form::close() !!}
    </div>
@stop



