@extends('layout') 
@section('content')
<head>
@include('head')
</head>
<body>
	<div class="loader"></div>
	<div id="wrapper">
		<!-- Sidebar -->
		@include('sidebar')
		<div id="page-wrapper">					
			<div class="row">	
				<hr/>			
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">
								<i class="fa fa-bar-chart-o"></i> Welcome to WMS.
							</h3>							
						</div>
						<div class="panel-body">
							<center>								
								Helo, <b> {{ Session::get('name') }} </b>.<br/>
								You're logged in as <b> {{ Session::get('role') }} </b>.
							</center>									
						</div>
					</div>					
				</div>						
			</div>												
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

	<!-- JavaScript -->
	@include('js')
</body>
@stop