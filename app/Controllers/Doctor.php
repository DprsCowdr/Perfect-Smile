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
        public function patients()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'doctor') {
            return redirect()->to('/dashboard');
        }
        $userModel = new \App\Models\UserModel();
        $patients = $userModel->where('user_type', 'patient')->findAll();
        return view('doctor/patients', [
            'user' => $user,
            'patients' => $patients,
        ]);
    }
     public function appointments()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'doctor') {
            return redirect()->to('/dashboard');
        }
        
        // Use your AppointmentModel
        $appointmentModel = new \App\Models\AppointmentModel();
        $appointments = $appointmentModel->getAppointmentsWithDetails(); // This gets appointments with patient names
        
        // Get patients and branches for the form
        $userModel = new \App\Models\UserModel();
        $branchModel = new \App\Models\BranchModel();
        
        $patients = $userModel->where('user_type', 'patient')->findAll();
        $branches = $branchModel->findAll();
        
        return view('doctor/appointments', [
            'user' => $user,
            'appointments' => $appointments,
            'patients' => $patients,
            'branches' => $branches
        ]);
    }

    public function createAppointment()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'doctor') {
            return redirect()->to('/dashboard');
        }

        $appointmentModel = new \App\Models\AppointmentModel();
        
        $data = [
            'branch_id' => $this->request->getPost('branch'),
            'user_id' => $this->request->getPost('patient'),
            'appointment_date' => $this->request->getPost('date'),
            'appointment_time' => $this->request->getPost('time'),
            'status' => 'scheduled',
            'remarks' => $this->request->getPost('remarks')
        ];

        // Debug logging
        log_message('info', 'Creating appointment with data: ' . json_encode($data));

        // Validate required fields
        if (empty($data['user_id']) || empty($data['appointment_date']) || empty($data['appointment_time'])) {
            log_message('error', 'Missing required fields for appointment creation');
            session()->setFlashdata('error', 'Required fields missing');
            return redirect()->back();
        }

        // Validate that patient exists
        $userModel = new \App\Models\UserModel();
        $patient = $userModel->find($data['user_id']);
        if (!$patient) {
            log_message('error', 'Patient not found: ' . $data['user_id']);
            session()->setFlashdata('error', 'Selected patient not found');
            return redirect()->back();
        }

        // Validate branch if provided
        if (!empty($data['branch_id'])) {
            $branchModel = new \App\Models\BranchModel();
            $branch = $branchModel->find($data['branch_id']);
            if (!$branch) {
                log_message('error', 'Branch not found: ' . $data['branch_id']);
                session()->setFlashdata('error', 'Selected branch not found');
                return redirect()->back();
            }
        }

        try {
            // Insert appointment
            $insertId = $appointmentModel->insert($data);
            
            if ($insertId === false) {
                // Get validation errors
                $errors = $appointmentModel->errors();
                log_message('error', 'Appointment model validation errors: ' . json_encode($errors));
                
                session()->setFlashdata('error', 'Failed to create appointment: ' . implode(', ', $errors));
                return redirect()->back();
            }

            log_message('info', 'Appointment created successfully with ID: ' . $insertId);
            
            // Verify the appointment was saved
            $savedAppointment = $appointmentModel->find($insertId);
            log_message('info', 'Saved appointment data: ' . json_encode($savedAppointment));
            
            session()->setFlashdata('success', 'Appointment created successfully');
            return redirect()->to('/doctor/appointments');
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to create appointment: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            session()->setFlashdata('error', 'Failed to create appointment: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function updateAppointment($id)
    {
        $appointmentModel = new \App\Models\AppointmentModel();
        
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT') {
            // Get original appointment for comparison
            $originalAppointment = $appointmentModel->find($id);
            
            if (!$originalAppointment) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Appointment not found']);
                }
                session()->setFlashdata('error', 'Appointment not found');
                return redirect()->to(base_url('doctor/appointments'));
            }
            
            // Debug: Log what we're receiving
            log_message('info', 'POST data received: ' . json_encode($this->request->getPost()));
            
            // Get current user
            $currentUser = Auth::getCurrentUser();
            
            // SIMPLIFIED APPROACH - Just get the POST data directly
            $data = [
                'appointment_date' => $this->request->getPost('date'),
                'appointment_time' => $this->request->getPost('time'),
                'status' => $this->request->getPost('status'),
                'remarks' => $this->request->getPost('remarks'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Add admin-only fields
            if ($currentUser['user_type'] === 'admin') {
                $data['user_id'] = $this->request->getPost('patient');
                $data['branch_id'] = $this->request->getPost('branch');
            }
            
            // Remove completely null values (but allow empty strings for remarks)
            $updateData = [];
            foreach ($data as $key => $value) {
                if ($value !== null) {
                    $updateData[$key] = $value;
                }
            }
            
            // Debug: Log what we're updating
            log_message('info', 'Updating appointment ID: ' . $id . ' with data: ' . json_encode($updateData));
            log_message('info', 'Original appointment: ' . json_encode($originalAppointment));
            
            // Perform the update
            if ($appointmentModel->update($id, $updateData)) {
                // Get updated appointment with all related data
                $updatedAppointment = $appointmentModel->select('appointments.*, users.name as patient_name, branches.name as branch_name')
                    ->join('users', 'users.id = appointments.user_id', 'left')
                    ->join('branches', 'branches.id = appointments.branch_id', 'left')
                    ->where('appointments.id', $id)
                    ->first();
                
                log_message('info', 'Appointment updated successfully. New data: ' . json_encode($updatedAppointment));
                
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true, 
                        'message' => 'Appointment updated successfully',
                        'original' => $originalAppointment,
                        'updated' => $updatedAppointment
                    ]);
                }
                session()->setFlashdata('success', 'Appointment updated successfully');
            } else {
                // Log failure
                $errors = $appointmentModel->errors();
                log_message('error', 'Failed to update appointment ID: ' . $id . ' Errors: ' . json_encode($errors));
                
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => 'Failed to update appointment: ' . implode(', ', $errors)
                    ]);
                }
                session()->setFlashdata('error', 'Failed to update appointment: ' . implode(', ', $errors));
            }
        }
        
        return redirect()->to(base_url('doctor/appointments'));
    }

    public function deleteAppointment($id)
    {
        $appointmentModel = new \App\Models\AppointmentModel();
        
        if ($appointmentModel->delete($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Appointment deleted successfully']);
            }
            session()->setFlashdata('success', 'Appointment deleted successfully');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete appointment']);
            }
            session()->setFlashdata('error', 'Failed to delete appointment');
        }
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        return redirect()->to(base_url('doctor/appointments'));
    }

    public function procedures()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'doctor') {
            return redirect()->to('/dashboard');
        }
        return view('doctor/procedures', ['user' => $user]);
    }

    public function records()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'doctor') {
            return redirect()->to('/dashboard');
        }
        return view('doctor/records', ['user' => $user]);
    }
    
    public function getPatientAppointments($patientId)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'doctor') {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        log_message('info', 'Doctor controller - Getting appointments for patient ID: ' . $patientId);
        
        $appointmentModel = new \App\Models\AppointmentModel();
        
        try {
            $appointments = $appointmentModel->getPatientAppointments($patientId);
            
            log_message('info', 'Doctor controller - Found appointments: ' . json_encode($appointments));
            
            return $this->response->setJSON([
                'success' => true,
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Doctor controller - Error getting appointments: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to load appointments: ' . $e->getMessage()]);
        }
    }

    // Add this method to your Doctor.php controller
    public function setAvailability()
    {
        if ($this->request->getMethod() === 'POST') {
            $availabilityModel = new \App\Models\DoctorAvailabilityModel();
            
            // Prepare data - NO doctor_id needed
            $data = [
                'availability_date' => $this->request->getPost('date'),
                'status' => $this->request->getPost('status'),
                'start_time' => $this->request->getPost('start_time'),
                'notes' => $this->request->getPost('notes')
            ];
            
            // Remove empty values except for notes
            $data = array_filter($data, function($value, $key) {
                if ($key === 'notes') return true; // Keep notes even if empty
                return $value !== null && $value !== '';
            }, ARRAY_FILTER_USE_BOTH);
            
            // Check if availability already exists for this date
            $existingAvailability = $availabilityModel->where('availability_date', $data['availability_date'])->first();
            
            if ($existingAvailability) {
                // Update existing availability
                if ($availabilityModel->update($existingAvailability['id'], $data)) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Availability updated successfully'
                        ]);
                    }
                    session()->setFlashdata('success', 'Availability updated successfully');
                } else {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Failed to update availability'
                        ]);
                    }
                    session()->setFlashdata('error', 'Failed to update availability');
                }
            } else {
                // Create new availability
                if ($availabilityModel->insert($data)) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Availability set successfully'
                        ]);
                    }
                    session()->setFlashdata('success', 'Availability set successfully');
                } else {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Failed to set availability'
                        ]);
                    }
                    session()->setFlashdata('error', 'Failed to set availability');
                }
            }
        }
        
        return redirect()->to(base_url('doctor/appointments'));
    }
}