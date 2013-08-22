<?php
require 'config.php';

$pipespec = array(
    0 => array( "pipe", "r"),
    1 => array( "pipe", "w"),
    2 => array( "pipe", "w")
);

// Parse output of crontab(1), building 'vars' and 'jobs' arrays
function read_crontab( &$msg) {
    global $vars, $jobs, $pipespec;
    $proc = proc_open( CRONCMD." -l", $pipespec, $pipes);
    if ($proc === false) return false;
    fclose( $stdin = $pipes[0]);
    $vars = array();
    $jobs = array();
    $desc = $msg = "";
    // Read entire output
    $stdout = $pipes[1];
    while (!feof( $stdout)) {
	$line = trim( fgets( $stdout, 1024));
	if ($line == "") continue;
	if ($line{0} == "#") {
	    // Add another line of comment
	    $line = trim( substr( $line, 1));
	    $desc .= $desc ? "\n".$line : $line;
	} elseif (preg_match( "/^\w+\s*=/", $line)) {
	    // Add an environment variable
	    list( $name, $value) = preg_split( '/\s*=\s*/', $line);
	    if (($value{0} == '"') && ($value{ strlen($value)-1} == '"'))
		$value = substr( $value, 1, strlen($value)-2);
	    $vars[] = array( 'name' => $name, 'value' => $value, 'desc' => $desc);
	    $desc = "";
	} else {
	    // Add a cron job
	    $a = array();
	    if (!preg_match( '/^(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(.+)$/',
		$line, $a)) continue;
	    $jobs[] = array( 'min' => $a[1], 'hour' => $a[2], 'day' => $a[3],
	    'month' => $a[4], 'wday' => $a[5], 'cmd' => $a[6], 'desc' => $desc);
	    $desc = "";
	}
    }
    fclose( $stdout);
    // Read error messages
    $stderr = $pipes[2];
    while (!feof( $stderr))
	$msg .= fgets( $stderr, 1024);
    fclose( $stderr);
    if (strpos( $msg, "no crontab for") !== false)
	return true;
    return (proc_close( $proc) == 0);
}

// Write a description as a multi-line comment
function write_description( $file, $desc) {
    if (!$desc) return;
    $lines = split( "\n", $desc);
    foreach ($lines as $line)
	fputs( $file, "# ".$line."\n");
}

// Install crontab file built from 'vars' and 'jobs' arrays
// Returns 0 if ok, exit code of crontab(1) if error
function write_crontab( &$msg) {
    global $vars, $jobs, $pipespec;
    $proc = proc_open( CRONCMD." -", $pipespec, $pipes);
    if ($proc === false) return false;
    // Write new crontab file
    $stdin = $pipes[0];
    foreach ($vars as $a) {
	write_description( $stdin, $a['desc']);
	$value = $a['value'];
	if (($value == "") || preg_match( '/\s/', $value))
	    $value = '"'.$value.'"';
	fputs( $stdin, $a['name'].'='.$value."\n");
    }
    fputs( $stdin, "\n");
    foreach ($jobs as $a) {
	write_description( $stdin, $a['desc']);
	$line = join( "\t", array( $a['min'], $a['hour'], $a['day'],
	    $a['month'], $a['wday'], $a['cmd']));
	fputs( $stdin, $line."\n");
    }
    fclose( $stdin);
    // Read error messages only
    fclose( $stdout = $pipes[1]);
    $msg = "";
    $stderr = $pipes[2];
    while (!feof( $stderr))
	$msg .= fgets( $stderr, 1024);
    fclose( $stderr);
    return (proc_close( $proc) == 0);
}

// Install given crontab file
// Returns 0 if ok, exit code of crontab(1) if error
function install_crontab( $filename, &$msg) {
    global $pipespec;
    $proc = proc_open( CRONCMD." -", $pipespec, $pipes);
    if ($proc === false) return false;
    // Write new crontab file
    $stdin = $pipes[0];
    $infile = fopen( $filename, "r");
    while (!feof( $infile)) {
	$line = trim( fgets( $infile, 1024));
	fputs( $stdin, $line."\n");
    }
    fclose( $stdin);
    // Read error messages only
    fclose( $stdout = $pipes[1]);
    $msg = "";
    $stderr = $pipes[2];
    while (!feof( $stderr))
	$msg .= fgets( $stderr, 1024);
    fclose( $stderr);
    return (proc_close( $proc) == 0);
}

function filter_quotes() {
    foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = trim($v);
	if (get_magic_quotes_gpc())
	    $_REQUEST[$k] = stripslashes( $_REQUEST[$k]);
    }
}

function error_msg( $msg) {
    echo "<p><b>".str_replace( "\n", "<br>\n", $msg)."</b></p>";
}

// Delete an element from given array by key
// and renumber keys of other elements
function array_delete_shift( &$array, $key) {
    $new = array();
    unset( $array[$key]);
    foreach ($array as $val)
	$new[] = $val;
    $array = $new;
}

// Create array of time names
function time_names( $from, $to, $names = 0) {
    $a = array( '*' => "every");
    for ($i = $from; $i <= $to; $i++) {
	if (is_array($names) && current($names)) {
	    $a[$i] = current( $names);
	    next( $names);
	} else $a[$i] = strval($i);
    }
    return $a;
}

$time_names = array(
    'min' => time_names( 0, 59),
    'hour' => time_names( 0, 23),
    'day' => time_names( 1, 31),
    'month' => time_names( 1, 12, array( "Jan", "Feb", "Mar", "Apr", "May",
    "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" )),
    'wday' => time_names( 0, 7, array( "Sun", "Mon", "Tue", "Wed", "Thu",
    "Fri", "Sat", "Sun" ))
);

// Convert time markers in job to their names
function time2name( $job) {
    global $time_names;
    $a = $job;
    foreach ($a as $k => $v) {
	if (isset( $time_names[$k][$v]))
	    $a[$k] = $time_names[$k][$v];
    }
    return $a;
}

?>
