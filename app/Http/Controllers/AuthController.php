<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'login', 'register', 'setRegister', 'passwordReset']]);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function setRegister(Request $request)
    {
        $response['error']['status'] = false;

        $validator = Validator::make(
            $request->only([
                'name', 'email', 'password', 'password_confirmation'
            ]),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required', 'min:4', 'confirmed',

            ]
        );

        if (!$validator->fails()) {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);

            $user->save();
            Auth::login($user);
        } else {
            $response["error"]['status']  = true;
            $response["error"]['messeger']  = $validator->getMessageBag();
        }

        $request->session()->flash('response', $response);
        return redirect()->route('register')->withErrors($validator);
    }

    public function passwordReset()
    {
        return view('auth.passwords.reset', ['token' => Auth()->user()->password]);
    }

    public function setPasswordReset(Request $request)
    {

        //reset password from email and password
        $user = User::where('email', $request->email)
            ->where('password', $request->token)->first();

        if ($user) {
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);
            $user->save();
        }

        Auth::logout();
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $creds = $request->only('email', 'password');

        $validator = Validator::make(
            $request->only([
                'email', 'password'
            ]),
            [
                'email' => 'required',
                'password' => 'required'

            ]
        );

        $remember = $request->input('remember', false);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        if (Auth::attempt($creds, $remember)) {
            return redirect()->route('dashboard');
        } else {
            $validator->errors()->add('password', 'E-mail e/ou senha inválido');
            return redirect()->route('login')->withErrors($validator)->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
