<?php
// Send current crontab file to browser

header( "Content-Type: application/text");
header( "Content-Disposition: attachment; filename=\"crontab.txt\"");
header( "Pragma: no-cache");
header( "Expires: 0");

$stdout = popen( croncmd('-l'), "rt");
fpassthru( $stdout);
pclose( $stdout);
exit();
?>
