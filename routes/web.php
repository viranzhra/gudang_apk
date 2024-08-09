<?php

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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::get('/supplier/delete/{id}', [SupplierController::class, 'delete'])->name('supplier.delete');
    Route::get('/supplier/getUserDataByName/{name}', [SupplierController::class, 'getUserDataByName']);
    Route::post('/supplier/delete-selected', [SupplierController::class, 'deleteSelected']);

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    Route::get('/customer/getUserDataByName/{name}', [CustomerController::class, 'getUserDataByName']);
    Route::post('/customer/delete-selected', [CustomerController::class, 'deleteSelected']);

    Route::get('/jenisbarang', [JenisBarangController::class, 'index'])->name('jenisbarang.index');
    Route::get('/jenisbarang/create', [JenisBarangController::class, 'create'])->name('jenisbarang.create');
    Route::post('/jenisbarang/store', [JenisBarangController::class, 'store'])->name('jenisbarang.store');
    Route::get('/jenisbarang/edit/{id}', [JenisBarangController::class, 'edit'])->name('jenisbarang.edit');
    Route::put('/jenisbarang/update/{id}', [JenisBarangController::class, 'update'])->name('jenisbarang.update');
    Route::get('/jenisbarang/delete/{id}', [JenisBarangController::class, 'delete'])->name('jenisbarang.delete');
    Route::post('/jenisbarang/delete-selected', [JenisBarangController::class, 'deleteSelected']);

    Route::get('/statusbarang', [StatusBarangController::class, 'index'])->name('statusbarang.index');
    Route::get('/statusbarang/create', [StatusBarangController::class, 'create'])->name('statusbarang.create');
    Route::post('/statusbarang/store', [StatusBarangController::class, 'store'])->name('statusbarang.store');
    Route::get('/statusbarang/edit/{id}', [StatusBarangController::class, 'edit'])->name('statusbarang.edit');
    Route::put('/statusbarang/update/{id}', [StatusBarangController::class, 'update'])->name('statusbarang.update');
    Route::get('/statusbarang/delete/{id}', [StatusBarangController::class, 'delete'])->name('statusbarang.delete');
    Route::post('/statusbarang/delete-selected', [StatusBarangController::class, 'deleteSelected']);

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/edit/{id}', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::get('/barang/delete/{id}', [BarangController::class, 'delete'])->name('barang.delete');
    Route::post('/barang/delete-selected', [BarangController::class, 'deleteSelected']);

    Route::get('/barangmasuk', [BarangMasukController::class, 'index'])->name('barangmasuk.index');
    Route::get('/barangmasuk/create/{id?}', [BarangMasukController::class, 'create'])->name('barangmasuk.create');
    Route::get('/barangmasuk/get-by-jenis/{id}', [BarangMasukController::class, 'getBarangByJenis']);
    Route::post('/barangmasuk/store', [BarangMasukController::class, 'store'])->name('barangmasuk.store');
    //Route::get('/barangmasuk/edit/{id}', [BarangMasukController::class, 'edit'])->name('barangmasuk.edit');
    Route::put('/barangmasuk/update/{id}', [BarangMasukController::class, 'update'])->name('barangmasuk.update');
    Route::get('/barangmasuk/delete/{id}', [BarangMasukController::class, 'delete'])->name('barangmasuk.delete');
    Route::post('/barangmasuk/delete-selected', [BarangMasukController::class, 'deleteSelected']);

    Route::get('/serialnumber', [SerialNumberController::class, 'index'])->name('serialnumber.index');
    Route::get('/serialnumber/create', [SerialNumberController::class, 'create'])->name('serialnumber.create');
    Route::post('/serialnumber/store', [SerialNumberController::class, 'store'])->name('serialnumber.store');
    Route::get('/serialnumber/edit/{id}', [SerialNumberController::class, 'edit'])->name('serialnumber.edit');
    Route::put('/serialnumber/update/{id}', [SerialNumberController::class, 'update'])->name('serialnumber.update');
    Route::get('/serialnumber/delete/{id}', [SerialNumberController::class, 'delete'])->name('serialnumber.delete');
    Route::post('/serialnumber/delete-selected', [SerialNumberController::class, 'deleteSelected']);

    Route::get('/permintaanbarangkeluar', [PermintaanBarangKeluarController::class, 'index'])->name('permintaanbarangkeluar.index');
    Route::get('/permintaanbarangkeluar/create', [PermintaanBarangKeluarController::class, 'create'])->name('permintaanbarangkeluar.create');
    Route::get('/permintaanbarangkeluar/get-by-jenis/{id}', [PermintaanBarangKeluarController::class, 'getBarangByJenis']);
    Route::get('/permintaanbarangkeluar/get-by-barang/{id}', [PermintaanBarangKeluarController::class, 'getSerialNumberByBarang']);
    Route::post('/permintaanbarangkeluar/store', [PermintaanBarangKeluarController::class, 'store'])->name('permintaanbarangkeluar.store');
    Route::post('/permintaanbarangkeluar/update-status', [PermintaanBarangKeluarController::class, 'updateStatus']);
    //Route::get('/permintaanbarangkeluar/edit/{id}', [PermintaanBarangKeluarController::class, 'edit'])->name('permintaanbarangkeluar.edit');
    Route::put('/permintaanbarangkeluar/update/{id}', [PermintaanBarangKeluarController::class, 'update'])->name('permintaanbarangkeluar.update');
    Route::get('/permintaanbarangkeluar/delete/{id}', [PermintaanBarangKeluarController::class, 'delete'])->name('permintaanbarangkeluar.delete');

    Route::get('/barangkeluar', [BarangKeluarController::class, 'index'])->name('barangkeluar.index');
    Route::get('/barangkeluar/create/{id?}', [BarangKeluarController::class, 'create'])->name('barangkeluar.create');
    Route::get('/barangkeluar/get-by-jenis/{id}', [BarangKeluarController::class, 'getBarangByJenis']);
    Route::post('/barangkeluar/store', [BarangKeluarController::class, 'store'])->name('barangkeluar.store');
    Route::put('/barangkeluar/update/{id}', [BarangKeluarController::class, 'update'])->name('barangkeluar.update');
    Route::get('/barangkeluar/delete/{id}', [BarangKeluarController::class, 'delete'])->name('barangkeluar.delete');
    Route::post('/barangkeluar/delete-selected', [BarangKeluarController::class, 'deleteSelected']);

    Route::get('/keperluan', [KeperluanController::class, 'index'])->name('keperluan.index');
    Route::get('/keperluan/create', [KeperluanController::class, 'create'])->name('keperluan.create');
    Route::post('/keperluan/store', [KeperluanController::class, 'store'])->name('keperluan.store');
    Route::get('/keperluan/edit/{id}', [KeperluanController::class, 'edit'])->name('keperluan.edit');
    Route::put('/keperluan/update/{id}', [KeperluanController::class, 'update'])->name('keperluan.update');
    Route::get('/keperluan/delete/{id}', [KeperluanController::class, 'delete'])->name('keperluan.delete');
    Route::post('/keperluan/delete-selected', [KeperluanController::class, 'deleteSelected']);

    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok.index');
    Route::get('/laporan/barangmasuk', [LaporanController::class, 'barangmasuk'])->name('laporan.barangmasuk.index');
    Route::get('/laporan/barangkeluar', [LaporanController::class, 'barangkeluar'])->name('laporan.barangkeluar.index');
});

require __DIR__.'/auth.php';
