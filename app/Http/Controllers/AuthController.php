<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Authentication methods will be implemented in Subphase 1.4
    public function showLoginForm()
    {
        return view('auth.login');
    }
}
