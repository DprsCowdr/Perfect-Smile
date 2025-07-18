<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorAvailabilityModel extends Model
{
    protected $table = 'doctor_availability';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'availability_date',
        'status',
        'start_time',
        'notes'
    ];

    // Dates - DISABLED timestamps
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'availability_date' => 'required|valid_date',
        'status' => 'required|in_list[available,unavailable]'
    ];

    protected $validationMessages = [
        'availability_date' => [
            'required' => 'Availability date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be either available or unavailable'
        ]
    ];

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
}