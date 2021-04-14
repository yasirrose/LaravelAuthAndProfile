<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Validator;
   
class LoginController
{   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error',
                'data'   =>  $validator->errors()
            ];

            return response()->json($response,200);        
        }

        $url = url('/oauth/token');

        $response = Http::asForm()->post($url, [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);
        return $response->json();      
    }

    public function logout(Request $request){
        $token =  $request->user()->token();
        $token->revoke();

        $response = [
            'success' => true,
            'data'    => 'success',
            'message' => 'User Logged out successfully',
        ];

        return response()->json($response, 200);
    }
}