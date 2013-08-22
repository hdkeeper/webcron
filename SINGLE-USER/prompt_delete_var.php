<?php
// Display a prompt to delete a variable

$id = $_REQUEST['id'];
if (!is_numeric( $id) || !isset($vars[$id])) die( "Invalid id");
foreach ($vars[$id] as $key => $val)
    $$key = htmlspecialchars( $val);
?>

<h3>Delete environment variable</h3>

<table>
<tr><th colspan=2><b>Are you sure want delete this variable?</th><tr>
<?php
if ($desc) {
    $desc = str_replace( "\n", "<br>\n", $desc);
    echo "<tr><td colspan=2><i>".$desc."</i></td><tr>";
}
if (!$value) $value = "&nbsp;";
?>
<tr><td>Name</td><td>Value</td></tr>
<tr><td><?=$name?> =</td><td><?=$value?></td></tr>
<tr><td>&nbsp;</td><td><form method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name="id" value="<?=$id?>">
<input type=submit name="delete_var" value="Delete">
<input type=submit value="Cancel">
</form></td></tr>
</table>
