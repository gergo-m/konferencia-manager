<?php
include_once('db_functions.php');
include_once('menu.php');
if (isset($_SESSION['szerepkor'])) {
    header("Location: conference_program.php");
}
echo preg_replace("/<#title>/", "Bejelentkezés", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <h1 class="mb-4">Bejelentkezés</h1>
    <form method="POST" action="login_user.php" accept-charset="utf-8" class="form-group needs-validation" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email cím:</label>
            <input type="text" name="email" id="email" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adja meg az email címét!
            </div>
        </div>

        <div class="mb-3">
            <label for="jelszo" class="form-label">Jelszó:</label>
            <input type="password" name="jelszo" id="jelszo" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adja meg a jelszavát!
            </div>
        </div>

        <div class="mb-3">
            <input type="submit" value="Belépés" class="btn btn-primary">
        </div>
    </form>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bootstrap validation script
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<?php echo file_get_contents("footer.html"); ?>
