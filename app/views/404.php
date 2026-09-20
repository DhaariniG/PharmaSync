<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | PharmaSync</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }
        .error-card {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            max-width: 480px;
            width: 100%;
        }
        .error-code {
            font-size: 80px;
            font-weight: 800;
            color: #0d9488;
            line-height: 1;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 22px;
            margin-bottom: 12px;
            color: #1f2937;
        }
        p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .btn-home {
            display: inline-block;
            background-color: #0d9488;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }
        .btn-home:hover {
            background-color: #0f766e;
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="error-code">404</div>
        <h1>Page Not Found</h1>
        <p>The page or feature you are trying to access does not exist or may have been moved.</p>
        <a href="<?= BASE_URL ?>/index.php" class="btn-home">Back to Home</a>
    </div>

</body>
</html>