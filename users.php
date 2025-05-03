<?php
include_once('db_functions.php');
include_once('menu.php');
if (!isset($_SESSION['szerepkor']) || !str_contains($_SESSION['szerepkor'], "ADMIN")) {
    header("Location: error.php");
}
echo preg_replace("/<#title>/", "Felhasználók", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <h1 class="mb-4">Függőben lévő felhasználók</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Azonosító</th>
            <th>Előtag</th>
            <th>Név</th>
            <th>Email</th>
            <th>Intézmény</th>
            <th>Művelet</th>
        </tr>
        </thead>
        <tbody>
        <?php
        conference_connect();
        $users_waiting = list_users_waiting();

        while (($row = mysqli_fetch_assoc($users_waiting))!= null) {
            echo '<form method="POST" accept-charset="utf-8">';
            echo '<tr>';
            echo '<td>' . $row["id"] . ' <input readonly hidden name="user_id" value="' . $row["id"] . '"></td>';
            echo '<td>' . $row["azonosito"] . '</td>';
            echo '<td>' . $row["elotag"] . '</td>';
            echo '<td>' . $row["nev"] . '</td>';
            echo '<td>' . $row["email"] . '</td>';
            echo '<td>' . $row["intezmeny"] . '</td>';
            echo '<td>
                    <button type="submit" formaction="approve_user_db.php" class="btn btn-success btn-sm">Jóváhagyás</button>
                    <button type="submit" formaction="decline_user.php" class="btn btn-danger btn-sm">Elutasítás</button>
                  </td>';
            echo '</tr>';
            echo '</form>';
        }
        mysqli_free_result($users_waiting);
        ?>
        </tbody>
    </table>

    <h1 class="mt-5 mb-4">Regisztrált felhasználók</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Azonosító</th>
            <th>Előtag</th>
            <th>Név</th>
            <th>Email</th>
            <th>Intézmény</th>
            <th>Cikkek száma</th>
            <th>Művelet</th>
        </tr>
        </thead>
        <tbody>
        <?php
        conference_connect();
        $users = list_users_with_article_counts();

        while (($row = mysqli_fetch_assoc($users))!= null) {
            $active = $row["status"] == 1;
            echo '<form method="POST" action="change_user_role.php" accept-charset="utf-8">';
            echo '<tr style="color: ' . ($row["status"] == 0 ? 'gray' : 'black') . '">';
            echo '<td>' . $row["id"] . ' <input readonly hidden name="user_id" value="' . $row["id"] . '"></td>';
            echo '<td>' . $row["azonosito"] . '</td>';
            echo '<td>' . $row["elotag"] . '</td>';
            echo '<td>' . $row["nev"] . '</td>';
            echo '<td>' . $row["email"] . '</td>';
            echo '<td>' . $row["intezmeny"] . '</td>';
            echo '<td>' . $row["cikkek_szama"] . '</td>';
            echo '<td>
                  <input name="from" hidden readonly value="users">
                  <input id="is_szerzo" name="is_szerzo" type="checkbox" ' . (isSzerzo($row["id"]) ? "checked" : "") . ' ' . ($row["status"] == 0 || (isSzerzo($row["id"]) && isAdmin($row["id"])) ? "disabled" : "") . ' onchange="this.form.submit()" class="form-check-input">
                    <label for="is_szerzo" class="form-check-label">Szerző</label>
                  <input id="is_admin" name="is_admin" type="checkbox" ' . (isAdmin($row["id"]) ? "checked" : "") . ' ' . ($row["status"] == 0 ? "disabled" : "") . ' onchange="this.form.submit()" class="form-check-input">
                    <label for="is_admin" class="form-check-label">Admin</label>
                  <button type="submit" formaction="' . ($active ? "deactivate_user_db.php" : "activate_user_db.php") . '" class="btn btn-' . ($active ? "danger" : "success") . ' btn-sm" name="change_status">' . ($active ? "Deaktiválás" : "Aktiválás") . '</button>
                  </td>';
            echo '</tr>';
            echo '</form>';
        }
        mysqli_free_result($users);
        ?>
        </tbody>
    </table>
</div>
<?php echo file_get_contents("footer.html"); ?>