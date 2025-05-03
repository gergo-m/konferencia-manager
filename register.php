<?php
include_once('db_functions.php');
include_once('menu.php');
if (isset($_SESSION['szerepkor'])) {
    header("Location: conference_program.php");
}
echo preg_replace("/<#title>/", "Regisztráció", file_get_contents("header.html"));
echo menu(); ?>

<div class="container mt-4">
    <?php if ($error = $_GET["error"] ?? null): ?>
        <div class="alert alert-danger alert-dismissable fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <h1 class="mb-4">Regisztráció</h1>
    <form method="POST" action="register_user.php" accept-charset="utf-8" class="form-group needs-validation" novalidate>
        <div class="mb-3">
            <label for="azonosito" class="form-label">Azonosító:</label>
            <input type="text" name="azonosito" id="azonosito" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adjon meg egy érvényes azonosítót! Megengedett karakterek: kis- és nagybetűk, számok, .'_- karakterek.
            </div>
        </div>

        <div class="mb-3">
            <label for="elotag" class="form-label">Előtag:</label>
            <input type="text" name="elotag" id="elotag" class="form-control">
        </div>

        <div class="mb-3">
            <label for="nev" class="form-label">Név:</label>
            <input type="text" name="nev" id="nev" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adjon meg a nevét!
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email cím:</label>
            <input type="email" name="email" id="email" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adjon meg egy érvényes email címet!
            </div>
        </div>

        <div class="mb-3">
            <label for="jelszo" class="form-label">Jelszó:</label>
            <input type="password" name="jelszo" id="jelszo" class="form-control" minlength="8" required>
            <div class="invalid-feedback">
                A jelszónak legalább 8 karakter hosszúnak kell lennie, és tartalmaznia kell legalább egy kis- és nagybetűt, valamint számot.
            </div>
        </div>

        <div class="mb-3">
            <label for="confirm_jelszo" class="form-label">Jelszó megerősítése:</label>
            <input type="password" name="confirm_jelszo" id="confirm_jelszo" class="form-control" minlength="8" required>
            <div class="invalid-feedback">
                A jelszavaknak meg kell egyezniük!
            </div>
        </div>

        <div class="mb-3">
            <label for="intezmeny" class="form-label">Intézmény:</label>
            <input type="text" name="intezmeny" id="intezmeny" class="form-control" required>
            <div class="invalid-feedback">
                Kérem, adja meg, melyik intézményhez tartozik!
            </div>
        </div>

        <div class="mb-3">
            <input type="submit" value="Regisztráció" class="btn btn-primary">
        </div>
    </form>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
                        if (input.id === 'confirm_jelszo' || input.id === 'jelszo') {
                            validatePasswordsMatch();  // Real-time feedback for password match
                        }
                    });

                    input.addEventListener('blur', function () {
                        validateInput(input);
                        if (input.id === 'confirm_jelszo' || input.id === 'jelszo') {
                            validatePasswordsMatch();  // Real-time feedback for password match
                        }
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

                validatePasswordsMatch();
                if (!isValid || !validatePasswordsMatch()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });

        function validateInput(input) {
            const value = input.value.trim();
            let pattern = /^[\p{L}\p{M}\d\s.'_-]+$/u;
            if (input.id === "azonosito") {
                pattern = /^[\p{L}\p{M}\d.'_-]+$/u;
            }
            if (input.id === "email") {
                pattern = /^[\p{L}\p{M}\d\s!@#$%^&*()_+[\]{};:'",.<>?\\|`~\-\/=]+$/u;
            }
            if (input.id === "jelszo" || input.id === "confirm_jelszo") {
                pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
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
                input.setCustomValidity('A jelszónak legalább 8 karakter hosszúnak kell lennie, és tartalmaznia kell legalább egy kis- és nagybetűt, valamint számot.');
            } else {
                input.setCustomValidity('');
            }

            // input.reportValidity(); // Show real-time error feedback
        }

        function validatePasswordsMatch() {
            const password = document.getElementById('jelszo').value;
            const confirmPassword = document.getElementById('confirm_jelszo').value;
            const confirmInput = document.getElementById('confirm_jelszo');

            if (password !== confirmPassword) {
                confirmInput.setCustomValidity('A jelszavak nem egyeznek!');
                return false;
            } else {
                confirmInput.setCustomValidity('');
                return true;
            }
        }
    })();
</script>
<?php echo file_get_contents("footer.html"); ?>