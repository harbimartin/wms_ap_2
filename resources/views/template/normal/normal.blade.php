@if(isset($normal))
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-bar-chart-o"></i> {{ $normal['tittle'] }}
		</h3>
	</div>
	<div class="panel-body">
		<div class="well">
			<div class="row">
				@foreach($normal['data'] as $data)																
					<div class="col-lg-{{ $data['col'] }}">
						<center>
						<table border="0">
							@foreach($data['main'] as $main)
							<tr>
								<td>{{ $main['label'] }}</td>
								<td width="30px" align="center"> : </td>
								<td>{{ $main['value'] }}</td>
							</tr>						
							@endforeach
						</table>
						</center>
					</div>				
				@endforeach								
			</div>	
		</div>
	</div>
</div>
@endif