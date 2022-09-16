@if(isset($table))
<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-table"></i> {!! $table['tittle'] ?? NULL !!}
	</div>
	<div class="panel-body">
		@include('template/form/formMessage')
		@if(!isset($table['default']))
			<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		@endif
		@if(isset($table['ex']))
			@foreach($table['ex'] as $ex)
				{!! $ex ?? NULL !!}
			@endforeach
		@endif

		@if(isset($table['search']))
			<input class="form-control input" placeholder="Type Any Keyword" id="{!! $table['search'] ?? NULL !!}"/>
			<br/>
		@endif

		<div class="table-responsive">
		@if(isset($table['default']))
			<table cellpadding="0" cellspacing="0" border="{{ isset($table['border']) ? $table['border'] : '0' }}" width="100%" class="table table-bordered table-hover" id="{!! $table['default'] ?? NULL !!}">
		@else
			<table cellpadding="0" cellspacing="0" border="{{ isset($table['border']) ? $table['border'] : '0' }}" class="display" id="datatable" width="100%">
		@endif
			<thead>
				<tr>
					@if(isset($table['head']))
						@foreach($table['head'] as $head)
							<th width="{!! $head['width'] ?? NULL !!}">{!! $head['name'] ?? NULL !!}</th>
						@endforeach
					@endif
				</tr>
			</thead>

			<tbody>
				@if(isset($table['row']))
					@foreach($table['row'] as $row)
						{!! $row ?? NULL !!}
					@endforeach
				@endif
			</tbody>
		</table>
		@if(isset($table['pagination']))
			{!! $table['pagination'] ?? NULL !!}
		@endif
		</div>
	</div>
</div>
@endif
