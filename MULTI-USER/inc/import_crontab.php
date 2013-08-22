<?php
// Import uploaded crontab file

// Check uploaded file presence
$error = "";
if (!$_FILES['crontab_file']['name'])
    $error = "Backup file not specified";
else {
    $file = $_FILES['crontab_file']['tmp_name'];
    if (!is_file($file) || !is_readable($file) || !is_uploaded_file($file))
	$error = "File upload error";
}

if (!$error) {
    // Install new crontab file
    if (install_crontab( $file, $error)) {
	if (!read_crontab( $msg)) die($msg);
    }
}
if ($error) error_msg( $error);
?>
