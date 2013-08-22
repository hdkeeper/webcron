<?php

// With per-user Apache, we use simple crontab(1) call
// define( 'CRONCMD', '/usr/bin/crontab');

// With shared Apache, we use sudo(8)'ed crontab(1) call
// Don't forget to add this line to your sudoers(5) file:
// www	ALL=(username) NOPASSWD: /usr/bin/crontab *
define( 'CRONCMD', '/usr/local/bin/sudo -u kaze /usr/bin/crontab');
?>
