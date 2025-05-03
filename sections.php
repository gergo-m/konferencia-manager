<?php
include_once('db_functions.php');
include_once('menu.php');
if (!isset($_SESSION['szerepkor']) || !str_contains($_SESSION['szerepkor'], "ADMIN")) {
    header("Location: error.php");
}
echo preg_replace("/<#title>/", "Szekciók", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <?php if (!isset($_GET['szekcio_nev'])): ?>
    <div class="row">
        <div class="col-md-12">
            <h1>Szekció hozzáadása</h1>
            <form method="POST" action="add_section_db.php" accept-charset="utf-8">
                <div class="mb-3">
                    <label for="szekcio_nev" class="form-label">Név:</label>
                    <input type="text" name="szekcio_nev" id="szekcio_nev" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kezdés:</label>
                    <input type="datetime-local" name="szekcio_kezdes" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Levezető elnök:</label>
                    <select name="levezeto_elnok_id" class="form-select" required>
                        <?php
                        $users = list_users();
                        while ($row = mysqli_fetch_assoc($users)) {
                            echo '<option value="'.$row["id"].'">'.$row["nev"].'</option>';
                        }
                        mysqli_free_result($users);
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Hozzáadás</button>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <h1>Szekció módosítása</h1>
            <form method="POST" action="edit_section_db.php" accept-charset="utf-8">
                <?php
                conference_connect();
                $section = get_section(htmlspecialchars($_GET["szekcio_nev"]));
                ?>
                <input type="text" hidden readonly name="prev_szekcio_nev" value="<?php echo $section["szekcio_nev"]; ?>">
                <div class="mb-3">
                    <label for="szekcio_nev" class="form-label">Név:</label>
                    <input type="text" name="szekcio_nev" id="szekcio_nev" value="<?php echo $section["szekcio_nev"]; ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kezdés:</label>
                    <input type="datetime-local" name="szekcio_kezdes" value="<?php echo $section["szekcio_kezdes"]; ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Levezető elnök:</label>
                    <select name="levezeto_elnok_id" class="form-select" required>
                        <?php
                        $users = list_users();
                        while ($row = mysqli_fetch_assoc($users)) {
                            echo '<option value="'.$row["id"].'"' . ($section["levezeto_elnok_id"] === $row["id"] ? "selected" : "") . '>'.$row["nev"].'</option>';
                        }
                        mysqli_free_result($users);
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Mentés</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="row mt-5">
        <div class="col-md-12">
            <h1>Szekciók</h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Név</th>
                    <th>Kezdés</th>
                    <th>Levezető elnök</th>
                    <th>Előadások száma</th>
                    <th>Művelet</th>
                </tr>
                </thead>
                <tbody>
                <?php
                conference_connect();
                $sections = list_sections();

                while (($row = mysqli_fetch_assoc($sections)) != null) {
                    echo '<form accept-charset="utf-8">';
                    echo '<tr>';
                    echo '<td>' . $row["szekcio_nev"] . ' <input readonly hidden name="szekcio_nev" value="' . $row["szekcio_nev"] . '"></td>';
                    echo '<td>' . $row["szekcio_kezdes"] . '</td>';
                    echo '<td>' . $row["levezeto_elnok"] . '</td>';
                    echo '<td>' . $row["eloadasok_szama"] . '</td>';
                    echo '<td>
                             <button type="submit" formmethod="GET" formaction="sections.php" class="btn btn-warning btn-sm">Módosítás</button>
                             <button type="submit" formmethod="POST" formaction="delete_section.php" class="btn btn-danger btn-sm" onclick="return confirm(\'Biztosan törölni akarja?\')">Törlés</button>
                          </td>';
                    echo '</tr>';
                    echo '</form>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php mysqli_free_result($sections); ?>
</div>
    <script>
        document.querySelector("form").addEventListener("submit", function (event) {
            const titleInput = document.getElementById("szekcio_nev").value.trim();
            if (!titleInput) {
                alert("A név nem lehet üres!");
                event.preventDefault();
            }
        });
    </script>
<?php echo file_get_contents("footer.html"); ?>