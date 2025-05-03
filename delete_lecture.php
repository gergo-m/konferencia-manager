<?php

include_once('db_functions.php');

$v_id = $_POST['eloadas_id'];

if (isset($v_id)) {

    $v_id = htmlspecialchars($v_id);

    $success = delete_lecture($v_id);

    if (!$success) {
        die("Failed to delete lecture");
    } else {
        header("Location: lectures.php");
    }
} else {
    error_log("ID is not set");
}