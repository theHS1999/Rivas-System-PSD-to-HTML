@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">  داروی جدید</li>
        <li ><a href="{{url('admin/medicines')}}">  مدیریت داروها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
@include('errors.form')
    <div class="col-sm-8 col-sm-offset-2">
        {!!Form::open()!!}
<div class="row">
    <div class="form-group col-xs-6">
        {!! Form::label('name','نام دارو') !!}
        {!!Form::text('name',null,['class'=>'form-control','required'])!!}
    </div>
    <div class="form-group col-xs-6" >
        {!! Form::label('price','قیمت دارو') !!}



        {!!Form::text('price',null,['class'=>'form-control','required'])!!}

    </div>
</div>
<div class="row">
    <div class="form-group  col-xs-6">
        {!! Form::label('type','نوع دارو') !!}

        {!!Form::select('type',[1=>'فارماکوپه ای',2=>'اقلام خارجی'],null,['class'=>'form-control'])!!}


    </div>
    <div class="form-group  col-xs-6">
        {!! Form::label('shape','شکل دارو') !!}
        {!!Form::text('shape',null,['class'=>'form-control','required'])!!}
    </div>
</div>
<div class="row">
    <div class="form-group col-xs-6" >
        {!! Form::label('first_insure_percent','درصد بیمه گر پایه') !!}
        {!!Form::text('first_insure_percent',null,['class'=>'form-control col-sm-12','required'])!!}
    </div>
    <div class="form-group col-xs-6" >
        {!! Form::label('iran_percent','درصد بیمه ایران') !!}
        {!!Form::text('iran_percent',null,['class'=>'form-control col-sm-12'])!!}
    </div>
</div>



        <div class="form-group col-xs-12" >
            <label for="">تخصص های دارو</label>
            <select name="med_cats[]" id="" class="select2 form-control" multiple>
                @foreach($expertises as $expertise)
                    <option value="{{$expertise->id}}" >{{$expertise->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-xs-12" >
            <label for="">کاربری های خاص</label>
            <select name="special_uses[]" id="" class="select2 form-control" multiple>
                @foreach($special_uses as $special_use)
                    <option value="{{$special_use->id}}" >{{$special_use->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group  col-sm-12">
            <button type="submit" class="btn btn-primary">
               ثبت دارو
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