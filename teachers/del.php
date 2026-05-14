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

    $query_run = mysqli_query($conn, "DELETE FROM student WHERE id=$id AND teacher_id=$tid");

    if($query_run && mysqli_affected_rows($conn) > 0)
    {
        header('Location: records.php'); 
        exit;
    }
    else
    {
        echo "<script>alert('Không xóa được (học sinh không thuộc quyền quản lý của bạn).');</script>";      
        header('Location: records.php'); 
        exit;
    }   
}
else {
    header('Location: records.php');
    exit;
}
?>
