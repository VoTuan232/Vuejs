<?php

namespace App\Http\Controllers\api\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    public function index() {
    	return Role::paginate(2);		
    }

    public function getAllRole() {
    	return Role::all();
    }

    public function store(Request $request) {
    	$this->validate($request, [
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255|unique:roles',
            'description' => 'sometimes',
        ]);

        $role = Role::create($request->only(['name', 'display_name', 'description']));

        return $role;
    }

    public function update(Request $request, Role $role) {
    	$this->validate($request, [
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'display_name' => 'required|string|max:255|unique:roles,display_name,'.$role->id,
            'description' => 'sometimes',
        ]);

        $role->update($request->only(['name', 'display_name', 'description']));

        return $role;
    }

    public function destroy(Role $role) {
    	$role->delete();
    }
}
