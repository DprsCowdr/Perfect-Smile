<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\ServiceModel;
use App\Models\BranchModel;

class Guest extends BaseController
{
    protected $appointmentModel;
    protected $serviceModel;
    protected $branchModel;

    public function __construct()
    {
        $this->appointmentModel = new \App\Models\AppointmentModel();
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->branchModel = new \App\Models\BranchModel();
    }

    /**
     * Display appointment booking form for guests
     */
    public function bookAppointment()
    {
        $data = [
            'services' => $this->serviceModel->findAll(),
            'branches' => $this->branchModel->findAll()
        ];

        return view('guest/book_appointment', $data);
    }

    /**
     * Handle guest appointment submission
     */
    public function submitAppointment()
    {
        // Validate input
        $rules = [
            'guest_name' => 'required|min_length[2]',
            'guest_email' => 'required|valid_email',
            'guest_phone' => 'required',
            'branch_id' => 'required|numeric',
            'appointment_date' => 'required|valid_date',
            'appointment_time' => 'required',
            'service_id' => 'required|numeric',
            'notes' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Create temporary guest user or store guest info
        $guestData = [
            'name' => $this->request->getPost('guest_name'),
            'email' => $this->request->getPost('guest_email'),
            'phone' => $this->request->getPost('guest_phone'),
            'user_type' => 'guest',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Save guest user first
        $userModel = new \App\Models\UserModel();
        $userId = $userModel->insert($guestData);

        // Create appointment
        $appointmentData = [
            'user_id' => $userId,
            'branch_id' => $this->request->getPost('branch_id'),
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'status' => 'pending',
            'remarks' => $this->request->getPost('notes'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $appointmentId = $this->appointmentModel->insert($appointmentData);

        // Link service to appointment
        if ($appointmentId) {
            $appointmentServiceModel = new \App\Models\AppointmentServiceModel();
            $appointmentServiceModel->insert([
                'appointment_id' => $appointmentId,
                'service_id' => $this->request->getPost('service_id')
            ]);
        }

        session()->setFlashdata('success', 'Appointment booked successfully! We will contact you soon to confirm.');
        return redirect()->to('/guest/book-appointment');
    }

    /**
     * Display available services
     */
    public function services()
    {
        $data = [
            'services' => $this->serviceModel->findAll()
        ];

        return view('guest/services', $data);
    }

    /**
     * Display branch locations
     */
    public function branches()
    {
        $data = [
            'branches' => $this->branchModel->findAll()
        ];

        return view('guest/branches', $data);
    }
} 