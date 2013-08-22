<?php
// Display a prompt to edit/create a variable

$id = $_REQUEST['id'];
$verb = ($id == 'new') ? "Create new" : "Edit";

if ($_REQUEST['prompt_edit_var'] == 'Retry') {
    foreach (array('name','value','desc') as $key)
	$$key = htmlspecialchars( $_REQUEST[$key]);
} elseif ($id == 'new') {
    $name = $value = $desc = "";
} else {
    if (!is_numeric( $id) || !isset($vars[$id])) die( "Invalid id");
    foreach ($vars[$id] as $key => $val)
	$$key = htmlspecialchars( $val);
}
?>

<h3><?=$verb?> environment variable</h3>

<form method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name="id" value="<?=$id?>">
<table>
<tr><td colspan=2>Description (optional):</td></tr>
<tr><td colspan=2><textarea name="desc" cols=60 rows=3><?=$desc?></textarea></td></tr>
<tr><td>Name</td><td>Value</td></tr>
<tr><td><input type=input size=10 name="name" value="<?=$name?>"> =</td>
<td><input type=input size=60 name="value" value="<?=$value?>"></td></tr>
<tr><td>&nbsp;</td><td><input type=submit name="edit_var" value="<?=$verb?>">
<input type=reset value="Revert">
<input type=submit value="Cancel"></td></tr>
</table>
</form>

<p>Most common used variables are SHELL, PATH, MAILTO.</p>
<p>See also: <a href="http://www.freebsd.org/cgi/man.cgi?query=crontab&sektion=5"
target="_blank">crontab(5)</a> - tables for driving cron</p>
