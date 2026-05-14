<?php
session_start();
include '../config.php';
include 'auth.inc.php';

$nt = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM teacher'));
$ns = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM student'));
$ne = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM exm_list'));
$admin_nav_active = 'dash';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Tổng quan</title>
    <link rel="stylesheet" href="../teachers/css/dash.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<?php include '_nav.php'; ?>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Quản trị hệ thống</span>
        </div>
        <div class="profile-details">
            <span class="admin_name"><?php echo htmlspecialchars($_SESSION['admin_fname']); ?></span>
        </div>
    </nav>
    <div class="home-content">
        <div class="overview-boxes">
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Giáo viên</div>
                    <div class="number"><?php echo (int) $nt['c']; ?></div>
                    <div class="brief"><span class="text">Tài khoản trong hệ thống</span></div>
                </div>
                <i class="bx bx-user ico"></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Học sinh</div>
                    <div class="number"><?php echo (int) $ns['c']; ?></div>
                    <div class="brief"><span class="text">Tài khoản trong hệ thống</span></div>
                </div>
                <i class="bx bx-group ico two"></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Đề kiểm tra</div>
                    <div class="number"><?php echo (int) $ne['c']; ?></div>
                    <div class="brief"><span class="text">Số đề trong hệ thống</span></div>
                </div>
                <i class="bx bx-book-content ico three"></i>
            </div>
        </div>
    </div>
</section>
<script src="../js/script.js"></script>
</body>
</html>
