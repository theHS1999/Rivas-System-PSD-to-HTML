@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active bold"> مدیریت تعهدات و خدمات</li>
        <li ><a href="{{url('admin')}}">صفحه اصلی</a></li>
    </ol>
    @include('errors.form')
    <a href="{{action('AdminController@getNewCommit')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>ایجاد تعهد جدید</button></a>

    <a href="{{action('AdminController@getNewService')}}"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>ایجاد خدمت جدید</button></a>
    <br/>
    <br>
    <div class="col-xs-12">
    <table class="table table-bordered ">
        <tr class="info bold">
            <td>نام تعهد</td>
            <td>خدمات</td>

        </tr>
        @foreach($commits as $commit)
        <tr class="text-right">
            <td >{{$commit->name}} <a href="{{url('admin/edit-commit/'.$commit->id)}}"><span class="glyphicon glyphicon-edit pull-left" data-toggle="tooltip" data-placement="top" title="ویرایش تعهد"></span></a></td>
            <td>
<ul class="text-right" style="list-style: none;padding-right: 5px">
                @foreach($services as $service)
                    @if($service->commit_id==$commit->id)
                       <li class="col-xs-3 pull-right" style="padding-left: 20px;">- {{$service->name}}
                           <a href="{{url('admin/edit-service/'.$service->id)}}"><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="top" title="ویرایش خدمت"></span></a>
            </li>
                    @endif
                @endforeach
</ul>
            </td>


        </tr>
        @endforeach
    </table>
    </div>

@endsection