<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('guest:employee')->except('logout');
    }

    // protected function attemptLogin(Request $request)
    // {
    //     $userAttempt = Auth::guard('web')->attempt(
    //         $this->credentials($request), $request->has('remember')
    //     );
    //     if(!$userAttempt){
    //         return Auth::guard('employee')->attempt(
    //             $this->credentials($request), $request->has('remember')
    //         );
    //     }
    //     return $userAttempt;
    // }

    public function showEmployeeLogin()
    {
        // return view('auth.login', ['url' => 'employee']);
        return view('auth.login', ['url' => 'employee']);
    }

    public function employeeLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ( Auth::guard('employee')->attempt(['email' => $request->email, 'password' => $request->password]) ) {
            return redirect()->intended('/dashboard');
        }

        return back()->withInput( $request->only('email') );
    }

    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'email'   => 'required|email',
    //         'password' => 'required|min:6'
    //     ]);

    //     if (Auth::guard('employee')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

    //         return redirect()->intended('/employees');
    //     }
    //     return back()->withInput($request->only('email', 'remember'));
    // }
}
