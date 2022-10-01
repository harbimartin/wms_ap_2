<?php

namespace App;

use App\Http\Helper\HTML;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Soskus extends Model {
    protected $table = "soskus";

    function Sos() {
        return $this->belongsTo(Sos::class, 'id_so');
        return $this->belongsTo('Sos', 'id_so');
    }

    function Skus() {
        return $this->belongsTo(Skus::class, 'id_sku');
    }

    function Paletsoskus() {
        return $this->hasMany(Paletsoskus::class, 'id_sosku');
    }

    function SosSkuTable($id_so) {
        // SosSku Table Configuration
        $SO = Sos::find($id_so);
        $Models = Soskus::with('Skus.Uoms')->where('id_so', $id_so)->orderBy('created_at', 'desc')->get();

        $table['tittle'] = 'SO SKU Table';
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['head'] = HTML::jrsTableHead(['SKU', 'Quantity']);
        $table['ex'][] = HTML::jrsTableDetail([["SO Code", $SO->so_code], ["SO Date", $SO->created_at], ["Address", $SO->address]]);

        if ($SO->status == "Open Sales Order") {
            $table['ex'][] = HTML::jrsBtn(['outbound/so-sku-form/' . $id_so, 'Add', 'primary', 'fajax', FALSE]);
            if (count($Models) > 0)
                $table['ex'][] = HTML::jrsBtn(['outbound/done-so-sku/' . $id_so, 'Done', 'success', '', TRUE]);
        }

        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $col = [$Model->skus->dsc, $Model->qty . ' ' . $Model->skus->uoms->dsc];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function SosSkuForm($id_so) {
        // SosSku Form Configuration
        $form['tittle'] = "Add SO SKU";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(array('url' => 'outbound/add-so-sku', 'method' => 'POST'));
        $form['field'][] = ['label' => 'SO Id', 'main' => Formz::jrsreadonly('id_so', $id_so)];
        $form['field'][] = ['label' => 'SKU', 'main' => Formz::jrsselect('id_sku', Formz::skuByMnf($id_so), '')];
        $form['field'][] = ['label' => 'Quantity', 'main' => Formz::jrsnumber('qty', '')];
        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
        return $form;
    }

    function SosSkuBC() {
        // SosSku Breadcrumbs Configuration
        return [['', url('main/home-page'), 'Home'], ['active', '', 'SO SKU']];
    }

    function SosSkuVal($input) {
        // Add/Edit SosSku Validate
        $rule = ['id_sku' => 'required', 'id_so' => 'required', 'qty' => 'required'];
        return Validator::make($input, $rule);
    }

    function AddSosSku(Request $request) {
        // Add SosSku Action Procedures
        $Model = new Soskus();
        $Model->id_so = $request->id_so;
        $Model->id_sku = $request->id_sku;
        $Model->qty = $request->qty;
        $Model->save();
    }

    function DoneSoSku($id_so) {
        // Done PosSku Action Procedures
        $SOSKUS = Soskus::where('id_so', $id_so)->get();
        foreach ($SOSKUS as $SS) {
            $qty_ordered = $SS->qty;
            $POSKUS = Poskus::with('Paletposkus.Slots')->where('id_sku', $SS->id_sku)->orderBy('ed')->get();
            foreach ($POSKUS as $PS) {
                foreach ($PS->paletposkus as $PSP) {
                    $SlotsByPPS = Slots::where('id_paletposkus', $PSP->id)->first();
                    if (!empty($SlotsByPPS)) {
                        if ($qty_ordered != 0) {
                            if ($PSP->qty <= $qty_ordered) {
                                $qty_ordered = $qty_ordered - $PSP->qty;
                                $PSosskus = new Paletsoskus();
                                $PSosskus->id_sosku = $SS->id;
                                $PSosskus->qty = $PSP->qty;
                                $PSosskus->id_slot = $SlotsByPPS->id;
                                $PSosskus->save();
                            } else {
                                $PSosskus = new Paletsoskus();
                                $PSosskus->id_sosku = $SS->id;
                                $PSosskus->qty = $qty_ordered;
                                $PSosskus->id_slot = $SlotsByPPS->id;
                                $PSosskus->save();
                                $qty_ordered = 0;
                            }
                        }
                    }
                }
            }
        }

        $Model = Sos::find($id_so);
        $Model->status = 'Order Sent';
        $Model->save();
    }

    function PlTable($id_so) {
        $Models = Soskus::with(['Skus', 'Paletsoskus.Slots'])->where('id_so', $id_so)->orderBy('created_at', 'desc')->get();
        $SoById = Sos::find($id_so);
        $SoStat = $SoById->status;

        $table['tittle'] = 'Picking List';
        $table['default'] = "zzzz";
        $table['border'] = TRUE;
        $table['search'] = "search";
        $table['head'] = HTML::jrsTableHead(['SKU Code', 'SKU', 'QTY', 'Pos. Located']);
        $table['ex'][] = HTML::jrsTableDetail([["SO Code", $SoById->so_code], ["SO Date", $SoById->created_at], ["Address", $SoById->address]]);
        $table['ex'][] = HTML::jrsBtn(['outbound/dl-pl-doc/' . $id_so, 'Download PL', 'warning', '', FALSE, 'download']) . ' ' . HTML::PlDoneBtn([$SoById->status, $id_so]);
        $table['ex'][] = '<hr/>';

        foreach ($Models as $Model) {
            foreach ($Model->paletsoskus as $MPSS) {
                $col = [
                    $Model->skus->sku_code, $Model->skus->dsc, $MPSS->qty, $MPSS->slots->zone . '-' .
                        $MPSS->slots->bay . '-' . $MPSS->slots->rack . '-' . $MPSS->slots->aisle . '-' . $MPSS->slots->level . '-' .
                        $MPSS->slots->lot
                ];

                $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            }
        }

        return $table;
    }

    function PlBC() {
        return [['', url('main/home-page'), 'Home'], ['active', '', 'Picking List']];
    }

    function DlPlTable($id_so) {
        $SoById = Sos::find($id_so);
        $Models = Soskus::with('Skus')->with('Paletsoskus.Slots')->where('id_so', $id_so)->orderBy('created_at', 'desc')->get();

        $table['tittle'] = 'Picking List';
        $table['default'] = "zzzz";
        $table['border'] = TRUE;
        $table['search'] = "search";
        $table['head'] = HTML::jrsTableHead(['SKU Code', 'SKU', 'QTY', 'Pos. Sgt', 'Pos. Act.']);
        $table['ex'][] = HTML::jrsTableDetail([["SO Code", $SoById->so_code], ["SO Date", $SoById->created_at], ["Address", $SoById->address]]);
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            foreach ($Model->paletsoskus as $MPSS) {
                $col = [
                    $Model->skus->sku_code, $Model->skus->dsc, $MPSS->qty, $MPSS->slots->zone . '-' .
                        $MPSS->slots->bay . '-' . $MPSS->slots->rack . '-' . $MPSS->slots->aisle . '-' . $MPSS->slots->level . '-' .
                        $MPSS->slots->lot, ''
                ];

                $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            }
        }

        return $table;
    }

    function DonePl($id_so) {
        $Models = Soskus::with('Paletsoskus.Slots.Paletposkus')->where('id_so', $id_so)->get();
        foreach ($Models as $Model) {
            foreach ($Model->paletsoskus as $MPSS) {
                $PPS = Paletposkus::find($MPSS->slots->id_paletposkus);
                $PPS->qty = $PPS->qty - $MPSS->qty;
                $PPS->save();

                if ($PPS->qty == 0) {
                    $PPS->delete();
                }
            }
        }

        $SObyId = Sos::find($id_so);
        $SObyId->status = "Ready To Ship";
        $SObyId->save();
    }

    function DoTable($id_so) {
        $SoById = Sos::find($id_so);
        $SoStat = $SoById->status;
        $Models = Soskus::with(['Skus', 'Paletsoskus.Slots'])->where('id_so', $id_so)->orderBy('created_at', 'desc')->get();

        $table['tittle'] = 'Delivery Order';
        $table['default'] = "zzzz";
        $table['border'] = TRUE;
        $table['search'] = "search";
        $table['head'] = HTML::jrsTableHead(['SKU Code', 'SKU', 'QTY']);
        $table['ex'][] = HTML::jrsTableDetail([["SO Code", $SoById->so_code], ["SO Date", $SoById->created_at], ["Address", $SoById->address]]);

        ($SoStat == "Ready To Ship")
            ? $DoneBtn = HTML::jrsBtn(['outbound/done-do/' . $id_so, 'Done', 'success', '', TRUE])
            : $DoneBtn = NULL;

        $table['ex'][] = HTML::jrsBtn(['outbound/dl-do-doc/' . $id_so, 'Download DO', 'warning', '', TRUE, 'download']) . ' ' . $DoneBtn;
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            foreach ($Model->paletsoskus as $MPSS) {
                $col = [$Model->skus->sku_code, $Model->skus->dsc, $MPSS->qty];
                $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            }
        }

        return $table;
    }

    function DoBC() {
        return [['', url('main/home-page'), 'Home'], ['active', '', 'Delivery Order']];
    }

    function DlDoTable($id_so) {
        $SoById = Sos::find($id_so);
        $SoStat = $SoById->status;
        $Models = Soskus::with(['Skus', 'Paletsoskus.Slots'])->where('id_so', $id_so)->orderBy('created_at', 'desc')->get();

        $table['tittle'] = 'Delivery Order';
        $table['default'] = "zzzz";
        $table['border'] = TRUE;
        $table['search'] = "search";
        $table['head'] = HTML::jrsTableHead(['SKU Code', 'SKU', 'QTY']);
        $table['ex'][] = HTML::jrsTableDetail([["SO Code", $SoById->so_code], ["SO Date", $SoById->created_at], ["Address", $SoById->address]]);
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            foreach ($Model->paletsoskus as $MPSS) {
                $col = [$Model->skus->sku_code, $Model->skus->dsc, $MPSS->qty];
                $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            }
        }

        return $table;
    }

    function DoneDo($id_so) {
        $SObyId = Sos::find($id_so);
        $SObyId->status = "Shipped";
        $SObyId->save();
    }
}
