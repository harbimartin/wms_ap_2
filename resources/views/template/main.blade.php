@extends('layout') 
@section('content')
<head>
@include('head ')
</head>
<body>	
	<div id="wrapper">
		<!-- Sidebar -->
		@include('sidebar')
		<div id="page-wrapper">
			<div class="row">
		        <div class="col-lg-12">
		            <div class="page-header">@include('template/bc/bc')</div>
		        </div>
		        <!-- /.col-lg-12 -->
		    </div>
		    <div class="row">
				<div class="col-lg-12">
					@include('template/form/form')
				</div>
			</div>			
			<br/>
			<div class="row">
				<div class="col-lg-12">
					@include('template/table/table')
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