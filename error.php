<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .error-container {
            max-width: 600px;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
    </style>
</head>
<body>

<div class="error-container">
    <h1 class="display-4 text-danger">404</h1>
    <p class="lead">Hoppá! A keresett oldal nem létezik.</p>
    <p>Ha úgy gondolja, hogy valami elromlott, jelezze.</p>
    <a href="conference_program.php" class="btn btn-primary btn-lg mt-3">Vissza a főoldalra</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
