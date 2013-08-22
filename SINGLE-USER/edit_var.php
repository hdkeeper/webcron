<?php
// Change variable's value or create a new one

// Do a sanity check
filter_quotes();
$id = $_REQUEST['id'];
if ($id != 'new') {
    if (!is_numeric( $id)) die( "Invalid id");
}
$error = "";
if ($_REQUEST['name'] == "")
    $error = "Variable name not specified";
elseif (!preg_match( '/^[A-Za-z_]\w*$/', $_REQUEST['name']))
    $error = "Invalid variable name";

if (!$error) {
    // Create a new variable or change existing
    $a = array( 'name' => $_REQUEST['name'], 'value' => $_REQUEST['value'],
	'desc' => $_REQUEST['desc']);
    if ($id == 'new') $vars[] = $a;
    else $vars[$id] = $a;
    write_crontab( $error);
}
if ($error) {
    error_msg( $error);
    $_REQUEST['prompt_edit_var'] = 'Retry';
}
?>
