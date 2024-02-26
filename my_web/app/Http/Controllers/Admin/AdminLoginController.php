<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    /**
     * Display the admin login view.
     *
     * This method is responsible for displaying the admin login view. It returns the 'admin.login' view, which is used to
     * render the login form for administrators.
     *
     * @return \Illuminate\Contracts\View\View The admin login view.
     */
    public function index()
    {
        return view('admin.login');
    }

    /**
     * Authenticate the admin user.
     *
     * This method is responsible for authenticating the admin user based on the provided email and password. It uses the
     * 'admin' guard and attempts to authenticate the user. If the authentication is successful, it checks the role of the
     * admin user. If the role is 2 (assuming 2 represents an authorized role), it redirects to the 'admin.dashboard' route.
     * Otherwise, it logs out the admin user and redirects to the 'admin.login' route with an error message indicating that
     * the user is not authorized to access the admin panel. If the authentication fails, it redirects to the 'admin.login'
     * route with an error message indicating incorrect email/password. If there are validation errors in the request,
     * it redirects to the 'admin.login' route with the validation errors and the entered email pre-filled in the form.
     *
     * @param  \Illuminate\Http\Request  $request The authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response after authentication.
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                $admin = Auth::guard('admin')->user();
                if ($admin->role == 2 ) {
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'You are not authorized to access admin panel.');
                }
            } else {
                return redirect()->route('admin.login')->with('error', 'Email/Password is incorrect.');
            }
        } else {
            return redirect()->route('admin.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }
}
