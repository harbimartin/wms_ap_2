<table class="table table-bordered table-hover highchart" data-graph-container-before="1" data-graph-type="column" id="xxxtable">
	<thead>
		<tr>
			<th>{{ $row['date'] }}</th>
			<th>Inbound Order</th>
			<th>Outbound Order</th>
		</tr>
	</thead>
	<tbody>
		@foreach($row['main'] as $rows)
			{{ $rows }}
		@endforeach		
	</tbody>
</table>