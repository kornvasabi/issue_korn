<?php require 'db.php';
/*26-05-2025*/
$search = $_GET['search'] ?? '';
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

$sql = "SELECT * FROM issues WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
}
if ($start) {
    $sql .= " AND created_at >= ?";
    $params[] = "$start 00:00:00";
}
if ($end) {
    $sql .= " AND created_at <= ?";
    $params[] = "$end 23:59:59";
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$issues = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บันทึกแก้ไขปัญหา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/web_tab_icon.png">
</head>
<body>
<div id="loadingSpinner" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
     background: rgba(255, 255, 255, 0.7); z-index: 9999; text-align: center; padding-top: 200px;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">กำลังโหลด...</span>
    </div>
    <div class="mt-3">กำลังโหลดข้อมูล...</div>
</div>
<div class="container mt-5">
    <h1 class="mb-4">📋 ระบบจัดการปัญหา</h1>

    <form class="row g-3 mb-4" method="get">
        <div class="col-md-4">
            <input type="text" class="form-control" name="search" placeholder="ค้นหาหัวข้อ..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" name="start" value="<?= htmlspecialchars($start) ?>">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" name="end" value="<?= htmlspecialchars($end) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 ค้นหา</button>
        </div>
    </form>

    <a href="create.php" class="btn btn-success mb-3">➕ เพิ่มปัญหา</a>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ระบบ</th>
                <th>หัวข้อ</th>
                <th>รายละเอียด</th>
                <th>วันที่</th>
                <th>รูป</th>
                <th colspan="2">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$issues): ?>
                <tr><td colspan="6" class="text-center">ไม่พบข้อมูล</td></tr>
            <?php endif; ?>
            <?php foreach ($issues as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <?php if ($row['image']): ?>
                            <a href="uploads/<?= $row['image'] ?>" target="_blank">
                                <img src="uploads/<?= $row['image'] ?>" width="100" class="img-thumbnail">
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="edit.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-warning">แก้ไข</button>
                        </form>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('ลบจริงหรือไม่?')">ลบ</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<script>
    // เมื่อ submit form ใด ๆ (เช่น ฟอร์มค้นหา)
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function () {
            document.getElementById("loadingSpinner").style.display = "block";
        });
    });

    // เมื่อคลิกลิงก์ <a> ที่เปลี่ยนหน้า (ที่ไม่ใช้ #)
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (href && href !== "#" && !href.startsWith("javascript:")) {
                document.getElementById("loadingSpinner").style.display = "block";
            }
        });
    });
</script>
