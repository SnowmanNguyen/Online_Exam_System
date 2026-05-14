<?php
session_start();
include '../config.php';
include 'auth.inc.php';

$aid = (int) $_SESSION['admin_id'];
$err = '';
$ok = '';

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, uname, fname FROM admin WHERE id=$aid LIMIT 1"));
if (!$row) {
    header('Location: ../login_admin.php');
    exit;
}

if (isset($_POST['save'])) {
    $fname = trim($_POST['fname'] ?? '');
    $uname = trim($_POST['uname'] ?? '');
    $np = $_POST['npass'] ?? '';
    $np2 = $_POST['npass2'] ?? '';

    if ($fname === '') {
        $err = 'Vui lòng nhập họ tên.';
    } elseif ($uname === '') {
        $err = 'Vui lòng nhập tên đăng nhập.';
    } elseif (($np !== '' || $np2 !== '') && $np !== $np2) {
        $err = 'Mật khẩu mới nhập lại không khớp.';
    } elseif ($np !== '' && strlen($np) < 6) {
        $err = 'Mật khẩu mới tối thiểu 6 ký tự.';
    } else {
        $fu = mysqli_real_escape_string($conn, $fname);
        $uu = mysqli_real_escape_string($conn, $uname);
        $dup = mysqli_query($conn, "SELECT id FROM admin WHERE uname='$uu' AND id<>$aid LIMIT 1");
        if ($dup && mysqli_num_rows($dup) > 0) {
            $err = 'Tên đăng nhập đã được sử dụng.';
        } else {
            $parts = ["fname='$fu'", "uname='$uu'"];
            if ($np !== '') {
                $hp = md5($np);
                $parts[] = "pword='" . mysqli_real_escape_string($conn, $hp) . "'";
            }
            mysqli_query($conn, 'UPDATE admin SET ' . implode(', ', $parts) . " WHERE id=$aid");
            $_SESSION['admin_fname'] = $fname;
            $_SESSION['admin_uname'] = $uname;
            $ok = 'Đã cập nhật thông tin.';
            $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, uname, fname FROM admin WHERE id=$aid LIMIT 1"));
        }
    }
}

$admin_nav_active = 'settings';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Cài đặt tài khoản</title>
    <link rel="stylesheet" href="../teachers/css/dash.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .admin-form label { display: block; margin-top: 12px; font-weight: 500; }
        .admin-form input { width: 100%; max-width: 400px; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        .msg-err { color: #b00020; margin: 10px 0; }
        .msg-ok { color: #0a7; margin: 10px 0; }
        .hint { font-size: 13px; color: #666; margin-top: 4px; }
    </style>
</head>
<body>
<?php include '_nav.php'; ?>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Cài đặt quản trị</span>
        </div>
        <div class="profile-details">
            <span class="admin_name"><?php echo htmlspecialchars($_SESSION['admin_fname'] ?? ''); ?></span>
        </div>
    </nav>
    <div class="home-content">
        <p><a href="dash.php">&larr; Tổng quan</a></p>
        <?php if ($err !== ''): ?><p class="msg-err"><?php echo htmlspecialchars($err); ?></p><?php endif; ?>
        <?php if ($ok !== ''): ?><p class="msg-ok"><?php echo htmlspecialchars($ok); ?></p><?php endif; ?>
        <div class="recent-stat box" style="width:100%;max-width:480px;">
            <div class="title">Thông tin tài khoản admin</div>
            <form method="post" class="admin-form">
                <label for="fname">Họ tên hiển thị</label>
                <input id="fname" name="fname" value="<?php echo htmlspecialchars($row['fname']); ?>" required maxlength="100">
                <label for="uname">Tên đăng nhập</label>
                <input id="uname" name="uname" value="<?php echo htmlspecialchars($row['uname']); ?>" required maxlength="100" autocomplete="username">
                <p class="hint">Để trống hai ô bên dưới nếu không đổi mật khẩu.</p>
                <label for="npass">Mật khẩu mới</label>
                <input type="password" id="npass" name="npass" maxlength="128" autocomplete="new-password">
                <label for="npass2">Nhập lại mật khẩu mới</label>
                <input type="password" id="npass2" name="npass2" maxlength="128" autocomplete="new-password">
                <p style="margin-top:16px;">
                    <button type="submit" name="save" class="btn">Lưu thay đổi</button>
                </p>
            </form>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
