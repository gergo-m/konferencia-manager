<?php

include_once('db_functions.php');

$v_cikk_id = $_POST['cikk_id'];
$v_szekcio = $_POST['szekcio'];
$v_kezdes = $_POST['kezdes'];
$v_hossz = $_POST['hossz'];
$v_eloado = $_POST['eloado'];

if (!isset($_POST['cikk_id']) || !isset($_POST['szekcio'])) {
    header('Location: lectures.php');
}

if (isset($v_cikk_id) && isset($v_szekcio)
    && isset($v_kezdes) && isset($v_hossz) && isset($v_eloado)) {

    $v_cikk_id = htmlspecialchars($v_cikk_id);
    $v_szekcio = htmlspecialchars($v_szekcio);
    $v_kezdes = htmlspecialchars($v_kezdes);
    $v_hossz = htmlspecialchars($v_hossz);
    $v_eloado = htmlspecialchars($v_eloado);

    $success = insert_eloadas($v_cikk_id, $v_szekcio, $v_kezdes, $v_hossz, $v_eloado);

    if (!$success) {
        die("Failed to insert eloadas");
    } else {
        header('Location: lectures.php');
    }
} else {
    error_log("Nincs beállítva valamely érték");
}