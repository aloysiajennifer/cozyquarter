<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     public function index(Request $request){
    $search = $request->query('search');

    if ($search) {
        $categories = Category::where('name_category', 'like', '%' . $search . '%')->get();

        if ($categories->isEmpty()) {
            return view('admin.category.index', [
                'categories' => [],
                'message' => "There's no category named \"$search\".",
                'alertType' => 'error'
            ]);
        }

        return view('admin.category.index', [
            'categories' => $categories,
            'message' => "Search result for \"$search\".",
            'alertType' => 'info'

        ]);
    }

    $categories = Category::get();
    return view('admin.category.index', [
        'categories' => $categories,
        'message' => null,
        'alertType' => null
    ]);
    }

    public function form() {
        return view('admin.category.form');
    }

    public function insert(Request $request) {
        // Validasi
        $request->validate([
            'name_category' => 'required'
        ]);

        $category = new Category;

        $category->name_category = $request->name_category;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category successfully added!');
    }

    public function detail(Request $request) {
        $id = decrypt($request->id);
        $category = Category::findOrFail($id);

        return view('category.detail', ['category' => $category ]);
    }

    public function update(Request $request) {
        $id = decrypt($request->id);

        $request->validate([
            'name_category' => 'required'
        ]);

        $category = Category::find($id);

        $category->name_category = $request->name_category;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category successfully updated!');
    }

    public function delete(Request $request) {
        $id = decrypt($request->id);
        $category = Category::firstWhere('id', $id);

        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category successfully deleted!');
    }
}
