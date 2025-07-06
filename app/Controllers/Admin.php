<?php

namespace App\Controllers;

use App\Controllers\Auth;

class Admin extends BaseController
{
    public function dashboard()
    {
        // Check if user is logged in and is admin
        if (!Auth::isAuthenticated()) {
            return redirect()->to('/login');
        }

        $user = Auth::getCurrentUser();
        
        // Check if user is admin
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        
        return view('admin/dashboard', [
            'user' => $user
        ]);
    }

    public function patients()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/patients', ['user' => $user]);
    }

    public function appointments()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/appointments', ['user' => $user]);
    }

    public function services()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/services', ['user' => $user]);
    }

    public function waitlist()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/waitlist', ['user' => $user]);
    }

    public function procedures()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/procedures', ['user' => $user]);
    }

    public function records()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/records', ['user' => $user]);
    }

    public function invoice()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/invoice', ['user' => $user]);
    }

    public function rolePermission()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/role_permission', ['user' => $user]);
    }

    public function branches()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/branches', ['user' => $user]);
    }

    public function settings()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/settings', ['user' => $user]);
    }
} 