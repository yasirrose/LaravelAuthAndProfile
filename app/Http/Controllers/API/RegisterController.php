<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_name' => 'required|min:4|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg|dimensions:max_width=256,max_height=256',
            'c_password' => 'required|same:password',
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
        $randomid = mt_rand(100000,999999); 
        $input['password'] = bcrypt($input['password']);
        $filename =  time() . '.' . $request->avatar->getClientOriginalExtension();
        $request->avatar->move(public_path('users') . '/images/' , $filename);

        $user =  new User;
        $user->name      = $input['name'];
        $user->user_name = $input['user_name'];
        $user->email     = $input['email'];
        $user->password  = $input['password'];
        $user->avatar    = 'users/images/' . $filename;
        $user->pin       =  $randomid;
        $user->save();

        $success['name']  =  $user->name;
        $success['user_role'] =  'user';       

        $email_send_to = $input['email'];
        $dataArr = array(
            'pin' => $randomid,
        );

        $subject = 'Verify Account';

        Mail::send('emails.verification', $dataArr, function ($message) use ($email_send_to)
        {
            $message->to($email_send_to)->subject('Verification');
        });

        $response = [
            'success' => true,
            'data'    => $success,
            'message' => 'Email with A confirmation PIN is sent to your Email.',
        ];

        return response()->json($response, 200);
    } 

    public function send_invitation_link(Request $request){       
        $user_role = $request->user()->user_role;
        if($user_role == 'admin'){
             $validator = Validator::make($request->all(), [
                'email_send_to' => 'required',
            ]);
       
            if($validator->fails()){
                $response = [
                    'success' => false,
                    'message' => 'Validation Error',
                    'data'   =>  $validator->errors()
                ];

                return response()->json($response);        
            }

            $email_send_to = $request->email_send_to;
            $details = [
                'title' => 'Verification',
                'heading' => 'Please click on the link to register your account.',
                'text' =>  'Register',
            ];
            $subject = 'Invitation';


            Mail::send('emails.invitation', $details, function ($message) use ($email_send_to)
            {
                $message->to($email_send_to)->subject('Invitation');
            });

            $response = [
                'success' => true,
                'data'    => 'Email Sent',
                'message' => 'Email Sent Successfully.',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'success' => false,
                'message' => 'Unauthorised',
                'error'   =>  'Unauthorised'
            ];

            return response()->json($response);    
        }       
    }    

    public function activate(Request $request){

        $validator =  Validator::make($request->all(), [
            'email' => 'required|exists:users',
            'pin' => 'required'
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error',
                'data'   =>  $validator->errors()
            ];

            return response()->json($response);             
        }

        $pin = $request->pin;
        $user = User::where('email', $request->email)->first();

        if($user->is_active == 1){
            $response = [
                'success' => true,
                'data'    => 'active',
                'message' => 'Your account is already active.',
            ];
            return response()->json($response,200);      
        }

        if(isset($user->pin) && $pin == $user->pin){
            $user->is_active = 1;
            $user->pin = null;
            $user->save();

            $response = [
                'success' => true,
                'data'    => 'Activated',
                'message' => 'Your account has been activated! Please Login.',
            ];

            return response()->json($response, 200);
        }else{             
            $response = [
                'success' => false,
                'message' => 'Invalid Pin',
                'error'   =>  'The PIN you enetered is invalid.'
            ];

            return response()->json($response);      
        }
    }
}