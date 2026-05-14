<?php
session_start();
include '../config.php';
include 'auth.inc.php';

if (isset($_POST['delete_exam'])) {
    $eid = (int) $_POST['exid'];
    mysqli_query($conn, "DELETE FROM qstn_list WHERE exid=$eid");
    mysqli_query($conn, "DELETE FROM atmpt_list WHERE exid=$eid");
    mysqli_query($conn, "DELETE FROM exm_list WHERE exid=$eid");
    header('Location: exams.php?msg=deleted');
    exit;
}

$list = mysqli_query($conn, 'SELECT e.*, t.fname AS teacher_fname FROM exm_list e LEFT JOIN teacher t ON t.id = e.teacher_id ORDER BY e.exid DESC');
$admin_nav_active = 'exams';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Đề kiểm tra</title>
    <link rel="stylesheet" href="../teachers/css/dash.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .admin-table{width:100%;border-collapse:collapse;font-size:13px;}
        .admin-table th,.admin-table td{padding:8px;border:1px solid #ddd;text-align:left;vertical-align:top;}
    </style>
</head>
<body>
<?php include '_nav.php'; ?>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Quản lý đề kiểm tra</span>
        </div>
    </nav>
    <div class="home-content">
        <?php if (!empty($_GET['msg'])): ?>
            <p style="margin-bottom:12px;color:green;">Đã xử lý.</p>
        <?php endif; ?>
        <div class="recent-stat box" style="width:100%;overflow-x:auto;">
            <div class="title">Danh sách đề</div>
            <p style="margin:8px 0;font-size:13px;">Sửa: tên, mô tả, môn, thời gian mở/hạn nộp. Số câu hỏi chỉ hiển thị.</p>
            <table class="admin-table" style="margin-top:8px;">
                <thead>
                    <tr>
                        <th>exid</th><th>Tên</th><th>Giáo viên</th><th>Môn</th><th>Số CH</th><th>Mở thi</th><th>Hạn nộp</th><th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($list && ($r = mysqli_fetch_assoc($list))): ?>
                    <tr>
                        <td><?php echo (int) $r['exid']; ?></td>
                        <td><?php echo htmlspecialchars($r['exname']); ?></td>
                        <td><?php echo !empty($r['teacher_fname']) ? htmlspecialchars($r['teacher_fname']) : '<span style="color:#999;">—</span>'; ?></td>
                        <td><?php echo htmlspecialchars($r['subject']); ?></td>
                        <td><?php echo (int) $r['nq']; ?></td>
                        <td><?php echo htmlspecialchars($r['extime']); ?></td>
                        <td><?php echo htmlspecialchars($r['subt']); ?></td>
                        <td>
                            <a href="exam_edit.php?exid=<?php echo (int) $r['exid']; ?>">Sửa</a>
                            <form method="post" style="display:inline;margin-left:8px;" onsubmit="return confirm('Xóa đề và toàn bộ câu hỏi + bài làm liên quan?');">
                                <input type="hidden" name="exid" value="<?php echo (int) $r['exid']; ?>">
                                <button type="submit" name="delete_exam" style="color:red;background:none;border:none;cursor:pointer;">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
