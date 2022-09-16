<!-- <script src="{{ asset('sb-admin') }}/js/jquery-1.10.2.js"></script>
<script src="{{ asset('sb-admin') }}/js/bootstrap.js"></script> -->


<!-- jQuery Version 1.11.0 -->
<script src="{{ asset('sb-admin-2') }}/js/jquery-1.11.0.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('sb-admin-2') }}/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('sb-admin-2') }}/js/plugins/metisMenu/metisMenu.min.js"></script>

<!-- Morris Charts JavaScript
<script src="{{ asset('sb-admin-2') }}/js/plugins/morris/raphael.min.js"></script>
<script src="{{ asset('sb-admin-2') }}/js/plugins/morris/morris.min.js"></script>
<script src="{{ asset('sb-admin-2') }}/js/plugins/morris/morris-data.js"></script> -->

<!-- Custom Theme JavaScript -->
<script src="{{ asset('sb-admin-2') }}/js/sb-admin-2.js"></script>

<script src="{{ asset('media') }}/js/jquery.dataTables.js"></script>

<script type="text/javascript" src="{{ asset('source') }}/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="{{ asset('source') }}/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="{{ asset('source') }}/helpers/jquery.fancybox-media.js"></script>
<script type="text/javascript" src="{{ asset('source') }}/helpers/jquery.fancybox-thumbs.js"></script>
<script type="text/javascript" src="{{ asset('datepicker') }}/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('noty') }}/packaged/jquery.noty.packaged.js"></script>
<script type="text/javascript" src="{{ asset('com-master') }}/bootstrap-combobox.js"></script>
<!-- <script type="text/javascript" src="{{ asset('highchart') }}/highcharts.js"></script>
<script type="text/javascript" src="{{ asset('highchart') }}/jquery.highchartTable.js"></script> -->
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<script>
    $(document).ready(function() {
        $('#container').highcharts({
            data: {
                table: document.getElementById('datatable')
            },
            chart: {
                type: 'column'
            },
            title: {
                text: '{{ Session::get('chart-title') }}'
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: '{{ Session::get('chart-y-text') }}'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                        this.point.y + ' {{ Session::get('chart-text') }}';
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#example').dataTable();
    });
</script>

<script>
    $(document).ready(function() {
        $(".fancy").fancybox({
            type: "iframe",
            fitToView: true,
            width: '600px',
            closeClick: true,
            openEffect: 'none',
            afterClose: function() {
                location.reload();
                return;
            },
            padding: 0
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(".fancy2").fancybox({
            type: "iframe",
            fitToView: true,
            width: '600px',
            closeClick: true,
            openEffect: 'none',
            afterClose: function() {
                location.reload();
                return;
            },
            padding: 0
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(".fajax").fancybox({
            type: "iframe",
            fitToView: true,
            width: '600px',
            minHeight: '30px',
            closeClick: true,
            openEffect: 'none',
            afterClose: function() {
                location.reload();
                return;
            },
            padding: 0
        });
    });
</script>

<script>
    function doconfirm() {
        job = confirm("Are you sure?");
        if (job != true) {
            return false;
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#{{ $table['search'] ?? null }}').keyup(function() {
            searchTable($(this).val());
        });
    });

    function searchTable(inputVal) {
        var table = $('#{{ $table['default'] ?? null }}');
        table.find('tr').each(function(index, row) {
            var allCells = $(row).find('td');
            if (allCells.length > 0) {
                var found = false;
                allCells.each(function(index, td) {
                    var regExp = new RegExp(inputVal, 'i');
                    if (regExp.test($(td).text())) {
                        found = true;
                        return false;
                    }
                });
                if (found == true) $(row).show();
                else $(row).hide();
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.combobox').combobox({
            bsVersion: '3'
        });
    });
</script>

<!-- <script type="text/javascript">
    $(document).ready(function() {
        $('input:submit').click(function() {
            $('input:submit').attr("disabled", true);
            $(this).parents('form').submit()
        });
    });
</script> -->
