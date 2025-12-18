
<a href="../public/login.php">Go to login</a><?php
require_once __DIR__.'/config.php';

session_start();
include 'db.php';
$user=$_POST['username-reg'];
$pass=$_POST['password-reg'];
$email=$_POST['email-reg'];

$ret=$dataB->insUtente($user,$pass,$email, $INITIAL_CREDITS, 1);
$_SESSION['set']= $ret;
if($ret== 0){
    header('Location:notuser.php');
    exit;
}
else if($ret == 1){
    header("../public/login.php");
}
else{
    echo "<br>".$ret."<br>";
    echo "login errato";
    header('Location: ../public/login.php');
}

?>