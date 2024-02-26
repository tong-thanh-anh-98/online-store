<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the admin dashboard view.
     *
     * This method is responsible for displaying the admin dashboard view. It returns the 'admin.dashboard' view, which is
     * used to render the dashboard for administrators.
     *
     * @return \Illuminate\Contracts\View\View The admin dashboard view.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Logout the admin user.
     *
     * This method is responsible for logging out the admin user. It calls the `logout` method on the 'admin' guard to
     * invalidate the user's session. After logout, it redirects to the 'admin.login' route.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response after logout.
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
