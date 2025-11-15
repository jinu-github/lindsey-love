<?php
session_start();

// Prevent browser from caching or showing this page from history
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Redirect if not logged in or not receptionist
if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'receptionist') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

require_once '../config/config.php';
require_once '../app/models/Patient.php';
require_once '../app/models/Department.php';

$patient_model = new Patient($conn);
$department_model = new Department($conn);

// Get all patients ordered by registration datetime descending
$patients = $patient_model->get_all_ordered_by_registration();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eQueue - Patient History</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* Additional specific styles for this page */
        .search-filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.875rem;
            background: var(--card-bg);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.875rem;
            background: var(--card-bg);
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 16px var(--shadow);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px var(--shadow);
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card-icon.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .stat-card-icon.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-card-icon.orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0 0 0.5rem 0;
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin: 0 0 0.5rem 0;
            color: var(--text-color);
        }

        .empty-state p {
            margin: 0;
        }

        /* Modal form styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }

        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9375rem;
            background: white;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-filter-bar {
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }

            .filter-select {
                width: 100%;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-history"></i> Patient History</h1>
        <div class="header-nav">
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="../app/controllers/StaffController.php?action=logout" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>

    <div class="container">
        <main>
            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-card-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Total Patients</h3>
                    <p class="stat-value" id="total-patients">0</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon green">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3>Today's Registrations</h3>
                    <p class="stat-value" id="today-patients">0</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon orange">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <h3>Departments</h3>
                    <p class="stat-value" id="total-departments">0</p>
                </div>
            </div>

            <!-- Patient History Table -->
            <div class="table-section">
                <div class="table-header">
                    <h3><i class="fas fa-table"></i> Patient Records</h3>
                    <div class="header-actions">
                    </div>
                </div>

                <!-- Search and Filter Bar -->
                <div style="padding: 1.5rem;">
                    <div class="search-filter-bar">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-input" placeholder="Search by name, queue number..." onkeyup="filterTable()">
                        </div>
                        <select class="filter-select" id="department-filter" onchange="filterTable()">
                            <option value="">All Departments</option>
                            <!-- Will be populated dynamically -->
                        </select>
                        <select class="filter-select" id="date-filter" onchange="filterTable()">
                            <option value="">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-wrapper">
                    <table id="patients-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> Queue</th>
                                <th><i class="fas fa-user"></i> Full Name</th>
                                <th><i class="fas fa-hospital-user"></i> Department</th>
                                <th><i class="fas fa-calendar-alt"></i> Date Registered</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_count = 0;
                            $today_count = 0;
                            $departments_set = [];
                            $today = date('Y-m-d');
                            
                            while ($patient = $patients->fetch_assoc()): 
                                $total_count++;
                                $reg_date = date('Y-m-d', strtotime($patient['registration_datetime']));
                                if ($reg_date === $today) {
                                    $today_count++;
                                }
                                if (!empty($patient['department_name'])) {
                                    $departments_set[$patient['department_name']] = true;
                                }
                            ?>
                                <tr data-department="<?php echo htmlspecialchars($patient['department_name'] ?? ''); ?>" 
                                    data-date="<?php echo date('Y-m-d', strtotime($patient['registration_datetime'])); ?>">
                                    <td>
                                        <div class="queue-number">
                                            <?php echo htmlspecialchars($patient['queue_number']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($patient_model->combineNames($patient['first_name'], $patient['middle_name'], $patient['last_name'])); ?></strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-waiting">
                                            <?php echo htmlspecialchars($patient['department_name'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-clock" style="color: var(--text-secondary); margin-right: 0.5rem;"></i>
                                        <?php echo date('M d, Y H:i', strtotime($patient['registration_datetime'])); ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_patient.php?id=<?php echo $patient['id']; ?>" class="btn btn-secondary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-warning view-patient" data-id="<?php echo $patient['id']; ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if (in_array($patient['status'], ['no show', 'cancelled'])): ?>
                                                <button class="btn btn-primary requeue-patient" data-id="<?php echo $patient['id']; ?>">
                                                    <i class="fas fa-redo"></i> Requeue
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php if ($total_count == 0): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>No Patient Records Found</h3>
                            <p>There are no patients registered in the system yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for viewing patient summary -->
    <div id="patient-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-circle"></i> Patient Information Summary</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="patient-summary-content">
                    <!-- Patient summary will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for requeue options -->
    <div id="requeue-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-redo"></i> Requeue Patient</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="requeue-form">
                    <div class="form-group">
                        <label for="requeue-department">Department</label>
                        <select id="requeue-department" name="department_id" required>
                            <option value="">Select Department</option>
                            <!-- Departments will be populated via JavaScript -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="requeue-staff">Staff</label>
                        <select id="requeue-staff" name="department_staff_id" required>
                            <option value="">Select Staff</option>
                            <!-- Staff will be populated via JavaScript -->
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary close-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Requeue Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Store statistics for dynamic updates
    const stats = {
        total: <?php echo $total_count; ?>,
        today: <?php echo $today_count; ?>,
        departments: <?php echo count($departments_set); ?>
    };

    $(document).ready(function() {
        // Update statistics
        $('#total-patients').text(stats.total);
        $('#today-patients').text(stats.today);
        $('#total-departments').text(stats.departments);

        // Populate department filter
        const departments = [
            <?php 
            $patients->data_seek(0);
            $dept_set = [];
            while ($patient = $patients->fetch_assoc()) {
                if (!empty($patient['department_name']) && !in_array($patient['department_name'], $dept_set)) {
                    $dept_set[] = $patient['department_name'];
                    echo "'" . addslashes($patient['department_name']) . "',";
                }
            }
            ?>
        ];
        
        departments.forEach(dept => {
            $('#department-filter').append(`<option value="${dept}">${dept}</option>`);
        });

        // Handle view patient button clicks
        $(document).on('click', '.view-patient', function() {
            var patientId = $(this).data('id');

            // Fetch patient data
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
                                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <strong><i class="fas fa-id-card"></i> Full Name</strong>
                                            <span>${patient.first_name || 'N/A'} ${patient.middle_name || ''} ${patient.last_name || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-birthday-cake"></i> Birthdate</strong>
                                            <span>${patient.birthdate ? new Date(patient.birthdate).toLocaleDateString() : 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-hashtag"></i> Age</strong>
                                            <span>${patient.age || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-venus-mars"></i> Gender</strong>
                                            <span>${patient.gender || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-heart"></i> Civil Status</strong>
                                            <span>${patient.civil_status || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-phone"></i> Contact Number</strong>
                                            <span>${patient.contact_number || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item full-width">
                                            <strong><i class="fas fa-map-marker-alt"></i> Address</strong>
                                            <span>${patient.address || 'N/A'}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-section">
                                    <h3><i class="fas fa-hospital"></i> Visit Information</h3>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <strong><i class="fas fa-hospital-user"></i> Department</strong>
                                            <span>${patient.department_name || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-user-md"></i> Doctor</strong>
                                            <span>${patient.doctor_name || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-notes-medical"></i> Reason for Visit</strong>
                                            <span>${patient.reason_for_visit || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-user-friends"></i> Parent/Guardian</strong>
                                            <span>${patient.parent_guardian || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-calendar-check"></i> Registration Date</strong>
                                            <span>${patient.registration_datetime ? new Date(patient.registration_datetime).toLocaleString() : 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-ticket-alt"></i> Queue Number</strong>
                                            <span>${patient.queue_number || 'N/A'}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-section">
                                    <h3><i class="fas fa-heartbeat"></i> Vital Signs</h3>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <strong><i class="fas fa-tint"></i> Blood Pressure</strong>
                                            <span>${patient.latest_bp || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-thermometer-half"></i> Temperature</strong>
                                            <span>${patient.latest_temp || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-heart"></i> CR/PR</strong>
                                            <span>${patient.latest_cr_pr || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-lungs"></i> Respiratory Rate</strong>
                                            <span>${patient.latest_rr || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-weight"></i> Weight</strong>
                                            <span>${patient.latest_wt || 'N/A'}</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong><i class="fas fa-wind"></i> Oxygen Saturation</strong>
                                            <span>${patient.latest_o2sat || 'N/A'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        summaryContent.html(summaryHTML);
                    } else {
                        summaryContent.html('<p class="no-patient"><i class="fas fa-exclamation-triangle"></i> Patient information not found.</p>');
                    }
                    $('#patient-modal').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching patient data:', error);
                    alert('Failed to load patient information. Please try again.');
                }
            });
        });

        // Close modal
        $('.close-modal').on('click', function() {
            $('#patient-modal').hide();
        });

        // Close modal when clicking outside
        $(window).on('click', function(event) {
            if (event.target == $('#patient-modal')[0]) {
                $('#patient-modal').hide();
            }
        });

        // Handle requeue patient button clicks
        $(document).on('click', '.requeue-patient', function() {
            var patientId = $(this).data('id');

            // Fetch departments and populate modal
            $.ajax({
                url: '../app/controllers/PatientController.php',
                type: 'GET',
                data: {
                    action: 'get_all_queue_overview'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Populate departments
                        $('#requeue-department').empty().append('<option value="">Select Department</option>');
                        data.overview.forEach(function(dept) {
                            $('#requeue-department').append(`<option value="${dept.id}">${dept.name}</option>`);
                        });

                        // Store patient ID for later use
                        $('#requeue-form').data('patient-id', patientId);

                        // Show modal
                        $('#requeue-modal').show();
                    } else {
                        alert('Failed to load departments. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching departments:', error);
                    alert('Failed to load departments. Please try again.');
                }
            });
        });

        // Handle department change to populate staff
        $(document).on('change', '#requeue-department', function() {
            var departmentId = $(this).val();
            var staffSelect = $('#requeue-staff');

            if (departmentId) {
                // Fetch staff for the selected department
                $.ajax({
                    url: 'get_department_staff.php',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    dataType: 'json',
                    success: function(staff) {
                        console.log('Staff data received:', staff);
                        staffSelect.empty().append('<option value="">Select Staff</option>');
                        if (staff && staff.length > 0) {
                            staff.forEach(function(member) {
                                console.log('Adding staff member:', member);
                                staffSelect.append(`<option value="${member.id}">${member.name}</option>`);
                            });
                        } else {
                            console.log('No staff found for this department');
                            staffSelect.append('<option value="">No staff available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching staff:', error);
                        console.error('Response:', xhr.responseText);
                        staffSelect.empty().append('<option value="">Select Staff</option>');
                        alert('Failed to load staff. Please try again.');
                    }
                });
            } else {
                staffSelect.empty().append('<option value="">Select Staff</option>');
            }
        });

        // Handle requeue form submission
        $(document).on('submit', '#requeue-form', function(e) {
            e.preventDefault();

            var patientId = $(this).data('patient-id');
            var departmentId = $('#requeue-department').val();
            var staffId = $('#requeue-staff').val();

            if (!departmentId || !staffId) {
                alert('Please select both department and staff.');
                return;
            }

            // Submit requeue request
            $.ajax({
                url: '../app/controllers/PatientController.php',
                type: 'GET',
                data: {
                    action: 'requeue_patient',
                    patient_id: patientId,
                    department_id: departmentId,
                    department_staff_id: staffId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Patient requeued successfully with queue number: ' + response.queue_number);
                        $('#requeue-modal').hide();
                        location.reload();
                    } else {
                        alert('Failed to requeue patient: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error requeuing patient:', error);
                    alert('Failed to requeue patient. Please try again.');
                }
            });
        });

        // Close requeue modal
        $(document).on('click', '#requeue-modal .close-btn', function() {
            $('#requeue-modal').hide();
        });

        // Close requeue modal when clicking outside
        $(window).on('click', function(event) {
            if (event.target == $('#requeue-modal')[0]) {
                $('#requeue-modal').hide();
            }
        });
    });

    // Filter table function
    function filterTable() {
        const searchValue = $('#search-input').val().toLowerCase();
        const deptFilter = $('#department-filter').val();
        const dateFilter = $('#date-filter').val();
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        $('#patients-table tbody tr').each(function() {
            const row = $(this);
            const name = row.find('td:eq(1)').text().toLowerCase();
            const queueNum = row.find('td:eq(0)').text().toLowerCase();
            const department = row.attr('data-department');
            const dateStr = row.attr('data-date');
            const rowDate = new Date(dateStr);
            
            let showRow = true;
            
            // Search filter
            if (searchValue && !name.includes(searchValue) && !queueNum.includes(searchValue)) {
                showRow = false;
            }
            
            // Department filter
            if (deptFilter && department !== deptFilter) {
                showRow = false;
            }
            
            // Date filter
            if (dateFilter === 'today') {
                const rowDateOnly = new Date(rowDate);
                rowDateOnly.setHours(0, 0, 0, 0);
                if (rowDateOnly.getTime() !== today.getTime()) {
                    showRow = false;
                }
            } else if (dateFilter === 'week') {
                const weekAgo = new Date(today);
                weekAgo.setDate(today.getDate() - 7);
                if (rowDate < weekAgo) {
                    showRow = false;
                }
            } else if (dateFilter === 'month') {
                const monthAgo = new Date(today);
                monthAgo.setMonth(today.getMonth() - 1);
                if (rowDate < monthAgo) {
                    showRow = false;
                }
            }
            
            row.toggle(showRow);
        });
    }

    // Theme toggle function
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Update icon
        const icon = document.querySelector('.theme-toggle i');
        icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        const icon = document.querySelector('.theme-toggle i');
        if (icon) {
            icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    });
    </script>
</body>
</html>