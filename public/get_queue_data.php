<?php
require_once '../config/config.php';
require_once '../app/models/Department.php';
require_once '../app/models/Patient.php';

$department_model = new Department($conn);
$departments = $department_model->get_all();

$patient_model = new Patient($conn);

while ($dept = $departments->fetch_assoc()) {
    $patients = $patient_model->get_all_by_department($dept['id']);
    $all_patients = [];

    while ($patient = $patients->fetch_assoc()) {
        $all_patients[] = $patient;
    }

    // Sort patients by queue number
    usort($all_patients, function($a, $b) {
        return $a['queue_number'] <=> $b['queue_number'];
    });

    // Get the current serving patient (first in queue with 'serving' or 'called' status)
    $current_queue = '000';
    $status = 'waiting';
    
    if (!empty($all_patients)) {
        foreach ($all_patients as $patient) {
            if (in_array(strtolower($patient['status']), ['serving', 'called', 'active'])) {
                $current_queue = str_pad($patient['queue_number'], 3, '0', STR_PAD_LEFT);
                $status = strtolower($patient['status']);
                break;
            }
        }
        // If no serving patient, show the first waiting patient
        if ($current_queue === '000' && !empty($all_patients)) {
            $current_queue = str_pad($all_patients[0]['queue_number'], 3, '0', STR_PAD_LEFT);
            $status = strtolower($all_patients[0]['status']);
        }
    }

    $dept_name = strtoupper($dept['name']);
    
    echo "<div class='queue-card'>";
    echo "<div class='queue-number'>" . $current_queue . "</div>";
    
    // Only show status if there's an actual queue number (not 000)
    if ($current_queue !== '000') {
        $status_display = ucwords(str_replace('_', ' ', $status));
        echo "<div class='queue-status'>" . $status_display . "</div>";
    }
    
    echo "<div class='queue-divider'></div>";
    echo "<div class='department-name'>" . $dept_name . "</div>";
    echo "</div>";
}
?>
