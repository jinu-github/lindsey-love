<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eQueue - Live Queue Display</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/components/display.css">
</head>
<body>
    <div id="queue-display" class="queue-grid">
        <div class="loading">
            <div class="spinner"></div>
            Loading queue data...
        </div>
    </div>

    <div class="footer-banner">
        <div class="footer-title">MISSED QUEUE NO.</div>
        <div class="footer-disclaimer">
            Disclaimer: Queue number may not be called in sequence. If your number have been missed, 
            please re-queue for a new number. Thank you.
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadQueue() {
                $.ajax({
                    url: 'get_queue_data.php',
                    type: 'GET',
                    timeout: 10000,
                    success: function(data) {
                        if (data && data.trim() !== '') {
                            $('#queue-display').html(data);
                        } else {
                            $('#queue-display').html('<div class="no-data">No queue data available</div>');
                        }
                    },
                    error: function() {
                        $('#queue-display').html('<div class="no-data">Unable to load queue data</div>');
                    }
                });
            }

            // Initial load
            loadQueue();

            // Refresh every 5 seconds
            setInterval(function() {
                loadQueue();
            }, 5000);
        });
    </script>
</body>
</html>
