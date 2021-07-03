@extends('master')

@section('content')
    <link rel="stylesheet" href="{{url('assets/css/formValidation.min.css')}}">
    <script src="{{url('assets/js/formValidation.min.js')}}"></script>
    <script src="{{url('assets/js/fr/bootstrap.min.js')}}"></script>
    <script src="{{url('assets/js/fa_IR.js')}}"></script>
    <ol class="breadcrumb" >
        <li class="active bold"> بیمه گزار جدید </li>
        <li ><a href="{{url('admin/insurers')}}"> مدیریت بیمه گذاران و بیمه شدگان</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    <div class="col-sm-8 col-sm-offset-2" >

        <script src="{{url('ckeditor/ckeditor.js')}}"></script>
         <!--<textarea name="editor1" id="editor1" rows="10" cols="80">
                This is my textarea to be replaced with CKEditor.
            </textarea>
        <script>
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace( 'editor1' );
        </script>-->
        @include('errors.form')
        {!! Form::open() !!}
        <div class="row">
        	<div class="form-group col-xs-6 ">
            <label>نام </label>

            {!!Form::text('name',null,['class'=>'form-control '])!!}

 </div>
        <div class="form-group col-xs-6">
        	<label>آدرس </label>


            {!!Form::text('address',null,['class'=>'form-control'])!!}

        </div>
        </div>
    <div class="row">
        <div class="form-group col-xs-6">
        	<label> تلفن ثابت </label>


            {!!Form::text('phone',null,['class'=>'form-control'])!!}
        </div>
        <div class="form-group col-xs-6">
        	<label> شماره موبایل </label>

            {!!Form::text('mobile',null,['class'=>'form-control'])!!}
        </div>
    </div>
        <div class="row">
            <div class="form-group col-xs-6">
                <label> ایمیل </label>
                {!!Form::text('mail',null,['class'=>'form-control'])!!}
            </div>
        <div class="form-group col-xs-6">
            {!! Form::label('fax','فکس') !!}
            {!!Form::text('fax',null,['class'=>'form-control'])!!}
        </div>

        </div>
            <div class="row">
                <div class="form-group col-xs-6">
                    {!! Form::label('website','وبسایت') !!}
                    {!!Form::text('website',null,['class'=>'form-control'])!!}
                </div>
        <div class="form-group col-xs-6" >
        	
        	<label> مسئول واحد درمانی </label>
            
            
            {!!Form::text('treatment_name',null,['class'=>'form-control'])!!}
        </div>

            </div>
        <div class="form-group col-xs-12">

            <button type="submit" class="btn btn-primary">
                ثبت بیمه گزار
            </button>

        </div>
        {!! Form::close() !!}

    </div>


<script>
    $(document).ready(function() {
        $('form').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'fa_IR',
            fields: {
                name: {
                    validators: {
                        notEmpty: {}
                    }
                },
                address: {
                    validators: {
                        notEmpty: {}
                    }
                },
                phone: {
                    validators: {
                        notEmpty: {

                        },
                        phone: {
                            country: 'CN',
                            message: 'شماره تلفن معتبر نیست ( مثال: 01234567899 )'
                        }
                    }
                },
                mobile: {
                    validators: {

                        notEmpty: {

                        },
                        phone: {
                            country: 'CN',
                            message: 'شماره موبایل معتبر نیست (مثال: 09112223344)'
                        }
                    }
                },
                mail: {
                    validators: {
                        notEmpty: {

                        },
                        emailAddress:{}
                    }
                }
            }
        });
    });
</script>
@endsection
