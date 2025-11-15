<?php
session_start();

// Prevent browser from caching or showing this page from history
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Redirect if not logged in
if (!isset($_SESSION['staff_id'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

require_once '../config/config.php';
require_once '../app/models/Department.php';
require_once '../app/models/Patient.php';

$department_model = new Department($conn);
$patient_model = new Patient($conn);

// Check user role
$user_role = $_SESSION['role'] ?? 'staff'; // Default to staff if not set
$is_receptionist = ($user_role === 'receptionist');
$is_admin = ($user_role === 'admin');

// For receptionists, get all departments; for staff, get only their assigned department; for admin, no departments needed
if ($is_receptionist) {
    $departments = $department_model->get_all()->fetch_all(MYSQLI_ASSOC);
    $staff_department = null; // Receptionists don't have a fixed department
} elseif ($is_admin) {
    $departments = []; // Admin doesn't manage specific departments
    $staff_department = null;
} else {
    $staff_department = $department_model->get_by_id($_SESSION['department_id']);
    $departments = [$staff_department]; // Make it an array for compatibility
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eQueue - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        header { margin-bottom: 0.5rem; }
        .container { padding-top: 1rem; }
        main { padding-top: 1rem; }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-tachometer-alt"></i> eQueue - Dashboard</h1>
        <div class="header-nav">
            <?php if (!$is_receptionist && !$is_admin): ?>
                <a href="display.php" target="_blank"><i class="fas fa-tv"></i> Display Page</a>
                <a href="sms.php"><i class="fas fa-sms"></i> Send SMS</a>
                <a href="queue_history.php"><i class="fas fa-history"></i> Queue History</a>
                <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
            <?php elseif ($is_receptionist): ?>
            <a href="receptionist_history.php"><i class="fas fa-history"></i> History</a>
            <?php elseif ($is_admin): ?>
            <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
            <?php endif; ?>
            <a href="../app/controllers/StaffController.php?action=logout" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>
    <div class="container">
        <main>
            <!-- Patient Registration Section - Only show for receptionists -->
            <?php if ($is_receptionist): ?>
            <div class="dashboard-layout">
                <div class="form-container">
                    <div class="form-header">
                       <h2><i class="fas fa-hospital"></i> Patient Information Form</h2>
                    </div>

                    <div class="form-body">
                        <?php if(isset($_GET['message'])): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($_GET['message']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_GET['error'])): ?>
                            <div class="alert alert-error">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="../app/controllers/PatientController.php" method="POST">
                            <input type="hidden" name="action" value="register">

                            <div class="grid">
                                <div class="field">
                                    <label for="first_name">First Name <span class="required">*</span></label>
                                    <input type="text" id="first_name" name="first_name" required>
                                </div>

                                <div class="field">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" id="middle_name" name="middle_name">
                                </div>

                                <div class="field">
                                    <label for="last_name">Last Name <span class="required">*</span></label>
                                    <input type="text" id="last_name" name="last_name" required>
                                </div>

                                <div class="field">
                                    <label for="birthdate">Birthdate <span class="required">*</span></label>
                                    <input type="date" id="birthdate" name="birthdate" required>
                                </div>

                                <div class="field">
                                    <label for="age">Age</label>
                                    <input type="number" id="age" name="age" readonly>
                                </div>

                                <div class="field">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number" required placeholder="+63">
                                </div>

                                <div class="field span-3">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" placeholder="Street, Barangay, City"></textarea>
                                </div>

                                <div class="field">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender">
                                        <option value=""disabled selected>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="prefer not to say">Prefer not to say</option>
                                    </select>
                                </div>

                                <div class="field">
                                    <label for="civil_status">Civil Status</label>
                                    <select id="civil_status" name="civil_status">
                                        <option value=""disabled selected>Select Status</option>
                                        <option value="single">Single</option>
                                        <option value="married">Married</option>
                                        <option value="widow">Widow</option>
                                    </select>
                                </div>

                                <div class="field">
                                    <label for="registration_datetime">Registration Date</label>
                                    <input type="datetime-local" id="registration_datetime" name="registration_datetime">
                                </div>

                                <div class="field span-2">
                                    <label for="reason_for_visit">Reason for Visit <span class="required">*</span></label>
                                    <select id="reason_for_visit" name="reason_for_visit" required>
                                        <option value=""disabled selected>Select Reason</option>
                                        <option value="Check-up">Check-up</option>
                                        <option value="Follow-up">Follow-up</option>
                                        <option value="Prescription">Prescription</option>
                                        <option value="Laboratory">Laboratory</option>
                                        <option value="Vaccination">Vaccination</option>
                                        <option value="Consultation">Consultation</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <div class="field">
                                    <label for="parent_guardian">Parent/Guardian</label>
                                    <input type="text" id="parent_guardian" name="parent_guardian" placeholder="If minor">
                                </div>

                                <div class="section-title">Vital Signs</div>

                                <div class="field">
                                    <label for="bp">Blood Pressure</label>
                                    <input type="text" id="bp" name="bp" placeholder="120/80">
                                </div>

                                <div class="field">
                                    <label for="temp">Temperature</label>
                                    <input type="text" id="temp" name="temp" placeholder="36.5°C">
                                </div>

                                <div class="field">
                                    <label for="cr_pr">Pulse Rate</label>
                                    <input type="text" id="cr_pr" name="cr_pr" placeholder="80 bpm">
                                </div>

                                <div class="field">
                                    <label for="rr">Respiratory Rate</label>
                                    <input type="text" id="rr" name="rr" placeholder="16/min">
                                </div>

                                <div class="field">
                                    <label for="wt">Weight</label>
                                    <input type="text" id="wt" name="wt" placeholder="70 kg">
                                </div>

                                <div class="field">
                                    <label for="o2sat">Oxygen Sat.</label>
                                    <input type="text" id="o2sat" name="o2sat" placeholder="98%">
                                </div>

                                <div class="field">
                                    <label for="department_id">Department <span class="required">*</span></label>
                                    <select id="department_id" name="department_id" required>
                                        <option value=""disabled selected>Select Department</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="field span-2">
                                    <label for="department_staff_id">Assign Staff</label>
                                    <select id="department_staff_id" name="department_staff_id" required>
                                        <option value="">Select Department First</option>
                                    </select>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="submit" class="btn btn-primary">Add Patient</button>
                                <button type="reset" class="btn btn-secondary">Clear Form</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Queue Overview Card -->
                <div class="queue-overview-card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-line"></i> Queue Overview</h3>
                        <button id="refresh-queue-btn" class="btn btn-secondary btn-sm" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body" id="queue-overview-content">
                        <!-- Queue data will be loaded here -->
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Auto-refreshes every 5 seconds</small>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php $departments_copy = $departments; ?>

            <!-- Admin User Management Section - Only show for admin -->
            <?php if ($is_admin): ?>
                <div class="admin-section">
                    <div class="section-header">
                        <h2><i class="fas fa-users-cog"></i> User Management</h2>
                    </div>

                    <!-- Add New Staff Button -->
                    <div class="admin-actions">
                        <button id="add-staff-btn" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Staff
                        </button>
                    </div>

                    <!-- Staff List Table -->
                    <div class="table-section">
                        <table id="staff-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="staff-table-body">
                                <!-- Staff data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Patient Queues Section - Only show for staff -->
            <?php if (!$is_receptionist && !$is_admin): ?>
                <div class="queue-sections">
                    <div class="section-header">
                        <h2>Department Queues</h2>
                    </div>

                    <?php foreach ($departments_copy as $dept): ?>
                        <div class="table-section">
                            <div class="table-header">
                                <h3 style="color: white;"><?php echo $dept['name']; ?> Department</h3>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Queue</th>
                                        <th>Patient Name</th>
                                        <th>Status</th>
                                        <th>Check-in Time</th>
                                        <th>Staff</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $patients = $patient_model->get_all_by_department($dept['id']);
                                    $has_patients = false;
                                    $previous_patient_completed = true; // First patient can always start
                                    while ($patient = $patients->fetch_assoc()):
                                        $has_patients = true;
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="queue-number"><?php echo $patient['queue_number']; ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($patient_model->combineNames($patient['first_name'], $patient['middle_name'], $patient['last_name'])); ?></strong>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $patient['status'])); ?>">
                                                    <?php echo $patient['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('g:i A', strtotime($patient['check_in_time'])); ?></td>
                                            <td><?php echo htmlspecialchars($patient['department_staff_name'] ?? 'Not Assigned'); ?></td>
                                            <td>
                                                <div class="actions">
                                                    <button type="button" class="btn btn-secondary view-patient-btn" data-patient-id="<?php echo $patient['id']; ?>" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if($patient['status'] !== 'in consultation' && $previous_patient_completed): ?>
                                                        <a href="../app/controllers/PatientController.php?action=update_status&id=<?php echo $patient['id']; ?>&status=in consultation"
                                                        class="btn btn-warning" title="Start Consultation">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if($patient['status'] === 'in consultation'): ?>
                                                        <a href="../app/controllers/PatientController.php?action=update_status&id=<?php echo $patient['id']; ?>&status=done"
                                                        class="btn btn-success" title="Complete">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="#" class="btn btn-danger remove-patient" data-id="<?php echo $patient['id']; ?>" title="Remove">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                        // Update for next patient: can start only if current patient is done
                                        $previous_patient_completed = ($patient['status'] === 'done');
                                    endwhile; ?>

                                    <?php if (!$has_patients): ?>
                                        <tr>
                                            <td colspan="6" class="text-center" style="color: #6b7280; padding: 2rem;">
                                                No patients in queue for this department
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php if (!$is_receptionist): ?>
    <!-- Modal for viewing patient summary (staff only) -->
    <div id="patient-form-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Patient Information Summary</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="patient-summary-content">
                    <!-- Patient summary will be populated here -->
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
    $(document).ready(function() {
        // Only initialize department_staff loading if the form is present (for receptionists)
        if ($('#department_staff_id').length > 0) {
            // Load department_staff based on department selection
            function loadDepartmentStaff(departmentId) {
                var departmentStaffSelect = $('#department_staff_id');

                if (departmentId) {
                    // Show loading state
                    departmentStaffSelect.html('<option value="">Loading staff...</option>');

                    $.ajax({
                        url: 'get_department_staff.php',
                        type: 'GET',
                        data: { department_id: departmentId },
                        dataType: 'json',
                        success: function(staff) {
                            departmentStaffSelect.empty().append('<option value=""> Select Staff </option>');
                            $.each(staff, function(key, member) {
                                departmentStaffSelect.append('<option value="' + member.id + '">' + member.name + '</option>');
                            });
                        },
                        error: function() {
                            departmentStaffSelect.html('<option value="">Error loading staff</option>');
                            alert('Failed to load staff. Please check the connection.');
                        }
                    });
                } else {
                    departmentStaffSelect.empty().append('<option value=""> Select Department First </option>');
                }
            }

            // Load department_staff for the staff's department on page load (for staff) - but since form is hidden for staff, this won't run
            var initialDepartmentId = $('input[name="department_id"]').val();
            if (initialDepartmentId) {
                loadDepartmentStaff(initialDepartmentId);
            }

            // Handle department change for receptionists
            $('#department_id').change(function() {
                var selectedDepartmentId = $(this).val();
                loadDepartmentStaff(selectedDepartmentId);
            });
        }

        // Handle remove patient button clicks
        $(document).on('click', '.remove-patient', function(e) {
            e.preventDefault();

            var patientId = $(this).data('id');
            var patientRow = $(this).closest('tr');

            // Remove the row immediately
            patientRow.fadeOut(300, function() {
                $(this).remove();
                // Check if table is empty and show no patients message if needed
                var tbody = patientRow.closest('tbody');
                if (tbody.find('tr').length === 1 && tbody.find('tr').text().includes('No patients')) {
                    // Already has no patients message
                } else if (tbody.find('tr').length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center" style="color: #6b7280; padding: 2rem;">No patients in queue for this department</td></tr>');
                }
            });

            // Send AJAX request to delete from database
            $.ajax({
                url: '../app/controllers/PatientController.php',
                type: 'GET',
                data: {
                    action: 'delete',
                    id: patientId
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        // If deletion failed, show error but row is already removed
                        alert('Failed to remove patient from database: ' + (response.message || 'Unknown error'));
                        console.log('Delete response:', response);
                        // Optionally, you could reload the page or add the row back
                        location.reload();
                    } else {
                        console.log('Patient deleted successfully');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error Details:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        readyState: xhr.readyState
                    });
                    alert('Error removing patient. Check console for details. Page will reload.');
                    location.reload();
                }
            });
        });



        // Theme toggle functionality (only if theme toggle button exists)
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const body = document.body;
                const currentTheme = body.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                body.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon();
            });

            function updateThemeIcon() {
                const currentTheme = document.body.getAttribute('data-theme');
                themeToggle.innerHTML = currentTheme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            }

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);
            updateThemeIcon();
        }

        // Function to calculate age from birthdate
        function calculateAge(birthdate) {
            if (!birthdate) return '';
            const birth = new Date(birthdate);
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            return age;
        }

        // Calculate age on birthdate change
        $('#birthdate').change(function() {
            const birthdate = $(this).val();
            const age = calculateAge(birthdate);
            $('#age').val(age);
            updateParentGuardianField(age);
        });

        // Function to update Parent/Guardian field based on age
        function updateParentGuardianField(age) {
            const parentGuardianField = $('#parent_guardian');
            if (age <= 17) {
                parentGuardianField.prop('required', true);
                parentGuardianField.val('');
                parentGuardianField.attr('placeholder', 'Enter parent or guardian name (required)');
            } else if (age > 18) {
                parentGuardianField.prop('required', false);
                parentGuardianField.val('N/A');
                parentGuardianField.attr('placeholder', 'N/A (optional to enter parent/guardian name)');
            } else {
                // Age is 18
                parentGuardianField.prop('required', false);
                parentGuardianField.val('');
                parentGuardianField.attr('placeholder', 'Optional: Enter parent or guardian name');
            }
        }

        // Update Parent/Guardian field on age change
        $('#age').change(function() {
            const age = parseInt($(this).val());
            updateParentGuardianField(age);
        });

        // Auto-add °C to temperature field
        $('#temp').on('input', function() {
            let value = $(this).val();
            // Remove any existing °C and non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            // If there's a valid number, add °C
            if (value && !isNaN(value)) {
                $(this).val(value + '°C');
            }
        });

        // Form validation on submit
        $('form[action*="PatientController.php"]').on('submit', function(e) {
            const age = parseInt($('#age').val());
            const parentGuardian = $('#parent_guardian').val().trim();
            if (age <= 17) {
                if (parentGuardian === '' || parentGuardian.toLowerCase() === 'n/a') {
                    e.preventDefault();
                    alert('Parent/Guardian name is required for patients 17 years or younger and cannot be "N/A".');
                    $('#parent_guardian').focus();
                    return false;
                }
            }
        });

        // Modal functionality for staff to view patient summary
        // Use event delegation to handle dynamically created buttons
        $(document).on('click', '.view-patient-btn', function() {
            var patientId = $(this).data('patient-id');

            // Fetch specific patient data by ID
            $.ajax({
                url: '../app/controllers/PatientController.php',
                type: 'GET',
                data: {
                    action: 'get_patient_by_id',
                    id: patientId
                },
                dataType: 'json',
                success: function(patient) {
                    var summaryContent = $('#patient-summary-content');

                    if (patient) {
                        // Build patient summary HTML
                        var summaryHTML = `
                            <div class="patient-summary">
                                <div class="summary-section">
                                    <h3>Personal Information</h3>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <strong>Full Name:</strong> ${patient.first_name || 'N/A'} ${patient.middle_name || ''} ${patient.last_name || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Birthdate:</strong> ${patient.birthdate ? new Date(patient.birthdate).toLocaleDateString() : 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Age:</strong> ${patient.age || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Gender:</strong> ${patient.gender || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Civil Status:</strong> ${patient.civil_status || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Contact Number:</strong> ${patient.contact_number || 'N/A'}
                                        </div>
                                        <div class="summary-item full-width">
                                            <strong>Address:</strong> ${patient.address || 'N/A'}
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-section">
                                    <h3>Visit Information</h3>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <strong>Department:</strong> ${patient.department_name || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Staff:</strong> ${patient.department_staff_name || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Reason for Visit:</strong> ${patient.reason_for_visit || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Parent/Guardian:</strong> ${patient.parent_guardian || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Registration Date:</strong> ${patient.registration_datetime ? new Date(patient.registration_datetime).toLocaleString() : 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Queue Number:</strong> ${patient.queue_number || 'N/A'}
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-section">
                                    <h3>Vital Signs</h3>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <strong>BP:</strong> ${patient.latest_bp || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Temperature:</strong> ${patient.latest_temp || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>CR/PR:</strong> ${patient.latest_cr_pr || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>RR:</strong> ${patient.latest_rr || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>Weight:</strong> ${patient.latest_wt || 'N/A'}
                                        </div>
                                        <div class="summary-item">
                                            <strong>O2SAT:</strong> ${patient.latest_o2sat || 'N/A'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        summaryContent.html(summaryHTML);
                    } else {
                        summaryContent.html('<p class="no-patient">No patient information available for this department.</p>');
                    }
                    $('#patient-form-modal').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching patient data:', error);
                    alert('Failed to load patient information. Please try again.');
                }
            });
        });

        $('.close-modal').on('click', function() {
            $('#patient-form-modal').hide();
        });

        // Close modal when clicking outside
        $(window).on('click', function(event) {
            if (event.target == $('#patient-form-modal')[0]) {
                $('#patient-form-modal').hide();
            }
        });

        // Admin functionality - Load staff data
        <?php if ($is_admin): ?>
        function loadStaffData() {
            $.ajax({
                url: '../app/controllers/StaffController.php',
                type: 'GET',
                data: { action: 'get_all_staff' },
                dataType: 'json',
                success: function(staff) {
                    var tbody = $('#staff-table-body');
                    tbody.empty();

                    if (staff && staff.length > 0) {
                        $.each(staff, function(index, member) {
                            var lastLogin = member.last_login ? new Date(member.last_login).toLocaleString() : 'Never';
                            var departmentName = member.department_name || 'N/A';

                            var row = `
                                <tr>
                                    <td>${member.name}</td>
                                    <td>${member.username}</td>
                                    <td><span class="role-badge role-${member.role}">${member.role}</span></td>
                                    <td>${departmentName}</td>
                                    <td>${lastLogin}</td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary edit-staff" data-id="${member.id}">Edit</button>
                                            <button class="btn btn-danger delete-staff" data-id="${member.id}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="6" class="text-center" style="color: #6b7280; padding: 2rem;">No staff members found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading staff data:', error);
                    $('#staff-table-body').html('<tr><td colspan="6" class="text-center" style="color: #dc3545; padding: 2rem;">Error loading staff data</td></tr>');
                }
            });
        }

        // Load staff data on page load
        loadStaffData();

        // Handle add staff button
        $('#add-staff-btn').on('click', function() {
            // Redirect to staff registration page or show modal
            window.location.href = 'register.php?type=staff';
        });

        // Handle edit staff button
        $(document).on('click', '.edit-staff', function() {
            var staffId = $(this).data('id');
            window.location.href = 'edit_staff.php?id=' + staffId;
        });

        // Handle delete staff button
        $(document).on('click', '.delete-staff', function() {
            var staffId = $(this).data('id');

            if (confirm('Are you sure you want to delete this staff member? This action cannot be undone.')) {
                $.ajax({
                    url: '../app/controllers/StaffController.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id: staffId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Staff member deleted successfully');
                            loadStaffData(); // Reload the staff list
                        } else {
                            alert('Failed to delete staff member: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting staff:', error);
                        alert('Error deleting staff member. Please try again.');
                    }
                });
            }
        });
        <?php endif; ?>

        // Function to load queue overview for receptionists
        <?php if ($is_receptionist): ?>
        function loadQueueOverview() {
            var contentDiv = $('#queue-overview-content');

            $.ajax({
                url: '../app/controllers/PatientController.php',
                type: 'GET',
                data: { action: 'get_all_queue_overview' },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.overview) {
                        response.overview.forEach(function(dept) {
                            var nextQueue = dept.next_queue || 'None';
                            var waitingCount = dept.waiting_count || 0;
                            var inConsultationCount = dept.in_consultation_count || 0;

                            // Update individual department items without fading
                            var deptItem = $('#dept-' + dept.id);
                            if (deptItem.length === 0) {
                                // Create new department item if it doesn't exist
                                var html = `
                                    <div class="queue-dept-item" id="dept-${dept.id}">
                                        <div class="dept-name">${dept.name}</div>
                                        <div class="queue-stats">
                                            <div class="stat-item">
                                                <span class="stat-label">Next:</span>
                                                <span class="stat-value queue-number">${nextQueue}</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">Waiting:</span>
                                                <span class="stat-value waiting-count">${waitingCount}</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">In Consultation:</span>
                                                <span class="stat-value consultation-count">${inConsultationCount}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                contentDiv.append(html);
                            } else {
                                // Update existing department item values
                                deptItem.find('.queue-number').text(nextQueue);
                                deptItem.find('.waiting-count').text(waitingCount);
                                deptItem.find('.consultation-count').text(inConsultationCount);
                            }
                        });
                    } else {
                        contentDiv.html('<div class="no-data">No queue data available</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading queue overview:', error);
                    contentDiv.html('<div class="error-message">Error loading queue data</div>');
                }
            });
        }

        // Manual refresh button
        $('#refresh-queue-btn').on('click', function() {
            var btn = $(this);
            var icon = btn.find('i');
            icon.addClass('fa-spin');
            btn.prop('disabled', true);

            loadQueueOverview();

            setTimeout(function() {
                icon.removeClass('fa-spin');
                btn.prop('disabled', false);
            }, 1000);
        });

        // Auto-refresh every 5 seconds
        setInterval(loadQueueOverview, 5000);

        // Load queue overview on page load
        loadQueueOverview();
        <?php endif; ?>

        // Auto-refresh queue data every 3 seconds for staff
        <?php if (!$is_receptionist && !$is_admin): ?>
        function refreshQueueData() {
            $('.table-section').each(function() {
                var tableSection = $(this);
                var departmentId = tableSection.find('.reset-queue-btn').data('department-id');
                var tbody = tableSection.find('tbody');

                if (departmentId) {
                    $.ajax({
                        url: '../app/controllers/PatientController.php',
                        type: 'GET',
                        data: {
                            action: 'get_queue_data',
                            department_id: departmentId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.patients) {
                                tbody.empty();
                                var hasPatients = false;

                                response.patients.forEach(function(patient, index) {
                                    hasPatients = true;
                                    var previousPatientCompleted = (index === 0) ? true : (response.patients[index - 1].status === 'done');

                                    var row = `
                                        <tr>
                                            <td>
                                                <span class="queue-number">${patient.queue_number}</span>
                                            </td>
                                            <td>
                                                <strong>${patient.first_name || ''} ${patient.middle_name || ''} ${patient.last_name || ''}</strong>
                                            </td>
                                            <td>
                                                <span class="status-badge status-${patient.status.toLowerCase().replace(' ', '-')}">
                                                    ${patient.status}
                                                </span>
                                            </td>
                                            <td>${new Date(patient.check_in_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</td>
                                            <td>${patient.department_staff_name || 'Not Assigned'}</td>
                                            <td>
                                                <div class="actions">
                                                    <button type="button" class="btn btn-secondary view-patient-btn" data-patient-id="${patient.id}"><strong>View</strong></button>
                                                    <a href="edit_patient.php?id=${patient.id}" class="btn btn-secondary">Edit</a>
                                                    ${patient.status !== 'in consultation' && previousPatientCompleted ? `<a href="../app/controllers/PatientController.php?action=update_status&id=${patient.id}&status=in consultation" class="btn btn-warning">Start</a>` : ''}
                                                    ${patient.status === 'in consultation' ? `<a href="../app/controllers/PatientController.php?action=update_status&id=${patient.id}&status=done" class="btn btn-success">Complete</a>` : ''}
                                                    <a href="#" class="btn btn-danger remove-patient" data-id="${patient.id}">Remove</a>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                    tbody.append(row);
                                });

                                if (!hasPatients) {
                                    tbody.append('<tr><td colspan="6" class="text-center" style="color: #6b7280; padding: 2rem;">No patients in queue for this department</td></tr>');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error refreshing queue data:', error);
                        }
                    });
                }
            });
        }

        // Refresh queue data every 3 seconds
        setInterval(refreshQueueData, 3000);
        <?php endif; ?>
    });
    </script>
<script>
window.addEventListener("pageshow", function(event) {
    if (event.persisted) {
        // If page was loaded from bfcache, force reload
        window.location.reload();
    }
});
</script>
</body>
</html>
