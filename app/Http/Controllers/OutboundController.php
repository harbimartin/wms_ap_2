<?php

namespace App\Http\Controllers;

use App\Http\Helper\Formz;
use App\Sos;
use App\Soskus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use PDF;

class OutboundController extends Controller {
    function getSo() {
        // Page with SO Table and SO Breadcrumbs
        $Model = new Sos();
        return View::make('template.main', array('table' => $Model->SoTable(), 'bc' => $Model->SoBC()));
    }

    function getOpenSo() {
        // Page with SO Table and SO Breadcrumbs
        $Model = new Sos();
        return View::make('template.main', array('table' => $Model->OpenSoTable(), 'bc' => $Model->SoBC()));
    }

    function getPickingListSo() {
        // Page with SO Table and SO Breadcrumbs
        $Model = new Sos();
        return View::make('template.main', array('table' => $Model->PickingListTable(), 'bc' => $Model->SoBC()));
    }

    function getDoSo() {
        // Page with SO Table and SO Breadcrumbs
        $Model = new Sos();
        return View::make('template.main', array('table' => $Model->DoTable(), 'bc' => $Model->SoBC()));
    }

    function getSoForm($type, $id = null) {
        // View Add SO Form
        $Model = new Sos();
        return View::make('template.form.formFancy', array('form' => $Model->SoForm($type, $id)));
    }

    function postAddSo(Request $request) {
        // Add SO action
        $Model = new Sos();
        $Validate = $Model->SoVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddSo($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function postEditSo(Request $request) {
        // Add PO action
        $Model = new Sos();
        $Validate = $Model->SoVal($request->all());

        if (!$Validate->fails()) {
            $Model->EditSo($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function postDeleteSo(Request $request) {
        $SO = Sos::find($request->id_so);
        $SO->delete();
        Session::flash('notif_msg', 'Action Success');
        return redirect('outbound/so-form/add');
    }

    function getSoSku($id_so) {
        // Page with SO SKU Table by id_so and SO SKU Breadcrumbs
        $Model = new Soskus();
        return View::make('template.main', array('table' => $Model->SosSkuTable($id_so), 'bc' => $Model->SosSkuBC()));
    }

    function getSoSkuForm($id_so) {
        // View Add SO Form
        $Model = new SoSkus();
        return View::make('template.form.formFancy', array('form' => $Model->SosSkuForm($id_so)));
    }

    function postAddSoSku(Request $request) {
        // Add SO SKU action
        $Model = new Soskus();
        $Validate = $Model->SosSkuVal($request->all());

        if (!$Validate->fails()) {
            $maxDemand = Formz::valSoDemand($request->id_so, $request->id_sku);
            if ($maxDemand >= $request->qty) {
                $Model->AddSosSku($request);
                Session::flash('notif_msg', 'Action Success');
                return redirect()->back();
            } else {
                Session::flash('alert_msg', '<b>Your demand is over than actual storage</b>');
                return redirect()->back();
            }
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function getDoneSoSku($id_so) {
        // Done PO SKU action
        $Model = new Soskus();
        $Model->DoneSoSku($id_so);
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function getPl($id_so) {
        $Model = new Soskus();
        return View::make('template.main', array('table' => $Model->PlTable($id_so), 'bc' => $Model->PlBC()));
    }

    function getDlPlDoc($id_so) {
        // Download Picking List Document for Picker by id_so
        $sign[] = array(array('Checked By', 'WH. Checker'), array('Operated By', 'WH. Operator'));

        $Model = new Soskus();
        $param['table']    = $Model->DlPlTable($id_so);
        $param['sign'] = $sign;
        $pdf = PDF::loadView('main.doc.DownloadedDOC', $param);
        return $pdf->download('PickingList ' . date('d/m/Y h-i-s') . '.pdf');
    }

    function getDonePl($id_so) {
        $Model = new Soskus();
        $Model->DonePl($id_so);
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function getDo($id_so) {
        $Model = new Soskus();
        return View::make('template.main', array('table' => $Model->DoTable($id_so), 'bc' => $Model->DoBC()));
    }

    function getDlDoDoc($id_so) {
        // Download Picking List Document for Picker by id_so
        $sign[] = array(array('Checked By', 'WH. Checker'), array('Delivered By', 'Carrier'), array('Received By', 'Consignee'));

        $Model = new Soskus();
        $param['table']    = $Model->DlDoTable($id_so);
        $param['sign'] = $sign;
        $pdf = PDF::loadView('main.doc.DownloadedDOC', $param);
        return $pdf->download('Delivery Order ' . date('d/m/Y h-i-s') . '.pdf');
    }

    function getDoneDo($id_so) {
        $Model = new Soskus();
        $Model->DoneDo($id_so);
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }
}
