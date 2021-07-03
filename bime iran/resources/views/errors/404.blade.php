@extends('master1')
@section('content')
    <div class="col-sm-6 col-sm-offset-3">
        <div class="col-sm-6 col-sm-offset-3">
        <img src="{{url('assets/img/logo.gif')}}" class="img-responsive " >
</div>
        <div class="col-xs-12">
        <div class="alert alert-danger " role="alert" >
            <div style="font-size: 50px;font-weight: bold;padding: 0px;margin-top: -20px">
            @if(Session::has('error'))
                500
            @else
               404
            @endif
            </div>
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            @if(Session::has('error'))
                 {{ Session::get('error') }}
                </div>
            @else
                صفحه مورد نظر یافت نشد
                @endif

        </div>
    </div>
    </div>
@endsection