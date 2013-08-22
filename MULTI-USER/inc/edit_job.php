<?php
// Change a cron job or create a new one

// Various time parameters
function time_params( $title, $min, $max) {
    return array( 'title' => $title, 'min' => $min, 'max' => $max);
}

$time_params = array (
    'min'   => time_params( "Minute", 0, 59),
    'hour'  => time_params( "Hour",   0, 23),
    'day'   => time_params( "Day",    1, 31),
    'month' => time_params( "Month",  1, 12),
    'wday'  => time_params( "Day of week", 0, 7)
);

// Check time fields in crontab(5) file
function check_time( $value, $min, $max) {
    // Extract range and step
    $a = explode( '/', $value);
    if (isset( $a[2])) return false;
    // Check step if present
    if (isset( $a[1])) {
	$step = $a[1];
	if (!preg_match( '/^[0-9]+$/', $step)) return false;
	if (($step < 1) || ($step > $max)) return false;
    }
    // Check range
    $range = $a[0];
    if ($range == '*') return true;
    $nums = split( '[,-]', $range);
    // Check numbers in range
    $last = (-1);
    foreach ($nums as $n) {
	if (!preg_match( '/^[0-9]+$/', $n)) return false;
	if (($n < $min) || ($n > $max) || ($last >= $n)) return false;
	$last = $n;
    }
    return true;
}

// Do a sanity check
filter_quotes();
$id = $_REQUEST['id'];
if ($id != 'new') {
    if (!is_numeric( $id)) die( "Invalid id");
}
// Check a time fields
$error = "";
foreach ($time_params as $key => $par) {
    if ($_REQUEST[$key] == "")
	$error .= $par['title']." is not specified\n";
    elseif (!check_time( $_REQUEST[$key], $par['min'], $par['max']))
	$error .= $par['title']." has invalid value\n";
}
// Check a command
if ($_REQUEST['cmd'] == "")
    $error .= "Command is not specified\n";

if (!$error) {
    // Create a new cron job or change existing
    $a = array();
    foreach (array('min','hour','day','month','wday','cmd','desc') as $key)
	$a[$key] = $_REQUEST[$key];
    if ($id == 'new') $jobs[] = $a;
    else $jobs[$id] = $a;
    write_crontab( $error);
}
if ($error) {
    error_msg( $error);
    $_REQUEST['prompt_edit_job'] = 'Retry';
}
?>
