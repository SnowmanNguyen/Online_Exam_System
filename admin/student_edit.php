<?php
session_start();
include '../config.php';
include 'auth.inc.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    header('Location: students.php');
    exit;
}

if (isset($_POST['update_student'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pw = trim($_POST['pword'] ?? '');
    if ($pw !== '') {
        $hp = mysqli_real_escape_string($conn, md5($pw));
        mysqli_query($conn, "UPDATE student SET uname='$uname', pword='$hp', fname='$fname', dob='$dob', gender='$gender', email='$email' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE student SET uname='$uname', fname='$fname', dob='$dob', gender='$gender', email='$email' WHERE id=$id");
    }
    header('Location: students.php?msg=updated');
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM student WHERE id=$id LIMIT 1");
$row = $q ? mysqli_fetch_assoc($q) : null;
if (!$row) {
    header('Location: students.php');
    exit;
}

$admin_nav_active = 'students';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa học sinh</title>
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
            <span class="dashboard">Sửa học sinh #<?php echo $id; ?></span>
        </div>
    </nav>
    <div class="home-content">
        <p><a href="students.php">&larr; Danh sách</a></p>
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
                <label>Ngày sinh</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required>
                <label>Giới tính (M/F)</label>
                <input name="gender" value="<?php echo htmlspecialchars($row['gender']); ?>" maxlength="1" required>
                <br><br>
                <button type="submit" name="update_student" class="btn">Lưu</button>
            </form>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
