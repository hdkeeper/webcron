<?php
// Send current crontab file to browser

require 'config.php';
header( "Content-Type: application/text");
header( "Content-Disposition: attachment; filename=\"crontab.txt\"");
header( "Pragma: no-cache");
header( "Expires: 0");

$stdout = popen( CRONCMD." -l", "r");
fpassthru( $stdout);
pclose( $stdout);
exit();
?>
