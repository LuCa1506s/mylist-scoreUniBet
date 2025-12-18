<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['idP'])) {
    die("Devi essere loggato per chiudere un esame.");
}

$userId   = (int)$_SESSION['idP'];
$groupId  = (int)$_POST['group_id'];
$finalGrade = $_POST['final_grade'];

// Validazioni di base
if ($groupId <= 0) {
    die("Gruppo non valido.");
}
if (empty($finalGrade)) {
    die("Voto finale mancante.");
}

// Verifica che l'utente sia l'admin del gruppo
$ownerId = $dataB->getGroupOwner($groupId);
if ($ownerId !== $userId) {
    die("Non sei l'admin di questo gruppo, non puoi chiudere l'esame.");
}

// Chiudi esame e calcola payout
$result = $dataB->closeExam($groupId, $finalGrade);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Chiusura esame</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="bet-form-container">
<?php if ($result['success']): ?>
    <h2>Esame chiuso con successo!</h2>
    <p>Il voto finale inserito è: <strong><?php echo htmlspecialchars($finalGrade); ?></strong></p>
    <p>Tutte le scommesse sono state valutate e i crediti aggiornati.</p>
    <a href="../public/index.php" class="primary-btn">Torna alla dashboard</a>
<?php else: ?>
    <h2>Errore nella chiusura dell'esame</h2>
    <p><?php echo htmlspecialchars($result['error']); ?></p>
    <a href="bets.php?group_id=<?php echo $groupId; ?>" class="primary-btn">Riprova</a>
<?php endif; ?>
</div>
</body>
</html>
