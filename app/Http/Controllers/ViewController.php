<?php

namespace App\Http\Controllers;

use App\Random;
use App\Skus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ViewController extends Controller {
    public function sbadmin2() {
        return View::make('sb-admin-2.example-content');
    }
    public function login_admin() {
        return View::make('login-admin');
    }

    public function throughput(Request $request) {
        $Model = new Random();
        return View::make('template.main', array('table' => $Model->TroughputVolumeTable($request), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Throughput']]));
    }

    public function inventory_utilization(Request $request) {
        $Model = new Random();
        return View::make('template.main', array('table' => $Model->TroughputWeightTable($request), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Inventory Utilization']]));
    }

    public function storage_utilization() {
        $Model = new Random();
        return View::make('template.main', array('table' => $Model->StorageUtilization(), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Storage Utilization']]));
    }

    public function revenue() {
        $Model = new Random();
        return View::make('template.main', array('table' => $Model->Revenue(), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Revenue']]));
    }

    public function tesquery() {
        echo "<pre>";
        var_dump(Skus::orderBy('id_mnf')->orderBy('inventory_level')->get()->toArray());
        echo "</pre>";
    }
}
