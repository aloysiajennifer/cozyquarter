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
            return redirect()->route('cwspace.index')->with('success', 'Co-working space successfully added.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kode error duplicate entry (23000)
            if ($e->getCode() == 23000) {
                return back()->with('error', "Failed to add data: room code '{$request->code_cwspace}' already exists. Please use a different code.");
            }
            return back()->with('error', 'Failed to add data due to a database error.');
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
            return redirect()->route('cwspace.index')->with('error', 'Data is not found');
        }
        try {
            $cwspace->update($validated);
            return redirect()->route('cwspace.index')->with('success', 'Co-working space has been successfully updated.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kode error duplicate entry (23000)
            if ($e->getCode() == 23000) {
                return back()->with('error', "Failed to update data: room code '{$request->code_cwspace}' already exists. Please use a different code.");
            } else {
                return back()->with('error', 'Failed to update data due to a database error.');
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $cwspace = Cwspace::find($id);
        if (!$cwspace) {
            return redirect()->route('cwspace.index')->with('error', 'Data is not found');
        }
        if ($cwspace->status_cwspace == 0) {
            return redirect()->route('cwspace.index')->with('error', 'Cannot delete an active space.');
        }

        $cwspace->delete();
        return redirect()->route('cwspace.index')->with('success', 'Co-working space has been successfully deleted.');
    }
}