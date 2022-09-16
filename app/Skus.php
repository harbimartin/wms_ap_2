<?php


namespace App;

use App\Http\Helper\HTML;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Skus extends Model {
    protected $table = "skus";

    function Poskus() {
        return $this->hasMany(Poskus::class, 'id_sku');
    }

    function Soskus() {
        return $this->hasMany(Soskus::class, 'id_sku');
    }

    function Uoms() {
        return $this->belongsTo(Uoms::class, 'id_uom');
    }

    function Manufactures() {
        return $this->belongsTo(Manufactures::class, 'id_mnf');
    }

    function SkuTable() {
        // SKU Table Configuration
        $Models = Skus::with('Uoms')->with('Manufactures')->orderBy('id_mnf')->orderBy('inventory_priority')->get();
        $table['tittle'] = 'SKU Table';
        $table['head'] = HTML::jrsTableHead([
            'SKU CODE',
            'Manufacturing CODE',
            'Description',
            'UOM',
            'Lot Size',
            'Price',
            'Demand',
            'Total Inventory',
            'Hit',
            'Zone',
            'Inv. Prty',
            'Max per Palet'
        ]);
        $table['default'] = "zzzz";
        $table['search'] = "search";

        $table['ex'][] = HTML::jrsBtn(['master/gen-inv-prty', 'Generate SKU Slotting', 'warning', '', TRUE, 'refresh']);

        $table['ex'][] = "<hr/>";

        $mnf = null;
        foreach ($Models as $Model) {
            $col = [
                $Model->sku_code,
                $Model->mnf_code,
                $Model->dsc,
                $Model->uoms->dsc,
                $Model->lot_size,
                $Model->price,
                $Model->demand,
                $Model->tot_inventory,
                $Model->hit,
                $Model->inventory_level,
                $Model->inventory_priority,
                $Model->max_qty_plt
            ];

            if ($mnf != $Model->id_mnf)
                $table['row'][] =
                    '<tr><td colspan="' . count($table['head']) . '" align="center">
					<b>' . $Model->manufactures->name . '</b>
					</td></tr>';

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            $mnf = $Model->id_mnf;
        }

        return $table;
    }

    function SkuFsnTable() {
        // SKU Table Configuration
        $Models = Skus::with('Uoms')->with('Manufactures')->orderBy('id_mnf')->orderBy('inventory_priority')->get();
        $table['tittle'] = 'SKU Table';
        $table['head'] = HTML::jrsTableHead([
            'SKU CODE',
            'Manufacturing CODE',
            'Description',
            'UOM',
            'Lot Size',
            'Price',
            'Demand',
            'Total Inventory',
            'Hit',
            'FSN Category',
            'Max per Palet'
        ]);
        $table['default'] = "zzzz";
        $table['search'] = "search";

        $table['ex'][] = HTML::jrsBtn(['master/gen-inv-prty', 'Generate FSN Slotting', 'warning', '', TRUE, 'refresh']);

        $table['ex'][] = "<hr/>";

        $mnf = null;
        foreach ($Models as $Model) {
            $col = [
                $Model->sku_code,
                $Model->mnf_code,
                $Model->dsc,
                $Model->uoms->dsc,
                $Model->lot_size,
                $Model->price,
                $Model->demand,
                $Model->tot_inventory,
                $Model->hit,
                array_rand(['FAST' => 'FAST', 'SLOW' => 'SLOW', 'NON' => 'NON']),
                $Model->max_qty_plt
            ];

            if ($mnf != $Model->id_mnf)
                $table['row'][] =
                    '<tr><td colspan="' . count($table['head']) . '" align="center">
					<b>' . $Model->manufactures->name . '</b>
					</td></tr>';

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            $mnf = $Model->id_mnf;
        }

        return $table;
    }

    function UpdateSkuTable() {
        // SKU Table Configuration
        $Models = Skus::with('Uoms')->with('Manufactures')->orderBy('id_mnf')->orderBy('inventory_priority')->get();
        $table['tittle'] = 'SKU Table';
        $table['head'] = HTML::jrsTableHead([
            'SKU CODE',
            'Manufacturing CODE',
            'Description',
            'UOM',
            'Lot Size',
            'Price',
            'Demand',
            'Total Inventory',
            'Hit',
            'Zone',
            'Inv. Prty',
            'Max per Palet'
        ]);
        $table['default'] = "zzzz";
        $table['search'] = "search";

        $table['ex'][] = Formz::jrsopen('master/update', 'sku', 'POST');
        $table['ex'][] = "<hr/>";

        $table['row'][] = '<tr><td align="center" colspan="' . count($table['head']) . '">' .
            Formz::jrssubmit('Submit', 'primary') . '</td></tr>';

        $UomLists = ['' => 'Select UOM'];
        $Uoms = Uoms::get();
        foreach ($Uoms as $u) $UomLists += [$u->id => $u->dsc];

        $mnf = null;
        $i = 0;
        foreach ($Models as $Model) {
            $col = [
                $Model->sku_code,
                $Model->mnf_code,
                Formz::hidden('id_sku' . $i, $Model->id) .
                    $Model->dsc,
                $Model->uoms->dsc,
                $Model->lot_size,
                Formz::jrsnumber('price[]', $Model->price),
                Formz::jrsnumber('demand' . $i, $Model->demand),
                Formz::jrsnumber('tot_inventory' . $i, $Model->tot_inventory),
                Formz::jrsnumber('hit' . $i, $Model->hit),
                $Model->inventory_level,
                $Model->inventory_priority,
                Formz::jrsnumber('max_qty_plt' . $i, $Model->max_qty_plt),
            ];

            if ($mnf != $Model->id_mnf)
                $table['row'][] =
                    '<tr><td colspan="' . count($table['head']) . '" align="center">
					<b>' . $Model->manufactures->name . '</b>
					</td></tr>';

            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
            $mnf = $Model->id_mnf;
            $i++;
        }

        $table['row'][] = Formz::close();

        return $table;
    }

    function GenInvPrty() {
        foreach ($this->ABCAnalysis() as $abcMnf) {
            foreach ($abcMnf as $abcSku) {
                $SKU = Skus::find($abcSku['id_sku']);
                $SKU->inventory_level = $abcSku['zone'];
                $SKU->save();
            }
        }

        foreach ($this->ABCPriorityAnalysis() as $abcPrty) {
            $SKU2 = Skus::find($abcPrty['id_sku']);
            $SKU2->inventory_priority = $abcPrty['inv_lvl'];
            $SKU2->save();
        }
    }

    function UpdateSku() {
        $i = 0;
        if (!empty($request['price'])) {
            foreach ($request['price'] as $price) {
                $Model = Skus::find($request['id_sku' . $i]);
                $Model->price = $price;
                $Model->demand = $request['demand' . $i];
                $Model->tot_inventory = $request['tot_inventory' . $i];
                $Model->hit = $request['hit' . $i];
                $Model->max_qty_plt = $request['max_qty_plt' . $i];
                $Model->save();
                $i++;
            }

            return TRUE;
        } else {
            return FALSE;
        }
    }

    function ABCPriorityAnalysis() {
        $Sku = Skus::orderBy('id_mnf')->orderBy('inventory_level')->get();
        foreach ($Sku as $sk) {
            $arr_sku[$sk->id_mnf][$sk->inventory_level][$sk->id] = ['nilai' => $sk->demand / $sk->hit, 'id_sku' => $sk->id,];
        }

        $MnfInSku = Skus::groupBy('id_mnf')->get();
        foreach ($MnfInSku as $mis) {
            $IlInSku = Skus::where('id_mnf', $mis->id_mnf)->groupBy('inventory_level')->get();
            foreach ($IlInSku as $iis) {
                $i = 0;
                arsort($arr_sku[$mis->id_mnf][$iis->inventory_level]);
                foreach ($arr_sku[$mis->id_mnf][$iis->inventory_level] as $arrsku) {
                    $otherarr_sku[] = ['id_sku' => $arrsku['id_sku'], 'inv_lvl' => $iis->inventory_level . ($i + 1)];
                    $i++;
                }
            }
        }

        return $otherarr_sku;
    }

    function ABCAnalysis() {
        $MnfInSku = Skus::groupBy('id_mnf')->get();
        foreach ($MnfInSku as $mis) {
            $SkuByMnf = Skus::where('id_mnf', $mis->id_mnf)->get();
            foreach ($SkuByMnf as $sbm) {
                $value = $sbm->price * $sbm->tot_inventory;
                $nilai_persediaan[$sbm->id_mnf][] = ['nilai_persediaan' => $value * $sbm->hit, 'id_sku' => $sbm->id];
            }

            $sum_nilai_persediaan[$sbm->id_mnf] = 0;
            foreach ($nilai_persediaan[$sbm->id_mnf] as $npmnf) {
                $sum_nilai_persediaan[$sbm->id_mnf] += $npmnf['nilai_persediaan'];
            }

            foreach ($nilai_persediaan[$sbm->id_mnf] as $npid) {
                $presentase_nilai_persediaan[$sbm->id_mnf][] = ['nilai' => ($npid['nilai_persediaan'] / $sum_nilai_persediaan[$sbm->id_mnf]) * 100, 'id_sku' => $npid['id_sku']];
            }
            arsort($presentase_nilai_persediaan[$sbm->id_mnf]);

            $i = 0;
            $array = [];
            foreach ($presentase_nilai_persediaan[$sbm->id_mnf] as $pnpsi) {
                if ($i == 0) {
                    $array[$sbm->id_mnf][$i] = $pnpsi['nilai'];
                } else {
                    $x = $i - 1;
                    $array[$sbm->id_mnf][$i] = $pnpsi['nilai'] + $array[$sbm->id_mnf][$x];
                }

                if ($array[$sbm->id_mnf][$i] <= 80) {
                    $zone[$sbm->id_mnf][$i] = ['id_sku' => $pnpsi['id_sku'], 'zone' => 'A', 'nilai' => $array[$sbm->id_mnf][$i]];
                } else if ($array[$sbm->id_mnf][$i] <= 95) {
                    $zone[$sbm->id_mnf][$i] = ['id_sku' => $pnpsi['id_sku'], 'zone' => 'B', 'nilai' => $array[$sbm->id_mnf][$i]];
                } else {
                    $zone[$sbm->id_mnf][$i] = ['id_sku' => $pnpsi['id_sku'], 'zone' => 'C', 'nilai' => $array[$sbm->id_mnf][$i]];
                }

                $i++;
            }
        }

        return $zone;
    }

    function SkuForm() {
        // SKU Form Configuration
        $MnfLists = ['' => 'Select Cargo Handler'];
        $Mnfs = Manufactures::get();
        foreach ($Mnfs as $m) $MnfLists += [$m->id => $m->name];

        $UomLists = ['' => 'Select UOM'];
        $Uoms = Uoms::get();
        foreach ($Uoms as $u) $UomLists += [$u->id => $u->dsc];

        $form['tittle'] = "Add SKU";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(array('url' => 'master/add-sku', 'method' => 'POST'));
        $form['field'][] = ['label' => 'Tenant (Cargo Handler)', 'main' => Formz::jrsselect('id_mnf', $MnfLists, '')];
        $form['field'][] = ['label' => 'Manufacturing Code', 'main' => Formz::jrstext('mnf_code', '')];
        $form['field'][] = ['label' => 'Description', 'main' => Formz::jrstext('dsc', '')];
        $form['field'][] = ['label' => 'UOM', 'main' => Formz::jrsselect('id_uom', $UomLists, '')];
        $form['field'][] = ['label' => 'Lot Size', 'main' => Formz::jrsselect('lot_size', ['' => 'Select Lot Size', 'BLK' => 'BLK', 'CTN' => 'CTN'], '')];
        $form['field'][] = ['label' => 'Price', 'main' => Formz::jrsnumber('price', '')];
        $form['field'][] = ['label' => 'Demand', 'main' => Formz::jrsnumber('demand', '')];
        $form['field'][] = ['label' => 'Total Inventory', 'main' => Formz::jrsnumber('tot_inventory', '')];
        $form['field'][] = ['label' => 'Hit', 'main' => Formz::jrsnumber('hit', '')];
        //$form['field'][] = [ 'label'=>'Inv. Lvl.','main'=>Formz::jrsselect('inventory_level', array('A'=>'A','B'=>'B','C'=>'C'), '')];
        //$form['field'][] = [ 'label'=>'Volume','main'=>Formz::jrsnumber('volume','')];
        //$form['field'][] = [ 'label'=>'Weight','main'=>Formz::jrsnumber('weight','')];
        $form['field'][] = ['label' => 'Max In Palet', 'main' => Formz::jrsnumber('max_qty_plt', '')];
        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary', FALSE)];
        return $form;
    }

    function SkuBC() {
        // SKU Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'SKU']
        ];
    }

    function CreateSkuBC() {
        // SKU Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'Create - SKU']
        ];
    }

    function SkuVal($input) {
        // Add/Edit SKU Validate
        $rule = [
            'id_uom' => 'required',
            'id_mnf' => 'required',
            'mnf_code' => 'required',
            'lot_size' => 'required',
            'tot_inventory' => 'required',
            'hit' => 'required',
            'dsc' => 'required',
            'demand' => 'required',
            'max_qty_plt' => 'required',
        ];
        return Validator::make($input, $rule);
    }

    function AddSku(Request $request) {
        // Add SKU Action Procedures
        $Model = new Skus();
        $Model->id_uom = $request['id_uom'];
        $Model->id_mnf = $request['id_mnf'];
        $Model->dsc = $request['dsc'];
        $Model->demand = $request['demand'];
        $Model->max_qty_plt = $request['max_qty_plt'];
        $Model->mnf_code = $request['mnf_code'];
        $Model->lot_size = $request['lot_size'];
        $Model->tot_inventory = $request['tot_inventory'];
        $Model->hit = $request['hit'];
        $Model->price = $request['price'];
        $Model->save();

        $getId = $Model->id;
        $length = strlen($getId);
        $maxzero = 5 - $length;
        $zero = null;
        for ($i = 0; $i < $maxzero; $i++) $zero .= '0';

        $ModelById = Skus::find($getId);
        $ModelById->sku_code = "SKU-" . $request['id_mnf'] . '-' . $request['lot_size'] . '-' . $zero . $getId;
        $ModelById->save();
    }
}
