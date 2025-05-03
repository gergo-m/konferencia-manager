<?php
include_once('db_functions.php');
include_once('menu.php');
echo preg_replace("/<#title>/", "Konferencia", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <h1 class="mb-4 text-center">A konferencia programjai</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Szekció</th>
                <th>Szekció kezdete</th>
                <th>Előadás (cikk)</th>
                <th>Kezdés</th>
                <th>Hossz</th>
                <th>Előadó</th>
                <th>Levezető elnök</th>
            </tr>
            </thead>
            <tbody>
            <?php
            conference_connect();
            $program = list_conference_program();

            $currentSection = '';
            $currentSectionStart = '';

            while (($row = mysqli_fetch_assoc($program)) != null) {
                $isNewSection = $row["szekcio_nev"] !== $currentSection || $row["szekcio_kezdes"] !== $currentSectionStart;

                echo '<tr' . ($isNewSection && $currentSection !== '' ? ' class="new-section"' : '') . '>';

                if ($isNewSection) {
                    echo '<td>' . htmlspecialchars($row["szekcio_nev"]) . '</td>';
                    echo '<td>' . htmlspecialchars($row["szekcio_kezdes"]) . '</td>';
                    $currentSection = $row["szekcio_nev"];
                    $currentSectionStart = $row["szekcio_kezdes"];
                } else {
                    echo '<td></td><td></td>';
                }

                echo '<td>' . htmlspecialchars($row["cikk_cim"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["eloadas_kezdes"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["eloadas_hossz"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["eloado"]) . '</td>';

                if ($isNewSection) {
                    echo '<td>' . htmlspecialchars($row["levezeto"]) . '</td>';
                } else {
                    echo '<td></td>';
                }
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php mysqli_free_result($program); ?>
<?php echo file_get_contents("footer.html"); ?>