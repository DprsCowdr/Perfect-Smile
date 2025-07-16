<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentative Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .center-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .tentative-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: #6c63ff;
        }
        .tentative-btn {
            font-size: 1.1rem;
            padding: 0.75rem 2.5rem;
            border-radius: 8px;
            background: #6c63ff;
            color: #fff;
            border: none;
            font-weight: 600;
            transition: background 0.2s;
        }
        .tentative-btn:hover {
            background: #5548c8;
        }
    </style>
</head>
<body>
    <div class="center-box">
        <div class="tentative-title">Tentative Home</div>
        <button class="tentative-btn">Button</button>
        <a href="<?= base_url('guest/book-appointment') ?>" class="tentative-btn">Guest Booking</a>
<a href="<?= base_url('login') ?>" class="tentative-btn">Login</a>
    </div>
</body>
</html>
