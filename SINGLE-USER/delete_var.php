<?php
// Delete a variable

// Do a sanity check
filter_quotes();
$id = $_REQUEST['id'];
if (!is_numeric( $id) || !isset($vars[$id])) die( "Invalid id");

// Delete a specified variable
array_delete_shift( $vars, $id);

if (!write_crontab( $error)) {
    error_msg( $error);
    $_REQUEST['prompt_delete_var'] = 'Retry';
}
?>
