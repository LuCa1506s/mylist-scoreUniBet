<?php
session_start();
include 'db.php';
$user=$_POST['userName'];
$pass=$_POST['password-login'];

$ret=$dataB->controllologin($user,$pass);
$_SESSION['set']= $ret;
if($ret== 0){
    header('Location:notuser.php');
} else if($ret == 1) {
    header("Location: ../public/index.php");
    exit;
} else {
    echo "<br>".$ret."<br>";
    echo "login errato";
    header('Location: ../public/login.php'
);
}
?>