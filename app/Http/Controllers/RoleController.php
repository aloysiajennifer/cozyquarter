<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
     public function index(Request $request){
    $search = $request->query('search');

    if ($search) {
        $roles = Role::where('type', 'like', '%' . $search . '%')->get();

        if ($roles->isEmpty()) {
            return view('admin.role.index', [
                'roles' => [],
                'message' => "There's no category named \"$search\".",
                'alertType' => 'error'
            ]);
        }

        return view('admin.role.index', [
            'roles' => $roles,
            'message' => "Search result for \"$search\".",
            'alertType' => 'info'

        ]);
    }

    $roles = Role::get();
    return view('admin.role.index', [
        'roles' => $roles,
        'message' => null,
        'alertType' => null
    ]);
    }

    public function form() {
        return view('admin.role.form');
    }

    public function insert(Request $request) {
        // Validasi
        $request->validate([
            'type' => 'required'
        ]);

        $role = new Role;

        $role->type = $request->type;
        $role->save();

        return redirect()->route('role.index')->with('success', 'Role successfully added!');
    }

    public function detail(Request $request) {
        $id = decrypt($request->id);
        $role = Role::findOrFail($id);

        return view('admin.role.detail', ['role' => $role ]);
    }

    public function update(Request $request) {
        $id = decrypt($request->id);

        $request->validate([
            'type' => 'required'
        ]);

        $role = Role::find($id);

        $role->type = $request->type;
        $role->save();

        return redirect()->route('role.index')->with('success', 'Role successfully updated!');
    }

    public function delete(Request $request) {
        $id = decrypt($request->id);
        $role = Role::firstWhere('id', $id);

        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role successfully deleted!');
    }
}
