<ul class="nav navbar-nav navbar-right navbar-user">
	@if(!empty(Session::get('role_name')))
		<li class=""><a> {{ Session::get("name") }} </a></li>
		<li class=""><a> {{ Session::get("role_name") }} </a></li>
		<li class=""><a href="#" > Logout </a></li>
	@else
		<li class=""><a> JERRYS </a></li>
		<li class=""><a> DEVELOPER </a></li>
		<li class=""><a href="{{ url('/main/log-out') }}" > Logout </a></li>
	@endif
</ul>