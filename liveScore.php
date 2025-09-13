<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Score</title>
    <link rel="stylesheet" href="CSS_File/livescoreStyle.css">
</head>
<body>
    <div id="live-score-container">
        </div>

    <script>
        function fetchLiveScore() {
            fetch('/Cricket-League-Website/getliveScore.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('live-score-container').innerHTML = data;
            })
            .catch(error => console.error('Error fetching live score:', error));
        }

        setInterval(fetchLiveScore, 5000);

        fetchLiveScore();
    </script>
</body>
</html>
<?php include 'footer.php'; ?>