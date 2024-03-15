<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Display the login page.
     *
     * @return \Illuminate\Contracts\View\View The view for the login page.
     */
    public function login()
    {
        return view('front.account.login');
    }

    /**
     * Display the registration page.
     *
     * @return \Illuminate\Contracts\View\View The view for the registration page.
     */
    public function register()
    {
        return view('front.account.register');
    }

    /**
     * Process the registration form submission.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the status and message/errors.
     */
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'You have been registerd successfully.');
            return response()->json([
                'status' => true,
                'message' => 'You have been registerd successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Authenticate the user based on the provided credentials.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response to the intended URL or the account profile page.
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))){
                if (!session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->withInput($request->only('email'))->with('error','Email/Password is incorrect.');
            }
        } else {
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    /**
     * Display the user profile page.
     *
     * @return \Illuminate\Contracts\View\View The view for the user profile page.
     */
    public function profile()
    {
        return view('front.account.profile');
    }

    /**
     * Log out the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response to the login page with a success message.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success','Your successfully logged out.');
    }
}
