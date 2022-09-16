@extends('layout')
@section('content')
<head>
</head>
<body>
      <div class="loader"></div>
	<div id="wrapper">
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					@include('template/table/table')
				</div>
			</div>
		</div>
		<!-- /#page-wrapper -->
		<div style="position:fixed; width:100%; height:320px; padding:5px; bottom:0px; ">
            <table width="100%">
                  @foreach($sign as $sg)
                        <tr>
                              @foreach($sg as  $sgd)
                                    <td width="23%">
                                          <p>
                                                {{ isset($sgd[0]) ? $sgd[0] : NULL }}<br/>
                                                {{ isset($sgd[1]) ? $sgd[1] : NULL }}
                                                <br/><br/><br/>
                                                __________________<br/>
                                                Name:
                                          </p>
                                    </td>
                              @endforeach
                        </tr>
                  @endforeach
            </table>
        </div>
	</div>
	<!-- /#wrapper -->
</body>
@stop
