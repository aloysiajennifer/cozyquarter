<?php

namespace App\Http\Controllers;

use App\Models\Beverages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

use function PHPUnit\Framework\isTrue;

class BeverageController extends Controller
{
    //index beverage 
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Beverages::orderBy('name');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $beverages = $query->paginate(10)->withQueryString();

        return view('admin.beverage.IndexBeverage', [
            'beverages' => $beverages,
            'search' => $search,
        ]);
    }

    //add beverage
    public function create()
    {
        return view('admin.beverage.FormBeverage');
    }

    //save beverage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|gte:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $beverage = new Beverages;

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/beverages'), $filename);
            $beverage->image = 'images/beverages/' . $filename;
        }

        $beverage->name = $request->name;
        $beverage->price = $request->price;
        $beverage->stock = $request->stock;

        $beverage->save();

        return redirect()->route('beverage.index')->with('success', 'Beverage created successfully.');
    }

    //edit beverage
    public function edit($id)
    {
        $beverage = Beverages::findOrFail($id);
        return view('admin.beverage.DetailBeverage', compact('beverage'));
    }

    //update beverage (edit)
    public function update(Request $request, $id)
    {
        $beverage = Beverages::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|gte:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($beverage->image && file_exists(public_path($beverage->image))) {
                unlink(public_path($beverage->image));
            }

            $filename = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/beverages'), $filename);
            $beverage->image = 'images/beverages/' . $filename;
        }

        $beverage->name = $request->name;
        $beverage->price = $request->price;
        $beverage->stock = $request->stock;

        $beverage->save();

        return redirect()->route('beverage.index')->with('success', 'Beverage updated successfully!');
    }

    //delete beverage
    public function destroy($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $beverage = Beverages::findOrFail($id);

        if ($beverage->image && file_exists(public_path($beverage->image))) {
            unlink(public_path($beverage->image));
        }

        $beverage->delete();

        return redirect()->route('beverage.index')->with('success', 'Beverage deleted successfully.');
    }
}