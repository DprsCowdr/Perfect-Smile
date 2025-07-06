<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'branch_id', 'user_id', 'appointment_date', 'appointment_time', 
        'status', 'remarks', 'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'branch_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
        'status' => 'required|in_list[pending,confirmed,cancelled,completed]'
    ];

    protected $validationMessages = [
        'branch_id' => [
            'required' => 'Branch is required',
            'numeric' => 'Invalid branch selection'
        ],
        'user_id' => [
            'required' => 'User is required',
            'numeric' => 'Invalid user'
        ],
        'appointment_date' => [
            'required' => 'Appointment date is required',
            'valid_date' => 'Please enter a valid date'
        ],
        'appointment_time' => [
            'required' => 'Appointment time is required'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Invalid status'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get appointments by user
     */
    public function getAppointmentsByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    /**
     * Get appointments by branch
     */
    public function getAppointmentsByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)->findAll();
    }

    /**
     * Get appointments by date
     */
    public function getAppointmentsByDate($date)
    {
        return $this->where('appointment_date', $date)->findAll();
    }

    /**
     * Get appointments with user and branch info
     */
    public function getAppointmentsWithDetails()
    {
        return $this->select('appointments.*, user.name as patient_name, user.email as patient_email, branches.name as branch_name')
                    ->join('user', 'user.id = appointments.user_id')
                    ->join('branches', 'branches.id = appointments.branch_id')
                    ->findAll();
    }
} 