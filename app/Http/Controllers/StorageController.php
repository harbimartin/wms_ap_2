<?php

namespace App\Http\Controllers;

use App\Slots;
use App\Tempstorages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use PDF;

class StorageController extends Controller {
    function getStorage() {
        // View Storage Table
        $Model = new Slots();
        return View::make('template.main', array('table' => $Model->StorageTable(), 'bc' => $Model->StorageBC()));
    }

    function getTempStorage() {
        // View Temporary Storage Table
        $Model = new Tempstorages();
        return View::make('template.main', array('table' => $Model->TempStorTable(), 'bc' => $Model->TempStorBC()));
    }

    function getDlRd() {
        $sign[] = array(
            array('Approved By', 'WH. Checker'),
            array('Operated By', 'WH. Operator'),
        );

        $Model = new Tempstorages();
        $param['table']    = $Model->DlReplacementDocTable();
        $param['sign'] = $sign;
        $pdf = PDF::loadView('main.doc.DownloadedDOC', $param);
        return $pdf->download('Replacement Doc. ' . date('d/m/Y h-i-s') . '.pdf');
    }

    function getGenRs() {
        $TS = new Tempstorages();
        $TS->GenReplacementSlot();
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function postAddReplacement(Request $request) {
        $TS = new Tempstorages();
        $TS->AddReplacementSlot($request);
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function getStockAdjustment() {
        $Model = new Slots();
        return View::make('template.main', array('table' => $Model->StockAdjustmentTable(), 'bc' => $Model->StockAdjustmentBC()));
    }

    function postUpdateStock(Request $request) {
        $Model = new Slots();
        ($Model->UpdateStock($request) == TRUE)
            ? Session::flash('msg', 'Action Success')
            : Session::flash('msg', 'No Action Done');
        return redirect()->back();
    }
}
