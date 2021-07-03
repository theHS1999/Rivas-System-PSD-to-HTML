@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">ویرایش کاربر</li>
        <li><a href="{{url('admin/users')}}">مدیریت کاربران</a></li>
        <li><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-sm-10 col-sm-offset-1">

        {!! Form::open(['files'=>true]) !!}
        <hr>
        <div  class="row">


            <div class="form-group col-xs-8">
                <div class="form-group col-xs-6">
                    <label >نام-نام خانوادگی</label>
                    <input type="text" class="form-control" name="name" value="{{$user->fullname}}" required>
                </div>

                <div class="form-group col-xs-6">
                    <label >ایمیل</label>
                    <input type="text" class="form-control" name="email" value="{{$user->email}}" required>
                </div>
                <div class="form-group col-xs-6">
                    <label>نوع کاربر</label>
                    <select class="form-control" name="type" >
                        <option value="admin" {{$user->type=='admin' ? 'selected' : ''}}>ادمین</option>
                        <option value="expert" {{$user->type=='expert' ? 'selected' : ''}}>کارشناس</option>
                    </select>
                </div>
                <div class="form-group col-xs-6">
                    <label >نام کاربری</label>
                    <input type="text" class="form-control" name="username" value="{{$user->username}}" required>
                </div>
                <div class="form-group col-xs-6">
                    <label >رمز عبور جدید</label>
                    <input type="password" class="form-control" name="password" >
                </div>

                <div class="form-group col-xs-6">
                    <label >تکرار رمز عبور جدید</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>

            </div>
            <div class="form-group col-xs-4">
                <div class="col-xs-12">
                    <label >عکس جدید</label>
                    <input type="file" class="form-control" name="image">
                </div>
                <div class="col-xs-12" >


                        @if($user->pic!=null)
                            <img src="{{url('uploads/'.$user->pic)}}" class="img-responsive thumbnail"  alt=>
                        @endif





                </div></div>
        </div>
        <hr>


        <div class="form-group col-xs-12">
            <div>
                <button type="submit" class="btn btn-primary">
                    ویرایش
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