<?php
require 'db.php';

$types = ['ระบบ QR-code-old', 'ระบบ Qr-code-new', 'ระบบ AX', 'ระบบ อื่นๆ'];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'] ?? null;

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }

    $stmt = $pdo->prepare("INSERT INTO issues (title, description, type, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $desc, $type, $imageName]);

    header("Location: index.php");
    exit;
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มปัญหา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/web_tab_icon.png">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">➕ เพิ่มปัญหาใหม่</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">ระบบ</label>
            <select name="type" class="form-select" required>
                <option value="">-- เลือกระบบ --</option>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t ?>"><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">หัวข้อ</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">รายละเอียด</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">แนบภาพ</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">บันทึก</button>
        <a href="index.php" class="btn btn-secondary">ย้อนกลับ</a>
    </form>
</div>
</body>
</html>
