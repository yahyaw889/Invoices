<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show-roles')->only(['index', 'show']);
        $this->middleware('permission:create-role')->only(['create', 'store']);
        $this->middleware('permission:edit-role')->only(['edit', 'update']);
        $this->middleware('permission:delete-role')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('roles.index', [
            'roles' => Role::with('permissions')->orderBy('id', 'DESC')->paginate(30)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('roles.create', [
            'permissions' => Permission::all() , 'assignedPermissions' => [1 ,2 ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' =>'required|unique:roles,name',
            'permission' =>'required'
        ]);

        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::query()->whereIn('id', $request->permission)->get(['name'])->toArray();

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success' , 'تم إنشاء قواعد جديدة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $rolePermissions =  $role['permissions'];
        return view('roles.show' , compact('role' , 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
//        if($role->name=='Super Admin'){
//            abort(403, 'SUPER ADMIN ROLE CAN NOT BE EDITED');
//        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$role->id)
            ->pluck('permission_id')
            ->all();
        $permission = Permission::all();

        return view('roles.edit', compact('role' , 'rolePermissions' , 'permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
//        dd($request->name , $role->id);
        $request->validate([
            'name' => 'required|unique:roles,id,' . $role->id,
            'permission' =>'required'
        ]);
        try {

            if ($role->name != $request['name']) {
                $role->update(['name' => $request['name']]);
            }

            $permissions = Permission::query()->whereIn('id', $request['permission'])->get(['name'])->toArray();

            $role->syncPermissions($permissions);

            return redirect()->route('roles.index')->with( 'success' , 'تم تحديث الصلاحيات بنجاح');

        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث. حاول مرة أخرى.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if($role->name !='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }
//        if(auth()->user()->hasRole($role->name)){
//            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
//        }
        try {
            $role->syncPermissions([]); // delete all permissions first to avoid orphaned permissions in the pivot table
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'تم حذف الصلاحية بنجاح');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف. حاول مرة أخرى.');
        }
    }


}
