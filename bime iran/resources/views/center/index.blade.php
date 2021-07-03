@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active">صفحه اصلی   </li>
    </ol>



    <div class="col-sm-8 col-sm-offset-2" style="border: 1px solid #7f7f7f;border-radius: 10px">
        {!! \App\Setting::find(333)->value !!}

    </div>

@endsection