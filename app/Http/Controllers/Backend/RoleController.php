<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function AllPermission(){
        $permissions = Permission::latest()->get();

        return view('backend.pages.permission.all_permission',compact('permissions'));
    }

    public function AddPermission(){
        return view('backend.pages.permission.add_permission');
    }

    public function StorePermission(Request $request){

        $permission = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification); 

    } 

    public function EditPermission($id){

        $permission = Permission::find($id);
        return view('backend.pages.permission.edit_permission',compact('permission'));

    }// End Method 


    public function UpdatePermission(Request $request){
        $per_id = $request->id;

        Permission::find($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification); 

    }


    public function DeletePermission($id){

        Permission::find($id)->delete();

        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }

    public function ImportPermissionPage() {
        return view('backend.pages.permission.import_permission');
    }

    public function ExportPermission() {
        return Excel::download(new PermissionExport, 'permissions.xlsx');
    }

    public function ImportPermission(Request $request) {
        Excel::import(new PermissionImport, $request->file('import_file'));
        
        $notification = array(
            'message' => 'Permission Imported Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }





    // ****************************************

    public function AllRole(){
        $roles = Role::latest()->get();

        return view('backend.pages.role.all_role',compact('roles'));
    }

    public function AddRole(){
        return view('backend.pages.role.add_role');
    }

    public function StoreRole(Request $request){

        $role = Role::create([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => 'Role Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.role')->with($notification); 

    } 

    public function EditRole($id){

        $role = Role::find($id);
        return view('backend.pages.role.edit_role',compact('role'));

    }// End Method 


    public function UpdateRole(Request $request){
        $per_id = $request->id;

        Role::find($per_id)->update([
            'name' => $request->name
        ]);

        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.role')->with($notification); 

    }


    public function DeleteRole($id){

        Role::find($id)->delete();

        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }


    // ****************************************

    public function AddRolePermission() {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();

        return view('backend.pages.roleSetup.add_role_permission',compact('roles','permissions','permission_groups'));
    }

    public function StoreRolePermission(Request $request){

        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        } // end foreach

        $notification = array(
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.role.permission')->with($notification);

    }

    public function AllRolePermission(){

        $roles = Role::all();
        return view('backend.pages.roleSetup.all_role_permission',compact('roles'));

    }

    public function AdminEditRoles($id){

        $role = Role::find($id);
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        return view('backend.pages.roleSetup.edit_role_permission',compact('role','permissions','permission_groups'));

    }

    public function AdminRoleUpdate(Request $request,$id){

        $role = Role::find($id);
        $permissions = $request->permission;

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        $notification = array(
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.role.permission')->with($notification); 

    }

    public function AdminDeleteRole($id){

        $role = Role::find($id);
        if (!is_null($role)) {
            $role->delete();
        }

        $notification = array(
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
