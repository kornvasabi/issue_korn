<?php
require 'db.php';

$types = ['ระบบ QR-code-old', 'ระบบ Qr-code-new', 'ระบบ AX', 'ระบบ อื่นๆ'];

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM issues WHERE id = ?");
$stmt->execute([$id]);
$issue = $stmt->fetch();

if (!$issue) exit("ไม่พบข้อมูล");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'];
    $imageName = $issue['image'];

    if (!empty($_FILES['image']['name'])) {
        if ($imageName && file_exists("uploads/$imageName")) {
            unlink("uploads/$imageName");
        }
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }

    $stmt = $pdo->prepare("UPDATE issues SET title = ?, description = ?, type = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $desc, $type, $imageName, $id]);

    header("Location: index.php");
    exit;
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขปัญหา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/web_tab_icon.png">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">✏️ แก้ไขปัญหา</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">ระบบ</label>
            <select name="type" class="form-select" required>
                <option value="">-- เลือกระบบ --</option>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t ?>" <?= ($issue['type'] === $t) ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">หัวข้อ</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($issue['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">รายละเอียด</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($issue['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">แนบภาพใหม่</label>
            <input type="file" name="image" class="form-control">
            <?php if ($issue['image']): ?>
                <div class="mt-2">
                    <p>ภาพปัจจุบัน:</p>
                    <img src="uploads/<?= $issue['image'] ?>" width="150" class="rounded border">
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">อัปเดต</button>
        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
</body>
</html>
