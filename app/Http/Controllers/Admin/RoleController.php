<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master\Company;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Role::class);
        $roles = Role::orderBy('name')->with('permissions','users')->paginate(30);
        return view('admin.role.index',[
            'items'=>$roles
        ]);
    }
    public function show(Role $role){
        $this->authorize(__FUNCTION__,Role::class);
        $permissions = $role->permissions->groupBy('description');
        return view('admin.role.show',[
            'role'=>$role,
            'permissions'=>$permissions

        ]);
    }
    public function create(){
        $this->authorize(__FUNCTION__,Role::class);
        $permissions = Permission::orderBy('description')->get()->groupBy('description');
        return view('admin.role.create',[
            'permissions'=>$permissions
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,Role::class);
        $this->validate($request,$this->rules());
        $role = Role::create(['name'=>$request->input('name'),'company_id'=>Company::getCompanyId()]);
        $role->attachPermissions($request->input('permissions'));
        return redirect()->route('roles.index')->with('success',trans('roles.created'));
    }

    public function edit(Role $role){
        $this->authorize(__FUNCTION__,Role::class);
        $permissions = Permission::get()->groupBy('description');
        return view('admin.role.edit',[
            'role'=>$role,
            'rolePermissions'=>$role->permissions->pluck('id')->all(),
            'permissions'=>$permissions

        ]);
    }
    public function update(Request $request,Role $role){
        $this->authorize(__FUNCTION__,Role::class);
        $this->validate($request,$this->rules(true,$role));
        $role->update(['name'=>$request->input('name')]);
        $role->syncPermissions($request->input('permissions'));
        Cache::flush();
        return redirect()->route('roles.index')->with('success',trans('roles.updated'));
    }

    protected function rules($is_update = false,$role = null){
        $rules =  [
            'name'=>'required|unique:roles,name'
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>'required|unique:roles,name,'.$role->id,
            ]);
        }
        return $rules;
    }

}
