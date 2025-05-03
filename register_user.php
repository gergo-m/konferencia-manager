<?php

include_once('db_functions.php');

// get parameters sent by POST request
$v_azonosito = $_POST['azonosito'];
$v_elotag = $_POST['elotag'];
$v_nev = $_POST['nev'];
$v_email = $_POST['email'];
$v_jelszo = $_POST['jelszo'];
$v_intezmeny = $_POST['intezmeny'];

// check if they got a value
if (isset($v_azonosito) && isset($v_nev) && isset($v_email)
    && isset($v_jelszo) && isset($v_intezmeny)) {

    $v_azonosito = htmlspecialchars($v_azonosito);
    $v_elotag = htmlspecialchars($v_elotag);
    $v_nev = htmlspecialchars($v_nev);
    $v_email = htmlspecialchars($v_email);
    $v_jelszo = htmlspecialchars($v_jelszo);
    $v_intezmeny = htmlspecialchars($v_intezmeny);

    $hashed_password = password_hash($v_jelszo, PASSWORD_DEFAULT);

    // insert the new record into database
    $success = insert_user($v_azonosito, $v_elotag, $v_nev, $v_email, $hashed_password, $v_intezmeny);

    // redirect to register.php if successful
    if (!$success) {
        die("Failed to insert user");
    } else {
        header('Location: login.php?msg=Sikeres regisztráció! Kérjük, várja meg, amíg egy adminisztrátorunk jóváhagyja profilját. Ezután fog tudni bejelentkezni.');
    }
} else {
    error_log("Nincs beállítva valamely érték");
}