<?php

include("db_functions.php");

session_start();

$v_cim = $_POST["cim"];
$v_szerzok = $_POST["szerzok"];

if (isset($v_cim) && isset($v_szerzok)) {
    $v_cim = htmlspecialchars($v_cim);
    array_push($v_szerzok, $_SESSION['id']);

    $success = insert_article($v_cim, $v_szerzok);

    if (!$success) {
        die("Failed to insert article");
    } else {
        header('Location: upload_article.php');
    }
} else {
    error_log("Nincs beállítva cím");
}