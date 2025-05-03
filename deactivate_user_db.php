<?php

include_once('db_functions.php');

$v_id = $_POST['user_id'];
$v_from = $_POST['from'];

session_start();

if (isset($v_id) && isset($v_from)) {

    $v_id = htmlspecialchars($v_id);
    $v_from = htmlspecialchars($v_from);

    $success = deactivate_user($v_id);

    if (!$success) {
        die("Failed to deactivate user");
    } else {
        if ($_SESSION['id'] == $v_id) {
            header("Location: logout_user.php");
            exit();
        }
        header("Location: " . $v_from . ".php");
    }
} else {
    error_log("ID is not set");
}