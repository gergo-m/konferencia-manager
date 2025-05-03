<?php

include_once('db_functions.php');

$v_szekcio_nev = $_POST['szekcio_nev'];

if (isset($v_szekcio_nev)) {

    $v_szekcio_nev = htmlspecialchars($v_szekcio_nev);

    $success = delete_section($v_szekcio_nev);

    if (!$success) {
        die("Failed to delete section");
    } else {
        header("Location: sections.php");
    }
} else {
    error_log("szekcio_nev is not set");
}