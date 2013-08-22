<?php
// Display a prompt to edit/create a cron job

function combobox( $key, $field) {
    global $time_names;
    echo "<select name='".$key."'>";
    foreach ($time_names[$key] as $i => $name) {
	$sel = (strval($field) == strval($i)) ? "selected" : "";
	echo "<option value='".$i."' ".$sel.">".$name."</option>";
    }
    echo "</select>";
}

$id = $_REQUEST['id'];
$verb = ($id == 'new') ? "Create new" : "Edit";
if ($_REQUEST['prompt_edit_job'] == 'Retry') {
    foreach (array('min','hour','day','month','wday','cmd','desc') as $key)
	$$key = htmlspecialchars( $_REQUEST[$key]);
} elseif ($id == 'new') {
    $min = $hour = $day = $month = $wday = '*';
    $cmd = $desc = "";
} else {
    if (!is_numeric( $id) || !isset($jobs[$id])) die( "Invalid id");
    foreach ($jobs[$id] as $key => $val)
	$$key = htmlspecialchars( $val);
}
// Is time markers simplified or advanced
if ($is_simple = ($_REQUEST['prompt_edit_job'] != "Advanced"))
foreach (array( $min, $hour, $day, $month, $wday) as $val) {
    $s = strval( $val);
    $is_simple = $is_simple && (($s == "*") || preg_match( '/^[0-9]+$/', $s));
    if (!$is_simple) {
	if ($_REQUEST['prompt_edit_job'] == "Simple")
	    echo "<p><b>Time fields are too complex for simple mode</b></p>\n";
	break;
    }
}
?>

<h3><?=$verb?> cron job</h3>

<form method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name="id" value="<?=$id?>">
<table>
<tr><td colspan=6>Description (optional):</td></tr>
<tr><td colspan=6><textarea name="desc" cols=60 rows=3><?=$desc?></textarea></td></tr>
<tr><td>Minute</td><td>Hour</td><td>Day</td><td>Month</td><td>Day of week</td><td>Command to run</td></tr>

<tr>
<? if ($is_simple) { ?>
<td><? combobox( 'min',   $min);?></td>
<td><? combobox( 'hour',  $hour);?></td>
<td><? combobox( 'day',   $day);?></td>
<td><? combobox( 'month', $month);?></td>
<td><? combobox( 'wday',  $wday);?></td>
<? } else { ?>
<td><input type=input size=5 name="min"   value="<?=$min?>"></td>
<td><input type=input size=5 name="hour"  value="<?=$hour?>"></td>
<td><input type=input size=5 name="day"   value="<?=$day?>"></td>
<td><input type=input size=5 name="month" value="<?=$month?>"></td>
<td><input type=input size=5 name="wday"  value="<?=$wday?>"></td>
<? } ?>
<td><input type=input size=50 name="cmd" value="<?=$cmd?>"></td>
</tr>

<tr><td colspan=5>&nbsp;</td>
<td><input type=submit name="edit_job" value="<?=$verb?>">
<input type=submit name="prompt_edit_job" value="<?=$is_simple?"Advanced":"Simple"?>">
<input type=reset value="Revert">
<input type=submit value="Cancel"></td></tr>
</table>
</form>

<p>See also: <a href="http://www.freebsd.org/cgi/man.cgi?query=crontab&sektion=5"
target="_blank">crontab(5)</a> - tables for driving cron</p>
