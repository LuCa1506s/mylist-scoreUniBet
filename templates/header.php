<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ScoreUniBet</title>
<?php require_once __DIR__.'/../includes/config.php'; ?>
<link rel="stylesheet" href="<?php echo $BASE_URL; ?>/css/styles.css">
</head>
<body>
<header class="site-header">
<div class="container">
<a class="logo" href="<?php echo $BASE_URL; ?>/index.php">ScoreUniBet</a>
<nav>
<?php
require_once __DIR__.'/../includes/auth.php';
$u=current_user();
if ($u) {
echo '<a href="'.$BASE_URL.'/dashboard.php">Dashboard</a>';
echo '<a href="'.$BASE_URL.'/logout.php">Logout</a>';
} else {
echo '<a href="'.$BASE_URL.'/index.php">Login</a>';
}
?>
</nav>
</div>
</header>
<main class="container">

