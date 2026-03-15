<?php
$file = isset($_GET['file']) ? basename($_GET['file']) : '';
$path = 'downloads/' . $file;

// Security check - only allow csv files
if (empty($file) || pathinfo($file, PATHINFO_EXTENSION) !== 'csv' || !file_exists($path)) {
    die('File not found.');
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . filesize($path));
header('Pragma: no-cache');
header('Expires: 0');

readfile($path);
exit;