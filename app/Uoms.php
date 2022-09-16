<?php

namespace App;

use App\Http\Helper\HTML;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Uoms extends Model {
    protected $table = "uoms";

    function Skus() {
        return $this->belongsTo('Skus', 'id_sku');
    }

    function UomTable() {
        // UOM Table Configuration
        $Models = Uoms::orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'UOM Table';
        $table['default'] = "zzzz";
        $table['border'] = TRUE;
        $table['search'] = "search";
        $table['head'] = HTML::jrsTableHead(['Name', 'Created At']);
        $table['ex'][] = HTML::jrsBtn(['master/uom-form', 'Add', 'primary', 'fajax', FALSE, 'plus']);
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $col = [$Model->dsc, $Model->created_at];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function UomForm() {
        // UOM Form Configuration
        $form['tittle'] = "Add UOM";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(array('url' => 'master/add-uom', 'method' => 'POST'));
        $form['field'][] = ['label' => 'Description', 'main' => Formz::jrstext('dsc', '')];
        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
        return $form;
    }

    function UomBC() {
        // UOM Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'UOM']
        ];
    }

    function UomVal($input) {
        // Add/Edit UOM Validate
        $rule = ['dsc' => 'required'];
        return Validator::make($input, $rule);
    }

    function AddUom(Request $request) {
        // Add UOM Action Procedures
        $Model = new Uoms();
        $Model->dsc = $request->dsc;
        $Model->save();
    }
}
