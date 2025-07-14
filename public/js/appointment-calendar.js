document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing calendar...');
    
    // Get user type from window object (set in PHP)
    const userType = window.userType || 'guest';
    const addAppointmentPanel = document.getElementById('addAppointmentPanel');
    const doctorAvailabilityPanel = document.getElementById('doctorAvailabilityPanel');
    const appointmentInfoPanel = document.getElementById('appointmentInfoPanel');
    const panelOverlay = document.getElementById('panelOverlay');
    
    // Close buttons
    const closeBtn = document.getElementById('closeAddAppointmentPanel');
    const closeDoctorBtn = document.getElementById('closeDoctorAvailabilityPanel');
    const closeInfoBtn = document.getElementById('closeAppointmentInfoPanel');
    const closeEditBtn = document.getElementById('closeEditAppointmentPanel');
    
    // Resize handles
    const resizeHandle = document.getElementById('resizeHandle');
    const doctorResizeHandle = document.getElementById('doctorResizeHandle');
    const infoResizeHandle = document.getElementById('infoResizeHandle');
    const editResizeHandle = document.getElementById('editResizeHandle');
    
    // Action buttons
    const viewAppointmentsBtn = document.getElementById('viewAppointmentsBtn');
    const viewTodayBtn = document.getElementById('viewTodayBtn');
    const viewUpcomingBtn = document.getElementById('viewUpcomingBtn');
    const editAppointmentPanel = document.getElementById('editAppointmentPanel');
    
    // Calendar click functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.calendar-day')) {
            const day = e.target.closest('.calendar-day');
            const date = day.getAttribute('data-date');
            const hasAppointments = day.getAttribute('data-has-appointments') === 'true';
            
            if (date) {
                // If clicking on appointment count, show appointment info
                if (e.target.closest('.text-green-600')) {
                    viewDayAppointments(date);
                    return;
                }
                
                // Otherwise, show appropriate panel based on user type
                if (userType === 'admin') {
                    // Admin creates appointments
                    document.getElementById('appointmentDate').value = date;
                    document.getElementById('selectedDateDisplay').value = formatDateForDisplay(date);
                    openPanel();
                } else if (userType === 'doctor') {
                    // Doctor sets availability
                    document.getElementById('availabilityDate').value = date;
                    document.getElementById('selectedAvailabilityDateDisplay').value = formatDateForDisplay(date);
                    openDoctorPanel();
                } else {
                    // Other users view appointments if available
                    if (hasAppointments) {
                        viewDayAppointments(date);
                    } else {
                        alert('Date: ' + formatDateForDisplay(date) + '\nNo appointments scheduled for this date.');
                    }
                }
            }
        }
    });
    
    // Action button event listeners
    if (viewAppointmentsBtn) {
        viewAppointmentsBtn.addEventListener('click', function() {
            viewAllAppointments();
        });
    }
    
    if (viewTodayBtn) {
        viewTodayBtn.addEventListener('click', function() {
            viewTodayAppointments();
        });
    }
    
    if (viewUpcomingBtn) {
        viewUpcomingBtn.addEventListener('click', function() {
            viewUpcomingAppointments();
        });
    }
    
    // Panel control functions
    function openPanel() {
        resetAppointmentForm();
        closeAllPanels();
        if (addAppointmentPanel && panelOverlay) {
            addAppointmentPanel.classList.add('active');
            panelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function openDoctorPanel() {
        closeAllPanels();
        if (doctorAvailabilityPanel && panelOverlay) {
            doctorAvailabilityPanel.classList.add('active');
            panelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function openInfoPanel() {
        closeAllPanels();
        if (appointmentInfoPanel && panelOverlay) {
            appointmentInfoPanel.classList.add('active');
            panelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function openEditPanel() {
        closeAllPanels();
        if (editAppointmentPanel && panelOverlay) {
            editAppointmentPanel.classList.add('active');
            panelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeAllPanels() {
        const panels = [addAppointmentPanel, doctorAvailabilityPanel, appointmentInfoPanel, editAppointmentPanel];
        panels.forEach(panel => {
            if (panel) {
                panel.classList.remove('active');
            }
        });
        
        if (panelOverlay) {
            panelOverlay.classList.remove('active');
        }
        document.body.style.overflow = 'auto';
    }
    
    // Close button functionality
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeAllPanels();
        });
    }
    
    if (closeDoctorBtn) {
        closeDoctorBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeAllPanels();
        });
    }
    
    if (closeInfoBtn) {
        closeInfoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeAllPanels();
        });
    }
    
    if (closeEditBtn) {
        closeEditBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeAllPanels();
        });
    }
    
    // Overlay click to close
    if (panelOverlay) {
        panelOverlay.addEventListener('click', function(e) {
            if (e.target === panelOverlay) {
                closeAllPanels();
            }
        });
    }
    
    // ESC key to close panels
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            closeAllPanels();
        }
    });
    
    // Resizable functionality
    if (resizeHandle && addAppointmentPanel) {
        setupResizeHandle(resizeHandle, addAppointmentPanel);
    }
    
    if (doctorResizeHandle && doctorAvailabilityPanel) {
        setupResizeHandle(doctorResizeHandle, doctorAvailabilityPanel);
    }
    
    if (infoResizeHandle && appointmentInfoPanel) {
        setupResizeHandle(infoResizeHandle, appointmentInfoPanel);
    }
    
    if (editResizeHandle && editAppointmentPanel) {
        setupResizeHandle(editResizeHandle, editAppointmentPanel);
    }
    
    function setupResizeHandle(handle, panel) {
        let isResizing = false;
        let startX = 0;
        let startWidth = 0;
        
        handle.addEventListener('mousedown', function(e) {
            isResizing = true;
            startX = e.clientX;
            startWidth = parseInt(document.defaultView.getComputedStyle(panel).width, 10);
            
            document.addEventListener('mousemove', handleResize);
            document.addEventListener('mouseup', stopResize);
            
            e.preventDefault();
            document.body.style.cursor = 'col-resize';
        });
        
        function handleResize(e) {
            if (!isResizing) return;
            
            const newWidth = startWidth + (startX - e.clientX);
            
            if (newWidth >= 300 && newWidth <= window.innerWidth * 0.8) {
                panel.style.width = newWidth + 'px';
            }
        }
        
        function stopResize() {
            isResizing = false;
            document.removeEventListener('mousemove', handleResize);
            document.removeEventListener('mouseup', stopResize);
            document.body.style.cursor = 'auto';
        }
    }
    
    // Appointment viewing functions
    window.viewDayAppointments = function(date = '') {
        const appointments = window.appointments || [];
        const dayAppointments = appointments.filter(apt => apt.appointment_date === date);
        
        let content = `
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Appointments for ${formatDateForDisplay(date)}</h3>
                <hr class="my-3">
            </div>
        `;
        
        if (dayAppointments.length === 0) {
            content += '<p class="text-gray-500 text-center py-8">No appointments scheduled for this date.</p>';
        } else {
            dayAppointments.forEach(apt => {
                content += generateAppointmentCard(apt);
            });
        }
        
        document.getElementById('appointmentInfoContent').innerHTML = content;
        openInfoPanel();
    };
    
    function viewAllAppointments() {
        const appointments = window.appointments || [];
        
        let content = `
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800">All Appointments</h3>
                <p class="text-sm text-gray-600">Total: ${appointments.length} appointments</p>
                <hr class="my-3">
            </div>
        `;
        
        if (appointments.length === 0) {
            content += '<p class="text-gray-500 text-center py-8">No appointments found.</p>';
        } else {
            // Sort by date and time
            const sortedAppointments = appointments.sort((a, b) => {
                const dateA = new Date(a.appointment_date + ' ' + a.appointment_time);
                const dateB = new Date(b.appointment_date + ' ' + b.appointment_time);
                return dateA - dateB;
            });
            
            sortedAppointments.forEach(apt => {
                content += generateAppointmentCard(apt);
            });
        }
        
        document.getElementById('appointmentInfoContent').innerHTML = content;
        openInfoPanel();
    }
    
    function viewTodayAppointments() {
        const today = new Date().toISOString().split('T')[0];
        const appointments = window.appointments || [];
        const todayAppointments = appointments.filter(apt => apt.appointment_date === today);
        
        let content = `
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Today's Appointments</h3>
                <p class="text-sm text-gray-600">${formatDateForDisplay(today)}</p>
                <hr class="my-3">
            </div>
        `;
        
        if (todayAppointments.length === 0) {
            content += '<p class="text-gray-500 text-center py-8">No appointments scheduled for today.</p>';
        } else {
            todayAppointments.forEach(apt => {
                content += generateAppointmentCard(apt);
            });
        }
        
        document.getElementById('appointmentInfoContent').innerHTML = content;
        openInfoPanel();
    }
    
    function viewUpcomingAppointments() {
        const today = new Date().toISOString().split('T')[0];
        const appointments = window.appointments || [];
        const upcomingAppointments = appointments.filter(apt => apt.appointment_date > today);
        
        let content = `
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Appointments</h3>
                <p class="text-sm text-gray-600">Starting from tomorrow</p>
                <hr class="my-3">
            </div>
        `;
        
        if (upcomingAppointments.length === 0) {
            content += '<p class="text-gray-500 text-center py-8">No upcoming appointments.</p>';
        } else {
            // Sort by date and time
            const sortedAppointments = upcomingAppointments.sort((a, b) => {
                const dateA = new Date(a.appointment_date + ' ' + a.appointment_time);
                const dateB = new Date(b.appointment_date + ' ' + b.appointment_time);
                return dateA - dateB;
            });
            
            sortedAppointments.forEach(apt => {
                content += generateAppointmentCard(apt);
            });
        }
        
        document.getElementById('appointmentInfoContent').innerHTML = content;
        openInfoPanel();
    }
    
    function generateAppointmentCard(apt) {
        const statusColors = {
            'scheduled': 'bg-blue-100 text-blue-800',
            'rescheduled': 'bg-yellow-100 text-yellow-800',  // Add this line
            'confirmed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800',
            'completed': 'bg-gray-100 text-gray-800'
        };
        
        const statusColor = statusColors[apt.status] || 'bg-gray-100 text-gray-800';
        
        return `
            <div class="bg-white border rounded-lg p-4 mb-4 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-gray-800">${apt.patient_name || 'Unknown Patient'}</h4>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${statusColor}">
                        ${apt.status ? apt.status.charAt(0).toUpperCase() + apt.status.slice(1) : 'Unknown'}
                    </span>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Date:</strong> ${formatDateForDisplay(apt.appointment_date)}</p>
                    <p><strong>Time:</strong> ${formatTime(apt.appointment_time)}</p>
                    ${apt.branch_name ? `<p><strong>Branch:</strong> ${apt.branch_name}</p>` : ''}
                    ${apt.remarks ? `<p><strong>Remarks:</strong> ${apt.remarks}</p>` : ''}
                </div>
                ${userType === 'admin' || userType === 'doctor' ? `
                    <div class="mt-3 flex gap-2">
                        <button onclick="editAppointment(${apt.id})" class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                            Edit
                        </button>
                        <button onclick="deleteAppointment(${apt.id})" class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                            Delete
                        </button>
                    </div>
                ` : ''}
            </div>
        `;
    }
    
    // Helper functions
    function formatTime(timeString) {
        if (!timeString) return 'Not specified';
        
        try {
            // Handle different time formats
            let time;
            if (timeString.includes('T')) {
                // ISO format
                time = new Date(timeString);
            } else if (timeString.length === 5) {
                // HH:MM format
                time = new Date('1970-01-01T' + timeString + ':00');
            } else if (timeString.length === 8) {
                // HH:MM:SS format
                time = new Date('1970-01-01T' + timeString);
            } else {
                return timeString; // Return as-is if format is unknown
            }
            
            return time.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        } catch (error) {
            console.error('Error formatting time:', error);
            return timeString;
        }
    }
    
    // Navigation functions
    window.navigateToDate = function() {
        const month = document.getElementById('monthDropdown').value;
        const year = document.getElementById('yearDropdown').value;
        window.location.href = `?month=${month}&year=${year}`;
    };
    
    // Date formatting function
    function formatDateForDisplay(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    // Appointment actions
window.editAppointment = function(id) {
    console.log('Editing appointment ID:', id);
    
    // Find the appointment data
    const appointments = window.appointments || [];
    const appointment = appointments.find(apt => apt.id == id);
    
    if (!appointment) {
        alert('Appointment not found!');
        return;
    }
    
    console.log('Found appointment:', appointment);
    console.log('Current status:', appointment.status);
    
    // Close any open panels
    closeAllPanels();
    
    // Store original appointment data
    window.originalAppointment = { ...appointment };
    
    // Set form action
    const editForm = document.getElementById('editAppointmentForm');
    editForm.action = `${window.baseUrl}${userType}/appointments/update/${id}`;
    
    // Populate the edit form
    document.getElementById('editAppointmentId').value = appointment.id;
    
    // Set date (ensure proper format)
    const dateInput = document.getElementById('editAppointmentDate');
    if (dateInput) {
        dateInput.value = appointment.appointment_date;
    }
    
    // Set time (ensure proper format)
    const timeInput = document.getElementById('editAppointmentTime');
    if (timeInput && appointment.appointment_time) {
        let timeValue = appointment.appointment_time;
        // If time is in HH:MM:SS format, truncate to HH:MM
        if (timeValue.length === 8) {
            timeValue = timeValue.substring(0, 5);
        }
        timeInput.value = timeValue;
    }
    
    // Set status - THIS IS THE CRUCIAL FIX
    const statusSelect = document.getElementById('editAppointmentStatus');
    if (statusSelect) {
        statusSelect.value = appointment.status || 'scheduled';
        console.log('Status dropdown set to:', statusSelect.value);
    }
    
    // Set remarks
    const remarksInput = document.getElementById('editAppointmentRemarks');
    if (remarksInput) {
        remarksInput.value = appointment.remarks || '';
    }
    
    // Populate dropdowns for admin
    if (userType === 'admin') {
        const patientSelect = document.getElementById('editPatientSelect');
        const branchSelect = document.getElementById('editBranchSelect');
        
        if (patientSelect) {
            patientSelect.value = appointment.user_id || appointment.patient_id || '';
        }
        if (branchSelect) {
            branchSelect.value = appointment.branch_id || '';
        }
    }
    
    // Show original values
    displayOriginalValues(appointment);
    
    // Open edit panel
    openEditPanel();
};
    
    // Function to display original values
function displayOriginalValues(appointment) {
    const originalValuesDiv = document.getElementById('originalValues');
    let originalHtml = `
        <div class="grid grid-cols-1 gap-2">
            <div><strong>Date:</strong> ${formatDateForDisplay(appointment.appointment_date)}</div>
            <div><strong>Time:</strong> ${formatTime(appointment.appointment_time)}</div>
            <div><strong>Status:</strong> ${appointment.status || 'scheduled'}</div>
            <div><strong>Patient:</strong> ${appointment.patient_name || 'N/A'}</div>
            <div><strong>Branch:</strong> ${appointment.branch_name || 'N/A'}</div>
            <div><strong>Remarks:</strong> ${appointment.remarks || 'None'}</div>
        </div>
    `;
    originalValuesDiv.innerHTML = originalHtml;
}
    
    // Function to open edit panel
    function openEditPanel() {
        closeAllPanels();
        if (editAppointmentPanel && panelOverlay) {
            editAppointmentPanel.classList.add('active');
            panelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Update closeAllPanels function to include edit panel
    function closeAllPanels() {
        const panels = [addAppointmentPanel, doctorAvailabilityPanel, appointmentInfoPanel, editAppointmentPanel];
        panels.forEach(panel => {
            if (panel) {
                panel.classList.remove('active');
            }
        });
        
        if (panelOverlay) {
            panelOverlay.classList.remove('active');
        }
        document.body.style.overflow = 'auto';
    }
    
    // Add resize functionality for edit panel
    if (editResizeHandle && editAppointmentPanel) {
        setupResizeHandle(editResizeHandle, editAppointmentPanel);
    }
    
    // Handle edit form submission
    const editForm = document.getElementById('editAppointmentForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const statusSelect = document.getElementById('editAppointmentStatus');
            console.log('Status before submission:', statusSelect.value);
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const submitText = document.getElementById('editSubmitText');
            const originalText = submitText.textContent;
            
            // Show loading state
            submitText.textContent = 'Updating...';
            submitBtn.disabled = true;
            
            // Prepare form data
            const formData = new FormData(this);
            
            // Debug: Log form data
            console.log('Form data being sent:');
            console.log('Status:', formData.get('status'));
            console.log('Date:', formData.get('date'));
            console.log('Time:', formData.get('time'));
            console.log('Appointment ID:', formData.get('appointment_id'));
            
            // Submit via fetch
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    showNotification('✅ Appointment updated successfully!', 'success');
                    
                    // Close panel and refresh page after delay
                    setTimeout(() => {
                        closeAllPanels();
                        location.reload(); // RESTORED: Page refresh after update
                    }, 1500);
                } else {
                    showNotification('❌ ' + (data.message || 'Failed to update appointment'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Error updating appointment: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                submitText.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Enhanced notification function
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => notif.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-[9999] p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' 
                ? 'bg-green-500 text-white border-l-4 border-green-700' 
                : 'bg-red-500 text-white border-l-4 border-red-700'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="font-semibold">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    ×
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification && notification.parentElement) {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    // Reset the openPanel function for new appointments
    function openPanel() {
        resetAppointmentForm();
        closeAllPanels();
        if (addAppointmentPanel && panelOverlay) {
            addAppointmentPanel.classList.add('active');
            panelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Reset form function
    function resetAppointmentForm() {
        const form = document.getElementById('appointmentForm');
        if (form) {
            form.reset();
            form.action = `${window.baseUrl}${userType}/appointments/create`;
            
            // Reset button text
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.textContent = 'Create Appointment';
            }
            
            // Reset panel title
            const panelTitle = document.querySelector('#addAppointmentPanel h2');
            if (panelTitle) {
                panelTitle.textContent = 'Create New Appointment';
            }
        }
    }
    
    // Initialize tooltips
    initializeTooltips();
    
    function initializeTooltips() {
        const calendarDays = document.querySelectorAll('.calendar-day');
        
        calendarDays.forEach(day => {
            const date = day.getAttribute('data-date');
            const hasAppointments = day.getAttribute('data-has-appointments') === 'true';
            
            if (date) {
                let tooltipText = '';
                if (userType === 'admin') {
                    tooltipText = `Click to create appointment for ${formatDateForDisplay(date)}`;
                } else if (userType === 'doctor') {
                    tooltipText = `Click to set availability for ${formatDateForDisplay(date)}`;
                } else {
                    tooltipText = hasAppointments ? 
                        `Click to view appointments for ${formatDateForDisplay(date)}` : 
                        `View ${formatDateForDisplay(date)}`;
                }
                day.setAttribute('title', tooltipText);
            }
        });
    }
    
    window.deleteAppointment = function(id) {
        if (!confirm('Are you sure you want to delete this appointment?')) {
            return;
        }
        
        // Show loading state
        const deleteBtn = document.querySelector(`button[onclick="deleteAppointment(${id})"]`);
        if (deleteBtn) {
            deleteBtn.textContent = 'Deleting...';
            deleteBtn.disabled = true;
        }
        
        // Make delete request
        fetch(`${window.baseUrl}${userType}/appointments/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove appointment from local data
                if (window.appointments) {
                    window.appointments = window.appointments.filter(apt => apt.id != id);
                }
                
                // Show success message
                showNotification('Appointment deleted successfully!', 'success');
                
                // Refresh the page or update the calendar
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Failed to delete appointment', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting appointment', 'error');
        })
        .finally(() => {
            if (deleteBtn) {
                deleteBtn.textContent = 'Delete';
                deleteBtn.disabled = false;
            }
        });
    };
    
    // Add this function before the closing of DOMContentLoaded
function updateAppointmentCards() {
    // Update all visible appointment cards
    const appointmentCards = document.querySelectorAll('.bg-white.border.rounded-lg');
    
    appointmentCards.forEach(card => {
        const editBtn = card.querySelector('button[onclick*="editAppointment"]');
        if (editBtn) {
            const onclickAttr = editBtn.getAttribute('onclick');
            const match = onclickAttr.match(/editAppointment\((\d+)\)/);
            if (match) {
                const appointmentId = match[1];
                const appointment = window.appointments.find(apt => apt.id == appointmentId);
                if (appointment) {
                    updateSingleAppointmentCard(card, appointment);
                }
            }
        }
    });
    
    // If we're currently viewing appointment info, refresh that content
    const appointmentInfoContent = document.getElementById('appointmentInfoContent');
    if (appointmentInfoContent && appointmentInfoPanel && appointmentInfoPanel.classList.contains('active')) {
        // Regenerate the content if info panel is open
        const appointments = window.appointments || [];
        let content = '';
        
        // Check if we're viewing all appointments, today's, or specific date
        const currentTitle = appointmentInfoContent.querySelector('h3');
        if (currentTitle) {
            const titleText = currentTitle.textContent;
            
            if (titleText.includes('All Appointments')) {
                // Regenerate all appointments view
                content = `
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">All Appointments</h3>
                        <p class="text-sm text-gray-600">Total: ${appointments.length} appointments</p>
                        <hr class="my-3">
                    </div>
                `;
                const sortedAppointments = appointments.sort((a, b) => {
                    const dateA = new Date(a.appointment_date + ' ' + a.appointment_time);
                    const dateB = new Date(b.appointment_date + ' ' + b.appointment_time);
                    return dateA - dateB;
                });
                sortedAppointments.forEach(apt => {
                    content += generateAppointmentCard(apt);
                });
            } else if (titleText.includes('Today\'s Appointments')) {
                // Regenerate today's appointments
                const today = new Date().toISOString().split('T')[0];
                const todayAppointments = appointments.filter(apt => apt.appointment_date === today);
                content = `
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Today's Appointments</h3>
                        <p class="text-sm text-gray-600">${formatDateForDisplay(today)}</p>
                        <hr class="my-3">
                    </div>
                `;
                todayAppointments.forEach(apt => {
                    content += generateAppointmentCard(apt);
                });
            } else if (titleText.includes('Appointments for')) {
                // Extract date from title and regenerate
                const dateMatch = titleText.match(/Appointments for (.+)/);
                if (dateMatch) {
                    const displayDate = dateMatch[1];
                    // Find the actual date
                    const dayAppointments = appointments.filter(apt => 
                        formatDateForDisplay(apt.appointment_date) === displayDate
                    );
                    content = `
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Appointments for ${displayDate}</h3>
                            <hr class="my-3">
                        </div>
                    `;
                    dayAppointments.forEach(apt => {
                        content += generateAppointmentCard(apt);
                    });
                }
            }
            
            if (content) {
                appointmentInfoContent.innerHTML = content;
            }
        }
    }
}

// Function to update a single appointment card
function updateSingleAppointmentCard(cardElement, appointment) {
    const statusColors = {
        'scheduled': 'bg-blue-100 text-blue-800',
        'rescheduled': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
        'completed': 'bg-gray-100 text-gray-800'
    };
    
    const statusColor = statusColors[appointment.status] || 'bg-gray-100 text-gray-800';
    
    // Update the status badge
    const statusBadge = cardElement.querySelector('.px-2.py-1.rounded-full');
    if (statusBadge) {
        statusBadge.className = `px-2 py-1 rounded-full text-xs font-medium ${statusColor}`;
        statusBadge.textContent = appointment.status ? 
            appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1) : 
            'Unknown';
    }
    
    // Update the content
    const contentDiv = cardElement.querySelector('.text-sm.text-gray-600');
    if (contentDiv) {
        contentDiv.innerHTML = `
            <div class="space-y-1">
                <p><strong>Date:</strong> ${formatDateForDisplay(appointment.appointment_date)}</p>
                <p><strong>Time:</strong> ${formatTime(appointment.appointment_time)}</p>
                ${appointment.branch_name ? `<p><strong>Branch:</strong> ${appointment.branch_name}</p>` : ''}
                ${appointment.remarks ? `<p><strong>Remarks:</strong> ${appointment.remarks}</p>` : ''}
            </div>
        `;
    }
}
});
