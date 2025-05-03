<?php
include_once('db_functions.php');
include_once('menu.php');
if (!isset($_SESSION['szerepkor']) || !str_contains($_SESSION['szerepkor'], "ADMIN")) {
    header("Location: error.php");
}
echo preg_replace("/<#title>/", "Előadások", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <?php if (!isset($_GET['eloadas_id'])): ?>
        <div class="row">
            <div class="col-md-12">
                <h1>Előadás hozzáadása</h1>
                <form method="POST" action="add_lecture_db.php" accept-charset="utf-8">
                    <div class="mb-3">
                        <label for="articleSelectOnCreate" class="form-label">Cikk címe:</label>
                        <select name="cikk_id" id="articleSelectOnCreate" class="form-select" required>
                            <?php if (!(isset($_GET["cikk_id"]))):
                                echo '<option value="noselect" selected disabled>Válasszon ki egy cikket...</option>';
                            endif; ?>
                            <?php
                            conference_connect();
                            $lectureless_articles = list_articles_without_lecture();
                            while ($row = mysqli_fetch_assoc($lectureless_articles)) {
                                echo '<option value="'.$row["id"].'"' . (isset($_GET["cikk_id"]) && $_GET["cikk_id"] == $row["id"] ? "selected" : "") . '>'.$row["cim"].'</option>';
                            }
                            mysqli_free_result($lectureless_articles);
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sectionSelect" class="form-label">Szekció:</label>
                        <select name="szekcio" id="sectionSelect" class="form-select" required>
                            <option value="noselect" selected disabled>Válasszok ki egy szekciót...</option>
                            <?php
                            conference_connect();
                            $szekciok = list_sections();
                            while ($row = mysqli_fetch_assoc($szekciok)) {
                                echo '<option value="'.$row["szekcio_nev"].'">'.$row["szekcio_nev"].'</option>';
                            }
                            mysqli_free_result($szekciok);
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="startTime" class="form-label">Kezdés:</label>
                        <select id="startTime" name="kezdes" class="form-select" required>
                            <?php
                            for ($hour = 0; $hour <= 23; $hour++) {
                                $hour = sprintf("%02d", $hour);
                                for ($minute = 0; $minute < 60; $minute += 5) {
                                    $minute = sprintf("%02d", $minute);
                                    echo '<option value="'.$hour.':'.$minute.'">'.$hour.':'.$minute.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="durationTime" class="form-label">Hossz:</label>
                        <select id="durationTime" name="hossz" class="form-select" required>
                            <?php
                            for ($hour = 0; $hour <= 3; $hour++) {
                                $hour = sprintf("%02d", $hour);
                                for ($minute = 0; $minute < 60; $minute += 15) {
                                    if ($hour == 0 && $minute == 0)
                                        continue;
                                    $minute = sprintf("%02d", $minute);
                                    echo '<option value="'.$hour.':'.$minute.'">'.$hour.':'.$minute.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="authorSelectOnCreate" class="form-label">Előadó:</label>
                        <select id="authorSelectOnCreate" name="eloado" class="form-select" required>
                            <?php
                            if (!isset($_GET["cikk_id"])) {
                                $szerzok = list_szerzok();
                            } else {
                                $szerzok = get_cikk_szerzok(htmlspecialchars($_GET["cikk_id"]));
                            }
                            while ($row = mysqli_fetch_assoc($szerzok)) {
                                echo '<option value="'.$row["id"].'">'.$row["nev"].'</option>';
                            }
                            mysqli_free_result($szerzok);
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
                <h1>Előadás módosítása</h1>
                <form method="POST" action="edit_lecture_db.php" accept-charset="utf-8">
                    <?php
                    conference_connect();
                    $eloadas = get_lecture(htmlspecialchars($_GET["eloadas_id"]));
                    ?>
                    <input readonly hidden type="text" name="eloadas_id" value="<?php echo $eloadas["eloadas_id"]; ?>" required>

                    <div class="mb-3">
                        <label class="form-label">Cikk címe:</label>
                        <input readonly type="text" class="form-control" name="cim" value="<?php echo $eloadas["cikk_cim"]; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="sectionSelect" class="form-label">Szekció:</label>
                        <select name="szekcio" id="sectionSelect" class="form-select" required>
                            <?php
                            $szekciok = list_sections();
                            while ($row = mysqli_fetch_assoc($szekciok)) {
                                echo '<option value="'.$row["szekcio_nev"].'"' . ($eloadas["szekcio_nev"] == $row["szekcio_nev"] ? "selected" : "") . '>'.$row["szekcio_nev"].'</option>';
                            }
                            mysqli_free_result($szekciok);
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="startTime" class="form-label">Kezdés:</label>
                        <select id="startTime" name="kezdes" class="form-select" required>
                            <?php
                            $_SESSION["editing_eloadas_kezdes"] = $eloadas["eloadas_kezdes"];
                            for ($hour = 0; $hour <= 23; $hour++) {
                                $hour = sprintf("%02d", $hour);
                                for ($minute = 0; $minute < 60; $minute += 5) {
                                    $minute = sprintf("%02d", $minute);
                                    echo '<option value="'.$hour.':'.$minute.'"' . (substr($eloadas["eloadas_kezdes"], 0, 5) == $hour.':'.$minute ? "selected" : "") . '>'.$hour.':'.$minute.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="durationTime" class="form-label">Hossz:</label>
                        <select id="durationTime" name="hossz" class="form-select" required>
                            <?php
                            $_SESSION["editing_eloadas_hossz"] = $eloadas["eloadas_hossz"];
                            for ($hour = 0; $hour <= 3; $hour++) {
                                $hour = sprintf("%02d", $hour);
                                for ($minute = 0; $minute < 60; $minute += 15) {
                                    if ($hour == 0 && $minute == 0)
                                        continue;
                                    $minute = sprintf("%02d", $minute);
                                    echo '<option value="'.$hour.':'.$minute.'"' . (substr($eloadas["eloadas_hossz"], 0, 5) == $hour.':'.$minute ? "selected" : "") . '>'.$hour.':'.$minute.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="eloado" class="form-label">Előadó:</label>
                        <select name="eloado" class="form-select" required>
                            <?php
                            conference_connect();
                            $szerzok = get_cikk_szerzok(htmlspecialchars($eloadas["cikk_id"]));
                            while ($row = mysqli_fetch_assoc($szerzok)) {
                                echo '<option value="'.$row["id"].'"' . ($eloadas["eloado"] == $row["nev"] ? "selected" : "") . '>'.$row["nev"].'</option>';
                            }
                            mysqli_free_result($szerzok);
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Módosít</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="row mt-5">
        <div class="col-md-12">
            <h1>Előadások</h1>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>Szekció</th>
                        <th>Szekció kezdete</th>
                        <th>Előadás ID</th>
                        <th>Előadás (cikk)</th>
                        <th>Kezdés</th>
                        <th>Hossz</th>
                        <th>Előadó</th>
                        <th>Levezető elnök</th>
                        <th>Művelet</th>
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

                        echo '<form accept-charset="utf-8">';
                        echo '<tr' . ($isNewSection && $currentSection !== '' ? ' class="new-section"' : '') . '>';

                        if ($isNewSection) {
                            echo '<td>' . htmlspecialchars($row["szekcio_nev"]) . '</td>';
                            echo '<td>' . htmlspecialchars($row["szekcio_kezdes"]) . '</td>';
                            $currentSection = $row["szekcio_nev"];
                            $currentSectionStart = $row["szekcio_kezdes"];
                        } else {
                            echo '<td></td><td></td>';
                        }

                        echo '<td>' . htmlspecialchars($row["eloadas_id"]) . '<input readonly hidden name="eloadas_id" value="' . $row["eloadas_id"] . '">' . '</td>';
                        echo '<td>' . htmlspecialchars($row["cikk_cim"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["eloadas_kezdes"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["eloadas_hossz"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["eloado"]) . '</td>';
                        if ($isNewSection) {
                            echo '<td>' . htmlspecialchars($row["levezeto"]) . '</td>';
                        } else {
                            echo '<td></td>';
                        }
                        echo '<td>
                              <button type="submit" formmethod="GET" formaction="lectures.php" class="btn btn-warning">Módosítás</button>
                              <button type="submit" formmethod="POST" formaction="delete_lecture.php" class="btn btn-danger" onclick="return confirm(\'Biztosan törölni akarja?\')">Törlés</button>
                              </td>';
                        echo '</tr>';
                        echo '</form>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php mysqli_free_result($program); ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>


    <?php $sections = get_sections_assoc(); ?>
    const sectionData = <?php echo json_encode($sections, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    document.addEventListener("DOMContentLoaded", function() {

        <?php if (!isset($_GET['eloadas_id'])): ?>
        const articleSelect = document.getElementById("articleSelectOnCreate");
        const authorSelect = document.getElementById("authorSelectOnCreate");
        <?php endif; ?>
        const sectionSelect = document.getElementById("sectionSelect");
        const startTimeInput = document.getElementById("startTime");
        const durationTimeInput = document.getElementById("durationTime");
        const orig_start_options = Array.from(document.getElementById("startTime").options);
        const orig_duration_options = Array.from(document.getElementById("durationTime").options);
        <?php if (isset($_GET['eloadas_id'])): ?>
        const orig_start_time = document.getElementById("startTime").value.toString().valueOf();
        const orig_duration = document.getElementById("durationTime").value.toString();
        <?php endif; ?>

        setStartTimes();
        setDurations();

        <?php if (!isset($_GET['eloadas_id'])): ?>
        articleSelect.addEventListener("change", function () {
            setAuthors();
        });
        <?php endif; ?>

        sectionSelect.addEventListener("change", function() {
            setStartTimes();
            setDurations();
        });

        startTimeInput.addEventListener("change", function() {
            setDurations();
        });

        function setAuthors() {
            const selectedArticle = articleSelect.value;

            // Prevent unnecessary requests if no article is selected
            if (selectedArticle === "noselect") return;

            fetch(`get_article_authors_db.php?article_id=${encodeURIComponent(selectedArticle)}`)
                .then(response => response.json())
                .then(authors => {
                    authorSelect.innerHTML = "";

                    if (authors.length > 0) {
                        // Add new options dynamically
                        authors.forEach(author => {
                            const option = document.createElement("option");
                            option.value = author.id;
                            option.textContent = author.nev;
                            authorSelect.appendChild(option);
                        });
                    } else {
                        // Optionally add a message if no authors found
                        const option = document.createElement("option");
                        option.textContent = "No authors available";
                        authorSelect.appendChild(option);
                    }
                })
                .catch(error => console.error("Error fetching authors:", error));
        }

        function setStartTimes() {
            const selectedSection = sectionSelect.value;
            const sectionInfo = sectionData[selectedSection];

            startTimeInput.innerHTML = "";
            orig_start_options.forEach(option => {
                startTimeInput.add(new Option(option.text, option.value));
            });

            if (sectionInfo) {
                const sectionStartTime = sectionInfo['start'].substring(0, 5);
                const lectures = sectionInfo['lectures'];

                const filteredOptions = Array.from(startTimeInput.options).filter(option => {
                    const conflict = lectures.some(lecture => {
                        <?php if (isset($_GET['eloadas_id'])): ?>
                        if (lecture['kezdes'].substring(0, 5) === orig_start_time) {
                            return;
                        }
                        <?php endif; ?>
                        const end = calculateEndTime(lecture['kezdes'], lecture['hossz']);
                        return option.value >= lecture['kezdes'].substring(0, 5) && option.value < end;
                    });
                    return option.value >= sectionStartTime && !conflict;
                });

                startTimeInput.innerHTML = ''; // Clear again to add only filtered
                filteredOptions.forEach(option => {
                    <?php if (isset($_SESSION["editing_eloadas_kezdes"])): ?>
                    const editingEloadasKezdes = "<?php echo $_SESSION["editing_eloadas_kezdes"]; ?>";
                    startTimeInput.add(new Option(option.text, option.value, false, (editingEloadasKezdes.substring(0, 5) === option.value)));
                    <?php else: ?>
                    startTimeInput.add(new Option(option.text, option.value));
                    <?php endif; ?>
                });
            }
        }

        function setDurations() {
            const selectedSection = sectionSelect.value;
            const sectionInfo = sectionData[selectedSection];
            const selectedStartTime = startTimeInput.value;

            durationTimeInput.innerHTML = "";
            orig_duration_options.forEach(option => {
                durationTimeInput.add(new Option(option.text, option.value));
            });

            if (sectionInfo) {
                const lectures = sectionInfo['lectures'];
                const newOptions = [];

                Array.from(durationTimeInput.options).forEach(option => {
                    const selectedEndTime = calculateEndTime(selectedStartTime, option.value);
                    const isConflicting = lectures.some(lecture => {
                        const lectureStart = lecture['kezdes'].substring(0, 5);
                        const lectureEnd = calculateEndTime(lecture['kezdes'], lecture['hossz']);

                        <?php if (isset($_GET['eloadas_id'])): ?>
                        // Skip conflict check for the lecture being edited (allow its original time)
                        if (lecture['eloadas_id'] === '<?php echo $_GET["eloadas_id"]; ?>') {
                            return false;
                        }
                        <?php endif; ?>

                        // Check if the new time slot overlaps with any existing lecture
                        return !(selectedEndTime <= lectureStart || selectedStartTime >= lectureEnd);
                    });

                    // If no conflict, add the option
                    if (!isConflicting) {
                        <?php if (isset($_SESSION["editing_eloadas_hossz"])): ?>
                        const editingEloadasHossz = "<?php echo $_SESSION["editing_eloadas_hossz"]; ?>";
                        newOptions.push(new Option(option.text, option.value, false, (editingEloadasHossz.substring(0, 5) === option.value)));
                        <?php else: ?>
                        newOptions.push(new Option(option.text, option.value));
                        <?php endif; ?>
                    }
                });

                durationTimeInput.innerHTML = ''; // Clear existing options
                newOptions.forEach(option => durationTimeInput.add(option)); // Add filtered options
            }
        }


        function calculateEndTime(startTime, duration) {
            const [startHours, startMinutes] = startTime.split(':').map(Number);
            const [durationHours, durationMinutes] = duration.split(':').map(Number);
            const endHours = startHours + durationHours + Math.floor((startMinutes + durationMinutes) / 60);
            const endMinutes = (startMinutes + durationMinutes) % 60;
            return `${String(endHours).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')}`;
        }
    });
</script>
<?php echo file_get_contents("footer.html"); ?>