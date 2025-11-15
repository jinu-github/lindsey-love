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

$patient_model = new Patient($conn);
$patient = $patient_model->get_by_id($_GET['id']);
$vitals = $patient_model->getVitals($patient['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient - eQueue</title>
    <link rel="stylesheet" href="css/components/edit_patient.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Patient Information</h1>
                <p class="required-note">Fields marked with <span class="required-star">*</span> are required</p>
            </div>
            
            <form action="../app/controllers/PatientController.php" method="POST">
                <input type="hidden" name="action" value="update_patient">
                <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">
                <input type="hidden" name="department_id" value="<?php echo htmlspecialchars($patient['department_id']); ?>">
                <input type="hidden" name="reason_for_visit" value="<?php echo htmlspecialchars($patient['reason_for_visit']); ?>">
                <input type="hidden" name="department_staff_id" value="<?php echo htmlspecialchars($patient['department_staff_id']); ?>">
                
                <!-- Personal Information Section -->
                <div class="section-header">Personal Information</div>
                
                <!-- Row 1: First Name, Middle Name, Last Name -->
                <div class="form-row form-row-3">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required-star">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($patient['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($patient['middle_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required-star">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($patient['last_name'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <!-- Row 2: Birthdate, Age, Gender -->
                <div class="form-row form-row-3">
                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($patient['birthdate'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" value="<?php echo $patient['age']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo ($patient['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($patient['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="prefer not to say" <?php echo ($patient['gender'] ?? '') == 'prefer not to say' ? 'selected' : ''; ?>>Prefer not to say</option>
                        </select>
                    </div>
                </div>
                
                <!-- Row 3: Civil Status, Contact Number -->
                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label for="civil_status">Civil Status</label>
                        <select id="civil_status" name="civil_status">
                            <option value="">Select Status</option>
                            <option value="single" <?php echo ($patient['civil_status'] ?? '') == 'single' ? 'selected' : ''; ?>>Single</option>
                            <option value="married" <?php echo ($patient['civil_status'] ?? '') == 'married' ? 'selected' : ''; ?>>Married</option>
                            <option value="widow" <?php echo ($patient['civil_status'] ?? '') == 'widow' ? 'selected' : ''; ?>>Widow</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($patient['contact_number']); ?>" required>
                    </div>
                </div>
                
                <!-- Row 4: Address -->
                <div class="form-row form-row-1">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address"><?php echo htmlspecialchars($patient['address'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <!-- Row 5: Parent/Guardian, Registration Date & Time -->
                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label for="parent_guardian">Parent/Guardian</label>
                        <input type="text" id="parent_guardian" name="parent_guardian" value="<?php echo htmlspecialchars($patient['parent_guardian'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="registration_datetime">Registration Date & Time</label>
                        <input type="datetime-local" id="registration_datetime" name="registration_datetime" value="<?php echo htmlspecialchars($patient['registration_datetime'] ?? ''); ?>">
                    </div>
                </div>
                
                <!-- Vital Signs Section -->
                <div class="section-header">Vital Signs</div>
                
                <!-- Vital Signs Row 1: BP, TEMP, CR/PR, RR, WT -->
                <div class="form-row form-row-5">
                    <div class="form-group">
                        <label for="bp">BP (mmHg)</label>
                        <input type="text" id="bp" name="bp" value="<?php echo htmlspecialchars($vitals['bp'] ?? ''); ?>" placeholder="120/80">
                    </div>
                    <div class="form-group">
                        <label for="temp">TEMP (°C)</label>
                        <input type="text" id="temp" name="temp" value="<?php echo htmlspecialchars($vitals['temp'] ?? ''); ?>" placeholder="36.5">
                    </div>
                    <div class="form-group">
                        <label for="cr_pr">CR/PR (bpm)</label>
                        <input type="text" id="cr_pr" name="cr_pr" value="<?php echo htmlspecialchars($vitals['cr_pr'] ?? ''); ?>" placeholder="72">
                    </div>
                    <div class="form-group">
                        <label for="rr">RR (breaths/min)</label>
                        <input type="text" id="rr" name="rr" value="<?php echo htmlspecialchars($vitals['rr'] ?? ''); ?>" placeholder="16">
                    </div>
                    <div class="form-group">
                        <label for="wt">WT (kg)</label>
                        <input type="text" id="wt" name="wt" value="<?php echo htmlspecialchars($vitals['wt'] ?? ''); ?>" placeholder="70.0">
                    </div>
                </div>
                
                <!-- Vital Signs Row 2: O2SAT -->
                <div class="form-row form-row-1">
                    <div class="form-group">
                        <label for="o2sat">O2SAT (%)</label>
                        <input type="text" id="o2sat" name="o2sat" value="<?php echo htmlspecialchars($vitals['o2sat'] ?? ''); ?>" placeholder="98">
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="button-container">
                    <a href="dashboard.php" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted) {
            // If page was loaded from bfcache, force reload
            window.location.reload();
        }
    });
    </script>

    <script>
    $(document).ready(function() {

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
        });

        // Calculate age on page load if birthdate is set
        const initialBirthdate = $('#birthdate').val();
        if (initialBirthdate) {
            const initialAge = calculateAge(initialBirthdate);
            $('#age').val(initialAge);
            updateParentGuardianField(initialAge);
        }

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
    });
    </script>
</body>
</html>