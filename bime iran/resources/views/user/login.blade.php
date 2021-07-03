@extends('master1')

@section('content')
	<div id="content" class="col-sm-6 " >
		<div class="col-md-8 col-md-offset-3" style="margin-top: 30%" id="login">
			<div class="panel panel-default">
				<div class="panel-heading">ورود به سایت</div>
				<div class="panel-body">
					@include('errors.form')
					{!! Form::open(['action'=>'HomeController@postLogin']) !!}
						<div class="form-group col-xs-12 ">
							<label class="control-label">نام کاربری</label>
							<input type="text" class="form-control" name="username">
						</div>
						<div class="form-group col-xs-12">
							<label class="control-label">رمز عبور</label>
							<input type="password" class="form-control" name="password">
						</div>
						<div class="form-group col-xs-12">
							<button type="submit" class="btn btn-info">ورود</button>
						</div>
					{!! Form::close() !!}
					<a href="#">رمز خود را فراموش کردید؟</a>
				</div>
			</div>
		</div>
	</div>
	<div  class="col-sm-6" >
		<div class="col-sm-8 ">
			<img src="{{url('assets/img/logo.gif')}}" class="img-responsive animated zoomIn" id="logo" style="animation-duration: 2s;margin-top: 40%">
		</div>
	</div>
	<script>
		$(document).ready(function(){
			document.getElementById('logo').style.height=document.getElementById('login').clientHeight+'px';
		});
	</script>
@endsection
