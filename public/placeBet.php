<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['idP'])) {
    die("Devi essere loggato per piazzare una scommessa.");
}

$userId        = (int)$_SESSION['idP'];              // chi piazza la scommessa
$groupId       = (int)$_POST['group_id'];            // gruppo/esame
$targetUserId  = (int)$_POST['target_user_id'];      // studente su cui si punta
$predictedGrade = $_POST['predicted_grade'];         // voto previsto
$amount        = (int)$_POST['amount'];              // crediti puntati

// Validazioni di base
if ($amount <= 0) {
    die("Importo non valido.");
}
if (empty($predictedGrade)) {
    die("Voto previsto mancante.");
}
if ($targetUserId <= 0) {
    die("Devi selezionare uno studente del gruppo.");
}

// Chiama la funzione placeBet aggiornata
$result = $dataB->placeBet($groupId, $userId, $targetUserId, $predictedGrade, $amount);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Risultato scommessa</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="bet-form-container">
<?php if ($result['success']): ?>
    <h2>Scommessa piazzata con successo!</h2>
    <p>ID scommessa: <?php echo htmlspecialchars($result['bet_id']); ?></p>
    <a href="../public/index.php" class="primary-btn">Torna alla dashboard</a>
<?php else: ?>
    <h2>Errore nella scommessa</h2>
    <p><?php echo htmlspecialchars($result['error']); ?></p>
    <a href="bets.php?group_id=<?php echo $groupId; ?>" class="primary-btn">Riprova</a>
<?php endif; ?>
</div>
</body>
</html>
