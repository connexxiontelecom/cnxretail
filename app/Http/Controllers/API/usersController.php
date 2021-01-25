<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class usersController extends Controller
{

    public function fetchAllUsers(Request $request)
    {

        $tenant_id = $request->tenant_id;
        // return response()->json(['data'=>$tenant_id], 200);

        if (is_null($tenant_id)) {
            return response()->json(['data' => "request error"], 400);
        } else {

            $users = User::where('users.tenant_id', $tenant_id)->get();
            if ($users != null && Count($users) > 0) {
                foreach ($users as $user) {
                    /* parse profile picture */
                    $user["avatar"] = url("/assets/images/avatars/thumbnails/" . $user["avatar"]);
                    //$user_id = $user['id'];
                }
                return response()->json(['data' => $users], 200);
            } else {
                return response()->json(['data' => "No data found"], 204);
            }

        }

    }

}
