<?php
// REQUEST parameters:
// id - index of variable or job record
// logout - log current user out
// prompt_edit_var - prompt to edit/create a variable
// prompt_delete_var - prompt to delete a variable
// prompt_edit_job - prompt to edit/create a job
// prompt_delete_job - prompt to delete a job
// edit_var - edit a variable actually
// delete_var - delete a variable actually
// edit_job - edit a job actually
// delete_job - delete a job actually
// export_crontab - send current crontab file
// import_crontab - install given crontab file

// Authenticate user by given password
require 'inc/functions.php';
$GLOBALS['username'] = authenticate_user();
// Log current user out
if (isset($_REQUEST['logout']) && ($_REQUEST['olduser'] == $GLOBALS['username']))
    send_auth_rejected( "Cron control");

// Send current crontab file 
if (isset($_REQUEST['export_crontab']))
    include 'inc/export_crontab.php';

// Choose a suitable title
if (isset($_REQUEST['prompt_edit_var']))
    $title = "Edit/create environment variable";
elseif (isset($_REQUEST['prompt_delete_var']))
    $title = "Delete environment variable";
elseif (isset($_REQUEST['prompt_edit_job']))
    $title = "Edit/create cron job";
elseif (isset($_REQUEST['prompt_delete_job']))
    $title = "Delete cron job";
else $title = "Cron control";

// These pages should not be cached
header( 'Cache-control: no-cache');
header( 'Pragma: no-cache');
header( 'Expires: 0');
?>
<html>
<head>
<title><?=$title?></title>
<style type="text/css">
i  {	color: gray; }
th {	background-color: #E8E8E8;
	font-weight: bold; }
tr {	background-color: #E8E8E8; }
tr.a {	background-color: #E0F0E0; }
tr.b {	background-color: #E0E0F0; }
</style>
</head>
<body>

<form method=post action="<?=$_SERVER['PHP_SELF']?>">
You are logged in as <b><?=$GLOBALS['username']?></b>
<input type=hidden name="olduser" value="<?=$GLOBALS['username']?>">
<input type=submit name="logout" value="Log out">
</form>

<?php
if (!read_crontab( $msg)) die($msg);

// Actually do something
if (isset($_REQUEST['edit_var']))
    include 'inc/edit_var.php';
elseif (isset($_REQUEST['delete_var']))
    include 'inc/delete_var.php';
elseif (isset($_REQUEST['edit_job']))
    include 'inc/edit_job.php';
elseif (isset($_REQUEST['delete_job']))
    include 'inc/delete_job.php';
elseif (isset($_REQUEST['import_crontab']))
    include 'inc/import_crontab.php';

// Display user prompt
if (isset($_REQUEST['prompt_edit_var']))
    include 'inc/prompt_edit_var.php';
elseif (isset($_REQUEST['prompt_delete_var']))
    include 'inc/prompt_delete_var.php';
elseif (isset($_REQUEST['prompt_edit_job']))
    include 'inc/prompt_edit_job.php';
elseif (isset($_REQUEST['prompt_delete_job']))
    include 'inc/prompt_delete_job.php';
else
    include 'inc/list.php';
?>

</body>
</html>
