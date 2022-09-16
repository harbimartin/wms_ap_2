<?php


namespace App;

use App\Http\Helper\HTML;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Slots extends Model {
    protected $table = "slots";

    function Manufactures() {
        return $this->belongsTo(Manufactures::class, 'id_mnf');
    }

    function Paletposkus() {
        return $this->belongsTo(Paletposkus::class, 'id_paletposkus');
    }

    function SlotTable() {
        // Slot Table Configuration
        $Models = Slots::with('Manufactures')->orderBy('id_mnf')->orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Slot Table';
        $table['head'] = HTML::jrsTableHead(['Zone', 'Zone Prty.', 'Bay', 'Aisle', 'Rack', 'Level', 'Slot', 'Status']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['border'] = TRUE;
        $table['ex'][] = HTML::jrsBtn(['master/slot-form', 'Add', 'primary', 'fajax', FALSE, 'plus']);
        $table['ex'][] = "<hr/>";

        $mnf = null;
        foreach ($Models as $Model) {
            $col = [$Model->zone, $Model->zone_priority, $Model->bay, $Model->aisle, $Model->rack, $Model->level, $Model->lot];

            (empty($Model->id_paletposkus))
                ? array_push($col, 'Empty')
                : array_push($col, 'Filled');

            if ($mnf != $Model->id_mnf)
                $table['row'][] = '<tr><td colspan="' . count($table['head']) . '" align="center"><b>' . $Model->manufactures->name . '</b></td></tr>';

            $table['row'][] = '<tr class="' . $this->SlotStat($Model->id_paletposkus) . '">' . HTML::jrsTableColumn($col) . '</tr>';
            $mnf = $Model->id_mnf;
        }

        return $table;
    }

    function SlotStat($param) {
        (empty($param))
            ? $stat = 'warning'
            : $stat = 'success';

        return $stat;
    }

    function SlotForm() {
        // Slot Form Configuration
        $MnfLists = ['' => 'Select Cargo Handler'];
        $Mnfs = Manufactures::get();
        foreach ($Mnfs as $m) $MnfLists += [$m->id => $m->name];

        $ZoneLists = ['' => 'Select Zone', 'A' => 'A', 'B' => 'B', 'C' => 'C'];

        $form['tittle'] = "Add Slots";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(['url' => 'master/add-slot', 'method' => 'POST']);
        $form['field'][] = ['label' => 'Tenant (Cargo Handler)', 'main' => Formz::jrsselect('id_mnf', $MnfLists, '')];
        $form['field'][] = ['label' => 'Zone', 'main' => Formz::jrsselect('zone', $ZoneLists, '')];
        $form['field'][] = ['label' => 'Zone Prty.', 'main' => Formz::jrsnumber('zone_priority', '')];
        $form['field'][] = ['label' => 'Aisle', 'main' => Formz::jrsnumber('aisle', '')];
        $form['field'][] = ['label' => 'Bay', 'main' => Formz::jrsnumber('bay', '')];
        $form['field'][] = ['label' => 'Rack', 'main' => Formz::jrsnumber('rack', '')];
        $form['field'][] = ['label' => 'Level', 'main' => Formz::jrsnumber('level', '')];
        $form['field'][] = ['label' => 'Lot', 'main' => Formz::jrsnumber('lot', '')];
        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
        return $form;
    }

    function SlotBC() {
        // Slot Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'Slot']
        ];
    }

    function SlotVal($input) {
        // Add/Edit Slot Validate
        $rule = [
            'zone' => 'required',
            'zone_priority' => 'required',
            'aisle' => 'required',
            'bay' => 'required',
            'rack' => 'required',
            'level' => 'required',
            'lot' => 'required',
            'id_mnf' => 'required',
        ];
        return Validator::make($input, $rule);
    }

    function AddSlot(Request $request) {
        // Add Slot Action Procedures
        $Model = new Slots();
        $Model->id_mnf = $request->id_mnf;
        $Model->zone = $request->zone;
        $Model->zone_priority = $request->zone . $request->zone_priority;
        $Model->aisle = $request->aisle;
        $Model->bay = $request->bay;
        $Model->rack = $request->rack;
        $Model->level = $request->level;
        $Model->lot = $request->lot;
        $Model->id_mnf = $request->id_mnf;
        $Model->save();
    }

    function StorageTable() {
        // Storage Table Configuration
        $Models = Slots::with('Manufactures', 'Paletposkus.Poskus.Skus.Uoms')->orderBy('id_mnf')->orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Storage Table';
        $table['head'] = HTML::jrsTableHead(['Zone', 'Zone Prty', 'Bay', 'Aisle', 'Rack', 'Level', 'Slot', 'Sku', 'Qty', 'Expire Date']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['border'] = TRUE;
        $table['ex'][] = HTML::jrsBtn(['storage/stock-adjustment', 'Start Stock Adjustment', 'warning', '', FALSE, 'refresh']);
        $table['ex'][] = "<hr/>";

        $mnf = null;
        foreach ($Models as $Model) {
            $col = [$Model->zone, $Model->zone_priority, $Model->bay, $Model->aisle, $Model->rack, $Model->level, $Model->lot];
            (empty($Model->id_paletposkus))
                ? array_push($col, 'Empty', 'Empty', 'Empty')
                : array_push(
                    $col,
                    $Model->Paletposkus->Poskus->Skus->dsc,
                    $Model->Paletposkus->qty . ' ' . $Model->Paletposkus->Poskus->Skus->Uoms->dsc,
                    $Model->Paletposkus->Poskus->ed
                );

            if ($mnf != $Model->id_mnf)
                $table['row'][] = '<tr><td colspan="' . count($table['head']) . '" align="center"><b>' . $Model->manufactures->name . '</b></td></tr>';

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            $mnf = $Model->id_mnf;
        }

        return $table;
    }

    function StorageBC() {
        // Slot Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'Storage']
        ];
    }

    function StockAdjustmentTable() {
        // Storage Table Configuration
        $Models = Slots::with('Manufactures', 'Paletposkus.Poskus.Skus.Uoms')->orderBy('id_mnf')->orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Stock Adjustment';
        $table['head'] = HTML::jrsTableHead(['Zone', 'Zone Prty', 'Bay', 'Aisle', 'Rack', 'Level', 'Slot', 'Sku', 'Qty', 'Expire Date']);
        $table['default'] = "zzzz";
        $table['border'] = TRUE;

        $table['ex'][] = Formz::jrsopen('storage/update', 'stock', 'POST');

        $table['row'][] = '<tr><td align="center" colspan="' . count($table['head']) . '">' .
            Formz::jrssubmit('Submit', 'primary') . '</td></tr>';

        $mnf = null;
        $i = 0;
        foreach ($Models as $Model) {
            $col = [$Model->zone, $Model->zone_priority, $Model->bay, $Model->aisle, $Model->rack, $Model->level, $Model->lot];

            if (empty($Model->id_paletposkus)) {
                array_push($col, 'Empty', 'Empty', 'Empty');
            } else {
                array_push(
                    $col,
                    $Model->Paletposkus->Poskus->Skus->dsc,
                    Formz::hidden('id_pps' . $i, $Model->Paletposkus->id) .
                        Formz::jrsnumber('qty[]', $Model->Paletposkus->qty),
                    $Model->Paletposkus->Poskus->ed
                );
                $i++;
            }

            if ($mnf != $Model->id_mnf)
                $table['row'][] = '<tr><td colspan="' . count($table['head']) . '" align="center"><b>' . $Model->manufactures->name . '</b></td></tr>';

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            $mnf = $Model->id_mnf;
        }

        $table['row'][] = Formz::close();

        return $table;
    }

    function StockAdjustmentBC() {
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'Stock Adjustment']
        ];
    }

    function UpdateStock(Request $request) {
        $i = 0;
        if (!empty($request->qty)) {
            foreach ($request->qty as $qty) {
                $Model = Paletposkus::find($request['id_pps' . $i]);
                if (!empty($qty)) {
                    $Model->qty = $qty;
                    $Model->save();
                } else {
                    $Model->delete();
                }

                $i++;
            }

            return TRUE;
        } else {
            return FALSE;
        }
    }
}
