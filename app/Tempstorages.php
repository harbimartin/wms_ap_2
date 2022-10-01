<?php

namespace App;

use App\Http\Helper\HTML;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tempstorages extends Model {
    protected $table = "tempstorages";

    function Paletposkus() {
        return $this->belongsTo(Paletposkus::class, 'id_paletposku');
    }

    function Slots() {
        return $this->belongsTo(Slots::class, 'id_slot');
    }

    function TempStorTable() {
        $Models = Tempstorages::with('Slots', 'Paletposkus.Poskus.Skus.Uoms')->orderBy('created_at', 'desc')->get();

        $table['tittle'] = 'Temp. Storage Table';
        $table['head'] = HTML::jrsTableHead(['PO Code Code', 'SKU Code', 'SKU', 'Qty.', 'Position Sugested']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['border'] = TRUE;
        $table['ex'][] =
            HTML::jrsBtn(['storage/dl-rd', 'Replacement Doc.', 'warning', '', FALSE, 'download']) . ' ' .
            HTML::jrsBtn(['storage/gen-rs', 'Replacement Slot.', 'success', '', FALSE, 'refresh']);
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $col = [$Model->paletposkus->poskus->pos->po_code, $Model->paletposkus->poskus->skus->sku_code, $Model->paletposkus->poskus->skus->dsc, $Model->paletposkus->qty];

            ($Model->id_slot != NULL)
                ? array_push(
                    $col,
                    Formz::open(array('url' => 'storage/add-replacement', 'method' => 'POST')) .
                        $Model->slots->zone . '-' .
                        $Model->slots->bay . '-' .
                        $Model->slots->rack . '-' .
                        $Model->slots->aisle . '-' .
                        $Model->slots->level . '-' .
                        $Model->slots->lot .
                        Formz::hidden('id', $Model->id) .
                        Formz::hidden('id_slot', $Model->id_slot) .
                        Formz::hidden('id_paletposkus', $Model->id_paletposku) . ' ' .
                        Formz::jrssubmit('Submit', 'primary', 'TRUE') .
                        Formz::close()
                )
                : array_push($col, 'Still No Slot Available');

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function TempStorBC() {
        return [['', url('main/home-page'), 'Home'], ['active', '', 'Temp. Storage']];
    }

    function DlReplacementDocTable() {
        $Models = Tempstorages::with('Paletposkus.Poskus.Skus.Uoms')->orderBy('created_at', 'desc')->get();

        $table['tittle'] = 'Replacement Doc.';
        $table['head'] = HTML::jrsTableHead(['PO Code Code', 'SKU Code', 'SKU', 'Qty.', 'Position Sugested']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['border'] = TRUE;
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $col = [$Model->paletposkus->poskus->pos->po_code, $Model->paletposkus->poskus->skus->sku_code, $Model->paletposkus->poskus->skus->dsc, $Model->paletposkus->qty];

            ($Model->id_slot != NULL)
                ? array_push(
                    $col,
                    $Model->slots->zone . '-' .
                        $Model->slots->bay . '-' .
                        $Model->slots->rack . '-' .
                        $Model->slots->aisle . '-' .
                        $Model->slots->level . '-' .
                        $Model->slots->lot
                )
                : array_push($col, 'Still No Slot Available');

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function GenReplacementSlot() {
        $Models = Tempstorages::with('Paletposkus.Poskus.Skus')->where('id_slot', NULL)->orderBy('created_at', 'desc')->get();
        foreach ($Models as $Model) {
            $Slots = Slots::where('id_mnf', $Model->paletposkus->poskus->pos->id_mnf)
                ->where('id_paletposkus', NULL)
                ->where('zone_priority', $Model->paletposkus->poskus->skus->inventory_priority)
                ->get();

            foreach ($Slots as $slot) {
                $CekSlot = Tempstorages::where('id_slot', $slot->id)->first();
                if (empty($CekSlot)) {
                    $TS = Tempstorages::find($Model->id);
                    $TS->id_slot = $slot->id;
                    $TS->save();
                }
            }
        }
    }

    function AddReplacementSlot(Request $request) {
        $Slot = Slots::find($request->id_slot);
        $Slot->id_paletposkus = $request->id_paletposkus;
        $Slot->save();

        $TempStor = Tempstorages::find($request->id);
        $TempStor->delete();
    }
}
