<?php
session_start();
require_once '../includes/db.php';

$money = '0,00';

if (!isset($_SESSION['idP']) || !isset($dataB)) {
    die("Devi essere loggato per piazzare una scommessa.");
}

if (!isset($_GET['group_id'])) {
    die("Gruppo non specificato.");
}

$groupId = (int)$_GET['group_id'];
$userId  = $_SESSION['idP'];

// Recupera info gruppo/esame
$stmt = $dataB->conn->prepare("SELECT name, owner_id FROM groups WHERE id = ?");
$stmt->bind_param('i', $groupId);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die("Gruppo inesistente.");
}
$group = $res->fetch_assoc();
$stmt->close();

// Recupera membri (incluso admin se la funzione è aggiornata)
$members = $dataB->getGroupMembers1($groupId);

// Recupera saldo utente
$rawMoney = $dataB->getmoneyByIdU($userId);
if ($rawMoney !== null && $rawMoney !== false && is_numeric($rawMoney)) {
    $money = number_format((float)$rawMoney, 2, ',', '.');
}

// Verifica se l'utente è admin del gruppo
$isAdmin = ($group['owner_id'] == $userId);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Piazza scommessa</title>
    <link rel="stylesheet" href="../css/style-bets.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header class="top-bar">
    <div class="left-area">
        <div class="logout-container">
            <form method="post" action="../public/index.php">
                <button type="submit" class="logout-btn"><strong> HOME </strong></button>
            </form>
        </div>
    </div>
    <div class="right-area">
        <div class="money-container" aria-label="Saldo utente">
            <span class="money-value"><?php echo htmlspecialchars($money, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="money-currency" aria-hidden="true">$</span>
        </div>
    </div>
</header>

<div class="forms-wrapper" style="display:flex; gap:40px; justify-content:center; align-items:flex-start;">

    <!-- Form piazzamento scommessa -->
    <div class="bet-form-container">
        <form method="post" action="placeBet.php">
            <h1>Piazza una scommessa sull'esame: <?php echo htmlspecialchars($group['name']); ?></h1>
            <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">
            
            <label>Studente su cui puntare:</label>
            <select name="target_user_id" class="type-input" required>
                <?php foreach ($members as $m): ?>
                    <option value="<?php echo $m['id']; ?>">
                        <?php echo htmlspecialchars($m['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            
            <label>Voto previsto:</label>
            <select name="predicted_grade" class="type-input" required>
                <?php
                $grades = ['18','19','20','21','22','23','24','25','26','27','28','29','30','30L'];
                foreach ($grades as $g) {
                    echo "<option value=\"$g\">$g</option>";
                }
                ?>
            </select>
            <br><br>
            
            <label>Importo crediti da puntare:</label>
            <input type="number" name="amount" min="1" value="1" class="type-input" required>
            <br><br>
            
            <button type="submit" class="primary-btn">Piazza scommessa</button>
        </form>
    </div>

    <!-- Form chiusura esame (solo admin) -->
    <?php if ($isAdmin): ?>
    <div class="bet-form-container">
        <form method="post" action="../includes/closeExam.php">
            <h1>Chiudi esame: <?php echo htmlspecialchars($group['name']); ?></h1>
            <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">
            
            <label>Voto finale ricevuto:</label>
            <select name="final_grade" class="type-input" required>
                <?php
                $grades = ['18','19','20','21','22','23','24','25','26','27','28','29','30','30L'];
                foreach ($grades as $g) {
                    echo "<option value=\"$g\">$g</option>";
                }
                ?>
            </select>
            <br><br>
            
            <button type="submit" class="primary-btn">Chiudi esame</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<!-- Tabella partecipanti -->
<div class="participants-container" style="margin:40px auto; max-width:800px;">
    <h2>Partecipanti del gruppo "<?php echo htmlspecialchars($group['name']); ?>"</h2>
    <?php echo $dataB->visPartecipanti($groupId, $isAdmin, $userId); ?>
</div>

</body>
</html>
