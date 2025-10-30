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
require_once '../app/models/Patient.php';
require_once '../app/models/Department.php';
require_once '../app/services/SmsService.php';

$patient_model = new Patient($conn);
$department_model = new Department($conn);

// Get staff's department
$staff_department_id = $_SESSION['department_id'] ?? null;

// Get patients only from staff's department
$staff_department_id = $_SESSION['department_id'] ?? null;
$patients = $staff_department_id ? $patient_model->get_all_by_department($staff_department_id) : null;
$departments = $department_model->get_all();

// Handle SMS sending and patient removal
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'remove_patients') {
        $removed_ids = $_POST['removed_ids'] ?? [];
        if (!isset($_SESSION['removed_patients'])) {
            $_SESSION['removed_patients'] = [];
        }
        $_SESSION['removed_patients'] = array_unique(array_merge($_SESSION['removed_patients'], $removed_ids));
        echo json_encode(['success' => true]);
        exit();
    }

    if ($action == 'send_sms') {
        $patient_ids = $_POST['patient_ids'] ?? [];
        $custom_message = trim($_POST['message']);
        $recipient_type = $_POST['recipient_type'];

        if (empty($custom_message)) {
            echo json_encode(['success' => false, 'message' => 'Please enter a message to send.']);
            exit();
        } elseif (empty($patient_ids)) {
            echo json_encode(['success' => false, 'message' => 'Please select at least one patient.']);
            exit();
        } else {
            $sms_service = new SmsService();
            $sent_count = 0;
            $failed_count = 0;

            foreach ($patient_ids as $patient_id) {
                $patient = $patient_model->get_by_id($patient_id);
                if ($patient && $sms_service->send_sms($patient['contact_number'], $custom_message)) {
                    $sent_count++;
                } else {
                    $failed_count++;
                }
            }

            if ($sent_count > 0) {
                echo json_encode(['success' => true, 'message' => "SMS sent successfully to {$sent_count} patient(s)." . ($failed_count > 0 ? " {$failed_count} message(s) failed to send." : "")]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send SMS messages.']);
            }
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS - eQueue</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components/sms.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        header { margin-bottom: 0.5rem; }
        .container { padding-top: 1rem; }
        main { padding-top: 1rem; }
        
        .template-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .template-buttons .btn {
            text-align: left;
            padding: 0.75rem 1rem;
            white-space: normal;
            height: auto;
            line-height: 1.4;
        }
        
        .template-buttons .btn i {
            margin-right: 0.5rem;
        }
        
        .form-section {
            margin-bottom: 1.5rem;
        }
        
        .form-section h3 {
            margin-bottom: 0.75rem;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-sms"></i> eQueue - Send SMS</h1>
        <div class="header-nav">
            <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <a href="../app/controllers/StaffController.php?action=logout" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>
    <div class="container">
        <main>
            
            <div class="form-section">
                <h2>Send Manual SMS Messages</h2>

                <?php if(!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="controls">
                    <div class="selection-controls">
                        <button class="btn btn-primary" onclick="selectAll()">Select All</button>
                        <button class="btn btn-secondary" onclick="unselectAll()">Unselect All</button>
                        <span class="selected-count">Selected: <strong id="selectedCount">0</strong></span>
                    </div>
                </div>

                <div class="table-container">
                    <table id="patientTable">
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll()">
                                </th>
                                <th>Patient Name</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody id="patientTableBody">
                            <?php
                            $patients->data_seek(0); // Reset pointer
                            while ($patient = $patients->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="checkbox-cell">
                                        <input type="checkbox" class="patient-checkbox" value="<?php echo $patient['id']; ?>" onchange="updateSelectedCount()">
                                    </td>
                                    <td><?php echo htmlspecialchars($patient_model->combineNames($patient['first_name'], $patient['middle_name'], $patient['last_name'])); ?></td>
                                    <td><?php echo htmlspecialchars($patient['contact_number']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="message-section" id="messageSection">
                    <div class="message-header">
                        <h3>📨 Compose Message</h3>
                    </div>
                    <textarea id="messageText" placeholder="Type your message here or select a template above..."></textarea>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button class="btn btn-success" onclick="sendMessages()">Send to Selected Patients</button>
                        <button class="btn btn-secondary" onclick="clearMessage()">Clear Message</button>
                    </div>
                </div>
            </div>

            <!-- Quick Message Templates -->
            <div class="form-section">
                <h3>Quick Message Templates</h3>
                <div class="template-buttons">
                    <button type="button" class="btn btn-secondary" onclick="useTemplate('Medicare Community Hospital: There are [X] patients ahead of you. Please prepare to proceed to the waiting area.')">
                        <i class="fas fa-info-circle"></i> Queue Status Update
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="useTemplate('Medicare Community Hospital: It\'s your turn now. Please proceed to the consultation room.')">
                        <i class="fas fa-bell"></i> Your Turn Notification
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="useTemplate('Medicare Community Hospital: You missed your turn. Please check in at reception to be re-queued.')">
                        <i class="fas fa-exclamation-triangle"></i> Missed Turn Notice
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="useTemplate('Medicare Community Hospital: Please arrive 15 minutes before your scheduled appointment time.')">
                        <i class="fas fa-calendar-check"></i> Appointment Reminder
                    </button>
                </div>
            </div>

            </div>
        </main>
    </div>

    <script>
    $(document).ready(function() {
        updateSelectedCount();
    });

    function selectAll() {
        document.querySelectorAll('.patient-checkbox').forEach(function(checkbox) {
            checkbox.checked = true;
        });
        updateSelectedCount();
    }

    function unselectAll() {
        document.querySelectorAll('.patient-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        updateSelectedCount();
    }

    function toggleAll() {
        var selectAllCheckbox = document.getElementById('selectAllCheckbox');
        var checkboxes = document.querySelectorAll('.patient-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        var selectedCount = document.querySelectorAll('.patient-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = selectedCount;
    }

    function toggleMessageSection() {
        var messageSection = document.getElementById('messageSection');
        if (messageSection.style.display === 'none' || messageSection.style.display === '') {
            messageSection.style.display = 'block';
        } else {
            messageSection.style.display = 'none';
        }
    }

    function useTemplate(templateText) {
        // Set the template text in the textarea
        document.getElementById('messageText').value = templateText;
        
        // Show the message section if it's hidden
        var messageSection = document.getElementById('messageSection');
        if (messageSection.style.display === 'none' || messageSection.style.display === '') {
            messageSection.style.display = 'block';
        }
        
        // Scroll to the message section
        messageSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Focus on the textarea
        document.getElementById('messageText').focus();
    }

    function clearMessage() {
        document.getElementById('messageText').value = '';
    }

    function sendMessages() {
        var selectedPatients = document.querySelectorAll('.patient-checkbox:checked');
        var patientIds = [];
        selectedPatients.forEach(function(checkbox) {
            patientIds.push(checkbox.value);
        });

        var message = document.getElementById('messageText').value.trim();

        if (patientIds.length === 0) {
            alert('Please select at least one patient.');
            return;
        }

        if (message === '') {
            alert('Please enter a message.');
            return;
        }

        $.post('sms.php', {
            action: 'send_sms',
            patient_ids: patientIds,
            message: message,
            recipient_type: 'selected'
        }, function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                alert('Messages sent successfully.');
                document.getElementById('messageText').value = '';
                unselectAll();
            } else {
                alert('Failed to send messages: ' + data.message);
            }
        }).fail(function() {
            alert('Error sending messages.');
        });
    }

    function showAlert(message, type) {
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + type;
        alertDiv.innerHTML = message;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.maxWidth = '400px';

        document.body.appendChild(alertDiv);

        // Remove alert after 3 seconds
        setTimeout(function() {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 3000);
    }
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