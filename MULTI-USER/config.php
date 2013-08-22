<?php
// Password file for authentication, contains lines like
// username:md5(password)
define( PASSFILE, '/home/cronadmin/auth/passwd');

// With shared Apache, we use sudo(8)'ed crontab(1) call
define( SUDO, '/usr/local/bin/sudo');
define( CRONTAB, '/usr/bin/crontab');

// Don't forget to add this line to your sudoers(5) file:
// www	ALL=(username) NOPASSWD: /usr/bin/crontab *
?>
