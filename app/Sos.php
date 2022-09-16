<?php

namespace App;

use App\Http\Helper\Formz;
use App\Http\Helper\HTML;
use App\Manufactures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Sos extends Model {
    protected $table = "sos";

    function Soskus() {
        return $this->hasMany(Soskus::class, 'id_so');
    }

    function Manufactures() {
        return $this->belongsTo(Manufactures::class, 'id_mnf');
    }

    function OpenSoTable() {
        // So Table Configuration
        $Models = Sos::where('status', 'Open Sales Order')->orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Sales Order Table';
        $table['head'] = HTML::jrsTableHead(['SO Code', 'Tenant', 'Address', 'Status', 'Action']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['ex'][] = HTML::jrsBtn(['outbound/so-form/add', 'Add', 'primary', 'fajax', FALSE]);
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $action = null;

            $action .= HTML::jrsBtn(['outbound/so-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
            $action .= HTML::jrsBtn(['outbound/so-form/edit/' . $Model->id, 'Edit', 'primary', 'fajax', FALSE, 'pencil']) . ' ';
            $action .= HTML::jrsBtn(['outbound/so-form/delete/' . $Model->id, 'Delete', 'primary', 'fajax', FALSE, 'eraser']) . ' ';

            $col = [$Model->so_code, $Model->manufactures->name, $Model->address, $Model->status, $action];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function PickingListTable() {
        // So Table Configuration
        $Models = Sos::where('status', 'Order Sent')->orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Picking List Table';
        $table['head'] = HTML::jrsTableHead(['SO Code', 'Tenant', 'Address', 'Status', 'Action']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $action = null;
            $action .= HTML::jrsBtn(['outbound/so-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
            $action .= HTML::jrsBtn(['outbound/pl/' . $Model->id, 'Picking List', 'warning', '', FALSE, 'file-text']) . ' ';

            $col = [$Model->so_code, $Model->manufactures->name, $Model->address, $Model->status, $action];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function DoTable() {
        // So Table Configuration
        $Models = Sos::where('status', 'Ready To Ship')->orWhere('status', 'Shipped')->orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Delivery Order Table';
        $table['head'] = HTML::jrsTableHead(['SO Code', 'Tenant', 'Address', 'Status', 'Action']);
        $table['default'] = "zzzz";
        $table['search'] = "search";
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $action = null;

            switch ($Model->status) {
                case 'Ready To Ship':
                    $action .= HTML::jrsBtn(['outbound/so-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
                    $action .= HTML::jrsBtn(['outbound/pl/' . $Model->id, 'Picking List', 'warning', '', FALSE, 'file-text']) . ' ';
                    $action .= HTML::jrsBtn(['outbound/do/' . $Model->id, 'DO', 'warning', '', FALSE, 'file-text']) . ' ';
                    break;

                case 'Shipped':
                    $action .= HTML::jrsBtn(['outbound/so-sku/' . $Model->id, 'SKU\'s', 'success', '', FALSE, 'cube']) . ' ';
                    $action .= HTML::jrsBtn(['outbound/pl/' . $Model->id, 'Picking List', 'warning', '', FALSE, 'file-text']) . ' ';
                    $action .= HTML::jrsBtn(['outbound/do/' . $Model->id, 'DO', 'warning', '', FALSE, 'file-text']) . ' ';
                    break;
            }

            $col = [$Model->so_code, $Model->manufactures->name, $Model->address, $Model->status, $action];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function SoForm($type, $id = null) {
        // So Form Configuration
        $MnfLists = array('' => 'Select Tenant');
        $Mnfs = Manufactures::get();
        foreach ($Mnfs as $m) $MnfLists += array($m->id => $m->name);
        $form['tittle'] = "SO Form";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::jrsopen('outbound/' . $type, 'so', 'POST');

        if ($id != null) $mdl = Sos::with('Manufactures')->find($id);

        switch ($type) {
            case 'delete':
                $form['field'][] = ['label' => 'SO Id', 'main' => Formz::jrsreadonly('id_so', $mdl->id)];
                $form['field'][] = ['label' => 'Tenant', 'main' => Formz::jrsreadonly('id_mnf', $mdl->id_mnf)];
                $form['field'][] = ['label' => 'Address', 'main' => Formz::jrsreadonly('address', $mdl->address)];
                $form['field'][] = ['main' => Formz::jrssubmit('Delete', 'primary')];
                break;

            case 'edit':
                $form['field'][] = ['label' => 'SO Id', 'main' => Formz::jrsreadonly('id_so', $mdl->id)];
                $form['field'][] = ['label' => 'Tenant', 'main' => Formz::jrsselect('id_mnf', $MnfLists, $mdl->id_mnf)];
                $form['field'][] = ['label' => 'Address', 'main' => Formz::jrstextarea('address', $mdl->address)];
                $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
                break;

            default:
                $form['field'][] = ['label' => 'Tenant', 'main' => Formz::jrsselect('id_mnf', $MnfLists, '')];
                $form['field'][] = ['label' => 'Address', 'main' => Formz::jrstextarea('address', '')];
                $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
                break;
        }

        return $form;
    }

    function SoBC() {
        // So Breadcrumbs Configuration
        return [['', url('main/home-page'), 'Home'], ['active', '', 'Sales Order']];
    }

    function SoVal($input) {
        // Add/Edit So Validate
        $rule = ['address' => 'required', 'id_mnf' => 'required'];
        return Validator::make($input, $rule);
    }

    function AddSo(Request $request) {
        // Add So Action Procedures
        $Model = new Sos();
        $Model->address = $request->address;
        $Model->id_mnf = $request->id_mnf;
        $Model->status = 'Open Sales Order';
        $Model->save();

        $getId = $Model->id;
        $length = strlen($getId);
        $maxzero = 5 - $length;
        $zero = null;
        for ($i = 0; $i < $maxzero; $i++) $zero .= '0';

        $ModelById = Sos::find($getId);
        $ModelById->so_code = "SO-" . $zero . $getId;
        $ModelById->save();
    }

    function EditSo(Request $request) {
        // Add Po Action Procedures
        $Model = Sos::find($request->id_so);
        $Model->address = $request->address;
        $Model->id_mnf = $request->id_mnf;
        $Model->save();
    }
}
