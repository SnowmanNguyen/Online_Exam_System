<?php
// Múi giờ dùng khi so sánh thời gian thi / hiển thị (datetime-local không gửi timezone)
date_default_timezone_set('Asia/Ho_Chi_Minh');

$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_eval";

if(!$conn = mysqli_connect($hostname, $username, $password, $database)){

 die("Database connection failed");
}

// Mỗi đề thi thuộc về giáo viên tạo (teacher_id). Tự thêm cột nếu DB cũ chưa có.
$__col = @mysqli_query($conn, "SHOW COLUMNS FROM `exm_list` LIKE 'teacher_id'");
if ($__col && mysqli_num_rows($__col) === 0) {
    @mysqli_query($conn, "ALTER TABLE `exm_list` ADD `teacher_id` int(11) NULL DEFAULT NULL AFTER `subject`");
    @mysqli_query($conn, "ALTER TABLE `exm_list` ADD KEY `idx_exm_teacher` (`teacher_id`)");
}

// Học sinh do giáo viên nào tạo (teacher_id); admin thêm thì để NULL.
$__st = @mysqli_query($conn, "SHOW COLUMNS FROM `student` LIKE 'teacher_id'");
if ($__st && mysqli_num_rows($__st) === 0) {
    @mysqli_query($conn, "ALTER TABLE `student` ADD `teacher_id` int(11) NULL DEFAULT NULL AFTER `email`");
    @mysqli_query($conn, "ALTER TABLE `student` ADD KEY `idx_student_teacher` (`teacher_id`)");
}

$time = date("H");
    /* Set the $timezone variable to become the current timezone */
$timezone = date("e");
    /* If the time is less than 1200 hours, show good morning */
if ($time < "12") {
     $greet= "Good Morning";
     $img="img/mng.jpg";
} else
    /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
 if ($time >= "12" && $time < "17") {
    $greet= "Good Afternoon";
    $img="img/aftn.jpg";
  } else
    /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
 if ($time >= "17" && $time < "19") {
    $greet= "Good Evening";
    $img="img/evng.jpg";
} else
    /* Finally, show good Evening if the time is greater than or equal to 1900 hours */
 if ($time >= "19") {
    $greet= "Good Evening";
    $img="img/evng.jpg";
}

?>