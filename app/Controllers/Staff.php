<?php

namespace App\Controllers;

use App\Controllers\Auth;

class Staff extends BaseController
{
    public function dashboard()
    {
        // Check if user is logged in and is staff
        if (!Auth::isAuthenticated()) {
            return redirect()->to('/login');
        }

        $user = Auth::getCurrentUser();
        
        // Check if user is staff
        if ($user['user_type'] !== 'staff') {
            return redirect()->to('/dashboard');
        }
        
        return view('staff/dashboard', [
            'user' => $user
        ]);
    }
} 