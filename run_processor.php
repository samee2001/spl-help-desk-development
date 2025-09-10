
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Processor Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .output-box {
            background-color: #212529;
            color: #f8f9fa;
            font-family: Consolas, 'Courier New', monospace;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap; /* Ensures line breaks are respected */
            word-wrap: break-word;
            margin-top: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h1 class="card-title">Helpdesk Email Processor</h1>
                <p class="card-text text-muted">Click the button below to manually run the script that checks for new emails.</p>
                
                <form method="post">
                    <button type="submit" name="run_script" class="btn btn-primary btn-lg">
                        Run Email Processor Now
                    </button>
                </form>

                <?php
                // This block will only run when the button is clicked
                if (isset($_POST['run_script'])) {
                    echo '<h3 class="mt-4">Script Output:</h3>';
                    
                    // This is the exact same command we tried in the command line
                    $command = '"C:\xampp\php\php.exe" -f "C:\xampp\htdocs\spl_help_desk_development_v2\email\process_emails.php"';
                    
                    // Use shell_exec to run the command and capture its output
                    $output = shell_exec($command);
                    
                    // Display the output in a formatted black box
                    echo '<div class="output-box text-start">';
                    if ($output) {
                        echo htmlspecialchars($output);
                    } else {
                        echo "No output was returned from the script. This could mean it ran successfully with nothing to do, or an error occurred that prevented output.";
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
