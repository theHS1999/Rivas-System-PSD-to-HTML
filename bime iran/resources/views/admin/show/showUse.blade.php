@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">نمایش کاربری خاص
        - {{$use->name}}</li>
        <li ><a href="{{url('admin/specialuses')}}">کاربری های خاص</a></li>
        <li><a href="{{url('admin/medicines')}}">  مدیریت داروها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    <div class="col-sm-8 col-sm-offset-2">
        <table class="table table-bordered">
            <tr class="info bold">
                <td>دارو های   {{$use->name}}</td>
            </tr>
            <tr>
                <td>
                    <ul class="text-right" >
                        <ul class="text-right" style="list-style: square">
                            @foreach($meds as $med)
                                <li style="width: 25%;float: right">{{$med->name}}  </li>

                            @endforeach


                        </ul>


                    </ul>
                </td>
            </tr>
        </table>
    </div>

@stop