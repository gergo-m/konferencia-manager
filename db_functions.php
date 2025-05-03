<?php
function conference_connect() {

    // for online hosting
    /*$conn = mysqli_connect("sql104.infinityfree.com", "if0_37823918", "7gVaVyDLPY") or die("Connection error");
    if (!mysqli_select_db($conn, "if0_37823918_konferencia")) {
        return null;
    }*/

    // for localhost with phpmyadmin and xampp
    $conn = mysqli_connect("localhost", "root", "") or die("Connection error");
    if (!mysqli_select_db($conn, "konferencia")) {
        return null;
    }

    mysqli_query($conn, "SET NAMES UTF8");
    mysqli_query($conn, "SET character_set_results=utf8");
    mysqli_set_charset($conn, "UTF8");

    return $conn;
}

function insert_user($azonosito, $elotag, $nev, $email, $jelszo, $intezmeny) {
    if (!$conn = conference_connect()) {
        return false;
    }

    if (exists_user_with_azonosito($azonosito)) {
        header("Location: register.php?error=Ezzel az azonosítóval már van regisztrálva fiók.");
        exit();
    }

    if (exists_user_with_email($email)) {
        header("Location: login.php?error=Ezzel az email címmel már van regisztrálva fiók. Jelentkezzen be.");
        exit();
    }

    // prepare query
    $stmt = mysqli_prepare($conn, "INSERT INTO Felhasznalo (azonosito, elotag, nev, email, jelszo, intezmeny) VALUES (?, ?, ?, ?, ?, ?)");

    // bind parameters for safety
    mysqli_stmt_bind_param($stmt, "ssssss", $azonosito, $elotag, $nev, $email, $jelszo, $intezmeny);

    // run sql query
    $success = mysqli_stmt_execute($stmt); // returns a boolean
    if (!$success)
        die(mysqli_error($conn));
    mysqli_close($conn);
    return $success;
}

function approve_user($id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    // INSERT INTO Szerzo TABLE
    $stmt = mysqli_prepare($conn, "INSERT INTO Szerzo (id) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    $active_status = 1;
    $stmt = mysqli_prepare($conn, "UPDATE Felhasznalo SET status=? WHERE id='$id'");
    mysqli_stmt_bind_param($stmt, "i", $active_status);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function decline_user($id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM Felhasznalo WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function deactivate_user($id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $deactivated_status = 0;
    $stmt = mysqli_prepare($conn, "UPDATE Felhasznalo SET status=? WHERE id='$id'");
    mysqli_stmt_bind_param($stmt, "i", $deactivated_status);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function activate_user($id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $deactivated_status = 1;
    $stmt = mysqli_prepare($conn, "UPDATE Felhasznalo SET status=? WHERE id='$id'");
    mysqli_stmt_bind_param($stmt, "i", $deactivated_status);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function delete_article($id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM Eloadas WHERE cikk_id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    $stmt = mysqli_prepare($conn, "DELETE FROM Szerzoje WHERE cikk_id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    $stmt = mysqli_prepare($conn, "DELETE FROM Cikk WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function delete_lecture($id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM Eloadas WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function delete_section($nev) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM Eloadas WHERE szekcio_nev=?");
    mysqli_stmt_bind_param($stmt, "s", $nev);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    $stmt = mysqli_prepare($conn, "DELETE FROM Szekcio WHERE nev=?");
    mysqli_stmt_bind_param($stmt, "s", $nev);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function get_user($email) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $sql = "SELECT * FROM Felhasznalo WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        return $result->fetch_assoc();
    } else {
        // die("Error, zero or multiple users found");
        header("Location: login.php?error=Nem található felhasználó ezzel az e-mail címmel, kérjük regisztráljon.");
        exit();
    }
}

function exists_user_with_email($email) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $sql = "SELECT * FROM Felhasznalo WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    return mysqli_num_rows($result) === 1;
}

function exists_user_with_azonosito($azonosito) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $sql = "SELECT * FROM Felhasznalo WHERE azonosito = '$azonosito'";
    $result = mysqli_query($conn, $sql);

    return mysqli_num_rows($result) === 1;
}

function insert_article($cim, $szerzok) {
    session_start();

    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO Cikk (cim) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $cim);
    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    $sql_get_article_id = "SELECT MAX(id) AS id FROM Cikk WHERE cim = '$cim'";
    $result = mysqli_query($conn, $sql_get_article_id);
    if (mysqli_num_rows($result) === 1) {
        $cikk_id = mysqli_fetch_row($result)[0];
    } else {
        die("Error, article ID not found");
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO Szerzoje (szerzo_id, cikk_id) VALUES (?, ?)");
    foreach ($szerzok as $szerzo_id) {
        mysqli_stmt_bind_param($stmt, "ii", $szerzo_id, $cikk_id);
        $success = mysqli_stmt_execute($stmt);
        if (!$success)
            die(mysqli_error($conn));
    }

    mysqli_close($conn);
    return $success;
}

function insert_eloadas($cikk_id, $szekcio, $kezdes, $hossz, $eloado) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO Eloadas (szekcio_nev, kezdes, hossz, eloado_id, cikk_id) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssii", $szekcio, $kezdes, $hossz, $eloado, $cikk_id);

    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function insert_section($nev, $kezdes, $levezeto_elnok_id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO Szekcio (nev, kezdes, levezeto_elnok_id) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssi", $nev, $kezdes, $levezeto_elnok_id);

    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function is_article_lectureless($cikk_id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Cikk.id
            FROM Cikk
            WHERE Cikk.id = '$cikk_id' AND Cikk.id NOT IN (
                SELECT Eloadas.cikk_id
                FROM Eloadas)";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 1) {
        return true;
    } else {
        return false;
    }
}

function get_articles_by_szerzo_id($id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Cikk.id AS cikk_id, Cikk.cim AS cikk_cim, GROUP_CONCAT(TRIM(CONCAT(COALESCE(elotag, ''), ' ', nev)) ORDER BY nev SEPARATOR ', ') AS szerzok
            FROM Cikk
            LEFT JOIN Szerzoje ON Cikk.id = Szerzoje.cikk_id
            LEFT JOIN Felhasznalo ON Szerzoje.szerzo_id = Felhasznalo.id
            WHERE Cikk.id IN (SELECT Cikk.id
                              FROM Cikk
                              LEFT JOIN Szerzoje ON Cikk.id = Szerzoje.cikk_id
                              WHERE Szerzoje.szerzo_id = '$id')
            GROUP BY Cikk.id, Cikk.cim";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_articles() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Cikk.id AS cikk_id, Cikk.cim AS cikk_cim, GROUP_CONCAT(TRIM(CONCAT(COALESCE(elotag, ''), ' ', nev)) ORDER BY Felhasznalo.nev SEPARATOR ', ') AS szerzok
            FROM Cikk
            LEFT JOIN Szerzoje ON Cikk.id = Szerzoje.cikk_id
            LEFT JOIN Felhasznalo ON Szerzoje.szerzo_id = Felhasznalo.id
            GROUP BY Cikk.id, Cikk.cim";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_articles_without_lecture() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Cikk.id, Cikk.cim
            FROM Cikk
            WHERE Cikk.id NOT IN (
                SELECT Eloadas.cikk_id
                FROM Eloadas)";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_sections() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Szekcio.nev AS szekcio_nev, Szekcio.kezdes AS szekcio_kezdes, TRIM(CONCAT(COALESCE(Felhasznalo.elotag, ''), ' ', Felhasznalo.nev)) AS levezeto_elnok, COUNT(Eloadas.id) AS eloadasok_szama
            FROM Szekcio
            INNER JOIN Felhasznalo ON Szekcio.levezeto_elnok_id = Felhasznalo.id
            LEFT JOIN Eloadas ON Szekcio.nev = Eloadas.szekcio_nev
            GROUP BY Szekcio.nev
            ORDER BY Szekcio.kezdes";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_szerzok() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Szerzo.id, TRIM(CONCAT(COALESCE(elotag, ''), ' ', nev)) AS nev FROM Felhasznalo, Szerzo WHERE Felhasznalo.id = Szerzo.id ORDER BY Felhasznalo.nev";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function get_cikk_szerzok($cikk_id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Szerzo.id AS id, TRIM(CONCAT(COALESCE(elotag, ''), ' ', Felhasznalo.nev)) AS nev
            FROM Szerzo
            LEFT JOIN Szerzoje ON Szerzo.id = Szerzoje.szerzo_id
            LEFT JOIN Felhasznalo ON Szerzoje.szerzo_id = Felhasznalo.id
            WHERE Szerzoje.cikk_id = '$cikk_id'";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_eloadasok() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Eloadas.id, Eloadas.szekcio_nev, Eloadas.kezdes, Eloadas.hossz, Felhasznalo.nev, Cikk.cim FROM Felhasznalo, Szerzo, Eloadas, Cikk WHERE Felhasznalo.id = Szerzo.id AND Szerzo.id = Eloadas.eloado_id AND Cikk.id = Eloadas.cikk_id ORDER BY Eloadas.szekcio_nev, Eloadas.kezdes";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_conference_program() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Eloadas.id AS eloadas_id,
                   Szekcio.nev AS szekcio_nev,
                   Szekcio.kezdes AS szekcio_kezdes,
                   Cikk.cim AS cikk_cim,
                   Eloadas.kezdes AS eloadas_kezdes,
                   Eloadas.hossz AS eloadas_hossz,
                   TRIM(CONCAT(COALESCE(Eloado.elotag, ''), ' ', Eloado.nev)) AS eloado,
                   TRIM(CONCAT(COALESCE(Levezeto.elotag, ''), ' ', Levezeto.nev)) AS levezeto
            FROM Eloadas
            INNER JOIN Szekcio ON Eloadas.szekcio_nev = Szekcio.nev
            INNER JOIN Felhasznalo AS Eloado ON Eloadas.eloado_id = Eloado.id
            INNER JOIN Felhasznalo AS Levezeto ON Szekcio.levezeto_elnok_id = Levezeto.id
            INNER JOIN Szerzo ON Eloado.id = Szerzo.id
            INNER JOIN Cikk ON Eloadas.cikk_id = Cikk.id
            ORDER BY szekcio_kezdes, eloadas_kezdes";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function get_article($id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Cikk.id AS id,
                   Cikk.cim AS cim
            FROM Cikk
            WHERE Cikk.id = '$id'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 1) {
        return $result->fetch_assoc();
    } else {
        die("Error, article not found");
    }
}

function get_lecture($eloadas_id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Eloadas.id AS eloadas_id,
                   Cikk.id AS cikk_id,
                   Cikk.cim AS cikk_cim,
                   Szekcio.nev AS szekcio_nev,
                   Eloadas.kezdes AS eloadas_kezdes,
                   Eloadas.hossz AS eloadas_hossz,
                   Felhasznalo.nev AS eloado
            FROM Eloadas
            INNER JOIN Szekcio ON Eloadas.szekcio_nev = Szekcio.nev
            INNER JOIN Cikk ON Eloadas.cikk_id = Cikk.id
            INNER JOIN Szerzo ON Eloadas.eloado_id = Szerzo.id
            INNER JOIN Felhasznalo ON Szerzo.id = Felhasznalo.id
            WHERE Eloadas.id = '$eloadas_id'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 1) {
        return $result->fetch_assoc();
    } else {
        die("Error, lecture not found");
    }
}

function get_section($szekcio_nev) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT Szekcio.nev AS szekcio_nev,
                   Szekcio.kezdes AS szekcio_kezdes,
                   Szekcio.levezeto_elnok_id AS levezeto_elnok_id
            FROM Szekcio
            WHERE Szekcio.nev = '$szekcio_nev'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 1) {
        return $result->fetch_assoc();
    } else {
        die("Error, section not found");
    }
}

function update_user($id, $nev, $email, $intezmeny, $elotag) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "UPDATE Felhasznalo SET nev=?, email=?, intezmeny=?, elotag=? WHERE id='$id'");
    mysqli_stmt_bind_param($stmt, "ssss", $nev, $email, $intezmeny, $elotag);

    $success = mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function update_article($id, $cim) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "UPDATE Cikk SET cim=? WHERE id='$id'");
    mysqli_stmt_bind_param($stmt, "s", $cim);

    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function update_lecture($eloadas_id, $szekcio, $kezdes, $hossz, $eloado) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "UPDATE Eloadas SET szekcio_nev=?, kezdes=?, hossz=?, eloado_id=? WHERE id='$eloadas_id'");
    mysqli_stmt_bind_param($stmt, "sssi", $szekcio, $kezdes, $hossz, $eloado);

    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function update_section($prev_szekcio_nev, $nev, $kezdes, $levezeto_elnok_id) {
    if (!$conn = conference_connect()) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "UPDATE Szekcio SET nev=?, kezdes=?, levezeto_elnok_id=? WHERE nev='$prev_szekcio_nev'");
    mysqli_stmt_bind_param($stmt, "ssi", $nev, $kezdes, $levezeto_elnok_id);

    $success = mysqli_stmt_execute($stmt);
    if (!$success)
        die(mysqli_error($conn));

    mysqli_close($conn);
    return $success;
}

function list_users_waiting() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT id, azonosito, elotag, nev, email, intezmeny, status
            FROM Felhasznalo
            WHERE Felhasznalo.status = 2";

    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_users() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT id, azonosito, TRIM(CONCAT(COALESCE(elotag, ''), ' ', nev)) AS nev, email, intezmeny, status
            FROM Felhasznalo
            WHERE Felhasznalo.status != 2";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function list_users_with_article_counts() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT id, azonosito, elotag, nev, email, intezmeny, status, COUNT(Szerzoje.cikk_id) AS cikkek_szama
            FROM Felhasznalo
            LEFT JOIN Szerzoje ON Felhasznalo.id = Szerzoje.szerzo_id
            WHERE Felhasznalo.status != 2
            GROUP BY Felhasznalo.id";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    return $result;
}

function isSzerzo($id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT id
            FROM Felhasznalo
            WHERE id = '$id' AND id IN (
                SELECT id FROM Szerzo)";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    if (mysqli_num_rows($result) === 1) {
        return true;
    } else if (mysqli_num_rows($result) === 0) {
        return false;
    } else {
        die("Error: multiple Szerzo found with the same ID");
    }
}

function isAdmin($id) {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sql = "SELECT id
            FROM Felhasznalo
            WHERE id = '$id' AND id IN (
                SELECT id FROM Adminisztrator)";
    $result = mysqli_query($conn, $sql) or die ('Invalid query');

    mysqli_close($conn);
    if (mysqli_num_rows($result) === 1) {
        return true;
    } else if (mysqli_num_rows($result) === 0) {
        return false;
    } else {
        die("Error: multiple Adminisztrator found with the same ID");
    }
}

function change_user_role($id, $is_szerzo, $is_admin) {
    if (!($conn = conference_connect())) {
        return false;
    }

    if (isSzerzo($id) && !isAdmin($id) && !$is_szerzo && !$is_admin) {
        return deactivate_user($id);

    }  else if (!isSzerzo($id) && $is_szerzo) {
        return approve_user($id);

    } else if (isAdmin($id) && !$is_admin) {
        return deactivate_user($id);

    } else if (!isAdmin($id) && $is_admin) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Adminisztrator (id) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        if (!$success)
            die(mysqli_error($conn));

        mysqli_close($conn);
        return $success;
    }

    return false;
}

function get_sections_assoc() {
    if (!($conn = conference_connect())) {
        return false;
    }

    $sections = [];
    $sql = "SELECT Eloadas.id AS eloadas_id, Szekcio.nev AS szekcio_nev, TIME(Szekcio.kezdes) AS szekcio_kezdes, TIME(Eloadas.kezdes) AS eloadas_kezdes, Eloadas.hossz AS eloadas_hossz
            FROM Szekcio
            LEFT JOIN Eloadas ON Szekcio.nev = Eloadas.szekcio_nev
            ORDER BY Szekcio.nev, Eloadas.kezdes";
    $result = mysqli_query($conn, $sql) or die("Invalid query");

    while ($row = mysqli_fetch_assoc($result)) {
        if (!isset($sections[$row['szekcio_nev']])) {
            $sections[$row['szekcio_nev']] = ['start' => $row['szekcio_kezdes'], 'lectures' => []];
        }
        if ($row['eloadas_kezdes']) {
            $sections[$row['szekcio_nev']]['lectures'][] = ['eloadas_id' => $row['eloadas_id'], 'kezdes' => $row['eloadas_kezdes'], 'hossz' => $row['eloadas_hossz']];
        }
    }

    mysqli_free_result($result);

    return $sections;
}