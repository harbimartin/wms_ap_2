<?php


namespace App;

use App\Http\Helper\HTML;
use Form;
use App\Http\Helper\Formz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Posusers extends Model {
    protected $table = "posusers";

    function PosusersTable() {
        // Manufacture Table Configuration
        $Models = Posusers::orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Cargo Handler Table';
        $table['head'] = HTML::jrsTableHead(['ID', 'Name', 'Username', 'Status', 'Edit', 'Delete']);
        $table['default'] = "zzzz";
        $table['search'] = "search";

        $table['ex'][] = HTML::jrsBtn(['master/posusers-form', 'Add', 'primary', 'fajax', FALSE, 'plus']);
        $table['ex'][] = "<hr/>";

        foreach ($Models as $Model) {
            $col = [$Model->id, $Model->name, $Model->username, '<div style="color:green">active</div>', HTML::jrsBtn(['#', '', 'warning', '', FALSE, 'pencil']), HTML::jrsBtn(['#', '', 'danger', '', FALSE, 'eraser'])];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function AddPosUserForm() {
        // Manufacture Form Configuration
        $form['tittle'] = "Cargo Handler";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(['url' => 'master/add-pos-user', 'method' => 'POST']);
        if (Session::get('role') == 'admin')
            $form['field'][] = ['label' => 'Airport', 'main' => Formz::jrsselect('name', ['' => 'Select Airport', '1' => 'BSH', '2' => 'KMO', '3' => 'MDN'], '')];
        else
            $form['field'][] = ['label' => 'Airport', 'main' => Formz::jrsreadonly('', 'Bandara Soekarno Hatta')];
        $form['field'][] = ['label' => 'Name', 'main' => Formz::jrstext('name', '')];
        $form['field'][] = ['label' => 'Username', 'main' => Formz::jrstext('username', '')];
        $form['field'][] = ['label' => 'Password', 'main' => Formz::jrspassword('password')];
        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
        return $form;
    }

    function PosUserBC() {
        // Manufacture Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'Cargo Handler']
        ];
    }

    function PosUserVal($input) {
        // Add/Edit Manufacture Validate
        $rule = [
            'username' => 'required',
            'password' => 'required',
        ];
        return Validator::make($input, $rule);
    }

    function AddPosUser(Request $request) {
        // Add Manufacture Action Procedures
        $Model = new Posusers();
        $Model->name = $request->name;
        $Model->username = $request->username;
        $Model->password = Hash::make($request->password);
        $Model->save();
    }
}
