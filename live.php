<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Score</title>
    <link rel="stylesheet" href="CSS_File/live-score-style.css">
</head>
<body>
    <div id="live-score-container">
        </div>

    <script>
        function fetchLiveScore() {
            fetch('/Cricket-League-Website/get-live-score.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('live-score-container').innerHTML = data;
            })
            .catch(error => console.error('Error fetching live score:', error));
        }

        // Fetch score every 5 seconds
        setInterval(fetchLiveScore, 5000);

        // Initial fetch
        fetchLiveScore();
    </script>
</body>
</html>
<?php include 'footer.php'; ?>