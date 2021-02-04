<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\ActivityLog;
use Image;
use Auth;

class UserController extends Controller
{
   public function __construct(){
       $this->middleware('auth');
   }


   public function allUsers(){
       $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
       return view('users.all-users', ['users'=>$users]);
   }


   public function showAddUserForm(){
    return view('users.add-new-user');
   }

   public function storeNewUser(Request $request){
       $this->validate($request,[
           'full_name'=>'required',
           'email'=>'required|unique:users,email',
           'mobile_no'=>'required',
           'gender'=>'required',
           'address'=>'required'
       ]);
        $ref_no = substr(sha1(time()), 32,40);
       $user = new User;
       $user->tenant_id = Auth::user()->tenant_id;
       $user->slug = $ref_no;
       $user->full_name = $request->full_name;
       $user->email = $request->email;
       $user->mobile_no = $request->mobile_no;
       $user->address = $request->address;
       $user->gender = $request->gender;
       $user->marital_status = $request->marital_status;
       $user->password = bcrypt($ref_no);
       $user->save();
       return response()->json(['route'=>'users']);
   }


   public function roles(){
       $roles = Role::orderBy('name', 'ASC')->get();
       return view('users.roles', ['roles'=>$roles]);
   }


   public function storeRole(Request $request){
       $this->validate($request,[
           'role_name'=>'required'
       ]);

       $role = new Role;
       $role->name = $request->role_name;
       $role->save();
   }

   public function permissions(){
    $permissions = Permission::orderBy('name', 'ASC')->get();
    return view('users.permissions', ['permissions'=>$permissions]);
}

public function storePermission(Request $request){
    $this->validate($request,[
        'permission_name'=>'required'
    ]);

    $permission = new Permission;
    $permission->name = $request->permission_name;
    $permission->save();
}


public function assignPermissionToRole($id){
    $role = Role::find($id);
    $permissions = Permission::orderBy('name', 'ASC')->get();
    if(!empty($role)){
        return view('users.assign-permissions', ['role'=>$role, 'permissions'=>$permissions]);
    }else{
        session()->flash("error", "<strong>Ooops!</strong> Record not found.");
        return back();
    }
}


public function assignRolePermissions(Request $request){
    $this->validate($request,[
        'role'=>'required',
        'permission'=>'required'
    ]);

    $role = Role::find($request->role);
    $role->givePermissionTo($request->permission);

    if($role){
        session()->flash("success", "<strong>Success!</strong> Permission(s) assigned to ".$role->name);
        return back();
    }
}


public function activityLog(){
    $logs = ActivityLog::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
    return view('users.activity-log',['logs'=>$logs]);
}

public function myProfile(){
    return view('users.my-profile');
}

public function editProfile(){
    return view('users.edit-profile');
}

public function saveProfileChanges(Request $request){
    $request->validate([
        'email'=>'required',
        'full_name'=>'required',
        'gender'=>'required',
        'mobile_no'=>'required',
        'marital_status'=>'required',
        'address'=>'required'
    ]);

    $user = User::find(Auth::user()->id);
    $user->full_name = $request->full_name;
    $user->email = $request->email;
    $user->gender = $request->gender;
    $user->mobile_no = $request->mobile_no;
    $user->marital_status = $request->marital_status;
    $user->address = $request->address;
    $user->save();
    session()->flash("success", "<strong>Success!</strong> Changes to profile saved.");
    return redirect()->route('my-profile');
}


    /*
    * Upload avatar
    */
    public function uploadAvatar(Request $request){
        $this->validate($request,[
            'avatar'=>'required'
        ]);
        if($request->avatar){
    	    $file_name = time().'.'.explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
    	    \Image::make($request->avatar)->resize(300, 300)->save(public_path('assets/images/avatars/medium/').$file_name);
    	    \Image::make($request->avatar)->resize(150, 150)->save(public_path('assets/images/avatars/thumbnails/').$file_name);


    	}
        $user = User::find(Auth::user()->id);
        $user->avatar = $file_name;
        $user->save();
        return response()->json(['message'=>'Success! Profile picture set.']);
    }
}
