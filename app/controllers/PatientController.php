<?php
session_start();
require_once '../../config/config.php';
require_once '../models/Patient.php';
require_once '../models/DepartmentStaff.php';
require_once '../models/Department.php';
require_once '../models/QueueHistory.php';
require_once '../services/SmsService.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'register') {
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'] ?? '';
        $last_name = $_POST['last_name'];
        $birthdate = $_POST['birthdate'];
        $contact_number = $_POST['contact_number'];
        $reason_for_visit = $_POST['reason_for_visit'];
        $parent_guardian = $_POST['parent_guardian'];
        $department_id = $_POST['department_id'];
        $department_staff_id = $_POST['department_staff_id'];
        $address = $_POST['address'] ?? null;
        $gender = $_POST['gender'] ?? null;
        $civil_status = $_POST['civil_status'] ?? null;
        $registration_datetime = $_POST['registration_datetime'] ?? null;

        $patient = new Patient($conn);
        $queue_number = $patient->get_next_queue_number($department_id);

        if ($patient->create($first_name, $middle_name, $last_name, $birthdate, $contact_number, $reason_for_visit, $parent_guardian, $queue_number, $department_id, $department_staff_id, $address, $gender, $civil_status, $registration_datetime)) {
            // Log the registration activity
            $queue_history = new QueueHistory($conn);
            $patient_id = $conn->insert_id; // Get the newly created patient ID
            $queue_history->log_activity($patient_id, 'registered', null, 'waiting', $department_id, $department_staff_id, $_SESSION['staff_id'] ?? null);

            // Add vitals if provided
            $bp = $_POST['bp'] ?? null;
            $temp = $_POST['temp'] ?? null;
            $cr_pr = $_POST['cr_pr'] ?? null;
            $rr = $_POST['rr'] ?? null;
            $wt = $_POST['wt'] ?? null;
            $o2sat = $_POST['o2sat'] ?? null;

            if ($bp || $temp || $cr_pr || $rr || $wt || $o2sat) {
                $patient->addVitals($patient_id, $bp, $temp, $cr_pr, $rr, $wt, $o2sat);
            }

            header("Location: ../../public/dashboard.php?message=Patient registered successfully. Queue number: " . $queue_number);
        } else {
            header("Location: ../../public/dashboard.php?error=Failed to register patient.");
        }
    } else if ($action == 'update_patient') {
        $id = $_POST['id'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'] ?? '';
        $last_name = $_POST['last_name'];
        $birthdate = $_POST['birthdate'];
        $contact_number = $_POST['contact_number'];
        $reason_for_visit = $_POST['reason_for_visit'];
        $parent_guardian = $_POST['parent_guardian'];
        $department_id = $_POST['department_id'];
        $department_staff_id = $_POST['department_staff_id'];
        $address = $_POST['address'] ?? null;
        $gender = $_POST['gender'] ?? null;
        $civil_status = $_POST['civil_status'] ?? null;
        $registration_datetime = $_POST['registration_datetime'] ?? null;

        $patient = new Patient($conn);
        if ($patient->update($id, $first_name, $middle_name, $last_name, $birthdate, $contact_number, $reason_for_visit, $parent_guardian, $department_id, $department_staff_id, $address, $gender, $civil_status, $registration_datetime)) {
            // Add or update vitals if provided
            $bp = $_POST['bp'] ?? null;
            $temp = $_POST['temp'] ?? null;
            $cr_pr = $_POST['cr_pr'] ?? null;
            $rr = $_POST['rr'] ?? null;
            $wt = $_POST['wt'] ?? null;
            $o2sat = $_POST['o2sat'] ?? null;

            if ($bp || $temp || $cr_pr || $rr || $wt || $o2sat) {
                $patient->addVitals($id, $bp, $temp, $cr_pr, $rr, $wt, $o2sat);
            }

            header("Location: ../../public/dashboard.php?message=Patient updated successfully.");
        } else {
            header("Location: ../../public/dashboard.php?error=Failed to update patient.");
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'];

    if ($action == 'delete') {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'];
            error_log("Delete request received for patient ID: " . $id);

            if (!is_numeric($id)) {
                error_log("Invalid patient ID: " . $id);
                echo json_encode(['success' => false, 'message' => 'Invalid patient ID']);
                exit();
            }

            $patient = new Patient($conn);
            $queue_history = new QueueHistory($conn);

            $patient_data = $patient->get_by_id($id);
            if ($patient_data) {
                error_log("Patient found: " . json_encode($patient_data));

                // Log activity BEFORE updating the patient status
                $log_result = $queue_history->log_activity($id, 'removed', $patient_data['status'], 'cancelled', $patient_data['department_id'], $patient_data['department_staff_id'], $_SESSION['staff_id'] ?? null);
                error_log("Activity logged: " . ($log_result ? 'true' : 'false'));

                // Update patient status to cancelled instead of deleting
                $update_result = $patient->update_status($id, 'cancelled');
                error_log("Status update result: " . ($update_result ? 'true' : 'false'));

                if ($update_result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update patient status']);
                }
            } else {
                error_log("Patient not found for ID: " . $id);
                echo json_encode(['success' => false, 'message' => 'Patient not found']);
            }
        } catch (Exception $e) {
            error_log("Exception in delete action: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit();
    } else if ($action == 'update_status') {
        $id = $_GET['id'];
        $status = $_GET['status'];
        $patient = new Patient($conn);
        $queue_history = new QueueHistory($conn);

        // Get the current patient to retrieve department_id and department_staff_id
        $patient_data = $patient->get_by_id($id);
        if ($patient_data) {
            $old_status = $patient_data['status'];
            if ($patient->update_status($id, $status)) {
                $queue_history->log_activity($id, 'status_changed', $old_status, $status, $patient_data['department_id'], $patient_data['department_staff_id'], $_SESSION['staff_id'] ?? null);
                header("Location: ../../public/dashboard.php?message=Patient status updated successfully.");
            } else {
                header("Location: ../../public/dashboard.php?error=Failed to update patient status.");
            }
        } else {
            header("Location: ../../public/dashboard.php?error=Patient not found.");
        }
        exit();
    } else if ($action == 'delete_history') {
        $patient_ids = explode(',', $_GET['ids']);
        $queue_history = new QueueHistory($conn);
        $deleted_count = 0;
        foreach ($patient_ids as $patient_id) {
            if (is_numeric($patient_id)) {
                // Delete all history entries for this patient
                $query = "DELETE FROM queue_history WHERE patient_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $patient_id);
                if ($stmt->execute()) {
                    $deleted_count += $stmt->affected_rows;
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'deleted' => $deleted_count]);
        exit();
    } else if ($action == 'clear_history') {
        $queue_history = new QueueHistory($conn);
        $success = $queue_history->clear_all();
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit();
    } else if ($action == 'get_patient_by_id') {
        header('Content-Type: application/json');
        $id = $_GET['id'];

        if (!is_numeric($id)) {
            echo json_encode(['error' => 'Invalid patient ID']);
            exit();
        }

        $patient_model = new Patient($conn);
        $patient = $patient_model->get_by_id($id);

        if (!$patient) {
            echo json_encode(['error' => 'Patient not found']);
            exit();
        }

        // Get vitals
        $vitals = $patient_model->getVitals($id);

        // Get department name
        $department_model = new Department($conn);
        $department = $department_model->get_by_id($patient['department_id']);
        $patient['department_name'] = $department ? $department['name'] : 'N/A';

        // Get staff name
        $staff_model = new DepartmentStaff($conn);
        $staff = $staff_model->get_by_id($patient['department_staff_id']);
        $patient['department_staff_name'] = $staff ? $staff['name'] : 'N/A';

        // Add latest vitals with prefix
        $patient['latest_bp'] = $vitals['bp'] ?? 'N/A';
        $patient['latest_temp'] = $vitals['temp'] ?? 'N/A';
        $patient['latest_cr_pr'] = $vitals['cr_pr'] ?? 'N/A';
        $patient['latest_rr'] = $vitals['rr'] ?? 'N/A';
        $patient['latest_wt'] = $vitals['wt'] ?? 'N/A';
        $patient['latest_o2sat'] = $vitals['o2sat'] ?? 'N/A';

        echo json_encode($patient);
        exit();
    } else if ($action == 'requeue_patient') {
        header('Content-Type: application/json');
        $patient_id = $_GET['patient_id'];
        $department_id = $_GET['department_id'] ?? null;
        $department_staff_id = $_GET['department_staff_id'] ?? null;

        if (!is_numeric($patient_id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid patient ID']);
            exit();
        }

        $patient_model = new Patient($conn);
        $queue_history = new QueueHistory($conn);
        $department_model = new Department($conn);
        $staff_model = new DepartmentStaff($conn);

        // Get patient data
        $patient_data = $patient_model->get_by_id($patient_id);
        if (!$patient_data) {
            echo json_encode(['success' => false, 'message' => 'Patient not found']);
            exit();
        }

        // Use provided department/staff or default to original
        $target_department_id = $department_id ?: $patient_data['department_id'];
        $target_staff_id = $department_staff_id ?: $patient_data['department_staff_id'];

        // Validate department if provided
        if ($department_id && !$department_model->get_by_id($department_id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid department selected']);
            exit();
        }

        // Validate staff if provided
        if ($department_staff_id && !$staff_model->get_by_id($department_staff_id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid staff selected']);
            exit();
        }

        // Get new queue number for the target department
        $new_queue_number = $patient_model->get_next_queue_number($target_department_id);

        // Create a new patient record with the same details but new queue number, department/staff, and status 'waiting'
        $create_result = $patient_model->create(
            $patient_data['first_name'],
            $patient_data['middle_name'],
            $patient_data['last_name'],
            $patient_data['birthdate'],
            $patient_data['contact_number'],
            $patient_data['reason_for_visit'],
            $patient_data['parent_guardian'],
            $new_queue_number,
            $target_department_id,
            $target_staff_id,
            $patient_data['address'],
            $patient_data['gender'],
            $patient_data['civil_status'],
            date('Y-m-d H:i:s') // New registration datetime
        );

        if ($create_result) {
            // Get the new patient ID
            $new_patient_id = $conn->insert_id;

            // Log the requeue activity for the new patient
            $queue_history->log_activity($new_patient_id, 'requeued', null, 'waiting', $target_department_id, $target_staff_id, $_SESSION['staff_id'] ?? null);

            echo json_encode(['success' => true, 'queue_number' => $new_queue_number]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to requeue patient']);
        }
        exit();
    } else if ($action == 'get_all_queue_overview') {
        header('Content-Type: application/json');

        $patient_model = new Patient($conn);
        $department_model = new Department($conn);

        // Get all departments
        $departments = $department_model->get_all()->fetch_all(MYSQLI_ASSOC);

        $overview = [];
        foreach ($departments as $dept) {
            // Get next queue number (lowest queue number with status 'waiting')
            $next_queue_query = "SELECT MIN(queue_number) as next_queue FROM patients WHERE department_id = ? AND status = 'waiting'";
            $stmt = $conn->prepare($next_queue_query);
            $stmt->bind_param("i", $dept['id']);
            $stmt->execute();
            $next_queue_result = $stmt->get_result()->fetch_assoc();
            $next_queue = $next_queue_result['next_queue'] ?: 'None';

            // Count waiting patients
            $waiting_query = "SELECT COUNT(*) as waiting_count FROM patients WHERE department_id = ? AND status = 'waiting'";
            $stmt = $conn->prepare($waiting_query);
            $stmt->bind_param("i", $dept['id']);
            $stmt->execute();
            $waiting_result = $stmt->get_result()->fetch_assoc();
            $waiting_count = $waiting_result['waiting_count'];

            // Count patients in consultation
            $consultation_query = "SELECT COUNT(*) as consultation_count FROM patients WHERE department_id = ? AND status = 'in consultation'";
            $stmt = $conn->prepare($consultation_query);
            $stmt->bind_param("i", $dept['id']);
            $stmt->execute();
            $consultation_result = $stmt->get_result()->fetch_assoc();
            $consultation_count = $consultation_result['consultation_count'];

            $overview[] = [
                'id' => $dept['id'],
                'name' => $dept['name'],
                'next_queue' => $next_queue,
                'waiting_count' => $waiting_count,
                'in_consultation_count' => $consultation_count
            ];
        }

        echo json_encode(['success' => true, 'overview' => $overview]);
        exit();
    }
}
?>
