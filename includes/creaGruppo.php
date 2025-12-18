<?php
session_start();
include 'db.php';
$name=$_POST['group-name'];
$color=$_POST['group-color'];
$id=$_SESSION["idP"];

$ret=$dataB->adGroup($name, $color, $id);
$_SESSION['set']= $ret;
if($ret == 1) {
    header("Location: ../public/index.php");
    exit;
} else {
    echo "<br>".$ret."<br>";
    echo "creazione gruoppo non riuscita";
    header('Location: ../public/index.php');
}
?>