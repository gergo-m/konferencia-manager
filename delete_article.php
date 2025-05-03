<?php

include_once('db_functions.php');

$v_id = $_POST['cikk_id'];
$v_from = $_POST['from'];

if (isset($v_id) && isset($v_from)) {

    $v_id = htmlspecialchars($v_id);
    $v_from = htmlspecialchars($v_from);

    $success = delete_article($v_id);

    if (!$success) {
        die("Failed to delete article");
    } else {
        header("Location: " . $v_from . ".php");
    }
} else {
    error_log("ID is not set");
}