<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Specify the path to the ZIP file you want to unzip
$zipFilePath = 'file.zip';

// Specify the directory where you want to extract the contents of the ZIP file
$extractToDir = '.';

// Execute the unzip command
$command = "unzip $zipFilePath -d $extractToDir";
$output = shell_exec($command);

// Check if the command was successful
if ($output !== null) {
    echo "File successfully unzipped:\n$output";
} else {
    echo "Failed to unzip the file.";
}
?>
