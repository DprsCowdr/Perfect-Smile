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
        $userModel = new \App\Models\UserModel();
        $patients = $userModel->where('user_type', 'patient')->findAll();
        return view('admin/patients', [
            'user' => $user,
            'patients' => $patients,
        ]);
    }

    public function addPatient()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        return view('admin/addPatient', ['user' => $user]);
    }

    public function storePatient()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $name = $this->request->getPost('name');

        // Server-side validation
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'name'          => 'required',
            'address'       => 'required',
            'date_of_birth' => 'required',
            'gender'        => 'required',
            'phone'         => 'required',
            'email'         => 'required|valid_email',
        ]);
        $formData = [
            'name'          => $name,
            'address'       => $this->request->getPost('address'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender'        => $this->request->getPost('gender'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email'),
        ];
        if (!$validation->run($formData)) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        $data = [
            'name'          => $name,
            'address'       => $this->request->getPost('address'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender'        => $this->request->getPost('gender'),
            'age'           => $this->request->getPost('age'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email'),
            'occupation'    => $this->request->getPost('occupation'),
            'nationality'   => $this->request->getPost('nationality'),
            'user_type'     => 'patient',
            'status'        => 'active',
        ];

        $userModel = new \App\Models\UserModel();
        $userModel->skipValidation(true)->insert($data);

        return redirect()->to('/admin/patients')->with('success', 'Patient added successfully.');
    }

    public function createAccount($id)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        $userModel = new \App\Models\UserModel();
        $patient = $userModel->find($id);
        if (!$patient || $patient['user_type'] !== 'patient') {
            return redirect()->to('/admin/patients')->with('error', 'Patient not found.');
        }
        return view('admin/createAccount', ['user' => $user, 'patient' => $patient]);
    }

    public function saveAccount($id)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        $userModel = new \App\Models\UserModel();
        $patient = $userModel->find($id);
        if (!$patient || $patient['user_type'] !== 'patient') {
            return redirect()->to('/admin/patients')->with('error', 'Patient not found.');
        }
        $password = $this->request->getPost('password');
        if (!$password || strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password must be at least 6 characters.');
        }
        $userModel->update($id, [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        return redirect()->to('/admin/patients')->with('success', 'Account created for patient.');
    }

    public function toggleStatus($id)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        
        $userModel = new \App\Models\UserModel();
        $patient = $userModel->find($id);
        
        if (!$patient || $patient['user_type'] !== 'patient') {
            return redirect()->to('/admin/patients')->with('error', 'Patient not found.');
        }
        
        // Toggle status between active and inactive
        $newStatus = ($patient['status'] === 'active') ? 'inactive' : 'active';
        
        $userModel->update($id, ['status' => $newStatus]);
        
        $statusText = ucfirst($newStatus);
        return redirect()->to('/admin/patients')->with('success', "Patient account status changed to {$statusText}.");
    }

    public function getPatient($id)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }
        
        $userModel = new \App\Models\UserModel();
        $patient = $userModel->find($id);
        
        if (!$patient || $patient['user_type'] !== 'patient') {
            return $this->response->setJSON(['error' => 'Patient not found']);
        }
        
        return $this->response->setJSON($patient);
    }

    public function updatePatient($id)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return redirect()->to('/dashboard');
        }
        
        $userModel = new \App\Models\UserModel();
        $patient = $userModel->find($id);
        
        if (!$patient || $patient['user_type'] !== 'patient') {
            return redirect()->to('/admin/patients')->with('error', 'Patient not found.');
        }
        
        // Debug: Log the incoming data
        log_message('info', 'Update Patient - ID: ' . $id);
        log_message('info', 'POST Data: ' . json_encode($this->request->getPost()));
        
        // Server-side validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'phone' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|valid_date',
        ]);
        
        $formData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'gender' => $this->request->getPost('gender'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
        ];
        
        if (!$validation->run($formData)) {
            log_message('error', 'Validation failed: ' . json_encode($validation->getErrors()));
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }
        
        $updateData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'gender' => $this->request->getPost('gender'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'age' => $this->request->getPost('age'),
            'occupation' => $this->request->getPost('occupation'),
            'nationality' => $this->request->getPost('nationality'),
        ];
        
        log_message('info', 'Update Data: ' . json_encode($updateData));
        
        // Skip validation for update since we're not changing password or user_type
        if ($userModel->skipValidation(true)->update($id, $updateData)) {
            log_message('info', 'Patient updated successfully');
            return redirect()->to('/admin/patients')->with('success', 'Patient updated successfully.');
        } else {
            log_message('error', 'Failed to update patient: ' . json_encode($userModel->errors()));
            return redirect()->back()->withInput()->with('error', 'Failed to update patient.');
        }
    }

    public function appointments()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
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
        
        return view('admin/appointments', [
            'user' => $user,
            'appointments' => $appointments,
            'patients' => $patients,
            'branches' => $branches
        ]);
    }

    public function createAppointment()
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
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

        // Validate required fields
        if (empty($data['user_id']) || empty($data['appointment_date']) || empty($data['appointment_time'])) {
            session()->setFlashdata('error', 'Required fields missing');
            return redirect()->back();
        }

        try {
            $appointmentModel->insert($data);
            session()->setFlashdata('success', 'Appointment created successfully');
            return redirect()->to('/admin/appointments');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to create appointment: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function updateAppointment($id)
    {
        $appointmentModel = new \App\Models\AppointmentModel();
        
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT') {
            $data = [
                'patient_id' => $this->request->getPost('patient'),
                'branch_id' => $this->request->getPost('branch'),
                'appointment_date' => $this->request->getPost('date'),
                'appointment_time' => $this->request->getPost('time'),
                'remarks' => $this->request->getPost('remarks'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // For AJAX requests
            if ($this->request->isAJAX()) {
                $jsonData = json_decode($this->request->getBody(), true);
                if ($jsonData) {
                    $data = array_merge($data, $jsonData);
                }
            }
            
            if ($appointmentModel->update($id, $data)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Appointment updated successfully']);
                }
                session()->setFlashdata('success', 'Appointment updated successfully');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to update appointment']);
                }
                session()->setFlashdata('error', 'Failed to update appointment');
            }
        }
        
        return redirect()->to(base_url('admin/appointments'));
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
        
        return redirect()->to(base_url('admin/appointments'));
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

    public function getPatientAppointments($patientId)
    {
        $user = Auth::getCurrentUser();
        if ($user['user_type'] !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $appointmentModel = new \App\Models\AppointmentModel();
        
        try {
            $appointments = $appointmentModel->getPatientAppointments($patientId);
            
            return $this->response->setJSON([
                'success' => true,
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to load appointments']);
        }
    }
}