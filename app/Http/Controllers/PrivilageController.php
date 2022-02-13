<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Privilage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PrivilageController extends Controller
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
        abort_unless(auth()->user()->hasRole('super admin'), 401, 'You don\'t have clearance to access this page.' );

        $roles = Role::all();
        $permissions = Permission::all();
        // $users = User::with("roles")->whereHas("roles", function($q) use ($role){
        //     $q->whereIn("name", $role);
        // })->get();

        return view('dashboard.personnel.privilage', compact(['permissions', 'roles']));
    }

   
    public function new_permissions(Request $request)
    {
        $permission = collect(explode(',', $request->permissions));
        $permissions = $permission->filter(function($item){
            return $item != '';
        });

        try {
            foreach ($permissions as $key => $permission) {
                
                $permission = Permission::create(
                    ['name' => $permission]
                );

            }
            Alert::success('Permissions added successfully!', 'Success!')->autoclose(2500);
            return back();
        } catch (\Throwable $th) {
            Alert::error($th->getMessage(), 'Error!')->autoclose(2500);
            return back();
        }
        
    }

    
    public function new_roles(Request $request)
    {

        try {
            $role = Role::create(['name' => $request->name]);
            $role->givePermissionTo(explode(',', $request->permissions));
            Alert::success('Role added successfully!', 'Success!')->autoclose(2500);
            return back();
        } catch (\Throwable $th) {
            Alert::error($th->getMessage(), 'Error!')->autoclose(2500);
            return back();
        }
        
    }


    public function asign_privilage(Request $request, User $user){
        
        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);
        Alert::success('Privilage assigned successfully!', 'Success!')->autoclose(222500);
        return back();
        
    }
}
