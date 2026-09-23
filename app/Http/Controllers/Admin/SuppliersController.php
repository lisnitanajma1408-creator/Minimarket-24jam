<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::latest()->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Suppliers::create($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function edit(Suppliers $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Suppliers $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Suppliers $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Pemasok berhasil dihapus.');
    }
}