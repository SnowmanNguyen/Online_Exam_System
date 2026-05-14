<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login_teacher.php');
    exit;
}
include('../config.php');

$tid = (int) $_SESSION['user_id'];

if (!isset($_POST['updatebtn'])) {
    header("Location: records.php");
    exit;
}

$id = (int) $_POST["id"];
$own = mysqli_query($conn, "SELECT id FROM student WHERE id=$id AND teacher_id=$tid LIMIT 1");
if (!$own || mysqli_num_rows($own) === 0) {
    header("Location: records.php");
    exit;
}

$fname = mysqli_real_escape_string($conn, $_POST["fname"]);
$dob = mysqli_real_escape_string($conn, $_POST["dob"]);
$gender = mysqli_real_escape_string($conn, $_POST["gender"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$sql = "UPDATE student SET fname='$fname', dob ='$dob', gender='$gender', email ='$email' WHERE id=$id AND teacher_id=$tid";
$query_run = mysqli_query($conn, $sql);
if ($query_run) {
    echo "<script>alert('Profile details updated!.');</script>";
} else {
    echo "<script>alert('Updation failed.');</script>";
}
header("Location: records.php");
exit;

?>
