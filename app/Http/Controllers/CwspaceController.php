<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cwspace;
use Illuminate\Http\Request;

class CwspaceController extends Controller
{
    public function index()
    {
        $cwspaces = Cwspace::all();
        return view('admin.cwspace.index', compact('cwspaces'));
    }

    public function insert(Request $request)
    {
        $validated = $request->validate([
            'code_cwspace' => 'required|max:25|string',
            'capacity_cwspace' => 'required|max:50|min:3|integer',
            'status_cwspace' => 'required|in:0,1',
        ]);

        try {
            Cwspace::create($validated);
            return redirect()->route('cwspace.index')->with('success', 'Co-working space berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kode error duplicate entry (23000)
            if ($e->getCode() == 23000) {
                return back()->with('error', "Gagal menambahkan data: kode ruang '{$request->code_cwspace}' sudah ada. Silakan gunakan kode lain.");
            }
            return back()->with('error', 'Gagal menambahkan data karena kesalahan database.');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code_cwspace' => 'required|max:25|string',
            'capacity_cwspace' => 'required|max:50|min:3|integer',
            'status_cwspace' => 'required|in:0,1',
        ]);

        $cwspace = Cwspace::find($id);
        if (!$cwspace) {
            return redirect()->route('cwspace.index')->with('error', 'Data tidak ditemukan');
        }
        try {
            $cwspace->update($validated);
            return redirect()->route('cwspace.index')->with('success', 'Co-working space berhasil di update.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kode error duplicate entry (23000)
            if ($e->getCode() == 23000) {
                return back()->with('error', "Gagal update data: kode ruang '{$request->code_cwspace}' sudah ada. Silakan gunakan kode lain.");
            } else {
                return back()->with('error', 'Gagal update data karena kesalahan database.');
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $cwspace = Cwspace::find($id);
        if (!$cwspace) {
            return redirect()->route('cwspace.index')->with('error', 'Data tidak ditemukan');
        }
        if ($cwspace->status_cwspace == 0) {
            return redirect()->route('cwspace.index')->with('error', 'Tidak bisa menghapus ruang yang masih aktif.');
        }

        $cwspace->delete();
        return redirect()->route('cwspace.index')->with('success', 'Co-working space berhasil di delete.');
    }
}