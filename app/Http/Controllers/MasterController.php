<?php

namespace App\Http\Controllers;

use App\Manufactures;
use App\Posusers;
use App\Random;
use App\Skus;
use App\Slots;
use App\Uoms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class MasterController extends Controller {
    function getHomePage() {
        return View::make('main/HomePage');
    }

    function getMnf() {
        // Page with Manufacture Table
        $Model = new Manufactures();
        return View::make('template.main', array('table' => $Model->MnfTable(), 'bc' => $Model->MnfBC()));
    }

    function getMnfForm() {
        // View Add Manufacture Form
        $Model = new Manufactures();
        return View::make('template.form.formFancy', array('form' => $Model->MnfForm()));
    }

    function postAddMnf(Request $request) {
        // Add Manufacture Action
        $Model = new Manufactures();
        $Validate = $Model->MnfVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddMnf($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function getPosUser() {
        // Page with Manufacture Table
        $Model = new Posusers();
        return View::make('template.main', array('table' => $Model->PosusersTable(), 'bc' => $Model->PosUserBC()));
    }

    function getPosusersForm() {
        // View Add Manufacture Form
        $Model = new Posusers();
        return View::make('template.form.formFancy', array('form' => $Model->AddPosUserForm()));
    }

    function postAddPosUser(Request $request) {
        // Add Manufacture Action
        $Model = new Posusers();
        $Validate = $Model->PosUserVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddPosUser($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function getUom() {
        // Page with Unit Of Messurement Table
        $Model = new Uoms();
        return View::make('template.main', array('table' => $Model->UomTable(), 'bc' => $Model->UomBC()));
    }

    function getUomForm() {
        // View Add UOM Form
        $Model = new Uoms();
        return View::make('template.form.formFancy', array('form' => $Model->UomForm()));
    }

    function postAddUom(Request $request) {
        // Add Unit Of UOM Action
        $Model = new Uoms();
        $Validate = $Model->UomVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddUom($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function getSkuSlotting() {
        // Page with SKU Table
        $Model = new Skus();
        return View::make('template.main', array('table' => $Model->SkuTable(), 'bc' => $Model->SkuBc()));
    }

    function getSkuSlottingFsn() {
        // Page with SKU Table
        $Model = new Skus();
        return View::make('template.main', array('table' => $Model->SkuFsnTable(), 'bc' => $Model->SkuBc()));
    }

    function getCreateSku() {
        $Model = new Skus();
        return View::make('template.main', array('form' => $Model->SkuForm(), 'bc' => $Model->CreateSkuBC()));
    }

    function getUpdateSku() {
        // View Add SKU Form
        $Model = new Skus();
        return View::make('template.main', array('table' => $Model->UpdateSkuTable(), 'bc' => $Model->SkuBc()));
    }

    function postUpdateSku() {
        // View Add SKU Form

        $Model = new Skus();
        ($Model->UpdateSku() == TRUE)
            ? Session::flash('msg', 'Action Success')
            : Session::flash('msg', 'No Action Done');
        return redirect()->back();
    }

    function postAddSku(Request $request) {
        // Add SKU Action
        $Model = new Skus();
        $Validate = $Model->SkuVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddSku($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function getSlot() {
        // Page with Slot Table
        $Model = new Slots();
        return View::make('template.main', array('table' => $Model->SlotTable(), 'bc' => $Model->SlotBC()));
    }

    function getSlotForm() {
        // View Add Slot Form
        $Model = new Slots();
        return View::make('template.form.formFancy', array('form' => $Model->SlotForm()));
    }

    function postAddSlot(Request $request) {
        // Add Slot Action
        $Model = new Slots();
        $Validate = $Model->SlotVal($request->all());

        if (!$Validate->fails()) {
            $Model->AddSlot($request);
            Session::flash('notif_msg', 'Action Success');
            return redirect()->back();
        } else {
            $message = $Validate->errors();
            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    function getGenInvPrty() {
        $Model = new Skus();
        $Model->GenInvPrty();
        Session::flash('msg', 'Action Success');
        return redirect()->back();
    }

    function getIncomingOrder() {
        // Page with Manufacture Table
        $Model = new Random();
        return View::make('template.main', array('table' => $Model->IncomingOrder(), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Incoming Order']]));
    }

    function getModule(Request $request) {
        $Model = new Random();
        return View::make('template.main', array('form' => $Model->ModuleForm($request), 'bc' => [['', url('main/home-page'), 'Home'], ['active', '', 'Module Management']]));
    }
}
