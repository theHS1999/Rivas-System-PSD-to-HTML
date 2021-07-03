@extends('master')
@section('header')
 <script src="{{url('ckeditor/ckeditor.js')}}"></script>
@endsection
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">تنظیمات سیستم</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    {!! Form::open() !!}
    <div class="col-sm-8 col-sm-offset-2">
        @include('errors.form')
    <div class="form-group col-xs-6">
    <label>
      حداکثر قیمت بدون نیاز به تایید    (ریال):
    </label>
   <input type="text" name="max" class="form-control" value="{{$settings[0]->value}}" required>
    </div>
        <div class="form-group col-xs-6">
    <label>تعهد مربوط به دارو خانه ها :</label>
    <select class="form-control" name="commit">
        @foreach($commits as $commit)
            <option value="{{$commit->id}}" @if($settings[1]->value==$commit->id) selected @endif >{{$commit->name}}</option>
            @endforeach
    </select>
        </div>
        <label>پیام صفحه اصلی:</label>
        <div class=" col-xs-12">


           <textarea name="message" id="message" rows="10" cols="80">{{\App\Setting::find(333)->value}}</textarea>
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'message' );
            </script>
        </div>
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary">
                ثبت
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
@endsection