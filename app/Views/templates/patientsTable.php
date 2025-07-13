<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold" style="font-size:2.2rem; color:#7a5fc0; letter-spacing:-1px;">Lists of Patients</h1>
    <?php if (in_array($user['user_type'], ['admin', 'staff'])): ?>
        <button id="showAddPatientFormBtn" class="btn btn-primary" style="background: #c7aefc; border: none; border-radius: 12px; font-weight: 700; font-size:1.1rem; box-shadow: 0 2px 8px #c7aefc44; padding: 10px 28px;">+ Add New Patient</button>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: #d4edda; color: #155724;">
        <i class="fas fa-check-circle me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: #f8d7da; color: #721c24;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php 
        $errors = session()->getFlashdata('error');
        if (is_array($errors)) {
            foreach ($errors as $field => $error) {
                if (is_array($error)) {
                    foreach ($error as $err) {
                        echo esc($err) . '<br>';
                    }
                } else {
                    echo esc($error) . '<br>';
                }
            }
        } else {
            echo esc($errors);
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="table-responsive mb-5">
    <table class="table align-middle patients-table" style="background: #fff; border-radius: 22px; box-shadow: 0 4px 32px #e6e6f6; overflow: hidden;">
        <thead style="background: #fff;">
            <tr style="color: #a89ad7; font-weight: 800; font-size:1.08rem;">
                <th style="border: none; padding: 18px 18px 12px 32px;">Name</th>
                <th style="border: none; padding: 18px 12px 12px 12px;">ID</th>
                <th style="border: none; padding: 18px 12px 12px 12px;">Email</th>
                <th style="border: none; padding: 18px 12px 12px 12px;">Phone number</th>
                <th style="border: none; padding: 18px 12px 12px 12px;">Address</th>
                <th style="border: none; padding: 18px 12px 12px 12px;">Status</th>
                <th style="border: none; padding: 18px 18px 12px 12px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($patients)): ?>
                <?php foreach ($patients as $patient): ?>
                <tr style="background: #fff; border-bottom: 1.5px solid #f0eafd; transition: background 0.2s;">
                    <td style="min-width: 220px; padding: 18px 18px 18px 32px;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px; height:48px; border-radius:50%; background:#ede6fa; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.2rem; color:#a89ad7;">
                                <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:800; color:#3d2e6e; font-size:1.13rem;"> <?= esc($patient['name']) ?> </div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:700; color:#7a5fc0; padding: 18px 12px;"> <?= esc($patient['id']) ?> </td>
                    <td style="color:#5e5e7a; padding: 18px 12px;"> <?= esc($patient['email']) ?> </td>
                    <td style="color:#5e5e7a; padding: 18px 12px;"> <?= esc($patient['phone']) ?> </td>
                    <td style="color:#5e5e7a; min-width:180px; padding: 18px 12px;">
                        <?= esc($patient['address']) ?>
                    </td>
                    <td style="padding: 18px 12px;">
                        <?php 
                        $status = $patient['status'] ?? 'active';
                        $statusClass = $status === 'active' ? 'status-active' : 'status-inactive';
                        $statusText = ucfirst($status);
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </td>
                    <td style="padding: 18px 18px 18px 12px;">
                        <a href="#" title="View" style="margin-right:10px;" class="showViewPatientPanelBtn" data-patient='<?= json_encode([
                            "id" => $patient["id"],
                            "name" => $patient["name"],
                            "email" => $patient["email"],
                            "phone" => $patient["phone"],
                            "gender" => $patient["gender"],
                            "date_of_birth" => $patient["date_of_birth"],
                            "address" => $patient["address"],
                            "age" => $patient["age"],
                            "occupation" => $patient["occupation"],
                            "nationality" => $patient["nationality"]
                        ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'><i class="fas fa-eye" style="color:#7a5fc0; font-size:1.15rem;"></i></a>
                        <a href="#" title="Edit" class="showUpdatePatientPanelBtnTable" data-patient-id="<?= $patient['id'] ?>" style="margin-right:10px;"><i class="fas fa-edit" style="color:#7a5fc0; font-size:1.15rem;"></i></a>
                        <a href="#" title="Delete" style="margin-right:10px;"><i class="fas fa-trash" style="color:#e57373; font-size:1.15rem;"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center" style="padding: 32px 0; color:#b0a4d6; font-weight:600;">No patients found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Patient Slide-in Panel -->
<div id="addPatientPanel" class="slide-in-panel">
    <button class="close-btn" id="closeAddPatientPanel" aria-label="Close">&times;</button>
    <h5 class="mb-4" style="font-weight:700; color:#888; font-size:1.35rem;">Add New Patient</h5>
    <form action="<?= base_url($user['user_type'] . '/patients/store') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="row mb-4 gx-3 gy-3">
            <div class="col-12">
                <input type="text" class="form-control form-control-lg" name="name" placeholder="Full Name" required>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <input type="text" class="form-control form-control-lg" name="address" placeholder="Address" required>
            </div>
        </div>
        <div class="row mb-4 gx-3 gy-3">
            <div class="col-md-4">
                <div class="modern-date-picker">
                    <input type="text" class="form-control form-control-lg modern-date-input" name="date_of_birth" placeholder="Date of Birth" required readonly>
                    <i class="fas fa-calendar-alt" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a89ad7; pointer-events: none;"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="modern-select-wrapper">
                    <select class="form-control form-control-lg modern-select" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">👨 Male</option>
                        <option value="Female">👩 Female</option>
                        <option value="Other">⚧ Other</option>
                    </select>
                    <i class="fas fa-chevron-down" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a89ad7; pointer-events: none; transition: transform 0.3s ease;"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="modern-number-input">
                    <input type="number" class="form-control form-control-lg modern-number" name="age" placeholder="Age" min="0" max="150">
                    <i class="fas fa-birthday-cake" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a89ad7; pointer-events: none;"></i>
                </div>
            </div>
        </div>
        <div class="row mb-4 gx-3 gy-3">
            <div class="col-md-6">
                <input type="email" class="form-control form-control-lg" name="email" placeholder="Email Address" required>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-lg" name="phone" placeholder="Phone Number" required pattern="[0-9+\-() ]+">
            </div>
        </div>
        <div class="row mb-4 gx-3 gy-3">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-lg" name="occupation" placeholder="Occupation">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-lg" name="nationality" placeholder="Nationality">
            </div>
        </div>
        <button type="submit" class="btn" style="background:#c7aefc; color:#fff; font-weight:600; border-radius:12px; padding:16px 0; font-size:1.1rem; box-shadow:0 2px 8px #c7aefc44; width:100%; margin-top:18px;">Add Patient</button>
    </form>
</div>

<!-- View Patient Slide-in Panel -->
<div id="viewPatientPanel" class="slide-in-panel">
    <button class="close-btn" id="closeViewPatientPanel" aria-label="Close">&times;</button>
    <div style="padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; margin-bottom: 16px;">
        <h2 style="font-weight:700; font-size:1.15rem; color:#222; margin-bottom:0;">Patient Info</h2>
    </div>
    <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
            <button style="background:#f5f6fa; border:none; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-size:1rem;"><i class="fas fa-plus"></i></button>
            <div style="width:48px; height:48px; border-radius:50%; background:#e9e9f6; display:flex; align-items:center; justify-content:center; font-size:1.4rem; color:#b0b0c0;">
                <i class="fas fa-user"></i>
            </div>
            <button style="background:#f5f6fa; border:none; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-size:1rem;"><i class="fas fa-file-invoice-dollar"></i></button>
        </div>
        <div style="flex:1;">
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-weight:600; font-size:1rem; color:#222;" id="view-patient-name"></span>
                <i class="fas fa-pen showUpdatePatientPanelBtn" style="font-size:0.9rem; color:#b0b0c0; cursor:pointer;"></i>
            </div>
            <div style="color:#b0b0c0; font-size:0.92rem;" id="view-patient-email"></div>
            <div style="display:flex; gap:12px; margin-top:10px; align-items:center;">
                <div style="color:#888; font-size:0.95rem;"><i class="far fa-clipboard"></i> Treatments: <b>0</b></div>
                <div style="color:#888; font-size:0.95rem;"><i class="fas fa-coins"></i> Spent: <b>$0</b></div>
            </div>
        </div>
    </div>
    <div style="display:flex; gap:18px; margin-bottom:14px; flex-wrap:wrap;">
        <div style="flex:1; min-width:120px;">
            <div style="color:#b0b0c0; font-size:0.93rem;">Phone</div>
            <div style="font-weight:500; color:#222; font-size:0.98rem;" id="view-patient-phone"></div>
        </div>
        <div style="flex:1; min-width:120px;">
            <div style="color:#b0b0c0; font-size:0.93rem;">Gender</div>
            <div style="font-weight:500; color:#222; font-size:0.98rem;" id="view-patient-gender"></div>
        </div>
        <div style="flex:1; min-width:120px;">
            <div style="color:#b0b0c0; font-size:0.93rem;">Date of Birth</div>
            <div style="font-weight:500; color:#222; font-size:0.98rem;" id="view-patient-date-of-birth"></div>
        </div>
        <div style="flex:1; min-width:120px;">
            <div style="color:#b0b0c0; font-size:0.93rem;">Full Address</div>
            <div style="font-weight:500; color:#222; font-size:0.98rem;" id="view-patient-address"></div>
        </div>
    </div>
    <hr style="border-top:1px dashed #e0e0e0; margin: 10px 0 14px 0;" />
    <div style="background:#f7f8fa; border-radius:10px; padding:4px 0 0 0; margin-bottom:14px; display:flex; gap:0;">
        <button class="view-patient-tab-btn active" data-tab="treatments" style="flex:1; background:#fff; border:none; border-radius:8px 8px 0 0; font-weight:600; color:#222; padding:8px 0; box-shadow:none; font-size:0.98rem;"> <i class="fas fa-notes-medical me-2"></i> Treatments</button>
        <button class="view-patient-tab-btn" data-tab="appointments" style="flex:1; background:none; border:none; font-weight:600; color:#7a7a8c; padding:8px 0; font-size:0.98rem;"> <i class="far fa-calendar-alt me-2"></i> Appointments</button>
        <button class="view-patient-tab-btn" data-tab="bills" style="flex:1; background:none; border:none; font-weight:600; color:#7a7a8c; padding:8px 0; font-size:0.98rem;"> <i class="fas fa-file-invoice-dollar me-2"></i> Patient Bills</button>
    </div>
    <div id="view-patient-tab-content-treatments" class="view-patient-tab-content" style="background:#fff; border-radius:10px; min-height:90px; display:flex; align-items:center; justify-content:center; color:#b0b0c0; font-size:1.05rem;">
        <span>.............................................</span>
    </div>
    <div id="view-patient-tab-content-appointments" class="view-patient-tab-content" style="display:none; background:#fff; border-radius:10px; min-height:90px; align-items:center; justify-content:center; color:#b0b0c0; font-size:1.05rem;">
        <span>Appointments placeholder</span>
    </div>
    <div id="view-patient-tab-content-bills" class="view-patient-tab-content" style="display:none; background:#fff; border-radius:10px; min-height:90px; align-items:center; justify-content:center; color:#b0b0c0; font-size:1.05rem;">
        <span>Patient Bills placeholder</span>
    </div>
</div>

<!-- Update Patient Slide-in Panel -->
<div id="updatePatientPanel" class="slide-in-panel">
    <button class="close-btn" id="closeUpdatePatientPanel" aria-label="Close">&times;</button>
    <h2 style="font-weight:700; font-size:1.35rem; color:#222; margin-bottom:18px;">Update Patient</h2>
    <form class="update-patient-form" id="updatePatientForm" method="post" action="">
        <?= csrf_field() ?>
        <input type="hidden" id="update-patient-id" name="patient_id">
        
        <div class="avatar-upload">
            <label for="update-patient-photo">
                <div class="avatar-preview">
                    <i class="fas fa-camera"></i>
                </div>
            </label>
            <input type="file" id="update-patient-photo" style="display:none;">
            <div class="avatar-desc">Allowed *.jpeg, *.jpg, *.png, *.gif<br>max size of 3 Mb</div>
        </div>
        
        <label>Full Name</label>
        <input type="text" id="update-patient-name" name="name" placeholder="Full Name" required />
        
        <div class="form-row">
            <div>
                <label>Email Address</label>
                <input type="email" id="update-patient-email" name="email" placeholder="Email Address" required />
            </div>
            <div>
                <label>Phone Number</label>
                <div style="display:flex; align-items:center; gap:6px;">
                    <span style="font-size:1.2rem;">🇺🇬</span>
                    <input type="text" id="update-patient-phone" name="phone" placeholder="Phone Number" style="flex:1;" required />
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div>
                <label>Gender</label>
                <div class="modern-select-wrapper">
                    <select id="update-patient-gender" name="gender" class="modern-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male">👨 Male</option>
                        <option value="Female">👩 Female</option>
                        <option value="Other">⚧ Other</option>
                    </select>
                    <i class="fas fa-chevron-down" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a89ad7; pointer-events: none; transition: transform 0.3s ease;"></i>
                </div>
            </div>
            <div>
                <label>Address</label>
                <input type="text" id="update-patient-address" name="address" placeholder="Address" required />
            </div>
        </div>
        
        <div class="form-row">
            <div>
                <label>Date of Birth</label>
                <div class="modern-date-picker">
                    <input type="text" id="update-patient-date-of-birth" name="date_of_birth" class="modern-date-input" placeholder="Select Date" required />
                    <i class="fas fa-calendar-alt" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a89ad7; pointer-events: none;"></i>
                </div>
            </div>
            <div>
                <label>Age</label>
                <div class="modern-number-input">
                    <input type="number" id="update-patient-age" name="age" placeholder="Age" min="0" max="150" />
                    <i class="fas fa-birthday-cake" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a89ad7; pointer-events: none;"></i>
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div>
                <label>Occupation</label>
                <input type="text" id="update-patient-occupation" name="occupation" placeholder="Occupation" />
            </div>
            <div>
                <label>Nationality</label>
                <input type="text" id="update-patient-nationality" name="nationality" placeholder="Nationality" />
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary" style="margin-top:18px; width:160px; float:right; font-weight:600; font-size:1.08rem; border-radius:10px;">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modern date picker
    flatpickr(".modern-date-input", {
        dateFormat: "Y-m-d",
        allowInput: false,
        clickOpens: true,
        maxDate: new Date(),
        yearDropdown: true,
        monthDropdown: true,
        theme: "light",
        disableMobile: false,
        locale: {
            firstDayOfWeek: 1
        }
    });

    // Initialize date picker for update form
    flatpickr("#update-patient-date-of-birth", {
        dateFormat: "Y-m-d",
        allowInput: false,
        clickOpens: true,
        maxDate: new Date(),
        yearDropdown: true,
        monthDropdown: true,
        theme: "light",
        disableMobile: false,
        locale: {
            firstDayOfWeek: 1
        }
    });

    // Add Patient Panel
    var addBtn = document.getElementById('showAddPatientFormBtn');
    var addPanel = document.getElementById('addPatientPanel');
    var addCloseBtn = document.getElementById('closeAddPatientPanel');
    if (addBtn && addPanel && addCloseBtn) {
        addBtn.addEventListener('click', function() {
            addPanel.classList.add('active');
        });
        addCloseBtn.addEventListener('click', function() {
            addPanel.classList.remove('active');
        });
    }

    // View Patient Panel
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.showViewPatientPanelBtn');
        if (btn) {
            e.preventDefault();
            var viewPanel = document.getElementById('viewPatientPanel');
            if (viewPanel) viewPanel.classList.add('active');
            // Get patient data
            var patient = btn.getAttribute('data-patient');
            if (patient) {
                try {
                    var data = JSON.parse(patient);
                    document.getElementById('view-patient-name').textContent = data.name || '';
                    document.getElementById('view-patient-email').textContent = data.email || '';
                    document.getElementById('view-patient-phone').textContent = data.phone || '';
                    document.getElementById('view-patient-gender').textContent = data.gender || '';
                    document.getElementById('view-patient-date-of-birth').textContent = data.date_of_birth || '';
                    document.getElementById('view-patient-address').textContent = data.address || '';
                } catch (err) {
                    console.error('Error parsing patient data:', err);
                }
            }
        }
        if (e.target.closest('#closeViewPatientPanel')) {
            var viewPanel = document.getElementById('viewPatientPanel');
            if (viewPanel) viewPanel.classList.remove('active');
        }
    });

    // Update Patient Panel (table edit icon)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.showUpdatePatientPanelBtnTable')) {
            e.preventDefault();
            var updatePanel = document.getElementById('updatePatientPanel');
            if (updatePanel) updatePanel.classList.add('active');
            
            // Get patient ID and load data
            var patientId = e.target.closest('.showUpdatePatientPanelBtnTable').getAttribute('data-patient-id');
            if (patientId) {
                loadPatientData(patientId);
            }
        }
        if (e.target.closest('#closeUpdatePatientPanel')) {
            var updatePanel = document.getElementById('updatePatientPanel');
            if (updatePanel) updatePanel.classList.remove('active');
        }
    });
    
    // Function to load patient data for update
    function loadPatientData(patientId) {
        console.log('Loading patient data for ID:', patientId);
        var userType = '<?= $user['user_type'] ?>';
        
        // Set form action first
        document.getElementById('updatePatientForm').action = '<?= base_url() ?>' + userType + '/patients/update/' + patientId;
        console.log('Form action set to:', document.getElementById('updatePatientForm').action);
        
        fetch('<?= base_url() ?>' + userType + '/patients/get/' + patientId)
            .then(response => response.json())
            .then(data => {
                console.log('Patient data received:', data);
                if (data.error) {
                    console.error('Error loading patient data:', data.error);
                    return;
                }
                
                // Fill the form fields
                document.getElementById('update-patient-id').value = data.id;
                document.getElementById('update-patient-name').value = data.name || '';
                document.getElementById('update-patient-email').value = data.email || '';
                document.getElementById('update-patient-phone').value = data.phone || '';
                document.getElementById('update-patient-address').value = data.address || '';
                document.getElementById('update-patient-gender').value = data.gender || '';
                document.getElementById('update-patient-date-of-birth').value = data.date_of_birth || '';
                document.getElementById('update-patient-age').value = data.age || '';
                document.getElementById('update-patient-occupation').value = data.occupation || '';
                document.getElementById('update-patient-nationality').value = data.nationality || '';
                
                console.log('Form fields populated');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    // Handle update form submission
    document.getElementById('updatePatientForm').addEventListener('submit', function(e) {
        console.log('Form submitted!');
        console.log('Form action:', this.action);
        console.log('Form method:', this.method);
        
        // Log all form fields
        var formData = new FormData(this);
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Let the form submit normally - no need to prevent default
        // The form will post to the correct URL and handle the redirect
    });
    
    // Update Patient Panel (pen in view panel)
    var showUpdateBtnPen = document.querySelector('.showUpdatePatientPanelBtn');
    if (showUpdateBtnPen) {
        showUpdateBtnPen.addEventListener('click', function(e) {
            e.preventDefault();
            var updatePanel = document.getElementById('updatePatientPanel');
            if (updatePanel) updatePanel.classList.add('active');
            
            // Get patient data from view panel
            var patientName = document.getElementById('view-patient-name').textContent;
            var patientEmail = document.getElementById('view-patient-email').textContent;
            var patientPhone = document.getElementById('view-patient-phone').textContent;
            var patientGender = document.getElementById('view-patient-gender').textContent;
            var patientDateOfBirth = document.getElementById('view-patient-date-of-birth').textContent;
            var patientAddress = document.getElementById('view-patient-address').textContent;
            
            // Fill the form fields (we'll need to get the full data via AJAX)
            // For now, we'll use the data from the view panel
            document.getElementById('update-patient-name').value = patientName;
            document.getElementById('update-patient-email').value = patientEmail;
            document.getElementById('update-patient-phone').value = patientPhone;
            document.getElementById('update-patient-gender').value = patientGender;
            document.getElementById('update-patient-date-of-birth').value = patientDateOfBirth;
            document.getElementById('update-patient-address').value = patientAddress;
        });
    }

    // Tab switching for View Patient panel
    var tabBtns = document.querySelectorAll('.view-patient-tab-btn');
    var tabContents = document.querySelectorAll('.view-patient-tab-content');
    tabBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tab = btn.getAttribute('data-tab');
            // Remove active from all
            tabBtns.forEach(function(b) { b.classList.remove('active'); b.style.background = 'none'; b.style.color = '#7a7a8c'; });
            tabContents.forEach(function(c) { c.style.display = 'none'; });
            // Set active
            btn.classList.add('active');
            btn.style.background = '#fff';
            btn.style.color = '#222';
            var content = document.getElementById('view-patient-tab-content-' + tab);
            if (content) content.style.display = 'flex';
        });
    });
});
</script> 