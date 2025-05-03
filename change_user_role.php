<?php

include_once('db_functions.php');

$v_id = $_POST['user_id'];
$v_is_szerzo = $_POST['is_szerzo'] ?? false;
$v_is_admin = $_POST['is_admin'] ?? false;

if (isset($v_id)) {

    $v_id = htmlspecialchars($v_id);
    $v_is_szerzo = htmlspecialchars($v_is_szerzo);
    $v_is_admin = htmlspecialchars($v_is_admin);

    $success = change_user_role($v_id, $v_is_szerzo, $v_is_admin);

    if (!$success) {
        die("Failed to change user role");
    } else {
        header("Location: users.php");
    }
} else {
    error_log("No ID or roles are set");
    echo "ERROR";
}