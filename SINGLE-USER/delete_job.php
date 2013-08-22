<?php
// Delete a cron job

// Do a sanity check
filter_quotes();
$id = $_REQUEST['id'];
if (!is_numeric( $id) || !isset($jobs[$id])) die( "Invalid id");

// Delete a specified job
array_delete_shift( $jobs, $id);

if (!write_crontab( $error)) {
    error_msg( $error);
    $_REQUEST['prompt_delete_job'] = 'Retry';
}
?>
