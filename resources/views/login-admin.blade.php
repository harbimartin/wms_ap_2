@extends('layout')

@section('content')
	<head>
		<title>e-Air Cargo</title>
		<!-- Bootstrap Core CSS -->
		<link href="{{ asset('sb-admin-2') }}/css/bootstrap.min.css" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="{{ asset('sb-admin-2') }}/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="{{ asset('sb-admin-2') }}/css/sb-admin-2.css" rel="stylesheet">

		<!-- Custom Fonts -->
		<link href="{{ asset('sb-admin-2') }}/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		
	</head>
	<body style="
		background: url('{{ asset('sb-admin-2') }}/background.jpeg') no-repeat center center fixed; 
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;		
	">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-panel panel panel-default" >
						<div class="panel-heading">
							<h3 class="panel-title"><center><b>e-Air Cargo</b><center></h3>							
						</div>
						<div class="panel-body">
							@if($errors->count() > 0)				
								<div class="alert alert-danger">		
								@foreach($errors->all() as $err)											
									{{ $err }} <br/>											
								@endforeach		
								</div>				
							@endif
							
							@if(!empty(Session::get('denied')))				
								<div class="alert alert-danger">										
									{{ Session::get('denied') }} <br/>																	
								</div>				
							@endif
							{{ Form::open(array('url'=>'main/log-in','method'=>'POST','role'=>'form')) }}							
								<fieldset>
									<div class="form-group">
										<input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="Password" name="password" type="password" value="">
									</div>									
									<!-- Change this to a button or input when using this as a form -->
									<input type="submit" value="Login" class="btn btn-lg btn-success btn-block"/>
								</fieldset>
							</form>
							<br/>
							<center><img src="{{ asset('pictures') }}/log_ap.jpeg" alt="for" width="46%" height="30%" /> <img src="{{ asset('pictures') }}/logo_escc.jpeg" alt="by" width="46%" height="30%" /></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
@stop