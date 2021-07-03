@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">ویرایش دارو</li>
        <li ><a href="{{url('admin/medicines')}}">  مدیریت داروها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    @include('errors.form')
    <div class="col-sm-8 col-sm-offset-2">
        {!!Form::model($medicine)!!}

        <div class="form-group col-xs-6">
            {!! Form::label('name','نام دارو') !!}
            {!!Form::text('name',null,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-6" >
            {!! Form::label('price','قیمت دارو') !!}



            {!!Form::text('price',null,['class'=>'form-control','requird'])!!}

        </div>
        <div class="form-group  col-xs-6">
            {!! Form::label('type','نوع دارو') !!}

            {!!Form::select('type',[1=>'فارماکوپه ای',2=>'اقلام خارجی'],null,['class'=>'form-control'])!!}


        </div> <div class="form-group  col-xs-6">
            {!! Form::label('shape','شکل دارو') !!}
            {!!Form::text('shape',null,['class'=>'form-control','requird'])!!}


        </div>


        <div class="form-group col-xs-6" >
            {!! Form::label('first_insure_percent','درصد بیمه گر پایه') !!}
            {!!Form::text('first_insure_percent',null,['class'=>'form-control col-sm-12','requird'])!!}
        </div>
        <div class="form-group col-xs-6" >
            {!! Form::label('iran_percent','درصد بیمه ایران') !!}
            {!!Form::text('iran_percent',null,['class'=>'form-control col-sm-12'])!!}
        </div>
        <div class="form-group col-xs-12" >
            <label for="">تخصص های مجاز برای تجویز</label>
            <select name="med_cats[]" id="" class="select2 form-control" multiple>
                @foreach($expertises as $expertise)
                    <option value="{{$expertise->id}}" @foreach($med_cats as $med_cat) {{$med_cat->expertise_id==$expertise->id ? 'selected' : ''}} @endforeach>{{$expertise->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-xs-12" >
            <label for="">کاربری های خاص</label>
            <select name="special_uses[]" id="" class="select2 form-control" multiple>
                @foreach($special_uses as $special_use)
                    <option value="{{$special_use->id}}" @foreach($special_meds as $special_med) {{$special_med->use_id==$special_use->id ? 'selected' : ''}} @endforeach>{{$special_use->name}}</option>
                @endforeach
            </select>
        </div>
       <div class="form-group  col-sm-12">
           <button type="submit" class="btn btn-primary">
               ویرایش
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