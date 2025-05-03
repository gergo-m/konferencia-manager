<?php
include_once('db_functions.php');
include_once('menu.php');
if (!isset($_SESSION['szerepkor']) || !str_contains($_SESSION['szerepkor'], "ADMIN")) {
    header("Location: error.php");
}
echo preg_replace("/<#title>/", "Cikkek", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">

    <?php if (isset($_GET['cikk_id'])): ?>
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Cikk módosítása</h1>
            <form method="POST" action="edit_article_db.php" accept-charset="utf-8">
                <?php
                conference_connect();
                $cikk = get_article(htmlspecialchars($_GET["cikk_id"]));
                ?>
                <input readonly hidden type="text" name="id" value="<?php echo $cikk["id"]; ?>">

                <!-- Article title -->
                <div class="mb-3">
                    <label for="cim" class="form-label">Cím:</label>
                    <input type="text" name="cim" id="cim" class="form-control"  value="<?php echo $cikk["cim"]; ?>" required>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Módosítás</button>
            </form>
        </div>
    </div>
    <hr>
    <?php endif; ?>


    <h1 class="mb-4">Feltöltött cikkek</h1>
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
        $articles = list_articles();

        while (($row = mysqli_fetch_assoc($articles)) != null) {
            echo '<form accept-charset="utf-8">';
            echo '<input name="from" hidden readonly value="articles">';
            echo '<tr>';
            echo '<td>' . $row["cikk_id"] . ' <input readonly hidden name="cikk_id" value="' . $row["cikk_id"] . '"></td>';
            echo '<td>' . $row["cikk_cim"] . '</td>';
            echo '<td>' . $row["szerzok"] . '</td>';
            echo '<td>
                      <button type="submit" class="btn btn-warning" formmethod="GET" formaction="articles.php">Módosítás</button>
                      <button type="submit" class="btn btn-danger" formmethod="POST" formaction="delete_article.php" onclick="return confirm(\'Biztosan törölni akarja?\')">Törlés</button>';
            if (is_article_lectureless($row["cikk_id"])) {
                echo '<button type="submit" class="btn btn-success" formmethod="GET" formaction="lectures.php">Előadáshoz rendelés</button>';
            }
            echo '</td>';
            echo '</tr>';
            echo '</form>';
        }
        ?>
        </tbody>
    </table>

    <!-- Free Result -->
    <?php mysqli_free_result($articles); ?>
</div>
<script>
    document.querySelector("form").addEventListener("submit", function (event) {
        const titleInput = document.getElementById("cim").value.trim();
        if (!titleInput) {
            alert("A cím nem lehet üres!");
            event.preventDefault();
        }
    });
</script>
<?php echo file_get_contents("footer.html"); ?>