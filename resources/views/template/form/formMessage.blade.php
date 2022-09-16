@if($errors->count() > 0)
	<div class="alert alert-dismissable alert-danger">													
	@foreach($errors->all() as $err)											
		{{ $err }} <br/>											
	@endforeach										
	</div>
@endif

@if(!empty(Session::get('alert_msg')))	
	<div class="alert alert-dismissable alert-danger" style="margin-bottom: 0;">
		{{ Session::get('alert_msg') }} <br/>
	</div>
	<br/>
@endif	

@if(!empty(Session::get('msg')))	
	<div class="alert alert-dismissable alert-success">
		{{ Session::get('msg') }}
	</div>
@endif