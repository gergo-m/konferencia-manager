<?php
include_once("db_functions.php");

$v_email = $_POST["email"];
$v_jelszo = $_POST["jelszo"];

if (isset($v_email) && isset($v_jelszo)) {
    $v_email = htmlspecialchars($v_email);
    $v_jelszo = htmlspecialchars($v_jelszo);

    if (!filter_var($v_email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=invalid email");
    }

    echo "getting user";
    $user = get_user($v_email);
    if ($user != null && $user['email'] == $v_email && password_verify($v_jelszo, $user['jelszo']) && $user['status'] == 1) {
        echo "Logged in!";
        session_start();
        $_SESSION["id"] = $user['id'];
        $_SESSION["azonosito"] = $user['azonosito'];
        $_SESSION["elotag"] = $user['elotag'];
        $_SESSION["nev"] = $user['nev'];
        $_SESSION["email"] = $user['email'];
        $_SESSION["intezmeny"] = $user['intezmeny'];
        $_SESSION["szerepkor"] = (isAdmin($user['id']) && isSzerzo($user['id']) ? "SZERZO_ADMIN" : (isAdmin($user['id']) ? "ADMIN_ADMIN" : (isSzerzo($user['id']) ? "SZERZO_SZERZO" : "PENDING")));
        header("Location: conference_program.php");
    } else {
        header("Location: login.php?error=Helytelen bejelentkezési adatok vagy nem aktív fiók!");
    }
}