<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
     public function index(Request $request){
    $search = $request->query('search');

    if ($search) {
        $shelves = Shelf::where('code_shelf', 'like', '%' . $search . '%')->get();

        if ($shelves->isEmpty()) {
            return view('admin.shelf.index', [
                'shelves' => [],
                'message' => "There's no shelf with code \"$search\".",
                'alertType' => 'error'
            ]);
        }

        return view('admin.shelf.index', [
            'shelves' => $shelves,
            'message' => "Search result for \"$search\".",
            'alertType' => 'info'

        ]);
    }

    $shelves = Shelf::get();
    return view('admin.shelf.index', [
        'shelves' => $shelves,
        'message' => null,
        'alertType' => null
    ]);
    }

    public function form() {
        return view('admin.shelf.form');
    }

    public function insert(Request $request) {
        // Validasi input
        $request->validate([
            'code_shelf' => 'required|unique:shelf,code_shelf'
        ]);

        $shelf = new Shelf;

        $shelf->code_shelf = $request->code_shelf;
        $shelf->save();

        return redirect()->route('shelf.index')->with('success', 'Shelf successfully added!');
    }

    public function detail(Request $request) {
        $id = decrypt($request->id);
        $shelf = Shelf::findOrFail($id);

        return view('admin.shelf.detail', ['shelf' => $shelf ]);
    }

    public function update(Request $request) {
        $id = decrypt($request->id);

        $request->validate([
             'code_shelf' => 'required|unique:shelf,code_shelf,' . $id
        ]);

        $shelf = Shelf::find($id);

        $shelf->code_shelf = $request->code_shelf;
        $shelf->save();

        return redirect()->route('shelf.index')->with('success', 'Shelf successfully updated!');
    }

    // buat delete
    public function delete(Request $request) {
        $id = decrypt($request->id);
        $shelf = Shelf::firstWhere('id', $id);

        $shelf->delete();

        return redirect()->route('shelf.index')->with('success', 'Shelf successfully deleted!');
    }
}
