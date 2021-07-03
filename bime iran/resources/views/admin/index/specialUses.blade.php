@extends('master')

@section('content')
    <ol class="breadcrumb" >
        <li class="active bold">کاربری های خاص</li>
        <li><a href="{{url('admin/medicines')}}">  مدیریت داروها</a></li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    @include('errors.form')
    <a class="btn btn-primary" href="{{url('admin/new-specialuses')}}"><span class="glyphicon glyphicon-plus"></span>ایجاد کاربری خاص جدید</a>
    <br><br>
    <table class="table table-bordered">
        <tr class="info bold">
            <td>کاربری های خاص</td>
        </tr>
        <tr>
            <td>
                <ul class="text-right" >
                    @foreach($uses as $use)
                        <li style="width: 25%;float: right"><a href="{{url('admin/show-use/'.$use->id)}}">{{$use->name}}</a>  <a href="{{url('admin/edit-specialuses/'.$use->id)}}"><span class="glyphicon glyphicon-edit"></span></a></li>

                    @endforeach


                </ul>
            </td>

        </tr>
    </table>
@endsection