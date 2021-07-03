@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">تعهد جدید</li>
        <li ><a href="{{url('admin/commits')}}"> مدیریت تعهدات و خدمات</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    <div class="col-sm-4 col-sm-offset-4" >
@include('errors.form')
{!!Form::open()!!}

<div class="form-group col-xs-12">
    <label></span>نام تعهد:</label>

    {!!Form::text('name',null,['class'=>'form-control','required'])!!}
</div>

        <div class="form-group  col-xs-12">

                <button type="submit" class="btn btn-primary">
                    ثبت  تعهد
            </button>
        </div>

{!!Form::close()!!}
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
@endsection