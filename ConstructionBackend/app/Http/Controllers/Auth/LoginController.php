<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function credentials(Request $request)
    {
        $login = $request->input('login'); 
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

       
        return [
            $field => $login,
            'password' => $request->input('password'),
            'active_status' => 'active', 
        ];
    }

    
    public function username()
    {
        return 'login'; 
    }

    // Phương thức đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
