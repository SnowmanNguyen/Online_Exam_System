<?php
session_start();
include '../config.php';
include 'auth.inc.php';

if (isset($_POST['add_student'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $pword = mysqli_real_escape_string($conn, md5($_POST['pword']));
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    mysqli_query($conn, "INSERT INTO student (uname, pword, fname, dob, gender, email, teacher_id) VALUES ('$uname','$pword','$fname','$dob','$gender','$email', NULL)");
    header('Location: students.php?msg=added');
    exit;
}

if (isset($_POST['delete_student'])) {
    $id = (int) $_POST['id'];
    mysqli_query($conn, "DELETE FROM student WHERE id=$id");
    header('Location: students.php?msg=deleted');
    exit;
}

$list = mysqli_query($conn, 'SELECT s.*, t.fname AS owner_teacher FROM student s LEFT JOIN teacher t ON t.id = s.teacher_id ORDER BY s.id ASC');
$admin_nav_active = 'students';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Học sinh</title>
    <link rel="stylesheet" href="../teachers/css/dash.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .admin-table{width:100%;border-collapse:collapse;font-size:14px;}
        .admin-table th,.admin-table td{padding:8px;border:1px solid #ddd;text-align:left;}
        .admin-form label{display:block;margin-top:8px;font-weight:500;}
        .admin-form input{width:100%;max-width:400px;padding:8px;margin-top:4px;}
    </style>
</head>
<body>
<?php include '_nav.php'; ?>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Quản lý học sinh</span>
        </div>
    </nav>
    <div class="home-content">
        <?php if (!empty($_GET['msg'])): ?>
            <p style="margin-bottom:12px;color:green;">Đã xử lý.</p>
        <?php endif; ?>
        <div class="stat-boxes">
            <div class="recent-stat box" style="width:100%;overflow-x:auto;">
                <div class="title">Danh sách học sinh</div>
                <table class="admin-table" style="margin-top:12px;">
                    <thead>
                        <tr>
                            <th>ID</th><th>Username</th><th>Họ tên</th><th>Email</th><th>Giới tính</th><th>Giáo viên quản lý</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($list && ($r = mysqli_fetch_assoc($list))): ?>
                        <tr>
                            <td><?php echo (int) $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['uname']); ?></td>
                            <td><?php echo htmlspecialchars($r['fname']); ?></td>
                            <td><?php echo htmlspecialchars($r['email']); ?></td>
                            <td><?php echo htmlspecialchars($r['gender']); ?></td>
                            <td><?php echo !empty($r['owner_teacher']) ? htmlspecialchars($r['owner_teacher']) : '<span style="color:#888;">Admin / chưa gán</span>'; ?></td>
                            <td>
                                <a href="student_edit.php?id=<?php echo (int) $r['id']; ?>">Sửa</a>
                                <form method="post" style="display:inline;margin-left:8px;" onsubmit="return confirm('Xóa học sinh này?');">
                                    <input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
                                    <button type="submit" name="delete_student" style="color:red;background:none;border:none;cursor:pointer;">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="stat-boxes" style="margin-top:20px;">
            <div class="recent-stat box" style="width:100%;">
                <div class="title">Thêm học sinh</div>
                <form method="post" class="admin-form" style="margin-top:12px;">
                    <label>Username *</label><input name="uname" required>
                    <label>Mật khẩu *</label><input type="password" name="pword" required>
                    <label>Họ tên *</label><input name="fname" required>
                    <label>Email *</label><input type="email" name="email" required>
                    <label>Ngày sinh *</label><input type="date" name="dob" required>
                    <label>Giới tính (M/F) *</label><input name="gender" maxlength="1" required pattern="[MF]">
                    <br><br><button type="submit" name="add_student" class="btn">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
