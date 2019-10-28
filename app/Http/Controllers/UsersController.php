<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Country;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function userLoginRegister(){
        
        return view('users.login_register');
    }

    public function register(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $usersCount = User::where('email', $data['email'])->count();
            if($usersCount > 0){
                return redirect()->back()->with('flash_message_error', 'Email already exists');
            }else{
                // echo "success"; die;
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->save();
                if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
                    Session::put('frontSession', $data['email']);
                    return redirect('/cart');
                }
            }
        }
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
                Session::put('frontSession', $data['email']);
                return redirect('/cart');
            }else{
                return redirect()->back()->with('flash_message_error', 'Username or Password invalid !');
            }
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('frontSession');
        return redirect('/');
    }

    public function account(Request $request){
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        if($request->isMethod('post')){
            $data = $request->all();

            if(empty($data['name'])){
                return redirect()->back()->with('flash_message_error', 'Please enter some Valid infos !');
            }
            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->adress = $data['adresse'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->Pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();

            return redirect()->back()->with('flash_message_success', 'Account updated successfully !');
        }

        // return $user->Pincode;
        return view('users.account')->with(compact('countries','userDetails'));
    }

    public function chkUserPassword(Request $request){
        dd("hi");
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $user_id = Auth::User()->id;
        $check_password = User::where('id', $user_id)->first();
        if (Hash::check($current_password, $check_password->password)) {
            echo "true", die;
        } else {
            echo "false", die;
        }

    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $check_password = User::where('id', Auth::User()->id)->first();
            $current_password = $data['current_pwd'];
            if(Hash::check($current_password, $check_password->password)){
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id', Auth::User()->id)->update(['password'=>$new_pwd]);
                return redirect()->back()->with('flash_message_success', 'Password successfully updated');
            }else{
                return redirect()->back()->with('flash_message_error', 'Password incorrect');
            }
        }

    }

    public function checkEmail(Request $request){
        $data = $request->all();
        $usersCount = User::where('email', $data['email'])->count();
        if($usersCount > 0){
            echo "false";
        }else{
            echo "true"; die;
        }
    }
}
