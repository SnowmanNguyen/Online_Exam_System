<?php
session_start();
include '../config.php';
include 'auth.inc.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    header('Location: teachers.php');
    exit;
}

if (isset($_POST['update_teacher'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $pw = trim($_POST['pword'] ?? '');
    if ($pw !== '') {
        $hp = mysqli_real_escape_string($conn, md5($pw));
        mysqli_query($conn, "UPDATE teacher SET uname='$uname', pword='$hp', fname='$fname', dob='$dob', gender='$gender', email='$email', subject='$subject' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE teacher SET uname='$uname', fname='$fname', dob='$dob', gender='$gender', email='$email', subject='$subject' WHERE id=$id");
    }
    header('Location: teachers.php?msg=updated');
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM teacher WHERE id=$id LIMIT 1");
$row = $q ? mysqli_fetch_assoc($q) : null;
if (!$row) {
    header('Location: teachers.php');
    exit;
}

$admin_nav_active = 'teachers';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa giáo viên</title>
    <link rel="stylesheet" href="../teachers/css/dash.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>.admin-form label{display:block;margin-top:10px;font-weight:500;}
    .admin-form input{width:100%;max-width:420px;padding:8px;margin-top:4px;}</style>
</head>
<body>
<?php include '_nav.php'; ?>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Sửa giáo viên #<?php echo $id; ?></span>
        </div>
    </nav>
    <div class="home-content">
        <p><a href="teachers.php">&larr; Danh sách</a></p>
        <div class="recent-stat box" style="width:100%;max-width:520px;">
            <form method="post" class="admin-form">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label>Username</label>
                <input name="uname" value="<?php echo htmlspecialchars($row['uname']); ?>" required>
                <label>Mật khẩu mới (để trống nếu giữ nguyên)</label>
                <input type="password" name="pword" placeholder="••••••••">
                <label>Họ tên</label>
                <input name="fname" value="<?php echo htmlspecialchars($row['fname']); ?>" required>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                <label>Môn (subject)</label>
                <input name="subject" value="<?php echo htmlspecialchars($row['subject']); ?>" required>
                <label>Ngày sinh</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required>
                <label>Giới tính (M/F)</label>
                <input name="gender" value="<?php echo htmlspecialchars($row['gender']); ?>" maxlength="1" required>
                <br><br>
                <button type="submit" name="update_teacher" class="btn">Lưu</button>
            </form>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
