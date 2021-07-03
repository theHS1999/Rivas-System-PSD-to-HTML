@extends('master1')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					{!! Form::open() !!}
						<div class="form-group">
							<label class="col-md-4 control-label">نام-نام خانوادگی</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">نام کاربری</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="username">
							</div>
						</div>



						<div class="form-group">
							<label class="col-md-4 control-label">رمز عبور</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">تکرار رمز عبور</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									ثبت نام
								</button>
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
