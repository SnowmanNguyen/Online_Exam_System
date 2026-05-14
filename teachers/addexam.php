<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login_teacher.php');
    exit;
}
include('../config.php');

$tid = (int) $_SESSION['user_id'];

//Below code to add exam details

if (isset($_POST["addexm"])) {
    $exname = mysqli_real_escape_string($conn, $_POST["exname"]);
    $nq = mysqli_real_escape_string($conn, $_POST["nq"]);
    $desp = mysqli_real_escape_string($conn, $_POST["desp"]);
    $subt = mysqli_real_escape_string($conn, str_replace('T', ' ', $_POST["subt"]));
    $extime = mysqli_real_escape_string($conn, str_replace('T', ' ', $_POST["extime"]));
    $subject = mysqli_real_escape_string($conn, $_POST["subject"]);
      $sql = "INSERT INTO exm_list (exname, nq, desp, subt, extime, subject, teacher_id) VALUES ('$exname', '$nq', '$desp', '$subt', '$extime', '$subject', $tid)";
      $result = mysqli_query($conn, $sql);
      if ($result) {
          header("Location: exams.php");
          exit;
      } else {
        echo "<script>alert('Adding exam failed.');</script>";
        header("Location: exams.php");
        exit;
    }
    }

// ********************************************

//Below code to add question to database

if (isset($_POST["addqp"])) {
    $nq = mysqli_real_escape_string($conn, $_POST["nq"]);
    $exid = (int) $_POST["exid"];
    $own = mysqli_query($conn, "SELECT exid FROM exm_list WHERE exid=$exid AND teacher_id=$tid LIMIT 1");
    if (!$own || mysqli_num_rows($own) === 0) {
        echo "<script>alert('Bạn không có quyền chỉnh sửa đề này.');window.location.href='exams.php';</script>";
        exit;
    }
    for($i=1; $i<=$nq; $i++){
        $q = mysqli_real_escape_string($conn, $_POST['q' . $i]);
        $o1 = mysqli_real_escape_string($conn, $_POST['o1' . $i]);
        $o2 = mysqli_real_escape_string($conn, $_POST['o2' . $i]);
        $o3 = mysqli_real_escape_string($conn, $_POST['o3' . $i]);
        $o4 = mysqli_real_escape_string($conn, $_POST['o4' . $i]);
        $a = mysqli_real_escape_string($conn, $_POST['a' . $i]);
        $sql = "INSERT INTO qstn_list (exid, qstn, qstn_o1, qstn_o2, qstn_o3, qstn_o4, qstn_ans, sno) VALUES ('$exid', '$q', '$o1', '$o2', '$o3', '$o4', '$a', '$i')";
        $result = mysqli_query($conn, $sql);
    }   
      if ($result) {
          header("Location: exams.php");
          exit;
      } else {
        echo "<script>alert('Updating questions failed.');</script>";
        header("Location: exams.php");
        exit;
    }
    }

// ********************************************



?>
