<?php
// List all variables and jobs, with actions buttons

// Write a table cell with action buttons 'Edit' and 'Delete'
function action_buttons( $what, $id, $rowspan = 1) { 
    if ($rowspan > 1) echo "<td rowspan=".$rowspan.">";
    else echo "<td>"; ?>
    <form method=post action="<?=$_SERVER['PHP_SELF']?>">
    <input type=hidden name="id" value="<?=$id?>">
    <input type=submit name="prompt_edit_<?=$what?>" value="Edit">
    <input type=submit name="prompt_delete_<?=$what?>" value="Delete">
    </form></td>
<? } ?>

<h3>Environment Variables</h3>

<table>
<tr><th>Name</th><th>Value</th><th>&nbsp;</th></tr>
<?php
$class = "a";
foreach ($vars as $id => $a) {
    if ($a['desc']) { 
	$desc = str_replace( "\n", "<br>\n", htmlspecialchars( $a['desc']));
	echo "<tr class=".$class."><td colspan=2><i>".$desc."</i></td>";
	action_buttons( 'var', $id, 2);
	echo "</tr>";
    }
    $value = $a['value'] ? htmlspecialchars( $a['value']) : "&nbsp;";
    echo "<tr class=".$class."><td>".$a['name']." =</td><td>".$value."</td>";
    if (!$a['desc']) action_buttons( 'var', $id);
    echo "</tr>";
    $class = ($class == "a") ? "b" : "a";
} ?>
<tr><td colspan=2>&nbsp;</td><td>
    <form method=post action="<?=$_SERVER['PHP_SELF']?>">
    <input type=hidden name="id" value="new">
    <input type=submit name="prompt_edit_var" value="Add new variable">
    </form>
</td></tr>
</table>


<h3>Cron Jobs</h3>

<table>
<tr><th>Minute</th><th>Hour</th><th>Day</th><th>Month</th><th>Day of week</th><th>Command to run</th><th>&nbsp;</th></tr>
<?php
$class = "a";
foreach ($jobs as $id => $job) {
    $a = time2name( $job);
    if ($a['desc']) { 
	$desc = str_replace( "\n", "<br>\n", htmlspecialchars( $a['desc']));
	echo "<tr class=".$class."><td colspan=6><i>".$desc."</i></td>";
	action_buttons( 'job', $id, 2);
	echo "</tr>";
    }
    echo "<tr class=".$class."><td>".$a['min']."</td><td>".$a['hour']."</td>";
    echo "<td>".$a['day']."</td><td>".$a['month']."</td><td>".$a['wday']."</td>";
    echo "<td>".htmlspecialchars( $a['cmd'])."</td>";
    if (!$a['desc']) action_buttons( 'job', $id);
    echo "</tr>";
    $class = ($class == "a") ? "b" : "a";
} ?>
<tr><td colspan=6>&nbsp;</td><td>
    <form method=post action="<?=$_SERVER['PHP_SELF']?>">
    <input type=hidden name="id" value="new">
    <input type=submit name="prompt_edit_job" value="Add new job">
    </form>
</td></tr>
</table>


<h3>Backup & Restore</h3>

<p><form method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=submit name="export_crontab" value="Backup crontab">
</form></p>

<p><form method=post action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type=submit name="import_crontab" value="Restore crontab">
from <input type=file name="crontab_file">
<input type=hidden name="MAX_FILE_SIZE" value="51200">
</form></p>
