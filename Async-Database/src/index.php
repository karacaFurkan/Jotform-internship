<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Async Database Test</title>
    <style>
        #result, #time {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <form method="POST" action="test.php">
        <label for="query">Write Query:</label>
        <input type="text" id="query" name="query" required>
        <button type="submit">Send Query</button>
    </form>

    <?php if (isset($_GET['result']) && isset($_GET['time'])): ?>
        <div id="result">
            <strong>Query Result:</strong>
            <pre><?php echo htmlspecialchars($_GET['result']); ?></pre>
        </div>
        <div id="time">
            <strong>Time passed:</strong>
            <pre><?php echo htmlspecialchars($_GET['time']); ?> seconds</pre>
        </div>
    <?php endif; ?>
</body>
</html>
