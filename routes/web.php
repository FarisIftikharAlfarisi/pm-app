<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskToDoController;
use App\Http\Controllers\SiteRequestController;
use App\Http\Controllers\SiteTaskJobController;
use App\Http\Controllers\GoodReceiptsController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PembelianBarangController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\PenerimaanBarangSiteController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/auth', [AuthenticationController::class, 'auth'])->name('login-process');



/*
|--------------------------------------------------------------------------
| Global Auth Route
|--------------------------------------------------------------------------
|
| Hak akses global untuk yang sudah login
|
|
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi-store', [AbsensiController::class, 'store'])->name('absensi.store');

    Route::get('/riwayat_absensi', [AbsensiController::class, 'riwayat_absensi'])->name('absensi.riwayat');
});



/*
|--------------------------------------------------------------------------
| PROJECT LEADER
|--------------------------------------------------------------------------
|
| Hak akses project leader : melakukan CRUD barang, membuat task untuk setiap site
| melihat progress dari setiap site
|
|
*/
Route::middleware(['auth', 'hak_akses:PROJECT_LEADER,ACCOUNTING'])->group(function () {

    // Dashboard
    Route::get('/dashboard/project/', function () {
        return view('Dashboard.project_leader');
    })->name('dashboard.project_leader');

    // Site Task Job
    Route::get('/site-task-jobs', [SiteTaskJobController::class, 'index'])->name('site-task-jobs.index');
    Route::get('/site-task-jobs/{id}', [SiteTaskJobController::class, 'show'])->name('site-task-jobs.show');
    Route::post('/site-task-jobs', [SiteTaskJobController::class, 'store'])->name('site-task-jobs.store');
    Route::get('/site-task-jobs/create', [SiteTaskJobController::class, 'create'])->name('site-task-jobs.create');
    Route::get('/site-task-jobs/{id}/edit', [SiteTaskJobController::class, 'edit'])->name('site-task-jobs.edit');
    Route::put('/site-task-jobs/{id}/update', [SiteTaskJobController::class, 'update'])->name('site-task-jobs.update');
    Route::delete('/site-task-jobs/{id}/delete', [SiteTaskJobController::class, 'destroy'])->name('site-task-jobs.destroy');
    //

    // Barang

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang-store', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}/delete', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
    Route::delete('/barang/{id}/force-delete', [BarangController::class, 'forceDelete'])->name('barang.forceDelete');
    Route::get('/barang/trashed', [BarangController::class, 'trashed'])->name('barang.trashed');

    // Bahan Baku
    Route::get('/new-product/bahan-baku', [BarangController::class, 'bahan_baku'])->name('barang.add.bahan_baku');

    Route::post('/bahan-baku-store', [BarangController::class, 'bahanBakuStore'])->name('barang.store.bahan_baku');

    // aftercraft
    Route::get('/new-product/aftercraft', [BarangController::class, 'aftercraft'])->name('barang.add.aftercraft');
    Route::post('/aftercraft-store', [BarangController::class, 'storeAftercraft'])->name('barang.store.aftercraft');

    // Bahan baku & Peralatan
    Route::get('/new-product/goods', [BarangController::class, 'bahan_baku'])->name('barang.goods_and_material');

    Route::get('/new-product/tools', [BarangController::class, 'tools'])->name('barang.tools');

    Route::get('/new-product/jasa', [BarangController::class, 'service'])->name('barang.service');

    Route::get('/barang/{id}/edit/aftercraft', [BarangController::class, 'editAftercraft'])->name('barang.edit.aftercraft');
    Route::get('/barang/{id}/edit/jasa', [BarangController::class, 'editJasa'])->name('barang.edit.jasa');
    Route::get('/barang/{id}/edit/bahanbaku', [BarangController::class, 'editBahanbaku'])->name('barang.edit.bahanbaku');
    Route::get('/barang/{id}/edit/peralatan', [BarangController::class, 'editPeralatan'])->name('barang.edit.peralatan');



    // End Barang



    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier-barang.create');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier-barang.edit');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier-barang.destroy');

    // end Supplier


    // Proyek

    Route::get('/proyek', [ProyekController::class, 'index'])->name('projects.index');
    Route::get('/proyek/create', [ProyekController::class, 'create'])->name('projects.create');
    Route::post('/proyek-store', [ProyekController::class, 'store'])->name('projects.store');

    Route::get('/proyek/{kode_proyek}', [ProyekController::class, 'edit'])->name('projects.edit');
    Route::put('/proyek/{kode_proyek}/update', [ProyekController::class, 'update'])->name('projects.update');

    // Site
    Route::get('/site', [SiteController::class, 'index'])->name('site.index');
    Route::post('/site-store', [SiteController::class, 'store'])->name('site.store');
    Route::post('/site/{id}', [SiteController::class, 'update'])->name('site.update');
    Route::delete('/site/{id}', [SiteController::class, 'destroy'])->name('site.destroy');
    // End Site

    //pengguna
    Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/pengguna-baru', [UserController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna-store', [UserController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [UserController::class, 'edit'])->name('pengguna.edit');
    Route::post('/pengguna/{id}', [UserController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('pengguna.destroy');
    Route::get('/pengguna/trashed', [UserController::class, 'trashed'])->name('pengguna.trashed');
    Route::post('/pengguna/{id}/restore', [UserController::class, 'restore'])->name('pengguna.restore');
    Route::delete('/pengguna/{id}/force-delete', [UserController::class, 'forceDelete'])->name('pengguna.forceDelete');
    // End Pengguna

    // Site Request
    Route::get('/site-request', [SiteRequestController::class, 'index'])->name('site-request.index');
    Route::post('/site-request', [SiteRequestController::class, 'store'])->name('site-request.store');
    Route::delete('/site-request/{id}/detail/delete', [SiteRequestController::class, 'destroyDetail'])->name('site-request.destroyDetail');
    Route::delete('/site-request/{id}/delete', [SiteRequestController::class, 'destroy'])->name('site-request.destroy');
    Route::get('/site-request/trashed', [SiteRequestController::class, 'trashed'])->name('site-request.trashed');
    Route::post('/site-request/{id}/restore', [SiteRequestController::class, 'restore'])->name('site-request.restore');
    Route::delete('/site-request/{id}/force-delete', [SiteRequestController::class, 'forceDelete'])->name('site-request.forceDelete');

    // Site Request Detail
    Route::get('/site-request/list_barang/{id}', [SiteRequestController::class, 'listBarang'])->name('site-request.list_barang');
    Route::post('/site-request/{id}/detail', [SiteRequestController::class, 'storeDetail'])->name('site-request.storeDetail');


    // approving site request
   // Approval routes
    Route::get('/site-request/review', [SiteRequestController::class, 'review'])->name('site_request.review');
    Route::get('/site-request/detail-review/{id}', [SiteRequestController::class, 'detailReview'])->name('site_request.detail_review');
    Route::post('/site-request/save-reviews/{id}', [SiteRequestController::class, 'saveReviews'])->name('site_request.save_reviews');



    // End Site Request


    // Pembelian Barang

    Route::get('/pembelian_barang', [PembelianBarangController::class, 'index'])->name('pembelian_barang.index');
    Route::post('/pembelian_barang', [PembelianBarangController::class, 'store'])->name('pembelian_barang.store');
    Route::get('/pembelian_barang/list_barang/{id}', [PembelianBarangController::class, 'listBarang'])->name('pembelian_barang.list_barang');
    Route::post('/pembelian_barang/detail/{id}', [PembelianBarangController::class, 'storeDetail'])->name('pembelian_barang.storeDetail');
    Route::delete('/pembelian_barang/detail/{id}/delete', [PembelianBarangController::class, 'destroyDetail'])->name('pembelian_barang.destroyDetail');
    // End Pembelian Barang

    //Task To Do

    Route::get('/task-to-do/wbs/{kode_proyek}', [TaskToDoController::class, 'index'])->name('task-to-do.index');
    Route::get('/task-to-do/create-wbs/{kode_proyek}', [TaskToDoController::class, 'create'])->name('task-to-do.create');
    Route::post('/task-to-do/store-wbs', [TaskToDoController::class, 'store'])->name('task-to-do.store');
    Route::get('/task-to-do/{kode_proyek}/{kode_task}/wbs-edit', [TaskToDoController::class, 'edit'])->name('task-to-do.edit');
    Route::put('/task-to-do/{kode_task}', [TaskToDoController::class, 'update'])->name('task-to-do.update');
    Route::post('/task-to-do/{id}/detail', [TaskToDoController::class, 'storeDetail'])->name('task-to-do.storeDetail');
    Route::delete('/task-to-do/{id}/detail/delete', [TaskToDoController::class, 'destroyDetail'])->name('task-to-do.destroyDetail');
    Route::delete('/task-to-do/{id}/delete', [TaskToDoController::class, 'destroy'])->name('task-to-do.destroy');
    Route::get('/task-to-do/trashed', [TaskToDoController::class, 'trashed'])->name('task-to-do.trashed');
    Route::post('/task-to-do/{id}/restore', [TaskToDoController::class, 'restore'])->name('task-to-do.restore');
    Route::delete('/task-to-do/{id}/force-delete', [TaskToDoController::class, 'forceDelete'])->name('task-to-do.forceDelete');

    Route::put('task/lock-wbs/{id}', [TaskToDoController::class, 'lockWbs'])->name('projects.lock-wbs');

    // End Task To Do

});





/*
|--------------------------------------------------------------------------
| ACCOUNTING
|--------------------------------------------------------------------------
|
| Hak akses : melakukan budgeting, melihat laporan keuangan, laba rugi, petty cash
|
|
|
*/

Route::middleware(['auth', 'hak_akses:ACCOUNTING'])->group(function () {
    // Dashboard
    Route::get('/accounting/dashboard', function () {
        return view('Dashboard.accounting');
    })->name('dashboard.accounting');


    // list finance view

    Route::get('/finance/{kode_proyek}', [ProyekController::class, 'financeIndex'])->name('finance.index');
    Route::get('finance/pilih-vendor/{kode_proyek}', [PurchaseRequisitionController::class, 'index'])->name('pr.pilih-vendor');
    //end list finance view


    //pembuatan PO

    Route::get('/purchase-requisition/{kode_proyek}', [PurchaseOrderController::class, 'index'])->name('pr.index');
    Route::post('/purchase-order/automate/{kode_proyek}', [PurchaseOrderController::class, 'automate'])
    ->name('purchase-order.automate');

    // list PO per supplier
    Route::get('/purchase-order/{kode_proyek}', [PurchaseOrderController::class, 'index_by_po'])->name('purchase-order.index_by_po');


    Route::get('/purchase-order/{id}/pdf', [PurchaseOrderController::class, 'downloadPdf'])
    ->name('purchase_order.download_pdf');
    //end pembuatan PO


    //produksi
    Route::get('/produksi/gantt', [ProduksiController::class, 'index'])->name('produksi.gantt');


    // good receipt

    Route::get('/good-receipt', [GoodReceiptsController::class, 'index'])->name('good-receipt.index');
    Route::post('/good-receipt-store', [GoodReceiptsController::class, 'store'])->name('good-receipt.store');
    // end good receipt

    // Site Request
    Route::get('/siterequirement/{kode_proyek}', [SiteRequestController::class, 'index_by_wbs'])->name('site-request.INDEX_WBS');
    Route::post('site-request/approve-items', [SiteRequestController::class, 'approveItems'])->name('site-request.approveItems');
    Route::post('/site-request/store/{kode_proyek}', [SiteRequestController::class, 'store'])->name('site-request.store');
    Route::delete('/site-request/{id}/detail/delete', [SiteRequestController::class, 'destroyDetail'])->name('site-request.destroyDetail');

});

/*
|--------------------------------------------------------------------------
| ADMIN SITE DAN KARYAWAN SITE
|--------------------------------------------------------------------------
|
| Hak akses karyawan : melakukan absensi
| Hak akses admin : melihat laporan absensi, melakukan
|                   penambahan barang yang diterima
|
|
*/
Route::middleware(['auth', 'hak_akses:ADMIN_SITE'])->group(function () {

     //Penerimaan Barang Site

     Route::get('/penerimaan_barang_site', [PenerimaanBarangSiteController::class, 'index'])->name('penerimaan_barang_site.index');
     Route::post('/penerimaan_barang_site', [PenerimaanBarangSiteController::class, 'store'])->name('penerimaan_barang_site.store');
     Route::get('/penerimaan_barang_site/list_barang/{id}', [PenerimaanBarangSiteController::class, 'listBarang'])->name('penerimaan_barang_site.list_barang');
     Route::post('/penerimaan_barang_site/detail/{id}', [PenerimaanBarangSiteController::class, 'storeDetail'])->name('penerimaan_barang_site.storeDetail');
     Route::delete('/penerimaan_barang_site/detail/{id}/delete', [PenerimaanBarangSiteController::class, 'destroyDetail'])->name('penerimaan_barang_site.destroyDetail');
     // End Penerimaan Barang Site

     // Dashboard
     Route::get('/dashboard/site/', function () {
        return view('Dashboard.admin');
    })->name('dashboard.admin_site');

});



/*
|--------------------------------------------------------------------------
| DEVELOPER
|--------------------------------------------------------------------------
|
| Hak akses : melakukan CRUD user
|
*/
