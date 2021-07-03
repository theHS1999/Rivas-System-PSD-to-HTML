@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold"> مدیریت قراردادها</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
        {!! Form::open(['url' => 'admin/search','class'=>'pull-left col-xs-4 s']) !!}
        <style>
            .select2-container--default .select2-selection--single{
                border-bottom-left-radius: 0px;
                border-top-left-radius: 0px;
                text-align: right;
            }
        </style>

        <select class="search" name="search" style="width: 80%">
            <option value=""></option>
            @foreach($contracts as $contract)
                <?php $insurer=App\Insurer::find($contract->insurer_id); ?>

                <option value="{{url('admin/show-contract/'.$contract->id)}}">{{$contract->contract_num}} -  {{$insurer->name}}</option>
            @endforeach
        </select>
        <button class="btn btn-info " type="submit" style="margin-right: -5px"><span class="glyphicon glyphicon-search" style="padding: 0;margin: 0"></span></button>

        {!! Form::close() !!}
    </ol>
@include('errors.form')
    <div class="row">
        <a href="{{action('AdminController@getNewContract')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد قرارداد جدید</button></a>

        <br><br>
    </div>

    <table class="table table-bordered">
    <tr class="info bold">
        <td>شماره قرارداد</td>
        <td>بیمه گزار</td>
        <td>تاریخ شروع قرارداد</td>
        <td>تاریخ پایان قرارداد</td>

    </tr>
    @foreach($contracts as $contract)
        <tr>
            <td><a href="{{url('admin/show-contract/'.$contract->id)}}">{{$contract->contract_num}}</a></td>
            <td>
                <?php $insurer=App\Insurer::find($contract->insurer_id); ?>
                    <a href="{{url('admin/show-insurer/'.$insurer->id)}}"> {{$insurer->name}}</a>

            </td>
            <td>
                {{$contract->start_date}}
            </td> <td>
                {{$contract->finish_date}}
            </td>

        </tr>


    @endforeach
    </table>
@endsection