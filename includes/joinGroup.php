<?php
session_start();
include 'db.php';
$token=$_POST['tokenG'];
$idU=$_SESSION['idP'];
$role="member";

$ret=$dataB->adPartecipanteG($idU, $role, $token);
$_SESSION['set']= $ret;
if($ret == 1) {
    header("Location: ../public/index.php");
    exit;
} else {
    echo "impossibile unirsi al gruppo, token errato o gruppo non esistente";
    header('Location: ../public/index.php');
}
?>