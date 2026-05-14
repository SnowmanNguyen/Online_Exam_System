<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login_teacher.php');
    exit;
}
include('../config.php');

$tid = (int) $_SESSION['user_id'];

if (isset($_POST["adduser"])) {
    $fname = mysqli_real_escape_string($conn, $_POST["fname"]);
    $uname = mysqli_real_escape_string($conn, $_POST["uname"]);
    $dob = mysqli_real_escape_string($conn, $_POST["dob"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pword = mysqli_real_escape_string($conn, md5($_POST["pword"]));
    $cpword = mysqli_real_escape_string($conn, md5($_POST["cpword"]));
  
    $check_user = mysqli_num_rows(mysqli_query($conn, "SELECT uname FROM student WHERE uname='$uname'"));
  
    if ($pword !== $cpword) {
        echo "<script>alert('Password did not match. Please try again');</script>";
        header("Location: records.php");
        exit;
    } elseif ($check_user > 0) {
      echo "<script>alert('Username already exists in the database.');</script>";
      header("Location: records.php");
      exit;
    } else {
      $sql = "INSERT INTO student (uname, pword, fname, dob, gender, email, teacher_id) VALUES ('$uname', '$pword', '$fname', '$dob', '$gender', '$email', $tid)";
      $result = mysqli_query($conn, $sql);
      if ($result) {
          header("Location: records.php");
          exit;
      } else {
        echo "<script>alert('Student registration failed.');</script>";
        header("Location: records.php");
        exit;
    }
    }
  }
?>
