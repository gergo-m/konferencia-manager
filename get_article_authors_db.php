<?php
include_once('db_functions.php');

if (isset($_GET['article_id'])) {
    conference_connect();
    $article_id = htmlspecialchars($_GET['article_id']);
    $szerzok = get_cikk_szerzok($article_id);

    $authors = [];
    while ($row = mysqli_fetch_assoc($szerzok)) {
        $authors[] = ['id' => $row["id"], 'nev' => $row["nev"]];
    }
    mysqli_free_result($szerzok);
    echo json_encode($authors);
}
?>
