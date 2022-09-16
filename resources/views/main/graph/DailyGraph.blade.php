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
				<div class="col-lg-12">
					@include('template/bc/bc')
				</div>
			</div>			
			<div class="row">				
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">
								<i class="fa fa-bar-chart-o"></i> Inbound Outbound Daily Report.
							</h3>							
						</div>
						<div class="panel-body">
							{{ Form::open(array('method'=>'POST','route'=>'postDaily')) }}
							<div class="row">
								<div class="col-lg-4">
									<input type="date" name="start_date" class="form-control input-sm" />
								</div>
								<div class="col-lg-4">
									<input type="date" name="end_date" class="form-control input-sm" />
								</div>
								<div class="col-lg-4">
									{{ Form::submit('Submit', array('class'=>'btn btn-primary')) }}
								</div>								
							</div>
							{{ Form::close() }}

							@if(isset($row))
								@include('template.graph')
							@endif
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
	<script type="text/javascript">
	    function generate(type,text) {
	        var n = noty({
	            text        : text,
	            type        : type,
	            dismissQueue: true,
	            modal       : false,
	            maxVisible  : 3,
	            timeout     : 5000,
	            layout      : 'top',
	            theme       : 'defaultTheme'
	        });

	        console.log('html: ' + n.options.id);
	    }
	    	
	    function generateAll() {
			@if(Session::get('role_name') == "Checker") 
				@if(Session::get('CountIOD') > 0)   	
	        		generate('success',"There are <b>{{ Session::get('CountIOD') }}</b> inbound orders you have to proceed");
	        	@endif

	        	@if(Session::get('CountOOD') > 0)   	
	        		generate('success',"There are <b>{{ Session::get('CountOOD') }}</b> outbound orders you have to proceed");
	        	@endif
	        @endif

	        @if(Session::get('role_name') == "Client")  
	        	@if(Session::get('CountRO') > 0)  	
	        		generate('success',"There are <b>{{ Session::get('CountRO') }}</b> inbound orders received");        
	        	@endif

	        	@if(Session::get('CountORO') > 0)  	
	        		generate('success',"There are <b>{{ Session::get('CountORO') }}</b> outbound orders shipped");        
	        	@endif
	        @endif

	        @if(Session::get('role_name') == "OrderEntry")
	        	@if(Session::get('CountIS') > 0)    	
	        		generate('success',"There are <b>{{ Session::get('CountIS') }}</b> inbound orders you have to proceed");        
	        	@endif

	        	@if(Session::get('CountOS') > 0)    	
	        		generate('success',"There are <b>{{ Session::get('CountOS') }}</b> outbound orders you have to proceed");        
	        	@endif
	        @endif
	    }    

	    $(document).ready(function () {
	        generateAll();
	    });
	</script>	
</body>
@stop