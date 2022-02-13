<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{


    // CONSTRUCTOR
    public function __construct()
    {
        $this->middleware(['auth', 'role:super admin']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!Gate::allows('isGlobalAdmin')) {
        //     abort(401);
        // }

        // $role = Role::create(['name' => 'super admin']);
        // $role = Role::create(['name' => 'admin admin']);
        // $role = Role::create(['name' => 'ops']);
        // $role = Role::create(['name' => 'cnai']);
        // $role = Role::create(['name' => 'int']);
        // $role = Role::create(['name' => 'tech']);
        // $role = Role::create(['name' => 'crisis']);
        // $permission = Permission::create(['name' => 'create posting']);
        // $permission = Permission::create(['name' => 'edit posting']);
        // $permission = Permission::create(['name' => 'delete posting']);

        // $role = Role::where('name', 'admin')->first();
        // $permissions = Permission::whereIn('name', ['create posting', 'edit posting', 'delete posting'])->get();
        // return $role->syncPermissions($permissions);\\\\

        // auth()->user()->assignRole('admin');
        // auth()->user()->removeRole('admin');

        // return auth()->user()->getPermissionsViaRoles();
        // $role = Role::create(['name' => 'Super Admin']);
        $counter = 0;
        $roles = Role::with('permissions')->get();
        $role = Role::pluck('name');
        $users = User::with("roles")->whereHas("roles", function($q) use ($role){
            $q->whereIn("name", $role);
        })->get();

        return view('dashboard.settings', compact(['users', 'roles', 'counter']));
    }

    public function add_role(Request $request){
        
        $role = Role::create(['name' => $request->name]);
        
        $permissions = $request->permissions;
        $permissions = explode(',', $permissions);
        foreach ($permissions as $key => $value) {
            if(empty($value)){
                continue;
            }else{
                $permission = Permission::create(['name' => $value]);
                $role->givePermissionTo($permission);
            }
        }
    
        Alert::success('Role added successfully!', 'Success!')->autoclose(222500);
        return back();
    }

    public function get_permissions(Request $request){
        $role = Role::where('id', $request->role)->with('permissions')->first();
        return $role->permissions;
    }

    // public function update_privilage(Request $request, User $user){
    //     $user->update(['role' => $request->role]) ?
    //     Alert::success('Privilage updated successfully!', 'Success!')->autoclose(222500) : 
    //     Alert::error('Something went wrong, contact webmaster!', 'Error!')->autoclose(222500);
    //     return back();
    // }
    
    public function asign_privilage(Request $request){
        $user = User::where('service_number', $request->svc_no)->first();
        $user->assignRole($request->role);
        // $user->givePermissionTo($request->permissions);

        Alert::success('Role assigned successfully!', 'Success!')->autoclose(222500);
        return back();
    }
}
