<?php
session_start();
include '../includes/db.php';

// Recupera saldo utente in modo sicuro
$money = '0,00';
if (isset($_SESSION['idP']) && isset($dataB)) {
    $id = $_SESSION['idP'];
    $rawMoney = $dataB->getmoneyByIdU($id);
    if ($rawMoney !== null && $rawMoney !== false && is_numeric($rawMoney)) {
        // Formato italiano: migliaia con punto, decimali con virgola
        $money = number_format((float)$rawMoney, 2, ',', '.');
    }
    }else{
        $money = '0,00';
        header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/copy-btn.css">
</head>
<body>

    <!-- Top bar: Logout a sinistra, saldo a destra -->
    <header class="top-bar">
        <div class="left-area">
            <div class="logout-container">
                <form method="post" action="../public/login.php">
                    <button type="submit" class="logout-btn">Logout</button>
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

    <div class="title-banner">
        <h1>SCOREUNIBET</h1>
    </div>

    <section class="dashboard-container">
        <div class="box groups-box">
            <h2>Gruppi</h2>
            <?php
            if (isset($_SESSION['idP']) && isset($dataB)) {
                $id = $_SESSION['idP'];
                $ret = $dataB->visGruppi($id);
                echo $ret;
            } else {
                echo '<p>Utente non autenticato.</p>';
            }
            ?>
        </div>

        <div class="box create-group-box">
            <h2>Crea un nuovo gruppo</h2>
            <form method="post" action="../includes/creaGruppo.php" class="auth-form">
                <label>Nome</label>
                <input type="text" name="group-name" placeholder="Nome" style="width:100%; padding:10px; margin-bottom:10px; border-radius:8px; border:1px solid #ccc;">
                <label>Colore</label>
                <input type="color" name="group-color" style="padding:5px; margin-bottom:10px;">
                <button type="submit" class="primary-btn">Crea Gruppo</button>
            </form>
        </div>

        <div class="box join-delete-box">
            <h2>Gestione gruppo</h2>
            <div style="margin-bottom:15px;">
                <h3 style="margin:6px 0;">Unisciti a un gruppo</h3>
                <form method="post" action="../includes/joinGroup.php" class="auth-form">
                    <input type="text" name="tokenG" placeholder="token" style="width:100%; padding:10px; margin-bottom:10px; border-radius:8px; border:1px solid #ccc;">
                    <button type="submit" class="primary-btn">Unisciti</button>
                </form>
            </div>
        </div>
    </section>

</body>
</html>
