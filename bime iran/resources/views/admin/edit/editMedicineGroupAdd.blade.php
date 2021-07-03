@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">تخصیص گروهی تخصص و کاربری خاص</li>
        <li ><a href="{{url('admin/medicines')}}">  مدیریت داروها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    {!! Form::open() !!}
    <div class="col-sm-10 col-sm-offset-1">
        <div class="col-xs-5">
            <div class="form-group  col-xs-12">
                <label>تخصص ها</label>
                <select name="exps[]" class="select2 form-control" multiple required>
                    @foreach($exps as $exp)
                        <option value="{{$exp->id}}">{{$exp->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group  col-xs-12">
                <label>کاربری های خاص</label>
                <select name="uses[]" class="select2 form-control" multiple >
                    @foreach($uses as $use)
                        <option value="{{$use->id}}">{{$use->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-7">
            <div class="form-group  col-xs-12">
                <label>داروها</label>
                <select name="meds[]" class="select2 form-control" style="height: 100px" multiple >
                    @foreach($meds as $med)
                        <option value="{{$med->id}}">{{$med->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary" >
                ویرایش
            </button>
        </div>

    </div>

        {!! Form::close() !!}
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