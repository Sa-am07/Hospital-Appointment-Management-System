<?php
include 'db.php';

// Import the SQL file only if a special marker file doesn't exist
if (!file_exists("installed.lock")) {
    $filename = 'nhs.sql'; // Your exported SQL file
    $templine = '';
    $lines = file($filename);

    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';') {
            if (!mysqli_query($conn, $templine)) {
                echo "Error performing query '<strong>$templine</strong>': " . mysqli_error($conn) . "<br><br>";
            }
            $templine = '';
        }
    }

    // Create a marker file to prevent re-import
    file_put_contents("installed.lock", "Installation completed on: " . date("Y-m-d H:i:s"));
    echo "Database imported successfully!";
} else {
    echo "Database already imported. To re-import, delete installed.lock.";
}
?>
