<?php
include_once('db_functions.php');
include_once('menu.php');
if (!isset($_SESSION['szerepkor']) || !str_contains($_SESSION['szerepkor'], "SZERZO")) {
    header("Location: error.php");
}
echo preg_replace("/<#title>/", "Cikk feltöltése", file_get_contents("header.html"));
echo menu();

conference_connect();
$authors = list_szerzok();
?>

<div class="container mt-4">
    <h1 class="mb-4">Cikk feltöltése</h1>

    <form method="POST" action="upload_article_db.php" accept-charset="utf-8">
        <!-- Article title -->
        <div class="mb-3">
            <label for="cim" class="form-label">Cím:</label>
            <input type="text" name="cim" id="cim" class="form-control" required>
        </div>

        <!-- Select authors -->
        <div class="mb-3">
            <label for="szerzok" class="form-label">Szerzők:</label>
            <select name="szerzok[]" id="szerzok" class="form-control" multiple required>
                <?php foreach ($authors as $author): ?>
                    <option value="<?php echo htmlspecialchars($author['id']); ?>" <?php echo ($author['id'] == $_SESSION['id'] ? "disabled" : "") ?>>
                        <?php echo htmlspecialchars($author['nev']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Több szerző kiválasztásához tartsa lenyomva a Ctrl (Windows) vagy Command (Mac) billentyűt.</div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary">Feltöltés</button>
    </form>

    <hr>
    <h1 class="mb-4">Az ön cikkei</h1>
    <!-- Articles Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Cím</th>
            <th>Szerzők</th>
            <th>Művelet</th>
        </tr>
        </thead>
        <tbody>
        <?php
        conference_connect();
        $articles = get_articles_by_szerzo_id($_SESSION['id']);

        while (($row = mysqli_fetch_assoc($articles)) != null) {
            echo '<form accept-charset="utf-8">';
            echo '<input name="from" hidden readonly value="upload_article">';
            echo '<tr>';
            echo '<td>' . $row["cikk_id"] . ' <input readonly hidden name="cikk_id" value="' . $row["cikk_id"] . '"></td>';
            echo '<td>' . $row["cikk_cim"] . '</td>';
            echo '<td>' . $row["szerzok"] . '</td>';
            echo '<td>
                      <button type="submit" class="btn btn-danger" formmethod="POST" formaction="delete_article.php" onclick="return confirm(\'Biztosan törölni akarja?\')">Törlés</button>';
            echo '</td>';
            echo '</tr>';
            echo '</form>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php echo file_get_contents("footer.html"); ?>