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
require_once '../app/models/Report.php';
require_once '../app/models/Department.php';
require_once '../app/models/Patient.php';

$report_model = new Report($conn);
$department_model = new Department($conn);
$patient_model = new Patient($conn);
$departments = $department_model->get_all();

// Get staff's department
$staff_department_id = $_SESSION['department_id'] ?? null;

// Get filter parameters - default to 'week' for better data visibility
$period = $_GET['period'] ?? 'week';
$date = $_GET['date'] ?? date('Y-m-d');

// Check if there's data for the selected period
$has_data = false;
$data_check = $report_model->get_patients_for_period($period, $staff_department_id, $date);
$has_data = count($data_check) > 0;

// If no data for 'day' period, suggest alternative
$suggestion_message = '';
if (!$has_data && $period == 'day') {
    // Check if there's data in the week
    $week_data = $report_model->get_patients_for_period('week', $staff_department_id, $date);
    if (count($week_data) > 0) {
        $suggestion_message = "No patients found for " . date('M d, Y', strtotime($date)) . ". Try selecting 'This Week' or 'This Month' to view available data.";
    } else {
        $suggestion_message = "No patient data available for the selected period.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - eQueue</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/components/reports.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <header>
        <h1><i class="fas fa-chart-bar"></i> Reports</h1>
        <div class="header-nav">
            <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <a href="../app/controllers/StaffController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>
    <div class="container">
        <main>
            <!-- Filters -->
            <div class="form-section">
                <h2>Filter Reports</h2>
                <form method="GET" action="reports.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="period">Period</label>
                            <select id="period" name="period">
                                <option value="day" <?php echo $period == 'day' ? 'selected' : ''; ?>>Today</option>
                                <option value="week" <?php echo $period == 'week' ? 'selected' : ''; ?>>This Week (Last 7 Days)</option>
                                <option value="month" <?php echo $period == 'month' ? 'selected' : ''; ?>>This Month (Last 30 Days)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date (for Daily Summary)</label>
                            <input type="date" id="date" name="date" value="<?php echo $date; ?>">
                        </div>
                    </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
            <form method="POST" action="export_report.php" style="margin-top: 1rem;">
                <input type="hidden" name="period" value="<?php echo htmlspecialchars($period); ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <label for="export_format">Export Format:</label>
                <select id="export_format" name="export_format" required>
                    <option value="pdf">PDF</option>
                    <option value="doc">Document (DOCX)</option>
                </select>
                <button type="submit" class="btn btn-secondary">Export Report</button>
            </form>
        </div>

            <?php if ($suggestion_message): ?>
            <div class="alert alert-info" style="background-color: #e3f2fd; border-left: 4px solid #2196f3; padding: 1rem; margin: 1rem 0; border-radius: 4px;">
                <i class="fas fa-info-circle" style="color: #2196f3;"></i>
                <strong>Note:</strong> <?php echo htmlspecialchars($suggestion_message); ?>
            </div>
            <?php endif; ?>

            <!-- Charts Section -->
            <div class="report-section">
                <h2>Visual Analytics</h2>
                <div class="charts-grid">
                    <!-- Patient Status Distribution -->
                    <div class="chart-container">
                        <h3>Patient Status Distribution</h3>
                        <canvas id="statusChart" width="300" height="200"></canvas>
                    </div>

                    <!-- Daily Patient Trends -->
                    <div class="chart-container">
                        <h3>Daily Patient Trends</h3>
                        <canvas id="trendsChart" width="300" height="200"></canvas>
                    </div>

                    <!-- Hourly Distribution -->
                    <div class="chart-container">
                        <h3>Hourly Patient Distribution</h3>
                        <canvas id="hourlyChart" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Patient Details Report -->
            <div class="report-section">
                <h2>Patient Details Report</h2>
                <h3>Patients for <?php echo ucfirst($period); ?> (<?php echo $period == 'day' ? date('M d, Y', strtotime($date)) : ($period == 'week' ? 'Last 7 days' : 'Last 30 days'); ?>)</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Contact</th>
                                <th>Department</th>
                                <th>Doctor</th>
                                <th>Reason for Visit</th>
                                <th>Queue Number</th>
                                <th>Check-in Time</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $patients = $report_model->get_patients_for_period($period, $staff_department_id, $date);
                            $has_patients = false;
                            foreach ($patients as $patient):
                                $has_patients = true;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($patient_model->combineNames($patient['first_name'], $patient['middle_name'], $patient['last_name'])); ?></td>
                                    <td><?php echo $patient['age']; ?></td>
                                    <td><?php echo htmlspecialchars($patient['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($patient['doctor_name'] ?? 'Not Assigned'); ?></td>
                                    <td><?php echo htmlspecialchars($patient['reason_for_visit'] ?? 'N/A'); ?></td>
                                    <td><?php echo $patient['queue_number']; ?></td>
                                    <td><?php echo date('M d, Y g:i A', strtotime($patient['check_in_time'])); ?></td>
                                    <td><?php echo date('M d, Y g:i A', strtotime($patient['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$has_patients): ?>
                                <tr>
                                    <td colspan="9" class="text-center" style="padding: 2rem; color: #6b7280;">
                                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                                        <strong>No patients found for the selected period</strong>
                                        <br>
                                        <small>Try selecting a different date range or period</small>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Chart data from PHP with error handling
        try {
            const period = '<?php echo $period; ?>';
            const statusData = <?php echo json_encode($report_model->get_patient_status_distribution($period, $staff_department_id, $date)); ?>;
            const trendsData = <?php echo json_encode($report_model->get_daily_patient_trends($period, $staff_department_id, $date)); ?>;
            const hourlyData = <?php echo json_encode($report_model->get_hourly_patient_distribution($period, $staff_department_id, $date)); ?>;

            console.log('Chart Data Loaded:', {
                period: period,
                statusData: statusData,
                trendsData: trendsData,
                hourlyData: hourlyData
            });

            // Status Distribution Pie Chart
            if (statusData && statusData.length > 0) {
                const statusLabels = statusData.map(item => {
                    const status = item.status.replace('-', ' ');
                    return status.charAt(0).toUpperCase() + status.slice(1);
                });
                const statusCounts = statusData.map(item => parseInt(item.count));
                const statusColors = {
                    'Waiting': '#fbbf24',
                    'Called': '#60a5fa',
                    'In consultation': '#3b82f6',
                    'Done': '#10b981',
                    'Cancelled': '#ef4444',
                    'No show': '#9ca3af'
                };
                const statusChartColors = statusLabels.map(label => statusColors[label] || '#6b7280');

                new Chart(document.getElementById('statusChart'), {
                    type: 'pie',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusCounts,
                            backgroundColor: statusChartColors,
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('Status chart rendered successfully');
            } else {
                document.getElementById('statusChart').parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: #6b7280;">No patient status data available for the selected period.</p>';
                console.log('No status data available');
            }

            // Daily Trends Line Chart
            if (trendsData && trendsData.length > 0) {
                const trendsLabels = trendsData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                });
                const trendsCounts = trendsData.map(item => parseInt(item.count));

                new Chart(document.getElementById('trendsChart'), {
                    type: 'line',
                    data: {
                        labels: trendsLabels,
                        datasets: [{
                            label: 'Patients',
                            data: trendsCounts,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
                console.log('Trends chart rendered successfully');
            } else {
                document.getElementById('trendsChart').parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: #6b7280;">No trend data available for the selected period.</p>';
                console.log('No trends data available');
            }

            // Hourly Distribution Bar Chart
            if (hourlyData && hourlyData.length > 0) {
                const hourlyLabels = Array.from({length: 24}, (_, i) => {
                    const hour = i % 12 || 12;
                    const ampm = i < 12 ? 'AM' : 'PM';
                    return hour + ' ' + ampm;
                });
                const hourlyCounts = Array(24).fill(0);
                hourlyData.forEach(item => {
                    const hour = parseInt(item.hour);
                    if (hour >= 0 && hour < 24) {
                        hourlyCounts[hour] = parseInt(item.count);
                    }
                });

                new Chart(document.getElementById('hourlyChart'), {
                    type: 'bar',
                    data: {
                        labels: hourlyLabels,
                        datasets: [{
                            label: 'Patients',
                            data: hourlyCounts,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: '#10b981',
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('Hourly chart rendered successfully');
            } else {
                document.getElementById('hourlyChart').parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: #6b7280;">No hourly distribution data available for the selected period.</p>';
                console.log('No hourly data available');
            }

            console.log('All charts initialized successfully');
        } catch (error) {
            console.error('Chart rendering error:', error);
            alert('There was an error rendering the charts. Please check the console for details.');
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
