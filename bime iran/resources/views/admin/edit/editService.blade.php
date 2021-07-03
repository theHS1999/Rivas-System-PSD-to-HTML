@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">ویرایش خدمت</li>
        <li ><a href="{{url('admin/commits')}}"> مدیریت تعهدات و خدمات</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>

    <div class="col-sm-8 col-sm-offset-2" >
        @include('errors.form')
        {!!Form::open()!!}

        <div class="form-group col-xs-6">
            <label><span class="glyphicon glyphicon-asterisk star"></span>نام خدمت:</label>
            {!!Form::text('name',$service->name,['class'=>'form-control','requird'])!!}
        </div>
        <div class="form-group col-xs-6">
            {!! Form::label('commit_id','نام تعهد:') !!}
            <select name="commit_id" class="form-control">
                @foreach($commits as $commit)
                    <option value="{{$commit->id}}" {{$commit->id==$service->commit_id ? 'selected' : ''}}>{{$commit->name}}</option>
                @endforeach
            </select>

            <br>
        </div>
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary ">
                ویرایش
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