<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/usuarios';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        if( \Auth::user() ){
            if(\Auth::user()->estado == 1)
            {
                if(\Auth::user()->id_rol == 1)
                {
                    return '/usuarios';        
                }
                if(\Auth::user()->id_rol == 2)
                {
                    return '/archivos';
                }
                if(\Auth::user()->id_rol == 3)
                {
                    return '/ver_archivos';
                }
            } 
            else 
            {
                \Auth::logout();
                return '/';
            }
        }
        else{
            return redirect('/');
        }   
    }
    
}
