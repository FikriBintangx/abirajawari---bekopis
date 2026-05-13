<?php

namespace App\Http\Controllers;

use App\Models\ExcelSheet;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function index()
    {
        $sheets = ExcelSheet::all();
        
        // If no sheets exist, create some defaults for demo
        if ($sheets->isEmpty()) {
            $defaults = [
                ['name' => 'Data Produk', 'data' => ['headers' => ['Nama', 'Kategori', 'Stok', 'Harga'], 'rows' => []]],
                ['name' => 'Penjualan', 'data' => ['headers' => ['Invoice', 'Pelanggan', 'Total', 'Status'], 'rows' => []]],
                ['name' => 'Gudang', 'data' => ['headers' => ['Rak', 'Item', 'Kapasitas'], 'rows' => []]],
            ];
            foreach ($defaults as $default) {
                ExcelSheet::create($default);
            }
            $sheets = ExcelSheet::all();
        }

        return view('admin.dashboard', compact('sheets'));
    }

    public function update(Request $request, $id)
    {
        $sheet = ExcelSheet::findOrFail($id);
        $sheet->update([
            'data' => $request->input('data')
        ]);

        return response()->json(['success' => true, 'message' => 'Sheet berhasil disimpan']);
    }

    public function store(Request $request)
    {
        $sheet = ExcelSheet::create([
            'name' => $request->input('name'),
            'data' => [
                'headers' => $request->input('headers', ['Kolom 1']),
                'rows' => []
            ]
        ]);

        return response()->json(['success' => true, 'sheet' => $sheet]);
    }

    public function rename(Request $request, $id)
    {
        $sheet = ExcelSheet::findOrFail($id);
        $sheet->update([
            'name' => $request->input('name')
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $sheet = ExcelSheet::findOrFail($id);
        $sheet->delete();

        return response()->json(['success' => true]);
    }
}
