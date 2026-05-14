<?php
session_start();
include '../config.php';
include 'auth.inc.php';

$exid = isset($_GET['exid']) ? (int) $_GET['exid'] : 0;
if ($exid < 1) {
    header('Location: exams.php');
    exit;
}

if (isset($_POST['update_exam'])) {
    $exname = mysqli_real_escape_string($conn, $_POST['exname']);
    $desp = mysqli_real_escape_string($conn, $_POST['desp']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $extime = mysqli_real_escape_string($conn, str_replace('T', ' ', $_POST['extime']));
    $subt = mysqli_real_escape_string($conn, str_replace('T', ' ', $_POST['subt']));
    mysqli_query($conn, "UPDATE exm_list SET exname='$exname', desp='$desp', subject='$subject', extime='$extime', subt='$subt' WHERE exid=$exid");
    header('Location: exams.php?msg=updated');
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM exm_list WHERE exid=$exid LIMIT 1");
$row = $q ? mysqli_fetch_assoc($q) : null;
if (!$row) {
    header('Location: exams.php');
    exit;
}

$toLocal = function ($mysqlDt) {
    $ts = strtotime(str_replace('T', ' ', $mysqlDt));
    if ($ts === false) {
        return '';
    }
    return date('Y-m-d\TH:i', $ts);
};

$admin_nav_active = 'exams';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa đề kiểm tra</title>
    <link rel="stylesheet" href="../teachers/css/dash.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>.admin-form label{display:block;margin-top:10px;font-weight:500;}
    .admin-form input,.admin-form textarea{width:100%;max-width:480px;padding:8px;margin-top:4px;}
    </style>
</head>
<body>
<?php include '_nav.php'; ?>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Sửa đề #<?php echo $exid; ?></span>
        </div>
    </nav>
    <div class="home-content">
        <p><a href="exams.php">&larr; Danh sách đề</a></p>
        <div class="recent-stat box" style="width:100%;max-width:560px;">
            <form method="post" class="admin-form">
                <label>Tên đề</label>
                <input name="exname" value="<?php echo htmlspecialchars($row['exname']); ?>" required maxlength="100">
                <label>Mô tả</label>
                <textarea name="desp" rows="2" required maxlength="200"><?php echo htmlspecialchars($row['desp']); ?></textarea>
                <label>Môn (subject)</label>
                <input name="subject" value="<?php echo htmlspecialchars($row['subject']); ?>" required maxlength="100">
                <label>Thời gian mở (extime)</label>
                <input type="datetime-local" name="extime" value="<?php echo htmlspecialchars($toLocal($row['extime'])); ?>" required>
                <label>Hạn nộp (subt)</label>
                <input type="datetime-local" name="subt" value="<?php echo htmlspecialchars($toLocal($row['subt'])); ?>" required>
                <p style="margin-top:10px;font-size:13px;">Số câu hiện tại: <b><?php echo (int) $row['nq']; ?></b></p>
                <br>
                <button type="submit" name="update_exam" class="btn">Lưu</button>
            </form>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
