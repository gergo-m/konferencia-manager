<?php
include_once('db_functions.php');
include_once('menu.php');
if (isset($_SESSION['szerepkor'])) {
    header("Location: conference_program.php");
}
echo preg_replace("/<#title>/", "Bejelentkezés", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <?php if ($error = $_GET["error"] ?? null): ?>
        <div class="alert alert-danger alert-dismissable fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($msg = $_GET["msg"] ?? null): ?>
        <div class="alert alert-success alert-dismissable fade show" role="alert">
            <?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <h1 class="mb-4">Bejelentkezés</h1>
    <form method="POST" action="login_user.php" accept-charset="utf-8" class="form-group needs-validation" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email cím:</label>
            <input type="email" name="email" id="email" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adjon meg egy érvényes email címet!
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

<script>
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');

        forms.forEach(function (form) {
            const inputs = form.querySelectorAll('input, textarea');

            // Iterate over all input fields
            inputs.forEach(function (input) {
                //if (input.type === "password") {
                input.addEventListener('input', function () {
                    validateInput(input);
                });

                input.addEventListener('blur', function () {
                    validateInput(input);
                });
                //}
            });

            form.addEventListener('submit', function (event) {
                let isValid = true;

                inputs.forEach(function (input) {
                    validateInput(input);
                    if (!input.checkValidity()) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });

        function validateInput(input) {
            const value = input.value.trim();
            let pattern = /^[\p{L}\p{M}\s.'_-]+$/u;
            if (input.id === "jelszo" || input.id === "email") {
                pattern = /^[\p{L}\p{M}\d\s!@#$%^&*()_+[\]{};:'",.<>?\\|`~\-\/=]+$/u;
            }

            if (input.id === "elotag") {
                input.setCustomValidity('');
                return;
            }

            if (input.required && value === '') {
                input.setCustomValidity('Kérem, töltse ki ezt a mezőt!');
            } else if (!pattern.test(value)) {
                input.setCustomValidity('Érvénytelen karakter található a szövegben.');
            } else if (input.id === 'jelszo' && value.length < 8) {
                input.setCustomValidity('A jelszónak legalább 8 karakter hosszúnak kell lennie.');
            } else {
                input.setCustomValidity('');
            }

            // input.reportValidity(); // Show real-time error feedback
        }
    })();
</script>
<?php echo file_get_contents("footer.html"); ?>