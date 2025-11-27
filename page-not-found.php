<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./vendor/css/style.css">
    <link rel="stylesheet" href="./vendor/css/theme.css">
    <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">
    <style>
        .notfound-wrap {
            min-height: 70vh;
            display: grid;
            place-items: center;
        }

        .notfound-card {
            max-width: 720px;
        }
    </style>
</head>

<body>
    <div class="container page-section notfound-wrap">
        <div class="card notfound-card p-5 text-center">
            <h1 class="display-4 fw-bold text-brand">404</h1>
            <p class="lead mb-4">We can't find the page you're looking for.</p>
            <div>
                <a href="./dashboard.php" class="btn btn-custom me-2">Go to Dashboard</a>
                <a href="./index.php" class="btn btn-outline-dark">Home</a>
            </div>
        </div>
    </div>
    <script src="./lib/js/bootstrap.bundle.min.js"></script>
</body>

</html>