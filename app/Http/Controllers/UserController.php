<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show-users')->only(['index', 'show']);
        $this->middleware('permission:create-user')->only(['create', 'store']);
        $this->middleware('permission:edit-user')->only(['edit', 'update']);
        $this->middleware('permission:delete-user')->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::query()->latest('id')->with('roles')->paginate(30);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::query()->pluck('name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(User::validation());
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        try {

                DB::beginTransaction();
            $user = User::query()->create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'status' => $input['status'],
            ]);
//            dd($request->roles_name);
            foreach ($request['roles_name'] as $role){
                $user->assignRole($role);
            }

                DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'تم إنشاء المستخدم بنجاح');
        }catch (\Exception $e) {
            return redirect()->route('users.create')
                ->withErrors('error', 'خطأ في إنشاء المستخدم. يرجي إعادة المحاولة');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): RedirectResponse
    {
        return redirect()->route('users.show' , compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }
        $roles = Role::query()->pluck('name')->all();
        $userRoles = $user->roles->pluck('name')->all();

        return view('users.edit', compact('user' , 'roles' , 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user): RedirectResponse
    {
        $input = $request->all();
            $request->validate([
                'name'      =>'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users,email,'.$user->id,
               'status' => 'required|in:1,0',

            ]);

        if(!empty($request->password)){
            $request->validate(['password' => 'required|string|min:8|confirmed',]);
            $input['password']  = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }


        try {
                if(isset($input['password'])) {
                    $user->update([
                        'name' => $input['name'],
                        'email' => $input['email'],
                        'password' => $input['password'],
                        'status' => $input['status'],
                    ]);
                }else{
                    $user->update([
                        'name' => $input['name'],
                        'email' => $input['email'],
                        'status' => $input['status'],
                    ]);
                }
            $user->syncRoles($request['roles'] );

            return redirect('/users')->with('success' , 'تم تحديث المستخدم بنجاح');
        }catch(Exception $e) {
                return redirect()->back()->withErrors('error' , 'خطأ في تحديث المستخدم');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // About if user is Super Admin or User ID belongs to Auth User
        if (!$user->hasRole('Super Admin') || $user->id != auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        $user->syncRoles([]);
        $user->delete();
        return redirect()->route('users.index')
            ->with('success','تم  حذف المستخدم بنجاح');
    }
}
