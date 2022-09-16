<?php

namespace App;

use App\Http\Helper\Formz;
use App\Http\Helper\HTML;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Manufactures extends Model {
    protected $table = "manufactures";

    function Slots() {
        return $this->hasMany(Slots::class, 'id_mnf');
    }

    function Skus() {
        return $this->hasMany(Skus::class, 'id_mnf');
    }

    function MnfTable() {
        // Manufacture Table Configuration
        $Models = Manufactures::orderBy('created_at', 'desc')->get();
        $table['tittle'] = 'Cargo Handler Table';
        $table['head'] = HTML::jrsTableHead(['Airport', 'Tenant Name', 'Username', 'Status', 'Edit', 'Delete']);
        $table['default'] = "zzzz";
        $table['search'] = "search";

        $table['ex'][] = HTML::jrsBtn(['master/mnf-form', 'Add', 'primary', 'fajax', FALSE, 'plus']);
        $table['ex'][] = "<hr/>";
        foreach ($Models as $Model) {
            $col = [
                array_rand(
                    ['Soekarno Hatta' => 'Soekarno Hatta', 'Kualanamu' => 'Kualanamu', 'Palembang' => 'Palembang', 'Pontianak' => 'Pontianak']
                ),
                $Model['name'], $Model['username'], '<div style="color:green">active</div>',
                HTML::jrsBtn(['#', '', 'warning', '', FALSE, 'pencil']),
                HTML::jrsBtn(['#', '', 'danger', '', FALSE, 'eraser'])
            ];
            $table['row'][] = '<tr>' . HTML::jrsTableColumn($col) . '</tr>';
        }

        return $table;
    }

    function MnfForm() {
        // Manufacture Form Configuration
        $form['tittle'] = "Add Tenant (Cargo Handler)";
        $form['fancy'] = TRUE;
        $form['open'] = Formz::open(['url' => 'master/add-mnf', 'method' => 'POST']);
        $form['field'][] = ['label' => 'Airport', 'main' => Formz::jrsselect('airport', ['1' => 'Soekarno Hatta', '2' => 'Kualanamu', '3' => 'Palembang', '4' => 'Pontianak'], '')];
        $form['field'][] = ['label' => 'Tenant Name (Cargo Handler)', 'main' => Formz::jrstext('name', '')];
        $form['field'][] = ['label' => 'Username', 'main' => Formz::jrstext('username', '')];
        $form['field'][] = ['label' => 'Password', 'main' => Formz::jrspassword('password')];
        $form['field'][] = ['main' => Formz::jrssubmit('Submit', 'primary')];
        return $form;
    }

    function MnfBC() {
        // Manufacture Breadcrumbs Configuration
        return [
            ['', url('main/home-page'), 'Home'],
            ['active', '', 'Airport']
        ];
    }

    function MnfVal($input) {
        // Add/Edit Manufacture Validate
        $rule = [
            'username' => 'required',
            'password' => 'required',
            'name' => 'required'
        ];
        return Validator::make($input, $rule);
    }

    function AddMnf(Request $request) {
        // Add Manufacture Action Procedures
        $Model = new Manufactures();
        $Model->name = $request->name;
        $Model->username = $request->username;
        $Model->password = Hash::make($request->password);
        $Model->save();
    }
}
