<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\StatusBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PermintaanBarangKeluarController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\KeperluanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SerialNumberController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BASTController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

// Route::middleware('guest')->group(function() {
//     Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [AuthController::class, 'login'])->name('login.post');

//     Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
//     Route::post('/register', [AuthController::class, 'register'])->name('register.post');
// });

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('jwt_token')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('check:roles.view');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('check:roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('check:roles.create');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('check:roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware('check:roles.edit');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('check:roles.delete');
    Route::put('/roles/assign-role/{userId}', [RoleController::class, 'assignRole'])->name('roles.assignRole')->middleware('check:roles.edit');

    // Route::prefix('roles')->group(function () {
    //     Route::get('/', [RoleController::class, 'index'])->name('roles.index'); // Menampilkan daftar roles dan permissions
    //     Route::get('/create', [RoleController::class, 'create'])->name('roles.create'); // Menampilkan daftar roles dan permissions
    //     Route::post('/', [RoleController::class, 'store'])->name('roles.store'); // Membuat role baru
    //     Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit'); // Mengedit role
    //     Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update'); // Memperbarui role
    //     Route::delete('/{id}', [RoleController::class, 'destroy'])->name('roles.destroy'); // Menghapus role
    //     Route::put('/assign-role/{userId}', [RoleController::class, 'assignRole'])->name('roles.assignRole'); // Menetapkan role ke pengguna
    // });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index')->middleware('check:supplier.view');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create')->middleware('check:supplier.create');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store')->middleware('check:supplier.create');
    Route::get('/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit')->middleware('check:supplier.edit');
    Route::put('/supplier/update/{id}', [SupplierController::class, 'update'])->name('supplier.update')->middleware('check:supplier.edit');
    Route::get('/supplier/delete/{id}', [SupplierController::class, 'delete'])->name('supplier.delete')->middleware('check:supplier.delete');
    Route::get('/supplier/getUserDataByName/{name}', [SupplierController::class, 'getUserDataByName']);
    Route::post('/supplier/delete-selected', [SupplierController::class, 'deleteSelected'])->middleware('check:supplier.delete');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index')->middleware('check:customer.view');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create')->middleware('check:customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store')->middleware('check:customer.create');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit')->middleware('check:customer.edit');
    Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update')->middleware('check:customer.edit');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete')->middleware('check:customer.delete');
    Route::get('/customer/getUserDataByName/{id}', [CustomerController::class, 'getUserDataByName']);
    Route::post('/customer/delete-selected', [CustomerController::class, 'deleteSelected'])->middleware('check:customer.delete');

    Route::get('/jenisbarang', [JenisBarangController::class, 'index'])->name('jenisbarang.index')->middleware('check:item type.view');
    Route::get('/jenisbarang/data', [JenisBarangController::class, 'getData'])->name('jenisbarang.data')->middleware('check:item type.view');
    Route::get('/jenisbarang/create', [JenisBarangController::class, 'create'])->name('jenisbarang.create')->middleware('check:item type.create');
    Route::post('/jenisbarang/store', [JenisBarangController::class, 'store'])->name('jenisbarang.store')->middleware('check:item type.create');
    Route::get('/jenisbarang/edit/{id}', [JenisBarangController::class, 'edit'])->name('jenisbarang.edit')->middleware('check:item type.edit');
    Route::put('/jenisbarang/update/{id}', [JenisBarangController::class, 'update'])->name('jenisbarang.update')->middleware('check:item type.edit');
    Route::get('/jenisbarang/delete/{id}', [JenisBarangController::class, 'delete'])->name('jenisbarang.delete')->middleware('check:item type.delete');
    Route::post('/jenisbarang/delete-selected', [JenisBarangController::class, 'deleteSelected'])->name('jenisbarang.deleteSelected')->middleware('check:item type.delete');

    Route::get('/statusbarang', [StatusBarangController::class, 'index'])->name('statusbarang.index')->middleware('check:item status.view');
    Route::get('/statusbarang/create', [StatusBarangController::class, 'create'])->name('statusbarang.create')->middleware('check:item status.create');
    Route::post('/statusbarang/store', [StatusBarangController::class, 'store'])->name('statusbarang.store')->middleware('check:item status.create');
    Route::get('/statusbarang/edit/{id}', [StatusBarangController::class, 'edit'])->name('statusbarang.edit')->middleware('check:item status.edit');
    Route::put('/statusbarang/update/{id}', [StatusBarangController::class, 'update'])->name('statusbarang.update')->middleware('check:item status.edit');
    Route::delete('/statusbarang/delete/{id}', [StatusBarangController::class, 'delete'])->name('statusbarang.delete')->middleware('check:item status.delete');
    Route::post('/statusbarang/delete-selected', [StatusBarangController::class, 'deleteSelected'])->middleware('check:item status.delete');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index')->middleware('check:item.view');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create')->middleware('check:item.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store')->middleware('check:item.create');
    Route::get('/barang/edit/{id}', [BarangController::class, 'edit'])->name('barang.edit')->middleware('check:item.edit');
    Route::put('/barang/update/{id}', [BarangController::class, 'update'])->name('barang.update')->middleware('check:item.edit');
    Route::get('/barang/delete/{id}', [BarangController::class, 'delete'])->name('barang.delete')->middleware('check:item.delete');
    Route::post('/barang/delete-selected', [BarangController::class, 'deleteSelected'])->middleware('check:item.delete');

    Route::get('/barangmasuk', [BarangMasukController::class, 'index'])->name('barangmasuk.index')->middleware('check:incoming item.view');
    Route::get('/barangmasuk/create/{id?}', [BarangMasukController::class, 'create'])->name('barangmasuk.create')->middleware('check:incoming item.create');
    Route::get('/barangmasuk/create', [BarangMasukController::class, 'create'])->middleware('check:incoming item.create');
    Route::get('/barangmasuk/get-by-jenis/{id}', [BarangMasukController::class, 'getBarangByJenis'])->middleware('check:incoming item.create');
    Route::post('/barangmasuk/store', [BarangMasukController::class, 'store'])->name('barangmasuk.store')->middleware('check:incoming item.create');
    //Route::get('/barangmasuk/edit/{id}', [BarangMasukController::class, 'edit'])->name('barangmasuk.edit');
    Route::put('/barangmasuk/update/{id}', [BarangMasukController::class, 'update'])->name('barangmasuk.update');
    Route::get('/barangmasuk/delete/{id}', [BarangMasukController::class, 'delete'])->name('barangmasuk.delete')->middleware('check:incoming item.delete');
    Route::post('/barangmasuk/delete-selected', [BarangMasukController::class, 'deleteSelected'])->middleware('check:incoming item.delete');
    Route::get('/preview/data', [BarangMasukController::class, 'getData'])->name('preview.data');

    Route::get('/download-template', [BarangMasukController::class, 'downloadTemplate'])->name('download.template');
    Route::post('/upload/excel', [BarangMasukController::class, 'uploadExcel'])->name('upload.excel');

    Route::get('/serialnumber', [SerialNumberController::class, 'index'])->name('serialnumber.index');
    Route::get('/serialnumber/create', [SerialNumberController::class, 'create'])->name('serialnumber.create');
    Route::post('/serialnumber/store', [SerialNumberController::class, 'store'])->name('serialnumber.store');
    Route::get('/serialnumber/edit/{id}', [SerialNumberController::class, 'edit'])->name('serialnumber.edit');
    Route::put('/serialnumber/update/{id}', [SerialNumberController::class, 'update'])->name('serialnumber.update');
    Route::get('/serialnumber/delete/{id}', [SerialNumberController::class, 'delete'])->name('serialnumber.delete');
    Route::post('/serialnumber/delete-selected', [SerialNumberController::class, 'deleteSelected']);

    Route::get('/permintaanbarangkeluar', [PermintaanBarangKeluarController::class, 'index'])->name('permintaanbarangkeluar.index')->middleware(['check:item request.viewFilterbyUser,item request.viewAll']);
    Route::get('/permintaanbarangkeluar/create', [PermintaanBarangKeluarController::class, 'create'])->name('permintaanbarangkeluar.create')->middleware('check:item request.create');
    Route::get('/permintaanbarangkeluar/get-stok/{barang_id}', [PermintaanBarangKeluarController::class, 'getStok']);
    Route::get('/permintaanbarangkeluar/get-by-jenis/{id}', [PermintaanBarangKeluarController::class, 'getBarangByJenis']);
    Route::get('/permintaanbarangkeluar/get-by-barang/{id}', [PermintaanBarangKeluarController::class, 'getSerialNumberByBarang']);
    Route::post('/permintaanbarangkeluar/store', [PermintaanBarangKeluarController::class, 'store'])->name('permintaanbarangkeluar.store')->middleware('check:item request.create');
    Route::post('/permintaanbarangkeluar/update-status', [PermintaanBarangKeluarController::class, 'updateStatus'])->middleware('check:item request.confirm');
    //Route::get('/permintaanbarangkeluar/edit/{id}', [PermintaanBarangKeluarController::class, 'edit'])->name('permintaanbarangkeluar.edit');
    Route::put('/permintaanbarangkeluar/update/{id}', [PermintaanBarangKeluarController::class, 'update'])->name('permintaanbarangkeluar.update')->middleware('check:item request.confirm');
    Route::get('/permintaanbarangkeluar/delete/{id}', [PermintaanBarangKeluarController::class, 'delete'])->name('permintaanbarangkeluar.delete');
    Route::get('/permintaanbarangkeluar/selectSN/{id}', [PermintaanBarangKeluarController::class, 'selectSN'])->name('permintaanbarangkeluar.selectSN')->middleware('check:item request.confirm');
    Route::post('/permintaanbarangkeluar/setSN', [PermintaanBarangKeluarController::class, 'setSN'])->name('permintaanbarangkeluar.setSN')->middleware('check:item request.confirm');

    Route::get('/permintaanbarangkeluar/generateBAST/{id}', [BASTController::class, 'index'])->name('generateBAST');

    Route::get('/barangkeluar', [BarangKeluarController::class, 'index'])->name('barangkeluar.index')->middleware('check:outbound item.view');
    Route::get('/barangkeluar/show', [BarangKeluarController::class, 'show'])->name('barangkeluar.show')->middleware('check:outbound item.view');
    Route::get('/barangkeluar/create/{id?}', [BarangKeluarController::class, 'create'])->name('barangkeluar.create')->middleware('check:outbound item.create');
    Route::get('/barangkeluar/get-by-jenis/{id}', [BarangKeluarController::class, 'getBarangByJenis'])->middleware('check:outbound item.create');
    Route::post('/barangkeluar/store', [BarangKeluarController::class, 'store'])->name('barangkeluar.store')->middleware('check:outbound item.create');
    Route::put('/barangkeluar/update/{id}', [BarangKeluarController::class, 'update'])->name('barangkeluar.update');
    Route::get('/barangkeluar/delete/{id}', [BarangKeluarController::class, 'delete'])->name('barangkeluar.delete')->middleware('check:outbound item.delete');
    Route::post('/barangkeluar/delete-selected', [BarangKeluarController::class, 'deleteSelected'])->middleware('check:outbound item.delete');

    Route::get('/keperluan', [KeperluanController::class, 'index'])->name('keperluan.index')->middleware('check:requirement type.view');
    Route::get('/keperluan/create', [KeperluanController::class, 'create'])->name('keperluan.create')->middleware('check:requirement type.create');
    Route::post('/keperluan/store', [KeperluanController::class, 'store'])->name('keperluan.store')->middleware('check:requirement type.create');
    Route::get('/keperluan/edit/{id}', [KeperluanController::class, 'edit'])->name('keperluan.edit')->middleware('check:requirement type.edit');
    Route::put('/keperluan/update/{id}', [KeperluanController::class, 'update'])->name('keperluan.update')->middleware('check:requirement type.edit');
    Route::get('/keperluan/delete/{id}', [KeperluanController::class, 'delete'])->name('keperluan.delete')->middleware('check:requirement type.delete');
    Route::post('/keperluan/delete-selected', [KeperluanController::class, 'deleteSelected'])->middleware('check:requirement type.delete');

    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok')->middleware('check:report.view stock');
    Route::get('/laporan/barangmasuk', [LaporanController::class, 'barangmasuk'])->name('laporan.barangmasuk.index')->middleware('check:report.view incoming item');
    Route::get('/laporan/barangkeluar', [LaporanController::class, 'barangkeluar'])->name('laporan.barangkeluar.index')->middleware('check:report.view outbound item');
    Route::get('api/laporan/barangkeluar/export/pdf', [LaporanController::class, 'exportPdf'])->middleware('check:report.export outbound item');
    Route::get('/export-barang-keluar', [LaporanController::class, 'exportBarangKeluar'])->middleware('check:report.export outbound item');
    Route::get('/export-barang-masuk', [LaporanController::class, 'exportBarangMasuk']);
});

// require __DIR__.'/auth.php';
