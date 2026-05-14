<?php
error_reporting(0);
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dash.php');
    exit;
}

include 'config.php';

@mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL,
  `pword` varchar(255) NOT NULL,
  `fname` varchar(100) NOT NULL DEFAULT 'Administrator',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$chk = @mysqli_query($conn, 'SELECT COUNT(*) AS c FROM admin');
if ($chk && ($r = mysqli_fetch_assoc($chk)) && (int) $r['c'] === 0) {
    $hp = md5('admin');
    @mysqli_query($conn, "INSERT INTO admin (uname, pword, fname) VALUES ('admin','$hp','Administrator')");
}

if (isset($_POST['signin'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $pword = mysqli_real_escape_string($conn, md5($_POST['pword']));
    $q = mysqli_query($conn, "SELECT * FROM admin WHERE uname='$uname' AND pword='$pword' LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_assoc($q);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_uname'] = $row['uname'];
        $_SESSION['admin_fname'] = $row['fname'];
        header('Location: admin/dash.php');
        exit;
    }
    echo "<script>alert('Sai tên đăng nhập hoặc mật khẩu.');</script>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="teachers/css/style.css">
    <title>Đăng nhập quản trị</title>
</head>
<style>
body {
  background-image: url('<?php echo $img;?>');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;
}
</style>
<body>
    <h1><?php echo htmlspecialchars($greet); ?></h1><br>
    <div class="container" id="container">
        <div class="form-container log-in-container">
            <form action="#" method="post">
                <h1>Admin</h1>
                <br><br>
                <span>Tài khoản quản trị hệ thống</span>
                <input type="text" name="uname" placeholder="Username" value="<?php echo isset($_POST['uname']) ? htmlspecialchars($_POST['uname']) : ''; ?>" required />
                <input type="password" name="pword" placeholder="Password" required />
                <button type="submit" name="signin">Đăng nhập</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <p>Giáo viên</p>
                    <button type="button" style="background-color:#ffffff;border-color:black;"><a href="login_teacher.php">Teacher</a></button>
                    <p style="margin-top:16px;">Học sinh</p>
                    <button type="button" style="background-color:#ffffff;border-color:black;"><a href="login_student.php">Student</a></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
