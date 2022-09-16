@extends('layout') 
@section('content')
<head>
@include('head')
</head>
<body>
	asdasd
	<div id="wrapper">
		<!-- Sidebar -->
		@include('sidebar')
		<div id="page-wrapper">						
			<div class="row">				
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">
								<i class="fa fa-bar-chart-o"></i> Something
							</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<center>Something</center>
							</div>							
						</div>
					</div>
				</div>
			</div>			
			<!-- /.row -->					<

		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

	<!-- JavaScript -->
	@include('js')
</body>
@stop