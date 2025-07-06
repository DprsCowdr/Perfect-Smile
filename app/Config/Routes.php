<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', to: 'Home::index');

// Guest routes (no authentication required)
$routes->get('guest/book-appointment', 'Guest::bookAppointment');
$routes->post('guest/book-appointment', 'Guest::submitAppointment');
$routes->get('guest/services', 'Guest::services');
$routes->get('guest/branches', 'Guest::branches');

// Authentication routes
$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/registerUser', 'Auth::registerUser');
$routes->get('auth/logout', 'Auth::logout');

// Dashboard routes
// $routes->get('dashboard', 'Dashboard::index');

// Admin routes (protected)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('patients', 'Admin::patients');
    $routes->get('appointments', 'Admin::appointments');
    $routes->get('services', 'Admin::services');
    $routes->get('waitlist', 'Admin::waitlist');
    $routes->get('procedures', 'Admin::procedures');
    $routes->get('records', 'Admin::records');
    $routes->get('invoice', 'Admin::invoice');
    $routes->get('role-permission', 'Admin::rolePermission');
    $routes->get('branches', 'Admin::branches');
    $routes->get('settings', 'Admin::settings');
});

// Doctor routes (protected)
$routes->group('doctor', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Doctor::dashboard');
});

// Patient routes (protected)
$routes->group('patient', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Patient::dashboard');
});

// Staff routes (protected)
$routes->group('staff', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Staff::dashboard');
});