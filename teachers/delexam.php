<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login_teacher.php');
    exit;
}
include('../config.php');

$tid = (int) $_SESSION['user_id'];

if(isset($_POST['delete_btn']))
{
    $id = (int) $_POST['delete_id'];

    $chk = mysqli_query($conn, "SELECT exid FROM exm_list WHERE exid=$id AND teacher_id=$tid LIMIT 1");
    if (!$chk || mysqli_num_rows($chk) === 0) {
        header('Location: exams.php');
        exit;
    }

    mysqli_query($conn, "DELETE FROM qstn_list WHERE exid=$id");
    mysqli_query($conn, "DELETE FROM atmpt_list WHERE exid=$id");
    $query_run = mysqli_query($conn, "DELETE FROM exm_list WHERE exid=$id AND teacher_id=$tid");
    
    if($query_run)
    {
        header('Location: exams.php'); 
        exit;
    }
    else
    {
        echo "<script>alert('Your Data is NOT DELETED');</script>";      
        header('Location: exams.php'); 
        exit;
    }   


}
else {
    header('Location: exams.php');
    exit;
}
?>
