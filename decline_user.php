<?php

include_once('db_functions.php');

$v_id = $_POST['user_id'];

if (isset($v_id)) {

    $v_id = htmlspecialchars($v_id);

    $success = decline_user($v_id);

    if (!$success) {
        die("Failed to decline user");
    } else {
        header("Location: users.php");
    }
} else {
    error_log("ID is not set");
}