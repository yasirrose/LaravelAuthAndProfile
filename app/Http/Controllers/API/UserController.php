<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class UserController
{
    /**
     * User Profile api
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request){

        $response = [
            'success' => true,
            'user' => $request->user(),
        ];

        return response()->json($response, 200);
    }

    public function update_profile(Request $request){

        $validator = Validator::make($request->all(), [
            'id'       => 'required|numeric',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'avatar' => 'dimensions:max_width=256,max_height=256',
            'name' => 'required',
            'user_name' => 'required|min:4|max:20',
            'c_password' => 'same:password',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error',
                'data'   =>  $validator->errors()
            ];

            return response()->json($response);        
        }


        $input = $request->all();

        $user = User::where('id',$request->id)->first();
        if(isset($user)){
            $user->name      = $input['name'];
            $user->user_name = $input['user_name'];
            $user->email     = $input['email'];             

            if(isset($input['password'])){
                $input['password'] = bcrypt($input['password']);
                $user->password  = $input['password'];   
            }

            if($request->avatar){
                $filename =  time() . '.' . $request->avatar->getClientOriginalExtension();
                $request->avatar->move(public_path('users') . '/images/' , $filename);
                $user->avatar    = 'users/images/' . $filename;
            }
            $user->save();

            $success['user'] =  $user;

            $response = [
                'success' => true,
                'data'    => 'Updated',
                'message' => 'Your profile is updated!.',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'success' => false,
                'message' => 'not found.',
                'data'   =>  'User does not exist!'
            ];

            return response()->json($response);   
        }       
    }    
}