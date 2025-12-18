<?php
session_start();
include 'db.php';
$user=$_POST['user'];
$pass=$_POST['pass'];

$ret=$dataB->controllologin($user,$pass);
$_SESSION['set']= $ret;
if($ret== 0){
    header('Location:admin.php');
    exit;
}
else if($ret== 1){
    header('Location:stud.php');
}
else if($ret== 2){
    header('Location:prof.php');
}
else{
    echo "<br>".$ret."<br>";
    echo "login errato";
    header('Location:login.php');
}
?>