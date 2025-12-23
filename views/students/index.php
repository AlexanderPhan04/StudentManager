<div class="container">
    <div class="card">
        <h2 style="margin-bottom: 20px;">📋 Danh sách sinh viên</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?= $type == 'success' ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Form tìm kiếm -->
        <form method="GET" class="search-box">
            <input type="hidden" name="action" value="students">
            <input type="text" name="search" placeholder="Tìm kiếm theo tên hoặc mã sinh viên..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn">🔍 Tìm kiếm</button>
            <?php if ($search): ?>
                <a href="index.php" class="btn">✖ Xóa tìm kiếm</a>
            <?php endif; ?>
        </form>

        <?php if ($isAdmin): ?>
            <div style="margin-bottom: 15px;">
                <a href="index.php?action=student_add" class="btn btn-success">➕ Thêm sinh viên mới</a>
            </div>
        <?php endif; ?>

        <?php if (count($students) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <?php if ($isAdmin): ?>
                            <th>Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td><?= htmlspecialchars($student['student_code']) ?></td>
                            <td><?= htmlspecialchars($student['full_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($student['birthday'])) ?></td>
                            <td><?= $student['gender'] == 'Male' ? 'Nam' : 'Nữ' ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['phone']) ?></td>
                            <?php if ($isAdmin): ?>
                                <td>
                                    <a href="index.php?action=student_edit&id=<?= $student['id'] ?>" class="btn btn-sm">✏️ Sửa</a>
                                    <a href="index.php?action=student_delete&id=<?= $student['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">🗑️ Xóa</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #666;">
                <?= $search ? 'Không tìm thấy sinh viên nào!' : 'Chưa có sinh viên nào trong hệ thống.' ?>
            </p>
        <?php endif; ?>
    </div>
</div>