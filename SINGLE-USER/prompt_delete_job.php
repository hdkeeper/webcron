<?php
// Display a prompt to delete a cron job

$id = $_REQUEST['id'];
if (!is_numeric( $id) || !isset($jobs[$id])) die( "Invalid id");
$job = time2name( $jobs[$id]);
foreach ($job as $key => $val)
    $$key = htmlspecialchars( $val);
?>

<h3>Delete cron job</h3>

<table>
<tr><th colspan=6>Are you sure want delete this job?</th><tr>
<?php
if ($desc) {
    $desc = str_replace( "\n", "<br>\n", $desc);
    echo "<tr><td colspan=6><i>".$desc."</i></td><tr>";
}
?>
<tr><td>Minute</td><td>Hour</td><td>Day</td><td>Month</td><td>Day of week</td><td>Command to run</td></tr>
<tr><td><?=$min?></td><td><?=$hour?></td><td><?=$day?></td><td><?=$month?></td><td><?=$wday?></td><td><?=$cmd?></td></tr>
<tr><td colspan=5>&nbsp;</td><td><form method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name="id" value="<?=$id?>">
<input type=submit name="delete_job" value="Delete">
<input type=submit value="Cancel">
</form></td></tr>
</table>
