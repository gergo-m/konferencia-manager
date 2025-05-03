<?php
include_once('db_functions.php');
include_once('menu.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php?error=please log in");
    exit();
}

$user_id = $_SESSION['id'];
$user_email = $_SESSION['email'];

// Fetch user data from the database
$user = get_user($user_email); // You'll need to create a function to fetch user by ID
$szerepkor = match ($_SESSION["szerepkor"]) {
    "SZERZO_SZERZO" => "Szerző",
    "ADMIN_ADMIN" => "Admin",
    "SZERZO_ADMIN" => "Szerző, Admin",
    default => "Vendég",
};

echo preg_replace("/<#title>/", "Profil - " . htmlspecialchars($user["nev"]), file_get_contents("header.html"));
echo menu();
?>

<div class="container mt-4">
    <h1 class="mb-4">Profil: <?php echo htmlspecialchars($user["nev"]); ?></h1>

    <!-- Display user profile details -->
    <div class="card">
        <?php if (!isset($_POST["user_id"])): ?>
        <div class="card-header">
            <h4>Felhasználói adatok</h4>
        </div>
        <div class="card-body">
            <p><strong>Név:</strong> <?php echo htmlspecialchars($user["nev"]); ?></p>
            <p><strong>Email cím:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
            <p><strong>Intézmény:</strong> <?php echo htmlspecialchars($user["intezmeny"]); ?></p>
            <p><strong>Előtag:</strong> <?php echo htmlspecialchars($user["elotag"]); ?></p>
            <p><strong>Szerepkör:</strong> <?php echo htmlspecialchars($szerepkor); ?></p>
        </div>
        <div class="card-footer">
            <form>
                <input name="user_id" hidden readonly value="<?php echo $user["id"] ?>">
                <input name="from" hidden readonly value="profile">
            <button type="submit" class="btn btn-warning" formmethod="POST" formaction="profile.php">Szerkesztés</button>
            <button type="submit" class="btn btn-danger" formmethod="POST" formaction="logout_user.php">Kijelentkezés</button>
            <button type="submit" class="btn btn-danger" formmethod="POST" formaction="deactivate_user_db.php">Fiók deaktiválása</button>
            </form>
        </div>

        <?php else: ?>
        <div class="card-header">
            <h4>Felhasználói adatok</h4>
        </div>
        <form method="POST">
            <div class="card-body">
                <input name="edit_id" hidden readonly value="<?php echo $user["id"] ?>">
                <div class="mb-3">
                    <label for="nev" class="form-label">Név:</label>
                    <input type="text" name="nev" id="nev" class="form-control" value="<?php echo htmlspecialchars($user["nev"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email cím:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user["email"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="intezmeny" class="form-label">Intézmény:</label>
                    <input type="text" name="intezmeny" id="intezmeny" class="form-control" value="<?php echo htmlspecialchars($user["intezmeny"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="elotag" class="form-label">Előtag:</label>
                    <input type="text" name="elotag" id="elotag" class="form-control" value="<?php echo htmlspecialchars($user["elotag"]); ?>">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success" formmethod="POST" formaction="edit_profile_db.php">Mentés</button>
                <a type="submit" class="btn btn-secondary" href="profile.php">Vissza</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php echo file_get_contents("footer.html"); ?>