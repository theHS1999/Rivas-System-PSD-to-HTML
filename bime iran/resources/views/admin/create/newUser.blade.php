@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">کاربر جدید</li>
        <li><a href="{{url('admin/users')}}">مدیریت کاربران</a></li>
        <li><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-sm-10 col-sm-offset-1">

    {!! Form::open() !!}
        <div class="row"><div class="form-group col-xs-4">
                <label >نام-نام خانوادگی</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group col-xs-4">
                <label >ایمیل</label>
                <input type="text" class="form-control" name="email" required>
            </div>
            <div class="form-group col-xs-4">
                <label>نوع کاربر</label>
                <select class="form-control" name="type">
                    <option value="admin">ادمین</option>
                    <option value="expert">کارشناس</option>
                </select>
            </div></div>
        <div class="row"><div class="form-group col-xs-4">
                <label >نام کاربری</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group col-xs-4">
                <label >رمز عبور</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group col-xs-4">
                <label >تکرار رمز عبور</label>
                <input type="password" class="form-control" name="password_confirmation" required >
            </div></div>
        <div class="row"><div class="form-group col-xs-4">
                <label >عکس کاربر</label>
                <input type="file" class="form-control" name="image">
            </div></div>



    <div class="form-group col-xs-12">
        <div>
            <button type="submit" class="btn btn-primary">
                ثبت نام
            </button>
        </div>
    </div>
    {!! Form::close() !!}
    </div>
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
@stop