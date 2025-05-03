<?php

include_once('db_functions.php');

$v_id = $_POST['edit_id'];
$v_nev = $_POST['nev'];
$v_email = $_POST['email'];
$v_intezmeny = $_POST['intezmeny'];
$v_elotag = $_POST['elotag'];

if (isset($v_id) && isset($v_nev) && isset($v_email) && isset($v_intezmeny) && isset($v_elotag)) {

    $v_id = htmlspecialchars($v_id);
    $v_nev = htmlspecialchars($v_nev);
    $v_email = htmlspecialchars($v_email);
    $v_intezmeny = htmlspecialchars($v_intezmeny);
    $v_elotag = htmlspecialchars($v_elotag);

    $success = update_user($v_id, $v_nev, $v_email, $v_intezmeny, $v_elotag);

    if (!$success) {
        die("Failed to update user");
    } else {
        header('Location: profile.php');
    }
} else {
    error_log("Nincs beállítva valamely érték");
}