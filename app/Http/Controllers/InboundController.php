<?php

namespace App\Http\Controllers;

use App\Pos;
use App\Poskus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use PDF;

class InboundController extends Controller {
    function getPoForm($type, $id = null) {
        // View Add SO Form
        $Model = new Pos();
        return View::make('template.form.formFancy', array('form' => $Model->PoForm($type, $id)));
    }

    function getInbPo() {
        // Page with PO Table and PO Breadcrumbs
        $Model = new Pos();
        return View::make('template.main2', array('table' => $Model->InboundPOTable(), 'form' => $Model->PoForm('add', null), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Inbound Order']]));
    }

    function getInbPoSku($id_po) {
        // Page with PO SKU Table by id_po and PO SKU Breadcrumbs
        $Model = new Poskus();
        return View::make('template.main2', array('table' => $Model->InbPoSkuTable($id_po), 'form' => $Model->PoSkuForm($id_po), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Purchase Order']]));
    }

    function getInbReceiving() {
        // Page with PO Table and PO Breadcrumbs
        $Model = new Pos();
        return View::make('template.main', array('table' => $Model->InbReceivingTable(), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Inbound Order']]));
    }

    function getInbSkuReceiving($id_po) {
        // Page with PO SKU Table by id_po and PO SKU Breadcrumbs
        $Model = new PoSkus();
        return View::make('template.main', array('table' => $Model->InbPoSkuReceivingTable($id_po), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Inbound Receiving']]));
    }

    function getInbPutAway() {
        // Page with PO Table and PO Breadcrumbs
        $Model = new Pos();
        return View::make('template.main', array('table' => $Model->InbPutAwayTable(), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Inbound Order']]));
    }

    function getInbSkuPutAway($id_po) {
        // Page with PO SKU Table by id_po and PO SKU Breadcrumbs
        $Model = new PoSkus();
        return View::make('template.main', array('table' => $Model->InbPutAwaySkuTable($id_po), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Purchase Order']]));
    }

    function postAddPo(Request $request) {
        // Add PO action
        $Model = new Pos();
        $Validate = $Model->PoVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddPo($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function postEditPo(Request $request) {
        // Add PO action
        $Model = new Pos();
        $Validate = $Model->PoVal($request->toArray());

        if (!$Validate->fails()) {
            $Model->EditPo($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function postDeletePo(Request $request) {
        $PO = Pos::find($request->id_po);
        $PO->delete();
        Session::flash('notif_msg', 'Action Success');
        return redirect('inbound/po-form/add');
    }

    function postAddPoSku(Request $request) {
        // Add PO SKU action
        $Model = new Poskus();
        $Validate = $Model->PoSkuVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddPoSku();
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function postInboundChecking() {
        $Model = new PoSkus();
        $Model->InboundChecking();
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function getDonePoSku($id_po) {
        // Done PO SKU action
        $Model = new PoSkus();
        $Model->DonePoSku($id_po);
        Session::flash('msg', 'Action Success');
        return redirect('inbound/inb-receiving');
    }

    function getDoneChecking($id_po) {
        // Done PO SKU action
        $Model = new PoSkus();
        $Model->DoneChecking($id_po);
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function getDlIsDoc($id_po) {
        // Download Inbound Shipment Document for Driver by id_po
        $sign[] = [['Checked By', 'WH. Checker'], ['Carried By', 'Carrier']];

        $Model = new Poskus();
        $param['table'] = $Model->DlIsTable($id_po);
        $param['sign'] = $sign;
        $pdf = PDF::loadView('main.doc.DownloadedDOC', $param);
        return $pdf->download('InboundShipment ' . date('d/m/Y h-i-s') . '.pdf');
    }

    function getTs($id_po) {
        // Page with Tally Sheet Execute Table by id_po
        $Model = new Poskus();
        return View::make('template.main', array('table' => $Model->TsTable($id_po), 'bc' => $Model->TsBC()));
    }

    function getDlTs($id_po) {
        // Download Tally Sheet for Checker by id_po
        $sign[] = array(
            array('Approved By', 'WH. Checker'),
            array('Operated By', 'WH. Operator'),
        );

        $Model = new Poskus();
        $param['table']    = $Model->DlTsTable($id_po);
        $param['sign'] = $sign;
        $pdf = PDF::loadView('main.doc.DownloadedDOC', $param);
        return $pdf->download('InboundShipment ' . date('d/m/Y h-i-s') . '.pdf');
    }
}
