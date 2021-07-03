@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">پزشک جدید</li>
        <li ><a href="{{url('admin/doctors')}}">مدیریت پزشکان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-sm-8 col-sm-offset-2">
        {!! Form::open() !!}
        <div class="form-group col-xs-6">
            <label>نام </label>
            <input type="text" class="form-control" name="fname" required>
        </div>
        <div class="form-group col-xs-6">
            <label>نام خانوادگی</label>
            <input type="text" class="form-control" name="lname" required>
        </div>
        <div class="form-group col-xs-6">
            <label>کد نظام پزشکی</label>
            <input type="text" class="form-control" name="medical_code" required>
        </div>
        <div class="form-group col-xs-6">
            <label>تخصص</label>
            <select type="text" class="form-control select2" name="expertise">
                @foreach($expertises as $expertise)
                    <option value="{{$expertise->id}}">{{$expertise->name}}</option>
                @endforeach

            </select>
        </div>
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary" >
               ایجاد پزشک
            </button>
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