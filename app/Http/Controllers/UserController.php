<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
    $search = $request->query('search');
    $roleFilter = $request->query('role_filter');
    // Ambil data role untuk dropdown filter
    $listRoles = Role::all();

    $query = User::with('role');

    if ($search) {
        $users = User::where('name', 'like', '%' . $search . '%')->get();

        if ($users->isEmpty()) {
            return view('admin.user.index', [
                'users' => [],
                'message' => "There's no user named \"$search\".",
                'alertType' => 'error'
            ]);
        }

        return view('admin.user.index', [
            'users' => $users,
            'message' => "Search result for \"$search\".",
            'alertType' => 'info'

        ]);
    }

    if ($roleFilter) {
        $query->where('role_id', $roleFilter);
    }

    $users = $query->get();

    return view('admin.user.index', [
        'users' => $users,
        'listRoles' => $listRoles,
        'message' => null,
        'alertType' => null
    ]);
    }

    public function form() {
        $listRoles = Role::get();
        return view('admin.user.form', compact('listRoles'));
    }

    public function insert(Request $request) {
        // Validasi
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'phone'    => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'role_id'  => 'required|exists:role,id'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('user.index')->with('success', 'User successfully added!');
    }

    public function detail(Request $request) {
        $id = decrypt($request->id);
        $user = User::findOrFail($id);
        $listRoles = Role::get();

        return view('admin.user.detail', ['user' => $user, 'listRoles' => $listRoles ]);
    }

    public function update(Request $request) {
        $id = decrypt($request->id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|unique:users,password,' . $id,
            'phone'    => 'required|string',
            'password' => 'confirmed',
            'role_id'  => 'required|exists:role,id'
        ]);

        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('user.index')->with('success', 'User successfully updated!');
    }

    public function delete(Request $request) {
        $id = decrypt($request->id);
        $user = User::firstWhere('id', $id);

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User successfully deleted!');
    }
}
