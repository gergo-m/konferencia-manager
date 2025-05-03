<?php

include_once('db_functions.php');

$v_id = $_POST['id'];
$v_cim = $_POST['cim'];

if (isset($v_id) && isset($v_cim)) {

    $v_id = htmlspecialchars($v_id);
    $v_cim = trim(htmlspecialchars($v_cim));

    if (empty($v_cim)) {
        header("Location: articles.php?cikk_id=" . $v_id);
        exit();
    }

    $success = update_article($v_id, $v_cim);

    if (!$success) {
        die("Failed to update article");
    } else {
        header('Location: articles.php');
    }
} else {
    error_log("Nincs beállítva valamely érték");
}