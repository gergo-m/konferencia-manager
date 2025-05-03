<?php

include_once('db_functions.php');

$v_nev = $_POST['szekcio_nev'];
$v_kezdes = $_POST['szekcio_kezdes'];
$v_levezeto_elnok_id = $_POST['levezeto_elnok_id'];

if (isset($v_nev) && isset($v_kezdes) && isset($v_levezeto_elnok_id)) {

    $v_nev = htmlspecialchars($v_nev);
    $v_kezdes = htmlspecialchars($v_kezdes);
    $v_levezeto_elnok_id = htmlspecialchars($v_levezeto_elnok_id);

    $success = insert_section($v_nev, $v_kezdes, $v_levezeto_elnok_id);

    if (!$success) {
        die("Failed to insert section");
    } else {
        header('Location: sections.php');
    }
} else {
    error_log("Nincs beállítva valamely érték");
}