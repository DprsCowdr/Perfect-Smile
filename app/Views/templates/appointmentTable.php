<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Calendar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('css/appointment-calendar.css') ?>">
</head>
<body class="bg-gradient-to-tr from-gray-100 to-purple-100 min-h-screen p-6 font-sans">

<?php
  $currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
  $currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
  if ($currentMonth < 1 || $currentMonth > 12) $currentMonth = date('n');
  if ($currentYear < 2020 || $currentYear > 2030) $currentYear = date('Y');

  $prevMonth = $currentMonth - 1;
  $prevYear = $currentYear;
  if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
  }

  $nextMonth = $currentMonth + 1;
  $nextYear = $currentYear;
  if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
  }

  $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];
  $daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
  $firstDay = date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
?>

<!-- Add this wrapper around your main content -->
<div class="main-content">
  <div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-4xl font-extrabold text-purple-700 tracking-tight">
        📅 Appointment Calendar
      </h1>
      <!-- Show different message based on user type -->
      <?php if ($user['user_type'] === 'admin'): ?>
        <div class="text-green-600 font-semibold">
          <!-- ✅ Full Access - You can create, edit, and delete appointments -->
        </div>
      <?php elseif ($user['user_type'] === 'doctor'): ?>
        <div class="text-blue-600 font-semibold">
          <!-- 🩺 Doctor Access - You can set your availability and view appointments -->
        </div>
      <?php else: ?>
        <div class="text-orange-600 font-semibold">
          <!-- 👁️ View Only - You can view appointments and doctor availability -->
        </div>
      <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-4 mb-6">   
      <button id="viewAppointmentsBtn">
        📋 View All Appointments
      </button>
      <button id="viewTodayBtn">
        📅 Today's Appointments
      </button>
      <button id="viewUpcomingBtn">
        ⏰ Upcoming Appointments
      </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-5">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-5">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <div class="calendar-container rounded-3xl shadow-xl bg-white overflow-hidden">
      <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <div class="flex gap-3 items-center">
          <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="bg-purple-100 hover:bg-purple-200 text-purple-800 px-4 py-2 rounded-lg">← Prev</a>

          <select id="monthDropdown" class="border rounded-lg px-3 py-2 text-purple-700 font-semibold" onchange="navigateToDate()">
            <?php for ($m = 1; $m <= 12; $m++): ?>
              <option value="<?= $m ?>" <?= $m === $currentMonth ? 'selected' : '' ?>>
                <?= $monthNames[$m] ?>
              </option>
            <?php endfor; ?>
          </select>

          <select id="yearDropdown" class="border rounded-lg px-3 py-2 text-purple-700 font-semibold" onchange="navigateToDate()">
            <?php for ($y = 2020; $y <= 2030; $y++): ?>
              <option value="<?= $y ?>" <?= $y === $currentYear ? 'selected' : '' ?>>
                <?= $y ?>
              </option>
            <?php endfor; ?>
          </select>

          <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="bg-purple-100 hover:bg-purple-200 text-purple-800 px-4 py-2 rounded-lg">Next →</a>
        </div>

        <h2 class="text-xl font-bold text-purple-700">
          <?= $monthNames[$currentMonth] ?> <?= $currentYear ?>
        </h2>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-7 gap-2 mb-3 text-center text-gray-500 font-semibold">
          <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day): ?>
            <div><?= $day ?></div>
          <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-7 gap-2">
          <?php for ($i = 0; $i < $firstDay; $i++) echo '<div></div>'; ?>
          <?php for ($d = 1; $d <= $daysInMonth; $d++):
            $date = $currentYear . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
            $isToday = ($date === date('Y-m-d'));
            $isPast = ($date < date('Y-m-d'));

            $hasAppointments = false;
            $appointmentCount = 0;
            $hasAvailability = false;
            $availabilityStatus = '';
            
            // Check for appointments
            if (isset($appointments) && is_array($appointments)) {
              foreach ($appointments as $apt) {
                if ($apt['appointment_date'] === $date) {
                  $hasAppointments = true;
                  $appointmentCount++;
                }
              }
            }
            
            // Check for doctor availability
            if (isset($availability) && is_array($availability)) {
              foreach ($availability as $avail) {
                if ($avail['availability_date'] === $date) {
                  $hasAvailability = true;
                  $availabilityStatus = $avail['status'];
                  break;
                }
              }
            }

            $classes = 'calendar-day bg-white border hover:border-purple-400 rounded-xl p-3 text-center shadow-sm transition cursor-pointer';
            if ($isToday) $classes .= ' border-purple-600 bg-purple-50';
            elseif ($hasAvailability && $availabilityStatus === 'unavailable') $classes .= ' border-red-300 bg-red-50';
            elseif ($hasAvailability && $availabilityStatus === 'available') $classes .= ' border-blue-300 bg-blue-50';
            elseif ($hasAppointments) $classes .= ' border-green-300 bg-green-50';
            elseif ($isPast) $classes .= ' opacity-50';
          ?>
          <div class="<?= $classes ?>" data-date="<?= $date ?>" data-has-appointments="<?= $hasAppointments ? 'true' : 'false' ?>">
            <div class="font-bold text-lg text-gray-700"><?= $d ?></div>
            <?php if ($hasAppointments): ?>
              <div class="text-sm text-green-600 font-medium mt-1 cursor-pointer hover:underline" onclick="viewDayAppointments('<?= $date ?>')">
                <?= $appointmentCount ?> appointment<?= $appointmentCount > 1 ? 's' : '' ?>
              </div>
            <?php endif; ?>
            <?php if ($hasAvailability): ?>
              <div class="text-xs mt-1 px-2 py-1 rounded-full <?= $availabilityStatus === 'available' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' ?>">
                <?= ucfirst($availabilityStatus) ?>
              </div>
            <?php endif; ?>
          </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Make sure your overlay has the proper classes -->
<div id="panelOverlay" class="panel-overlay"></div>

<!-- Admin Appointment Panel -->
<?php if ($user['user_type'] === 'admin'): ?>
<div id="addAppointmentPanel" class="slide-in-panel">
  <div class="resize-handle" id="resizeHandle"></div>
  <button class="close-btn" id="closeAddAppointmentPanel">&times;</button>
  <h2 class="text-2xl font-bold text-purple-700 mb-6 mt-4">Create New Appointment</h2>
  
  <form id="appointmentForm" action="<?= base_url($user['user_type'] . '/appointments/create') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="date" id="appointmentDate">
    
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Selected Date</label>
      <input type="text" id="selectedDateDisplay" class="form-control-lg w-full bg-gray-100" readonly>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
      <select name="patient" class="form-control-lg w-full" required>
        <option value="">Select Patient</option>
        <?php if (isset($patients) && is_array($patients)): ?>
          <?php foreach ($patients as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
      <select name="branch" class="form-control-lg w-full" required>
        <option value="">Select Branch</option>
        <?php if (isset($branches) && is_array($branches)): ?>
          <?php foreach ($branches as $b): ?>
            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
      <input type="time" name="time" class="form-control-lg w-full" required>
    </div>

    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
      <textarea name="remarks" rows="3" class="form-control-lg w-full" placeholder="Optional remarks"></textarea>
    </div>

    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition">
      Create Appointment
    </button>
  </form>
</div>
<?php endif; ?>

<!-- Doctor Availability Panel -->
<?php if ($user['user_type'] === 'doctor'): ?>
<div id="doctorAvailabilityPanel" class="slide-in-panel">
  <div class="resize-handle" id="doctorResizeHandle"></div>
  <button class="close-btn" id="closeDoctorAvailabilityPanel">&times;</button>
  <h2 class="text-2xl font-bold text-blue-700 mb-6 mt-4">Set Your Availability</h2>
  
  <form id="availabilityForm" action="<?= base_url('doctor/availability/set') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="date" id="availabilityDate">
    
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Selected Date</label>
      <input type="text" id="selectedAvailabilityDateDisplay" class="form-control-lg w-full bg-gray-100" readonly>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
      <select name="status" class="form-control-lg w-full" required>
        <option value="">Select Status</option>
        <option value="available">✅ Available</option>
        <option value="unavailable">❌ Unavailable</option>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Start Time (if available)</label>
      <input type="time" name="start_time" class="form-control-lg w-full">
    </div>

    <!-- <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">End Time (if available)</label>
      <input type="time" name="end_time" class="form-control-lg w-full">
    </div> -->

    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
      <textarea name="notes" rows="3" class="form-control-lg w-full" placeholder="Optional notes (e.g., reason for unavailability, special instructions)"></textarea>
    </div>

    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition">
      Set Availability
    </button>
  </form>
</div>
<?php endif; ?>

<!-- Appointment Information Panel -->
<div id="appointmentInfoPanel" class="slide-in-panel">
  <div class="resize-handle" id="infoResizeHandle"></div>
  <button class="close-btn" id="closeAppointmentInfoPanel">&times;</button>
  <h2 class="text-2xl font-bold text-indigo-700 mb-6 mt-4">Appointment Information</h2>
  
  <div id="appointmentInfoContent">
    <!-- Content will be loaded here -->
    <div class="text-center py-8">
      <div class="text-gray-500 text-lg">Loading...</div>
    </div>
  </div>
</div>

<!-- Edit Appointment Panel -->
<div id="editAppointmentPanel" class="slide-in-panel">
  <div class="resize-handle" id="editResizeHandle"></div>
  <button class="close-btn" id="closeEditAppointmentPanel">&times;</button>
  <h2 class="text-2xl font-bold text-amber-700 mb-6 mt-4">Edit Appointment</h2>
  
  <form id="editAppointmentForm" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="appointment_id" id="editAppointmentId">
    
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
      <input type="date" name="date" id="editAppointmentDate" class="form-control-lg w-full" required>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
      <input type="time" name="time" id="editAppointmentTime" class="form-control-lg w-full" required>
    </div>

    <?php if ($user['user_type'] === 'admin'): ?>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
      <select name="patient" id="editPatientSelect" class="form-control-lg w-full" required>
        <option value="">Select Patient</option>
        <?php if (isset($patients) && is_array($patients)): ?>
          <?php foreach ($patients as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
      <select name="branch" id="editBranchSelect" class="form-control-lg w-full" required>
        <option value="">Select Branch</option>
        <?php if (isset($branches) && is_array($branches)): ?>
          <?php foreach ($branches as $b): ?>
            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    <?php endif; ?>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
      <select name="status" id="editAppointmentStatus" class="form-control-lg w-full" required>
        <option value="scheduled">Scheduled</option>
        <option value="rescheduled">Re-Scheduled</option>
        <option value="confirmed">Confirmed</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
      <textarea name="remarks" id="editAppointmentRemarks" rows="3" class="form-control-lg w-full" placeholder="Optional remarks"></textarea>
    </div>

    <!-- Show original values -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
      <h4 class="font-semibold text-gray-700 mb-2">Original Values:</h4>
      <div id="originalValues" class="text-sm text-gray-600">
        <!-- Original values will be populated here -->
      </div>
    </div>

    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-semibold transition">
      <span id="editSubmitText">Update Appointment</span>
    </button>
  </form>
</div>

<!-- Hidden data for JavaScript -->
<script>
// Pass data to JavaScript
window.userType = '<?= $user['user_type'] ?>';
window.appointments = <?= json_encode($appointments ?? []) ?>;
window.baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/appointment-calendar.js') ?>"></script>
</body>
</html>