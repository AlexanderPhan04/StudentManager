<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background: #fff;
            padding: 32px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-width: 320px;
            width: 100%;
            max-width: 350px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 18px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: #d8000c;
            background: #ffd2d2;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 12px;
            text-align: center;
        }

        @media (max-width: 500px) {
            .login-container {
                min-width: 90vw;
                padding: 18px 6vw;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="submit">Đăng nhập</button>
        </form>
        <div style="text-align:center; margin-top:12px;">
            <a href="index.php?action=register">Chưa có tài khoản? Đăng ký</a>
        </div>
    </div>
</body>

</html>