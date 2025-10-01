<?php
// Set error reporting level
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Path to your error log file
$logFile = __DIR__ . '/utils/error_log.txt';

// Function to format and display the error log
function formatErrors($errors)
{
    // Convert special characters to HTML entities
    $formattedErrors = htmlspecialchars($errors);

    $formattedErrors = str_replace("\n#", "<br>\n#", $formattedErrors);

    // Replace newlines with <br> for HTML line breaks
    $formattedErrors = nl2br($formattedErrors);

    // Optional: Add custom formatting or styles
    return $formattedErrors;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['error_log'])) {
    $errorLogContent = $_POST['error_log'];
    file_put_contents($logFile, $errorLogContent);
    $errors = formatErrors($errorLogContent);
} else {
    $errors = '';
}

// Display the page
?>
<!DOCTYPE html>
<html>

<head>
    <title>Debug Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .form-container,
        .error-display {
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 150px;
        }

        .error-display {
            white-space: pre-wrap;
            /* Preserve whitespace and line breaks */
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <h1>Debug Page</h1>
    <div class="form-container">
        <form method="post">
            <label for="error_log">Paste error messages below:</label>
            <textarea id="error_log" name="error_log"><?php echo isset($errorLogContent) ? htmlspecialchars($errorLogContent) : ''; ?></textarea>
            <button type="submit">Save and Display</button>
        </form>
    </div>

    <div class="error-display">
        <?php echo $errors; ?>
    </div>
</body>

</html>