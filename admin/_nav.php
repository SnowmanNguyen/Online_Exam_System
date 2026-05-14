<?php
$admin_nav_active = isset($admin_nav_active) ? $admin_nav_active : '';
?>
<div class="sidebar">
    <div class="logo-details">
        <i class='bx bx-shield-quarter'></i>
        <span class="logo_name">Admin</span>
    </div>
    <ul class="nav-links">
        <li><a href="dash.php" class="<?php echo $admin_nav_active === 'dash' ? 'active' : ''; ?>"><i class='bx bx-grid-alt'></i><span class="links_name">Tổng quan</span></a></li>
        <li><a href="teachers.php" class="<?php echo $admin_nav_active === 'teachers' ? 'active' : ''; ?>"><i class='bx bx-user'></i><span class="links_name">Giáo viên</span></a></li>
        <li><a href="students.php" class="<?php echo $admin_nav_active === 'students' ? 'active' : ''; ?>"><i class='bx bx-group'></i><span class="links_name">Học sinh</span></a></li>
        <li><a href="exams.php" class="<?php echo $admin_nav_active === 'exams' ? 'active' : ''; ?>"><i class='bx bx-book-content'></i><span class="links_name">Đề kiểm tra</span></a></li>
        <li><a href="settings.php" class="<?php echo $admin_nav_active === 'settings' ? 'active' : ''; ?>"><i class='bx bx-cog'></i><span class="links_name">Cài đặt</span></a></li>
        <li class="log_out"><a href="logout.php"><i class='bx bx-log-out-circle'></i><span class="links_name">Đăng xuất</span></a></li>
    </ul>
</div>
