<?php


namespace App;

use App\Http\Helper\HTML;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Random extends Model {
    function IncomingOrder() {
        // Manufacture Table Configuration
        $table['tittle'] = 'Incoming Order';
        $table['head'] = HTML::jrsTableHead(['Source', 'Order Type', 'Airline', 'Tenant', 'Action']);
        $table['default'] = "zzzz";
        $table['search'] = "search";

        for ($i = 0; $i <= 12; $i++) {
            $col = [
                array_rand(['NSW' => 'NSW', 'SITATEX' => 'SITATEX']),
                array_rand(['Inbound' => 'Inbound', 'Outbound' => 'Outbound']),
                array_rand(['Garuda' => 'Garuda', 'Air Asia' => 'Air Asia', 'Lion Air' => 'Lion Air']),
                array_rand(['JASCardig' => 'JASCardig', 'Gapura' => 'Gapura']),
                HTML::jrsBtn(['#', 'Receive', 'success', '', FALSE, 'check'])
            ];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    public function TroughputVolumeTable(Request $request) {
        $table['head'] = HTML::jrsTableHead(['', 'Incoming - Inbound', 'Incoming - Outbound', 'Outgoing - Inbound', 'Outgoing - Outbound']);
        $table['ex'][] = '<form action="' . url('/throughput') . '" method="get">
		<select name="year"><option value="2013">2013</option><option value="2014">2014</option></select>
		<input type="submit" value="submit"/></form>';

        if ($request->year == '2013') {
            $table['tittle'] = 'Throughput 2013';
            $table['row'][] = '<tr><td>JASCardig</td><td>310</td><td>121</td><td>410</td><td>210</td></tr>';
            $table['row'][] = '<tr><td>Gapura</td><td>130</td><td>410 </td><td>110</td><td>70</td></tr>';

            Session::put('chart-title', 'Throughput 2013');
        } else {
            $table['tittle'] = 'Throughput 2014';
            $query_1 = Manufactures::get();
            foreach ($query_1 as $query) {
                $table['row'][] = '<tr><td>' . $query->name . '</td><td>' . count(Pos::where('id_mnf', $query->id)->get()) . '</td><td>' . count(Sos::where('id_mnf', $query->id)->get()) . ' </td><td>' . array_rand(['2' => '2', '3' => '3']) . '</td><td>' . array_rand(['2' => '2', '3' => '3']) . '</td></tr>';
            }
            Session::put('chart-title', 'Throughput 2014');
        }

        Session::put('chart-y-text', 'Order');
        Session::put('chart-text', 'Order');

        return $table;
    }

    public function TroughputWeightTable(Request $request) {
        $table['head'] = HTML::jrsTableHead(['', 'A', 'B', 'C']);
        $table['ex'][] = '<form action="' . url('/inventory-utilization') . '" method="get">
		<select name="year"><option value="2013">2013</option><option value="2014">2014</option></select>
		<input type="submit" value="submit"/></form>';

        if ($request->year == '2013') {
            $table['tittle'] = 'Inventory Utilization 2013';
            $table['row'][] = '<tr><td>JASCardig</td><td>87</td><td>65</td><td>32</td></tr>';
            $table['row'][] = '<tr><td>Gapura</td><td>45</td><td>32</td><td>16</td></tr>';

            Session::put('chart-title', 'Inventory Utilization 2013');
        } else {
            $table['tittle'] = 'Inventory Utilization 2014';
            $query_1 = Manufactures::get();
            foreach ($query_1 as $query) {
                $A = count(Slots::where('id_mnf', $query->id)->where('zone', 'A')->get());
                if ($A != 0) {
                    $used_A = count(Slots::where('id_mnf', $query->id)->where('zone', 'A')->where('id_paletposkus', '!=', 'NULL')->get());
                    $total_A = ($used_A / $A) * 100;
                } else {
                    $total_A = 0;
                }


                $B = count(Slots::where('id_mnf', $query->id)->where('zone', 'B')->get());
                if ($B != 0) {
                    $used_B = count(Slots::where('id_mnf', $query->id)->where('zone', 'B')->where('id_paletposkus', '!=', 'NULL')->get());
                    $total_B = ($used_B / $B) * 100;
                } else {
                    $total_B = 0;
                }

                $C = count(Slots::where('id_mnf', $query->id)->where('zone', 'C')->get());
                if ($C != 0) {
                    $used_C = count(Slots::where('id_mnf', $query->id)->where('zone', 'C')->where('id_paletposkus', '!=', 'NULL')->get());
                    $total_C = ($used_C / $C) * 100;
                } else {
                    $total_C = 0;
                }

                $table['row'][] = '<tr><td>' . $query->name . '</td><td>' . $total_A . '</td><td>' . $total_B . '</td><td>' . $total_C . '</td></tr>';
            }
            Session::put('chart-title', 'Inventory Utilization 2014');
        }

        Session::put('chart-y-text', '%');
        Session::put('chart-text', '%');

        return $table;
    }

    public function StorageUtilization() {
        $table['head'] = HTML::jrsTableHead(['', '\'2013', '\'2014']);
        $table['tittle'] = 'Storage Utilization';
        $table['row'][] = '<tr><td>JASCardig</td><td>120</td><td>30</td></tr>';
        $table['row'][] = '<tr><td>Gapura</td><td>80</td><td>10</td></tr>';

        Session::put('chart-title', 'Storage Utilization');
        Session::put('chart-y-text', '%');
        Session::put('chart-text', '%');

        return $table;
    }

    public function Revenue() {
        $table['head'] = HTML::jrsTableHead(['', '\'2013', '\'2014']);
        $table['tittle'] = 'Revenue';
        $table['row'][] = '<tr><td>JASCardig</td><td>32154897000</td><td>1316749000</td></tr>';
        $table['row'][] = '<tr><td>Gapura</td><td>28739865120</td><td>2481288000</td></tr>';

        Session::put('chart-title', 'Revenue');
        Session::put('chart-y-text', 'rupiah');
        Session::put('chart-text', 'rupiah');

        return $table;
    }

    public static function countPoTotal() {
        $query = Pos::get();
        return count($query);
        /*countPoTotal
		countPoIpo
		countPoIr
		countPoPa
		countSoTotal
		countSoOso
		countSoPl
		countSoDo*/
    }

    public static function countPoIpo() {
        $query = Pos::where('status', 'Open Order')->get();
        return count($query);
    }

    public static function countPoIr() {
        $query = Pos::where('status', 'Order Sent')->get();
        return count($query);
    }

    public static function countPoPa() {
        $query = Pos::where('status', 'Stored')->get();
        return count($query);
    }

    public static function countSoTotal() {
        $query = Sos::get();
        return count($query);
    }

    public static function countSoOso() {
        $query = Sos::where('status', 'Open Sales Order')->get();
        return count($query);
    }

    public static function countSoPl() {
        $query = Sos::where('status', 'Order Sent')->get();
        return count($query);
    }

    public static function countSoDo() {
        $query = Sos::where('status', 'Ready To Ship')->orWhere('status', 'Shipped')->get();
        return count($query);
    }

    public static function ModuleForm(Request $request) {
        $form['tittle'] = "Module Management Form";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(array('url' => 'master/module', 'method' => 'GET'));
        $form['field'][] = ['label' => 'Role', 'main' => Formz::jrsselect('role', ['1' => 'Tenant (Cargo Handler)', '2' => 'Airport'], '')];

        if ($request->role == 1) {
            $form['field'][] = ['label' => 'Module', 'main' => '
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Create SKU
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>View/ Update SKU
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>ABC Analysis
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>FSN Analysis
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked readonly disabled>Receiver [<font style="color:red">Mandatory</font>]
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Inbound Purchase Order
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Inbound Receiving
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Directed Put Away
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Storage Mapping
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Stock Adjustment
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Replacement Storage
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Outbound Sales Order
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Directed Picking
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Delivery Order
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Report - Throughput
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Report - Inventory Utilization
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Report - Storage Utilization
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Report - Revenue
					</label>
				</div>
			'];
        }

        if ($request->role == 2) {
            $form['field'][] = ['label' => 'Module', 'main' => '
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Create SKU
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >View/ Update SKU
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >ABC Analysis
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >FSN Analysis
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked readonly disabled>Receiver [<font style="color:red">Mandatory</font>]
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Inbound Purchase Order
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Inbound Receiving
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Directed Put Away
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Storage Mapping
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Stock Adjustment
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Replacement Storage
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Outbound Sales Order
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Directed Picking
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" >Delivery Order
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Report - Throughput
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Report - Inventory Utilization
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Report - Storage Utilization
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="" checked>Report - Revenue
					</label>
				</div>
			'];
        }

        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary', FALSE)];
        return $form;
    }
}
