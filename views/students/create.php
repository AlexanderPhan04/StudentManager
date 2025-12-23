<div class="container">
    <div class="card">
        <h2 style="margin-bottom: 20px;">➕ Thêm sinh viên mới</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" style="max-width: 600px;">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Mã sinh viên <span style="color: red;">*</span></label>
                <input type="text" name="student_code" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    value="<?= htmlspecialchars($_POST['student_code'] ?? '') ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Họ tên <span style="color: red;">*</span></label>
                <input type="text" name="full_name" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Ngày sinh <span style="color: red;">*</span></label>
                <input type="date" name="birthday" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    value="<?= htmlspecialchars($_POST['birthday'] ?? '') ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Giới tính <span style="color: red;">*</span></label>
                <select name="gender" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Male" <?= (($_POST['gender'] ?? '') == 'Male') ? 'selected' : '' ?>>Nam</option>
                    <option value="Female" <?= (($_POST['gender'] ?? '') == 'Female') ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Email</label>
                <input type="email" name="email"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Số điện thoại</label>
                <input type="text" name="phone"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Địa chỉ</label>
                <textarea name="address" rows="3"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-success">💾 Lưu</button>
                <a href="index.php" class="btn">🔙 Quay lại</a>
            </div>
        </form>
    </div>
</div>