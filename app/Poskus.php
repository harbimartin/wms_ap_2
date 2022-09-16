<?php


namespace App;

use App\Http\Helper\Formz;
use App\Http\Helper\HTML;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Poskus extends Model {
	protected $table = "poskus";

	function Pos() {
		return $this->belongsTo(Pos::class, 'id_po');
	}

	function Skus() {
		return $this->belongsTo(Skus::class, 'id_sku');
	}

	function Paletposkus() {
		return $this->hasMany(Paletposkus::class, 'id_posku');
	}

	function InbPoSkuTable($id_po) {
		// PosSku Table Configuration
		$PO = Pos::with('Manufactures')->find($id_po);
		$Models = Poskus::with('Skus.Uoms')->where('id_po', $id_po)->orderBy('created_at', 'desc')->get();
		$table['tittle'] = 'PO SKU Table';
		$table['head'] = HTML::jrsTableHead(['SKU', 'Quantity', 'Expire Date', 'Manufacturing Date']);
		$table['default'] = "zzzz";
		$table['search'] = "search";
		$table['border'] = TRUE;
		$table['ex'][] = HTML::jrsTableDetail([['SMU NO.', $PO->po_code], ['Shipment Date', $PO->sd], ['Tenant', $PO->manufactures->name]]);

		if (count($Models) > 0 && $PO->status == "Open Order")
			$table['ex'][] = HTML::jrsBtn(['inbound/done-po-sku/' . $id_po, 'Done', 'success', '', TRUE, 'check']);

		$table['ex'][] = "<hr/>";

		$action = null;
		$i = 0;
		foreach ($Models as $Model) {
			$col = [$Model->skus->dsc, $Model->qty . ' ' . $Model->skus->uoms->dsc, $Model->ed, $Model->md];
			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}
		$table['row'][] = Formz::close();

		return $table;
	}

	function InbPoSkuReceivingTable($id_po) {
		// PosSku Table Configuration
		$PO = Pos::with('Manufactures')->find($id_po);
		$Models = Poskus::with('Skus.Uoms')->where('id_po', $id_po)->orderBy('created_at', 'desc')->get();
		$table['tittle'] = 'PO SKU Table';
		$table['head'] = HTML::jrsTableHead(['SKU', 'Quantity', 'Expire Date', 'Manufacturing Date']);
		$table['default'] = "zzzz";
		$table['search'] = "search";
		$table['border'] = TRUE;
		$table['ex'][] = HTML::jrsTableDetail([['SMU NO.', $PO->po_code], ['Shipment Date', $PO->sd], ['Tenant', $PO->manufactures->name]]);

		$MWN = Poskus::whereNull('qty_ac')->orWhere('qty_ac', 0)->where('id_po', $id_po)->get();
		$DlIsDoc = HTML::jrsBtn(['inbound/dl-is-doc/' . $id_po, 'Inb. Shipment Doc', 'warning', '', FALSE, 'download']);

		(count($MWN) == 0)
			? $RecBtn = HTML::jrsBtn(['inbound/done-checking/' . $id_po, 'Done Checking', 'success', '', TRUE, 'check'])
			: $RecBtn = NULL;

		$table['head'][] = ['name' => 'Quantity Actual'];
		$table['ex'][] = Form::open(['url' => 'inbound/inbound-checking', 'method' => 'POST']);
		$table['row'][] = '<tr><td align="center" colspan="' . count($table['head']) . '">'
			. Form::jrssubmit('Submit', 'primary') . ' ' . $RecBtn . ' ' . $DlIsDoc . '</td></tr>';

		$table['ex'][] = "<hr/>";

		$action = null;
		$i = 0;
		foreach ($Models as $Model) {
			$col = [$Model->skus->dsc, $Model->qty . ' ' . $Model->skus->uoms->dsc, $Model->ed, $Model->md];
			if ($PO->status == "Order Sent") {
				array_push($col, Form::hidden('id_posku' . $i, $Model->id) . Form::jrsnumber('qty_ac[]', $Model->qty_ac));
				$i++;
			}
			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}
		$table['row'][] = Form::close();

		return $table;
	}

	function qweqwe($id_po) {
		// PosSku Table Configuration
		$PO = Pos::with('Manufactures')->find($id_po);
		$Models = Poskus::with('Skus.Uoms')->where('id_po', $id_po)->orderBy('created_at', 'desc')->get();
		$table['tittle'] = 'PO SKU Table';
		$table['head'] = HTML::jrsTableHead(['SKU', 'Quantity', 'Expire Date', 'Manufacturing Date']);
		$table['default'] = "zzzz";
		$table['search'] = "search";
		$table['border'] = TRUE;
		$table['ex'][] = HTML::jrsTableDetail([['PO Code', $PO->po_code], ['Shipment Date', $PO->sd], ['Tenant', $PO->manufactures->name]]);

		if ($PO->status == "Order Sent") {
			$MWN = Poskus::whereNull('qty_ac')->orWhere('qty_ac', 0)->where('id_po', $id_po)->get();
			$DlIsDoc = HTML::jrsBtn(['inbound/dl-is-doc/' . $id_po, 'Inb. Shipment Doc', 'warning', '', FALSE, 'download']);

			(count($MWN) == 0)
				? $RecBtn = HTML::jrsBtn(['inbound/done-checking/' . $id_po, 'Done Checking', 'success', '', TRUE, 'check'])
				: $RecBtn = NULL;

			$table['head'][] = ['name' => 'Quantity Actual'];
			$table['ex'][] = Form::open(['url' => 'inbound/inbound-checking', 'method' => 'POST']);
			$table['row'][] = '<tr><td align="center" colspan="' . count($table['head']) . '">'
				. Form::jrssubmit('Submit', 'primary') . ' ' . $RecBtn . ' ' . $DlIsDoc . '</td></tr>';
		}

		if ($PO->status == "Open Order") {
			if (count($Models) > 0)
				$table['ex'][] = HTML::jrsBtn(['inbound/done-po-sku/' . $id_po, 'Done', 'success', '', TRUE, 'check']);
		}

		$table['ex'][] = "<hr/>";

		$action = null;
		$i = 0;
		foreach ($Models as $Model) {
			$col = [$Model->skus->dsc, $Model->qty . ' ' . $Model->skus->uoms->dsc, $Model->ed, $Model->md];

			if ($PO->status == "Order Sent") {
				array_push($col, Form::hidden('id_posku' . $i, $Model->id) . Form::jrsnumber('qty_ac[]', $Model->qty_ac));
				$i++;
			}

			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}
		$table['row'][] = Form::close();

		return $table;
	}

	function PoSkuTable($id_po) {
		// PosSku Table Configuration
		$PO = Pos::with('Manufactures')->find($id_po);
		$Models = Poskus::with('Skus.Uoms')->where('id_po', $id_po)->orderBy('created_at', 'desc')->get();
		$table['tittle'] = 'PO SKU Table';
		$table['head'] = HTML::jrsTableHead(['SKU', 'Quantity', 'Expire Date', 'Manufacturing Date']);
		$table['default'] = "zzzz";
		$table['search'] = "search";
		$table['border'] = TRUE;
		$table['ex'][] = HTML::jrsTableDetail([['SMU NO.', $PO->po_code], ['Shipment Date', $PO->sd], ['Tenant', $PO->manufactures->name]]);

		if ($PO->status == "Order Sent") {
			$MWN = Poskus::whereNull('qty_ac')->orWhere('qty_ac', 0)->where('id_po', $id_po)->get();
			$DlIsDoc = HTML::jrsBtn(['inbound/dl-is-doc/' . $id_po, 'Inb. Shipment Doc', 'warning', '', FALSE, 'download']);

			(count($MWN) == 0)
				? $RecBtn = HTML::jrsBtn(['inbound/done-checking/' . $id_po, 'Done Checking', 'success', '', TRUE, 'check'])
				: $RecBtn = NULL;

			$table['head'][] = ['name' => 'Quantity Actual'];
			$table['ex'][] = Form::open(['url' => 'inbound/inbound-checking', 'method' => 'POST']);
			$table['row'][] = '<tr><td align="center" colspan="' . count($table['head']) . '">'
				. Form::jrssubmit('Submit', 'primary') . ' ' . $RecBtn . ' ' . $DlIsDoc . '</td></tr>';
		}

		if ($PO->status == "Open Order") {
			if (count($Models) > 0)
				$table['ex'][] = HTML::jrsBtn(['inbound/done-po-sku/' . $id_po, 'Done', 'success', '', TRUE, 'check']);
		}

		$table['ex'][] = "<hr/>";

		$action = null;
		$i = 0;
		foreach ($Models as $Model) {
			$col = [$Model->skus->dsc, $Model->qty . ' ' . $Model->skus->uoms->dsc, $Model->ed, $Model->md];

			if ($PO->status == "Order Sent") {
				array_push($col, Form::hidden('id_posku' . $i, $Model->id) . Form::jrsnumber('qty_ac[]', $Model->qty_ac));
				$i++;
			}

			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}
		$table['row'][] = Form::close();

		return $table;
	}

	function PoSkuForm($id_po) {
		// PosSku Form Configuration
		$PBI = Pos::find($id_po);
		$SkuLists = ['' => 'Select SKU'];
		$Skus = Skus::with('Uoms')->where('id_mnf', $PBI->id_mnf)->get();
		foreach ($Skus as $m) $SkuLists += [$m->id => $m->dsc . ' - ' . $m->uoms->dsc];

		$form['tittle'] = "Add PO SKU";
		$form['fancy'] = TRUE;
		$form['open'] = Form::open(['url' => 'inbound/add-po-sku', 'method' => 'POST']);
		$form['field'][] = ['label' => 'PO Id', 'main' => Form::jrsreadonly('id_po', $id_po)];
		$form['field'][] = ['label' => 'SKU', 'main' => Form::jrsselect('id_sku', $SkuLists, '')];
		$form['field'][] = ['label' => 'Quantity', 'main' => Form::jrstext('qty', '')];
		$form['field'][] = ['label' => 'Expired Date', 'main' => Form::jrsdate('ed', '')];
		$form['field'][] = ['label' => 'Manufacturing Date', 'main' => Form::jrsdate('md', '')];
		$form['field'][] = ['main' => Form::jrssubmit('Submit', 'primary')];

		$PO = Pos::find($id_po);
		($PO->status == "Open Order")
			? $return = $form
			: $return = FALSE;

		return $return;
	}

	function PoSkuBC() {
		// PosSku Breadcrumbs Configuration
		return [
			['', url('main/home-page'), 'Home'],
			['active', '', 'PO SKU']
		];
	}

	function PoSkuVal($input) {
		// Add/Edit PosSku Validate
		$rule = [
			'id_sku' => 'required',
			'id_po' => 'required',
			'qty' => 'required',
			'ed' => 'required',
			'md' => 'required',
		];
		return Validator::make($input, $rule);
	}

	function AddPoSku() {
		// Add PosSku Action Procedures
		$Model = new Poskus();
		$Model->id_po = Input::get('id_po');
		$Model->id_sku = Input::get('id_sku');
		$Model->qty = Input::get('qty');
		$Model->ed = Input::get('ed');
		$Model->md = Input::get('md');
		$Model->save();
	}

	function DonePoSku($id_po) {
		// Done PosSku Action Procedures
		$Model = Pos::find($id_po);
		$Model->status = 'Order Sent';
		$Model->save();
	}

	function InboundChecking() {
		$i = 0;
		foreach (Input::get('qty_ac') as $qty) {
			$Poskus = Poskus::find(Input::get('id_posku' . $i));
			$Poskus->qty_ac = $qty;
			$Poskus->save();
			$i++;
		}
	}

	function DoneChecking($id_po) {
		// Done Receive Action Procedures
		$Poskus = Poskus::with(['Pos', 'Skus'])->where('id_po', $id_po)->get();

		foreach ($Poskus as $po) {
			$temp_qty = 0;
			$palet_needs = $po->qty_ac / $po->skus->max_qty_plt;
			$rounded_palet_needs = ceil($palet_needs);

			for ($i = 1; $i <= $rounded_palet_needs; $i++) {
				if ($i == $rounded_palet_needs) {
					$qty_stored = $po->qty_ac - $temp_qty;
					$PPS = new Paletposkus();
					$PPS->id_posku = $po->id;
					$PPS->qty = $qty_stored;
					$PPS->save();
				} else {
					$temp_qty += $po->skus->max_qty_plt;
					$PPS = new Paletposkus();
					$PPS->id_posku = $po->id;
					$PPS->qty = $po->skus->max_qty_plt;
					$PPS->save();
				}
			}

			$Paletposkus = Paletposkus::with(['Poskus.Skus.Uoms', 'Poskus.Pos'])->where('id_posku', $po->id)->get();
			foreach ($Paletposkus as $PPS) {
				$Slots = Slots::where('id_mnf', $PPS->poskus->pos->id_mnf)
					->where('id_paletposkus', NULL)
					->where('zone_priority', $PPS->poskus->skus->inventory_priority)
					->first();

				if (!empty($Slots)) {
					$UpdtSlots = Slots::find($Slots->id);
					$UpdtSlots->id_paletposkus = $PPS->id;
					$UpdtSlots->save();

					$TTS = new TempTallySheets();
					$TTS->po_code = $PPS->poskus->pos->po_code;
					$TTS->sku_code = $PPS->poskus->skus->sku_code;
					$TTS->sku_desc = $PPS->poskus->skus->dsc;
					$TTS->pos = $UpdtSlots->zone . '-' . $UpdtSlots->bay . '-' . $UpdtSlots->rack . '-' . $UpdtSlots->aisle . '-' . $UpdtSlots->level . '-' . $UpdtSlots->lot;
					$TTS->qty = $PPS->qty;
					$TTS->save();
				} else {
					$UpdtTempStor = new Tempstorages();
					$UpdtTempStor->id_paletposku = $PPS->id;
					$UpdtTempStor->save();

					$TTS = new TempTallySheets();
					$TTS->po_code = $PPS->poskus->pos->po_code;
					$TTS->sku_code = $PPS->poskus->skus->sku_code;
					$TTS->sku_desc = $PPS->poskus->skus->dsc;
					$TTS->pos = 'Temp Storage';
					$TTS->qty = $PPS->qty;
					$TTS->save();
				}
			}
		}

		$Model = Pos::find($id_po);
		$Model->status = 'Stored';
		$Model->save();
	}

	function DlIsTable($id_po) {
		// PosSku Table Configuration
		$PO = Pos::with('Manufactures')->find($id_po);
		$Models = Poskus::with('Skus.Uoms')->where('id_po', $id_po)->orderBy('created_at', 'desc')->get();
		$table['tittle'] = 'PO SKU Table';
		$table['head'] = HTML::jrsTableHead(['SKU', 'Qty. Exp', 'Qty. Act', 'Expire Date', 'Manufacturing Date']);
		$table['border'] = TRUE;
		$table['ex'][] = HTML::jrsTableDetail([['PO Code', $PO->po_code], ['Shipment Date', $PO->sd], ['Tenant', $PO->manufactures->name]]);
		$table['ex'][] = "<hr/>";

		$i = 0;
		foreach ($Models as $Model) {
			$col = [$Model->skus->dsc, $Model->qty . ' ' . $Model->skus->uoms->dsc, '', $Model->ed, $Model->md];
			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}
		$table['row'][] = Form::close();

		return $table;
	}

	function TsTable($id_po) {
		// Tally Sheet Table Configuration
		$PObyId = Pos::find($id_po);
		$TTS = TempTallySheets::where('po_code', $PObyId->po_code)->get();
		$table['tittle'] = 'Tally Sheet';
		$table['head'] = HTML::jrsTableHead(['SKU Code', 'SKU Desc.', 'Qty.', 'Sugested Pos.']);
		$table['default'] = "zzzz";
		$table['search'] = "search";
		$table['ex'][] = HTML::jrsTableDetail([['SMU NO.', $PObyId->po_code], ['Shipment Date', $PObyId->sd], ['Tenant', $PObyId->manufactures->name]]);
		$table['ex'][] = "<hr/>";
		$table['ex'][] = HTML::jrsBtn(['inbound/dl-ts/' . $id_po, 'Download Tally Sheet', 'warning', '', FALSE, 'download']);
		$table['ex'][] = "<hr/>";

		foreach ($TTS as $MPPS) {
			$col = [$MPPS->sku_code, $MPPS->sku_desc, $MPPS->qty, $MPPS->pos];
			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}

		return $table;
	}

	function DlTsTable($id_po) {
		// Download Tally Sheet Table Configuration
		$PObyId = Pos::find($id_po);
		$TTS = TempTallySheets::where('po_code', $PObyId->po_code)->get();
		$table['tittle'] = 'Tally Sheet';
		$table['head'] = HTML::jrsTableHead(['SKU Code', 'SKU Desc.', 'Qty.', 'Sugested Pos.']);
		$table['default'] = "zzzz";
		$table['search'] = "search";
		$table['border'] = TRUE;
		$table['ex'][] = HTML::jrsTableDetail([['PO Code', $PObyId->po_code], ['Shipment Date', $PObyId->sd], ['Tenant', $PObyId->manufactures->name]]);
		$table['ex'][] = "<hr/>";

		foreach ($TTS as $MPPS) {
			$col = [$MPPS->sku_code, $MPPS->sku_desc, $MPPS->qty, $MPPS->pos];
			$table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
		}

		return $table;
	}

	function TsBC() {
		// Tally Sheet Breadcrumbs Configuration
		return [
			['', url('main/home-page'), 'Home'],
			['active', '', 'Tally Sheet']
		];
	}
}
