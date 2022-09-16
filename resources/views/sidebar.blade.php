<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html"><center>e-Air Cargo</center></a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> {{ Session::get('name') }} Profile</a>
                </li>                
                <li class="divider"></li>
                <li><a href="{{ url('main/log-out') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <img src="{{ asset('pictures') }}/log_ap.jpeg" alt="for" width="46%" height="30%" /> <img src="{{ asset('pictures') }}/logo_escc.jpeg" alt="by" width="46%" height="30%" />                
                </li>                
                @if(Session::get('role') == 'admin')
				<li><a href="{{ url('master/module') }}"><i class="fa fa-cloud fa-fw"></i>Module Management</a></li>
                <li><a href="#"><i class="fa fa-list fa-fw"></i> Master Management<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">						
						<li><a href="{{ url('master/mnf') }}"><i class="fa fa-minus fa-fw"></i>Cargo Handler Management</a></li>
                        <li><a href="#"><i class="fa fa-minus fa-fw"></i>SKU Management<span class="fa arrow"></a>
                            <ul class="nav nav-third-level">                   
                                <li><a href="{{ url('master/create-sku') }}">Create SKU</a></li>
                                <li><a href="{{ url('master/update-sku') }}">View/Update SKU</a></li>
                                <li><a href="{{ url('master/sku-slotting') }}">Slotting By ABC Analysis</a></li>
                                <li><a href="{{ url('master/sku-slotting-fsn') }}">Slotting By FSN Analysis</a></li>								
                            </ul>         
                        </li>
                        <li><a href="{{ url('master/uom') }}"><i class="fa fa-minus fa-fw"></i>UOM Management</a></li>                                                
                        <li><a href="{{ url('master/slot') }}"><i class="fa fa-minus fa-fw"></i>Slot Management</a></li>												
                    </ul>
                    <!-- /.na Handv-second-level -->
                </li>                 
				<li><a href="{{ url('master/incoming-order') }}"><i class="fa fa-inbox fa-fw"></i> Incoming Order (Receiver) <span class="label label-danger">12</span></a></li>
                <li><a href="#"><i class="fa fa-arrow-right fa-fw"></i> Inbound Management <span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-danger">{{ Session::get('data-order.po-total') }}</span><span class="fa arrow"></a>
                    <ul class="nav nav-second-level">                        
                        <li><a href="{{ url('inbound/inb-po') }}"><i class="fa fa-minus fa-fw"></i>Inbound Purchase Order <span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-warning">{{ Session::get('data-order.po-ipo') }}</span></a></li>
                        <li><a href="{{ url('inbound/inb-receiving') }}"><i class="fa fa-minus fa-fw"></i>Inbound Receiving <span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-warning">{{ Session::get('data-order.po-ir') }}</span></a></li>                        
                        <li><a href="{{ url('inbound/inb-put-away') }}"><i class="fa fa-minus fa-fw"></i>Put Away<span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-warning">{{ Session::get('data-order.po-pa') }}</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><i class="fa fa-arrow-left fa-fw"></i> Outbound Management <span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-danger">{{ Session::get('data-order.so-total') }}</span><span class="fa arrow"></a>
                    <ul class="nav nav-second-level">                        
                        <li><a href="{{ url('outbound/open-so') }}"><i class="fa fa-minus fa-fw"></i>Open Sales Order <span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-warning">{{ Session::get('data-order.so-oso') }}</span></a></li>
                        <li><a href="{{ url('outbound/picking-list-so') }}"><i class="fa fa-minus fa-fw"></i>Picking List<span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-warning">{{ Session::get('data-order.so-pl') }}</span></a></li>                        
                        <li><a href="{{ url('outbound/do-so') }}"><i class="fa fa-minus fa-fw"></i>Delivery Order<span class="pull-right"><i class="icon-angle-left"></i></span>&nbsp;<span class="label label-warning">{{ Session::get('data-order.so-do') }}</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><i class="fa fa-cube fa-fw"></i> Storage Management<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('storage/storage') }}"><i class="fa fa-minus fa-fw"></i>Storage Mapping</a></li>
                        <li><a href="{{ url('storage/stock-adjustment') }}"><i class="fa fa-minus fa-fw"></i>Stock Adjustment</a></li>
                        <li><a href="{{ url('storage/temp-storage') }}"><i class="fa fa-minus fa-fw"></i>Temporary Storage</a></li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>                
                <li><a href="#"><i class="fa fa-table fa-fw"></i> Report<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('throughput') }}?year=2013"><i class="fa fa-minus fa-fw"></i>Throughput</a></li>
                        <li><a href="{{ url('inventory-utilization') }}?year=2013"><i class="fa fa-minus fa-fw"></i>Inventory Utilization</a></li>                        
						<li><a href="{{ url('storage-utilization') }}"><i class="fa fa-minus fa-fw"></i>Storage Utilization</a></li>
						<li><a href="{{ url('revenue') }}"><i class="fa fa-minus fa-fw"></i>Revenue</a></li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li> 
                @endif
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>