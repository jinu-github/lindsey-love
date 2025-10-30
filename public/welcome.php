<?php
session_start();

// Prevent browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Redirect if not logged in
if (!isset($_SESSION['staff_id']) || !isset($_SESSION['staff_name'])) {
    header("Location: login.php");
    exit();
}

$staff_name = $_SESSION['staff_name'];
$staff_role = ucfirst($_SESSION['role'] ?? 'Staff');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Welcome - eQueue</title>
    <link rel="stylesheet" href="css/components/welcome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-content">
            <div class="welcome-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="welcome-title">Welcome Back!</h1>
            <p class="welcome-name"><?php echo htmlspecialchars($staff_name); ?></p>
            <p class="welcome-role"><?php echo htmlspecialchars($staff_role); ?></p>
            <div class="loading-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <p class="welcome-message">Redirecting to dashboard...</p>
        </div>
    </div>

    <script>
        // Prevent browser back navigation
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        // Auto-redirect to dashboard after 2.5 seconds
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 1500);
    </script>
</body>
</html>
