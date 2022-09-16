@if(isset($bc))
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				@foreach($bc as $bc)
					@if( $bc[0] == "active")
						<li class="{{ $bc[0] ?? NULL }}"> {{ $bc[2] ?? NULL }} </li>
					@else
						<li class="{{ $bc[0] ?? NULL }}"><a href="{{ $bc[1] ?? NULL }}"> {{ $bc[2] ?? NULL }}</a></li>
					@endif
				@endforeach
			</ol>
		</div>
	</div>
@endif
