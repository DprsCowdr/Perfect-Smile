<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message', [
            'title' => 'Perfect Smile - Dental Clinic Management System',
            'guestBookingUrl' => base_url('guest/book-appointment')
        ]);
    }
}
