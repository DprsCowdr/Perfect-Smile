<?php

namespace App\Controllers;

use App\Controllers\Auth;

class Doctor extends BaseController
{
    public function dashboard()
    {
        // Check if user is logged in and is doctor
        if (!Auth::isAuthenticated()) {
            return redirect()->to('/login');
        }

        $user = Auth::getCurrentUser();
        
        // Check if user is doctor
        if ($user['user_type'] !== 'doctor') {
            return redirect()->to('/dashboard');
        }
        
        return view('doctor/dashboard', [
            'user' => $user
        ]);
    }
} 