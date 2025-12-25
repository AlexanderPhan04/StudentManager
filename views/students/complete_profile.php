<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoàn thiện thông tin sinh viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px 0;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            background: #fff;
            padding: 32px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 400px;
            width: 100%;
            max-width: 500px;
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 8px;
            color: #333;
        }

        .profile-container .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .info-notice {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            color: #1976d2;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
        }

        label .required {
            color: #d32f2f;
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            min-height: 80px;
            resize: vertical;
        }

        .readonly-field {
            background: #f5f5f5;
            color: #666;
            cursor: not-allowed;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            color: #d32f2f;
            background: #ffebee;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
            border: 1px solid #ef5350;
        }

        .success {
            color: #2e7d32;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
            border: 1px solid #66bb6a;
        }

        @media (max-width: 600px) {
            .profile-container {
                min-width: 90vw;
                padding: 24px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h2>Hoàn thiện thông tin sinh viên</h2>
        <p class="subtitle">Vui lòng cập nhật đầy đủ thông tin để tiếp tục</p>

        <div class="info-notice">
            <strong>Lưu ý:</strong> Bạn cần hoàn tất thông tin cá nhân trước khi sử dụng hệ thống.
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="student_code">Mã sinh viên</label>
                <input type="text" id="student_code" name="student_code" value="<?= htmlspecialchars($student['student_code']) ?>" class="readonly-field" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" class="readonly-field" readonly>
            </div>

            <div class="form-group">
                <label for="full_name">Họ và tên <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" required value="<?= htmlspecialchars($student['full_name'] ?? '') ?>" placeholder="Nguyễn Văn A">
            </div>

            <div class="form-group">
                <label for="birthday">Ngày sinh <span class="required">*</span></label>
                <input type="date" id="birthday" name="birthday" required value="<?= htmlspecialchars($student['birthday'] === '2000-01-01' ? '' : $student['birthday']) ?>" max="<?= date('Y-m-d') ?>">
            </div>

            <div class="form-group">
                <label for="gender">Giới tính <span class="required">*</span></label>
                <select id="gender" name="gender" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Male" <?= ($student['gender'] === 'Male') ? 'selected' : '' ?>>Nam</option>
                    <option value="Female" <?= ($student['gender'] === 'Female') ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại <span class="required">*</span></label>
                <input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars($student['phone'] ?? '') ?>" placeholder="0912345678">
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ <span class="required">*</span></label>
                <textarea id="address" name="address" required placeholder="Nhập địa chỉ đầy đủ"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
            </div>

            <button type="submit" name="submit">Hoàn tất</button>
        </form>
    </div>
</body>

</html>