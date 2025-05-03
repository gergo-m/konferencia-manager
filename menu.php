<?php
session_start();
function menu() {

    $menustr = file_get_contents("menu.html");
    if (!isset($_SESSION['id'])) {
        $menustr = preg_replace("/<#ifSession>.*?<#endIfSession>/ms", "", $menustr);
        $menustr = preg_replace("/<#ifNoSession>(.*?)<#endIfNoSession>/ms", "\\1", $menustr);
    } else {
        $menustr = preg_replace("/<#ifNoSession>.*?<#endIfNoSession>/ms", "", $menustr);
        $menustr = preg_replace("/<#ifSession>(.*?)<#endIfSession>/ms", "\\1", $menustr);
        if (isset($_SESSION["szerepkor"]) && str_contains($_SESSION["szerepkor"], "ADMIN")) {
            $menustr = preg_replace("/<#ifAdmin>(.*?)<#endIfAdmin>/ms", "\\1", $menustr);
        } else {
            $menustr = preg_replace("/<#ifAdmin>.*?<#endIfAdmin>/ms", "", $menustr);
        }
        if (isset($_SESSION["szerepkor"]) && str_contains($_SESSION["szerepkor"], "SZERZO")) {
            $menustr = preg_replace("/<#ifSzerzo>(.*?)<#endIfSzerzo>/ms", "\\1", $menustr);
        } else {
            $menustr = preg_replace("/<#ifSzerzo>.*?<#endIfSzerzo>/ms", "", $menustr);
        }
    }

    return $menustr;
}