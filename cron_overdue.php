<?php
if (php_sapi_name() !== 'cli' && !isset($_SERVER['HTTP_HOST'])) {
    die('Access denied.');
}

define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/includes/config.php');

// Set Sri Lanka timezone
date_default_timezone_set('Asia/Colombo');

$query = "UPDATE invoices 
          SET status = 'overdue' 
          WHERE invoice_due_date < CURDATE() 
          AND status = 'open'";

if ($mysqli->query($query)) {
    $affected = $mysqli->affected_rows;
    $log_message = date('Y-m-d H:i:s') . " - Overdue update ran. Rows updated: " . $affected . "\n";
    file_put_contents(ROOT_PATH . '/downloads/cron_log.txt', $log_message, FILE_APPEND);
    echo $log_message;
} else {
    $error_message = date('Y-m-d H:i:s') . " - Error: " . $mysqli->error . "\n";
    file_put_contents(ROOT_PATH . '/downloads/cron_log.txt', $error_message, FILE_APPEND);
    echo $error_message;
}

$mysqli->close();