<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    /**
     * User Profile Returns the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request){
        return response()->json($request->user(), 200);
    }

    /**
     * Update user profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_profile(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,'.$request->user()->id,
            'avatar' => 'dimensions:max_width=256,max_height=256',
            'name' => 'required',
            'user_name' => 'required|min:4|max:20',
            'c_password' => 'same:password',
        ]);

        if($validator->fails()){
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $user = User::where('id',$request->user()->id)->first();

        if($user){
            $user->name      = $request->name;
            $user->user_name = $request->user_name;
            $user->email     = $request->email;

            if($request->has("password")){
                $request->password = bcrypt($request->password);
                $user->password  = $request->password;
            }

            if($request->avatar){
                $filename =  time() . '.' . $request->avatar->getClientOriginalExtension();
                $request->avatar->move(public_path('users') . '/images/' , $filename);
                $user->avatar    = 'users/images/' . $filename;
            }

            if ($user->save()){
                return response()->json(["message" => "Your profile is updated!"], 200);
            }else{
                return response()->json(["message" => "Profile update failed!"], 400);
            }

        }else{
            return response()->json(["message" => "Unauthenticated."], 401);
        }
    }
}
