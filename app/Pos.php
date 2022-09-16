<?php

namespace App;

use App\Http\Helper\Formz;
use App\Http\Helper\HTML;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Pos extends Model {
	protected $table = "pos";

	function Poskus() {
		return $this->hasMany(Poskus::class, 'id_po');
	}

	function Manufactures() {
		return $this->belongsTo(Manufactures::class, 'id_mnf');
	}

	function PoTable() {
		// Po Table Configuration
		$Models = Pos::orderBy('created_at', 'desc')->get();
		$table['tittle'] = 'Purchase Order Table';
		$table['head'] = HTML::jrsTableHead(['PO Code', 'Manufacture', 'Shipment Date', 'Status', 'Action']);
		$table['default'] = "zzzz";
		$table['search'] = "search";

		$table['ex'][] = "<hr/>";

		foreach ($Models as $Model) {
			$action = null;

			switch ($Model->status) {
				case 'Open Order':
					$action .= HTML::jrsBtn(['inbound/po-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
					$action .= HTML::jrsBtn(['inbound/po-form/edit/' . $Model->id, 'Edit', 'primary', 'fajax', FALSE, 'pencil']) . ' ';
					$action .= HTML::jrsBtn(['inbound/po-form/delete/' . $Model->id, 'Delete', 'primary', 'fajax', FALSE, 'eraser']) . ' ';
					break;

				case 'Order Sent':
					$action .= HTML::jrsBtn(['inbound/po-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
					break;

				case 'Stored':
					$action .= HTML::jrsBtn(['inbound/po-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
					$action .= HTML::jrsBtn(['inbound/ts/' . $Model->id, 'Tally Sheet', 'warning', '', FALSE, 'file-text']) . ' ';
					break;
			}

			$col = [$Model->po_code, $Model->manufactures->name, $Model->sd, $Model->status, $action];

			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}

		return $table;
	}

	function InboundPOTable() {
		// Po Table Configuration
		$Models = Pos::orderBy('created_at', 'desc')->where('status', 'Open Order')->get();
		$table['tittle'] = 'Inbound Purchase Order Table';
		$table['head'] = HTML::jrsTableHead(['PO Code', 'Owner', 'Shipment Date', 'Status', 'Action']);
		$table['default'] = "zzzz";
		$table['search'] = "search";

		$table['ex'][] = "<hr/>";

		foreach ($Models as $Model) {
			$action = null;
			$action .= HTML::jrsBtn(['inbound/inb-po-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
			$action .= HTML::jrsBtn(['inbound/po-form/edit/' . $Model->id, 'Edit', 'primary', 'fajax', FALSE, 'pencil']) . ' ';
			$action .= HTML::jrsBtn(['inbound/po-form/delete/' . $Model->id, 'Delete', 'primary', 'fajax', FALSE, 'eraser']) . ' ';

			$col = [$Model->po_code, $Model->manufactures->name, $Model->sd, $Model->status, $action];

			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}

		return $table;
	}

	function InbReceivingTable() {
		// Po Table Configuration
		$Models = Pos::orderBy('created_at', 'desc')->where('status', 'Order Sent')->get();
		$table['tittle'] = 'Receiving Table';
		$table['head'] = HTML::jrsTableHead(['PO Code', 'Owner', 'Shipment Date', 'Status', 'Action']);
		$table['default'] = "zzzz";
		$table['search'] = "search";

		$table['ex'][] = "<hr/>";

		foreach ($Models as $Model) {
			$action = null;
			$action .= HTML::jrsBtn(['inbound/inb-sku-receiving/' . $Model->id, 'Receive', 'warning', '', FALSE, 'arrow-right']) . ' ';
			$col = [$Model->po_code, $Model->manufactures->name, $Model->sd, $Model->status, $action];

			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}

		return $table;
	}

	function InbPutAwayTable() {
		// Po Table Configuration
		$Models = Pos::orderBy('created_at', 'desc')->where('status', 'Stored')->get();
		$table['tittle'] = 'Put Away Table';
		$table['head'] = HTML::jrsTableHead(['PO Code', 'Owner', 'Shipment Date', 'Status', 'Action']);
		$table['default'] = "zzzz";
		$table['search'] = "search";

		$table['ex'][] = "<hr/>";

		foreach ($Models as $Model) {
			$action = null;
			$action .= HTML::jrsBtn(['inbound/ts/' . $Model->id, 'Tally Sheet', 'warning', '', FALSE, 'file-text']) . ' ';
			$col = [$Model->po_code, $Model->manufactures->name, $Model->sd, $Model->status, $action];

			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}

		return $table;
	}

	function PoForm($type, $id = null) {
		// Po Form Configuration
		$MnfLists = array('' => 'Select Owner');
		$Mnfs = Manufactures::get();
		foreach ($Mnfs as $m) $MnfLists += array($m->id => $m->name);
		$form['tittle'] = "PO Form";
		$form['fancy'] = TRUE;
		$form['open'] = Formz::jrsopen('inbound/' . $type, 'po', 'POST');

		if ($id != null) $mdl = Pos::with('Manufactures')->find($id);

		switch ($type) {
			case 'delete':
				$form['field'][] = ['label' => 'Po Id', 'main' => Formz::jrsreadonly('id_po', $mdl->id)];
				$form['field'][] = ['label' => 'Owner', 'main' => Formz::jrsreadonly('id_mnf', $mdl->manufactures->name)];
				$form['field'][] = ['label' => 'Shipment Date', 'main' => Formz::jrsreadonly('sd', $mdl->sd)];
				$form['field'][] = ['main' => Formz::jrssubmit('Delete', 'primary')];
				break;

			case 'edit':
				$form['field'][] = ['label' => 'Po Id', 'main' => Formz::jrsreadonly('id_po', $mdl->id)];
				$form['field'][] = ['label' => 'Owner', 'main' => Formz::jrsselect('id_mnf', $MnfLists, $mdl->id_mnf)];
				$form['field'][] = ['label' => 'Shipment Date', 'main' => Formz::jrsdate('sd',  $mdl->sd)];
				$form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
				break;

			default:
				$form['field'][] = ['label' => 'Owner', 'main' => Formz::jrsselect('id_mnf', $MnfLists)];
				$form['field'][] = ['label' => 'Shipment Date', 'main' => Formz::jrsdate('sd')];
				$form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
				break;
		}

		return $form;
	}

	function PoVal($input) {
		// Add/Edit Po Validate
		$rule = ['id_mnf' => 'required', 'sd' => 'required'];
		return Validator::make($input, $rule);
	}

	function AddPo(Request $request) {
		// Add Po Action Procedures
		$Model = new Pos();
		$Model->sd = $request->sd;
		$Model->id_mnf = $request->id_mnf;
		$Model->status = 'Open Order';
		$Model->save();

		$getId = $Model->id;
		$length = strlen($getId);
		$maxzero = 5 - $length;
		$zero = null;
		for ($i = 0; $i < $maxzero; $i++) $zero .= '0';

		$ModelById = Pos::find($getId);
		$ModelById->po_code = "PO-" . $zero . $getId;
		$ModelById->save();
	}

	function EditPo(Request $request) {
		// Add Po Action Procedures
		$Model = Pos::find($request->id_po);
		$Model->sd = $request->sd;
		$Model->id_mnf = $request->id_mnf;
		$Model->save();
	}
}
