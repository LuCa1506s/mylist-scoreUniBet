<?php
require_once __DIR__.'/db.php';
function start_session() {
  if (session_status()===PHP_SESSION_NONE) {
    session_start();
  }
}
function login_user($user_id) {
  start_session();
  session_regenerate_id(true);
  $_SESSION['uid']=$user_id;
}
function logout_user() {
  start_session();
  $_SESSION=[];
  if (ini_get("session.use_cookies")) {
    $params=session_get_cookie_params();
    setcookie(session_name(),'',time()-42000,$params['path'],$params['domain'],$params['secure'],$params['httponly']);
  }
  session_destroy();
}
function current_user() {
  start_session();
  if (!isset($_SESSION['uid'])) return null;
  $stmt=db()->prepare('SELECT id,email,username,credits,created_at FROM users WHERE id=?');
  $stmt->execute([$_SESSION['uid']]);
  return $stmt->fetch();
}
function require_login() {
  $u=current_user();
  if (!$u) {
    header('Location: /index.php');
    exit;
  }
  return $u;
}

