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
        'user_id', 
        'branch_id', 
        'appointment_date', 
        'appointment_time', 
        'status', 
        'remarks'
    ];

    // Dates - DISABLE TIMESTAMPS
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
        'status' => 'permit_empty|in_list[scheduled,rescheduled,confirmed,completed,cancelled]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'Patient is required',
            'integer' => 'Invalid patient ID'
        ],
        'appointment_date' => [
            'required' => 'Appointment date is required',
            'valid_date' => 'Invalid date format'
        ],
        'appointment_time' => [
            'required' => 'Appointment time is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

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
                    ->join('branches', 'branches.id = appointments.branch_id', 'left')
                    ->orderBy('appointments.appointment_date', 'DESC')
                    ->orderBy('appointments.appointment_time', 'DESC')
                    ->findAll();
    }

    /**
     * Get patient appointments
     */
    public function getPatientAppointments($patientId)
    {
        log_message('info', 'Getting appointments for patient ID: ' . $patientId);
        
        $result = $this->select('appointments.*, branches.name as branch_name')
                    ->join('branches', 'branches.id = appointments.branch_id', 'left')
                    ->where('appointments.user_id', $patientId)
                    ->orderBy('appointments.appointment_date', 'DESC')
                    ->orderBy('appointments.appointment_time', 'DESC')
                    ->findAll();
        
        log_message('info', 'Found ' . count($result) . ' appointments for patient ' . $patientId);
        log_message('info', 'Appointments data: ' . json_encode($result));
        
        return $result;
    }
}