<?php
require_once __DIR__.'/../includes/auth.php';
$u=require_login();
require_once __DIR__.'/../templates/header.php';
?>
<section class="card">
<h2>Ciao <?php echo htmlspecialchars($u['username'],ENT_QUOTES,'UTF-8'); ?></h2>
<p>Saldo: <?php echo number_format($u['credits'],0,',','.'); ?> crediti</p>
<div class="actions">
<a class="btn" href="#">I miei gruppi</a>
<a class="btn" href="#">Crea gruppo</a>
</div>
</section>
<?php
require_once __DIR__.'/../templates/footer.php';
?>

