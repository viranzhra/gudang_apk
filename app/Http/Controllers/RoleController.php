<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoleController extends Controller
{
    private $apiUrl;
    protected $permissionNames;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url', env('API_URL'));

        $this->permissionNames = [
            'supplier.view' => 'Lihat Data Supplier',
            'supplier.create' => 'Tambah Data Supplier',
            'supplier.edit' => 'Edit Data Supplier',
            'supplier.delete' => 'Hapus Data Supplier',
            'customer.view' => 'Lihat Data Customer',
            'customer.create' => 'Tambah Data Customer',
            'customer.edit' => 'Edit Data Customer',
            'customer.delete' => 'Hapus Data Customer',
            'item.view' => 'Lihat Data Barang',
            'item.create' => 'Tambah Data Barang',
            'item.edit' => 'Edit Data Barang',
            'item.delete' => 'Hapus Data Barang',
            'item type.view' => 'Lihat Data Jenis Barang',
            'item type.create' => 'Tambah Data Jenis Barang',
            'item type.edit' => 'Edit Data Jenis Barang',
            'item type.delete' => 'Hapus Data Jenis Barang',
            'item status.view' => 'Lihat Status Barang',
            'item status.create' => 'Tambah Status Barang',
            'item status.edit' => 'Edit Status Barang',
            'item status.delete' => 'Hapus Status Barang',
            'requirement type.view' => 'Lihat Jenis Keperluan',
            'requirement type.create' => 'Tambah Jenis Keperluan',
            'requirement type.edit' => 'Edit Jenis Keperluan',
            'requirement type.delete' => 'Hapus Jenis Keperluan',
            'incoming item.view' => 'Lihat Barang Masuk',
            'incoming item.create' => 'Tambah Barang Masuk',
            'incoming item.edit' => 'Edit Barang Masuk',
            'incoming item.delete' => 'Hapus Barang Masuk',
            'outbound item.view' => 'Lihat Barang Keluar',
            'outbound item.create' => 'Tambah Barang Keluar',
            'outbound item.edit' => 'Edit Barang Keluar',
            'outbound item.delete' => 'Hapus Barang Keluar',
            'item request.view' => 'Lihat Permintaan Barang',
            'item request.create' => 'Tambah Permintaan Barang',
            'item request.confirm' => 'Konfirmasi Permintaan Barang',
            'report.view stock' => 'Lihat Laporan Stok',
            'report.export stock' => 'Export Laporan Stok',
            'report.view incoming item' => 'Lihat Laporan Barang Masuk',
            'report.export incoming item' => 'Export Laporan Barang Masuk',
            'report.view outbound item' => 'Lihat Laporan Barang Keluar',
            'report.export outbound item' => 'Export Laporan Barang Keluar'
        ];    
    }

    public function index()
    {
        $response = Http::get("{$this->apiUrl}/roles");

        if ($response->successful()) {
            return view('roles.index', [
                'roles' => $response->json('roles'),
                'users' => $response->json('users'),
                'permissions' => $response->json('permissions'),
                'permissionNames' => $this->permissionNames, // Kirimkan ke view
            ]);
        }

        return back()->withErrors(['error' => 'Failed to fetch roles data from API.']);
    }

    public function create()
    {
        $response = Http::get("{$this->apiUrl}/roles/create");

        if ($response->successful()) {
            return view('roles.create', [
                'groupedPermissions' => $response->json('permissions'),
            ]);
        }

        return back()->withErrors(['error' => 'Failed to fetch permissions data from API.']);
    }

    public function store(Request $request)
    {
        $response = Http::post("{$this->apiUrl}/roles", [
            'name' => $request->name,
            'permissions' => $request->permissions
        ]);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        }

        return back()->withErrors(['error' => 'Failed to create role.']);
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/roles/{$id}/edit");

        if ($response->successful()) {
            return view('roles.edit', [
                'role' => $response->json('role'),
                'rolePermissions' => $response->json('rolePermissions'), // Mengirim rolePermissions ke blade
                'groupedPermissions' => $response->json('groupedPermissions'),
            ]);
        }

        return back()->withErrors(['error' => 'Failed to fetch role data from API.']);
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->apiUrl}/roles/{$id}", [
            'name' => $request->name,
            'permissions' => $request->permissions
        ]);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        }

        return back()->withErrors(['error' => 'Failed to update role.']);
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/roles/{$id}");

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        }

        return back()->withErrors(['error' => 'Failed to delete role.']);
    }

    public function assignRole(Request $request, $userId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('auth_token'),
            'Accept' => 'application/json',
        ])->put(env('API_URL') . "/roles/assign/{$userId}", [
            'roles' => $request->roles,
        ]);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'User roles updated successfully');
        }

        return back()->withErrors(['error' => 'Failed to update user roles']);
    }

}
