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
    $routes->get('patients/add', 'Admin::addPatient');
    $routes->post('patients/store', 'Admin::storePatient');
    $routes->get('patients/toggle-status/(:num)', 'Admin::toggleStatus/$1');
    $routes->get('patients/get/(:num)', 'Admin::getPatient/$1');
    $routes->post('patients/update/(:num)', 'Admin::updatePatient/$1');
    $routes->get('appointments', 'Admin::appointments');
    $routes->get('services', 'Admin::services');
    $routes->get('waitlist', 'Admin::waitlist');
    $routes->get('procedures', 'Admin::procedures');
    $routes->get('records', 'Admin::records');
    $routes->get('invoice', 'Admin::invoice');
    $routes->get('role-permission', 'Admin::rolePermission');
    $routes->get('branches', 'Admin::branches');
    $routes->get('settings', 'Admin::settings');
    $routes->get('patients/create-account/(:num)', 'Admin::createAccount/$1');
    $routes->post('patients/save-account/(:num)', 'Admin::saveAccount/$1');
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
    $routes->get('patients', 'Staff::patients');
    $routes->get('patients/add', 'Staff::addPatient');
    $routes->post('patients/store', 'Staff::storePatient');
    $routes->get('patients/toggle-status/(:num)', 'Staff::toggleStatus/$1');
    $routes->get('patients/get/(:num)', 'Staff::getPatient/$1');
    $routes->post('patients/update/(:num)', 'Staff::updatePatient/$1');
});