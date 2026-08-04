<div class="page-content">
    <div class="container-fluid">
        <div class="container">
            <h2 class="mb-4">Danh sách liên hệ</h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Chủ đề</th>
                        <th>Nội dung</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($contacts)): ?>
                    <?php foreach ($contacts as $index => $contact): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($contact['ten']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['so_dien_thoai']) ?></td>
                        <td><?= htmlspecialchars($contact['chu_de']) ?></td>
                        <td><?= htmlspecialchars($contact['noi_dung']) ?></td>

                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có liên hệ nào.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>