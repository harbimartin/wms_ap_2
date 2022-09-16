<?php

use App\Http\Controllers\GeneralController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/sbadmin2', [ViewController::class, 'sbadmin2']);
Route::get('/', [ViewController::class, 'login_admin']);
Route::get('/throughput', [ViewController::class, 'throughput']);
Route::get('/inventory-utilization', [ViewController::class, 'inventory_utilization']);
Route::get('/storage-utilization', [ViewController::class, 'storage_utilization']);
Route::get('/revenue', [ViewController::class, 'revenue']);
Route::get('/tesquery', [ViewController::class, 'tesquery']);
Route::group(
    ['prefix' => 'main'],
    function () {
        Route::get('/home-page', [MasterController::class, 'getHomePage']);
        Route::post('/log-in', [GeneralController::class, 'postLogIn']);
        Route::get('/log-out', [GeneralController::class, 'getLogOut']);
    }
);
Route::middleware(['authjrs'])->group(function () {
    Route::group(['prefix' => 'inbound'], function () {
        Route::get('/po-form', [InboundController::class, 'getPoForm']);
        Route::get('/inb-po', [InboundController::class, 'getInbPo']);
        Route::get('/inb-po-sku', [InboundController::class, 'getInbPoSku']);
        Route::get('/inb-receiving', [InboundController::class, 'getInbReceiving']);
        Route::get('/inb-sku-receiving', [InboundController::class, 'getInbSkuReceiving']);
        Route::get('/inb-put-away', [InboundController::class, 'getInbPutAway']);
        Route::get('/inb-sku-put-away', [InboundController::class, 'getInbPutAway']);
        Route::get('/done-po-sku', [InboundController::class, 'getDonePoSku']);
        Route::get('/done-checking', [InboundController::class, 'getDoneChecking']);
        Route::get('/dl-is-doc', [InboundController::class, 'getDlIsDoc']);
        Route::get('/ts', [InboundController::class, 'getTs']);
        Route::get('/dl-ts', [InboundController::class, 'getDlTs']);
        Route::prefix('inbound')->group(function(){
            Route::post('/add-po', [InboundController::class, 'postAddPo']);
            Route::post('/edit-po', [InboundController::class, 'postEditPo']);
            Route::post('/delete-po', [InboundController::class, 'postDeletePo']);
            Route::post('/add-po-sku', [InboundController::class, 'postAddPoSku']);
            Route::post('/in-bound-checking', [InboundController::class, 'postInboundChecking']);
        });
    });
    Route::group(['prefix' => 'outbound'], function () {
        Route::get('/so', [OutboundController::class, 'getSo']);
        Route::get('/open-so', [OutboundController::class, 'getOpenSo']);
        Route::get('/picking-list-so', [OutboundController::class, 'getPickingListSo']);
        Route::get('/do-so', [OutboundController::class, 'getDoSo']);
        Route::get('/so-form', [OutboundController::class, 'getSoForm']);
        Route::get('/so-sku', [OutboundController::class, 'getSoSku']);
        Route::get('/so-sku-form', [OutboundController::class, 'getSoSkuForm']);
        Route::get('/done-so-sku', [OutboundController::class, 'getDoneSoSku']);
        Route::get('/pl', [OutboundController::class, 'getPl']);
        Route::get('/dl-pl-doc', [OutboundController::class, 'getDlPlDoc']);
        Route::get('/done-pl', [OutboundController::class, 'getDonePl']);
        Route::get('/do', [OutboundController::class, 'getDo']);
        Route::get('/dl-do-doc', [OutboundController::class, 'getDlDoDoc']);
        Route::get('/done-do', [OutboundController::class, 'getDoneDo']);
        Route::prefix('outbound')->group(function(){
            Route::post('/add-so', [OutboundController::class, 'postAddSo']);
            Route::post('/edit-so', [OutboundController::class, 'postEditSo']);
            Route::post('/delete-so', [OutboundController::class, 'postDeleteSo']);
            Route::post('/add-so-sku', [OutboundController::class, 'postAddSoSku']);
        });
    });

    Route::group(['prefix' => 'storage'], function () {
        Route::get('/storage', [StorageController::class, 'getStorage']);
        Route::get('/temp-storage', [StorageController::class, 'getTempStorage']);
        Route::get('/dl-rd', [StorageController::class, 'getDlRd']);
        Route::get('/gen-rs', [StorageController::class, 'getGenRs']);
        Route::get('/stock-adjustment', [StorageController::class, 'getStockAdjustment']);
        Route::prefix('storage')->group(function(){
            Route::post('/update-stock', [StorageController::class, 'postUpdateStock']);
            Route::post('/add-replacement', [StorageController::class, 'postAddReplacement']);
        });
    });

    Route::group(['prefix' => 'master'], function () {
        Route::get('/home-page', [MasterController::class, 'getHomePage']);
        Route::get('/mnf', [MasterController::class, 'getMnf']);
        Route::get('/mnf-form', [MasterController::class, 'getMnfForm']);
        Route::get('/pos-user', [MasterController::class, 'getPosUser']);
        Route::get('/posusersform', [MasterController::class, 'getPosusersForm']);
        Route::get('/uom', [MasterController::class, 'getUom']);
        Route::get('/uom-form', [MasterController::class, 'getUomForm']);
        Route::get('/sku-slotting', [MasterController::class, 'getSkuSlotting']);
        Route::get('/sku-slotting-fsn', [MasterController::class, 'getSkuSlottingFsn']);
        Route::get('/create-sku', [MasterController::class, 'getCreateSku']);
        Route::get('/update-sku', [MasterController::class, 'getUpdateSku']);
        Route::get('/slot', [MasterController::class, 'getSlot']);
        Route::get('/slot-form', [MasterController::class, 'getSlotForm']);
        Route::get('/gen-inv-prty', [MasterController::class, 'getGenInvPrty']);
        Route::get('/incoming-order', [MasterController::class, 'getIncomingOrder']);
        Route::get('/module', [MasterController::class, 'getModule']);
        Route::prefix('master')->group(function(){
            Route::post('/add-slot', [MasterController::class, 'postAddSlot']);
            Route::post('/update-sku', [MasterController::class, 'postUpdateSku']);
            Route::post('/add-sku', [MasterController::class, 'postAddSku']);
            Route::post('master/add-uom', [MasterController::class, 'postAddUom']);
            Route::post('/add-pos-user', [MasterController::class, 'postAddPosUser']);
            Route::post('/add-mnf', [MasterController::class, 'postAddMnf']);
        });
    });
});
