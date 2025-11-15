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

$department_model = new Department($conn);

// Get department_id from query parameter
$department_id = $_GET['department_id'] ?? null;

if (!$department_id) {
    echo json_encode([]);
    exit();
}

// Get department staff for the department
$staff = $department_model->get_staff_by_department($department_id);

$staff_list = [];
while ($member = $staff->fetch_assoc()) {
    $staff_list[] = [
        'id' => $member['id'],
        'name' => htmlspecialchars($member['name'])
    ];
}

header('Content-Type: application/json');
echo json_encode($staff_list);
?>
