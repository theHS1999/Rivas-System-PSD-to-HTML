@extends('master')
@section('content')
    <ol class="breadcrumb" >
        <li class="active">مدیریت نسخه ها</li>
        <li ><a href="{{url('expert')}}">صفحه اصلی   </a></li>
    </ol>
   @include('errors.form')
    <div class="col-xs-8 col-xs-offset-2">
        <div class="btn-group" role="group" aria-label="...">
        <a href="{{url('expert/prescs/all')}}"
           class="btn @if($status1==0) btn-primary @else btn-default @endif">همه نسخه ها</a>
        <a href="{{url('expert/prescs/send-to-expert')}}"
           class="btn @if($status1==2) btn-primary @else btn-default @endif">ارسال های به کارشناس</a>
        <a href="{{url('expert/prescs/tickets')}}"
           class="btn @if($status1==3) btn-primary @else btn-default @endif">تیکت های دستی</a>
        <a href="{{url('expert/prescs/registered')}}"
           class="btn @if($status1==1) btn-primary @else btn-default @endif">ثبت شده ها</a>

</div>


        <br><br>
<table class="table table-bordered">
    <tr class="info bold">
        <td>نام دارو خانه</td>
        <td>نام بیمار</td>
        <td>وضعیت</td>
        <td>عملیات</td>
    </tr>

@foreach($prescs as $presc)
        <tr>
            <?php $center=App\ServiceCenter::find($presc->serviceCenter_id);
            $insured=App\Insured::find($presc->insured_id);
            ?>
        <td> {{count($center)>0 ? $center->name : ''}}</td>
        <td>{{count($insured)>0 ? $insured->fname.' '.$insured->lname : ''}}</td>
                <td><?php
                    switch($presc->status){
                        case 1:
                            echo '<span class="label label-success">ثبت شده</span> ';
                            break;
                        case 2:
                            echo '<span class="label label-info">ارسال برای کارشناس - منتظر بررسی</span>';
                            break;
                        case 3:
                            echo '<span class="label label-warning">تیکت دستی - منتظر بررسی</span>';
                            break;
                        case 4:
                            echo '<span class="label label-success">ارسال برای کارشناس - تایید شده</span>';
                            break;
                        case 5:
                            echo '<span class="label label-success">تیکت دستی - تایید شده</span>';
                            break;
                        case 6:
                            echo '<span class="label label-danger">ارسال برای کارشناس - رد شده</span>';
                            break;
                        case 7:
                            echo '<span class="label label-danger">تیکت دستی - رد شده</span>';
                            break;
                        case 8:
                            echo '<span class="label label-danger">رد شده</span>';
                            break;

                    }
                    ?></td>
                <td>

                <a class="btn btn-info btn-xs" @if($presc->status!=2 && $presc->status!=3) href="{{url('print/'.$presc->id)}}" @else href="{{url('expert/presc/'.$presc->id)}}" @endif><span class="glyphicon glyphicon-list-alt"></span>نمایش نسخه</a></td>
        </tr>
    @endforeach
</table>

        <div style="direction: ltr">
            {!! $prescs->render() !!}
        </div>
    </div>
@endsection