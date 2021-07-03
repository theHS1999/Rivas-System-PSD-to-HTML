
@if($errors->any())
    <h3 style="text-align: right">توجه : </h3>
<ul class="alert alert-danger" style="text-align: right;list-style: none">
    @foreach($errors->all() as $error)
    <li ><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span> {{$error}} </li>
    @endforeach
</ul>
@endif
@if(Session::has('message'))
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> {{ Session::get('message') }}
    </div>
@endif
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ Session::get('error') }}
    </div>
@endif