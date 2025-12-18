<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['idP'])) {
    die("Devi essere loggato per gestire i partecipanti.");
}

$userId   = (int)$_SESSION['idP'];       // utente corrente (admin)
$groupId  = (int)$_POST['group_id'];     // gruppo da cui rimuovere
$targetId = (int)$_POST['user_id'];      // partecipante da rimuovere

if ($groupId <= 0 || $targetId <= 0) {
    die("Parametri non validi.");
}

// Verifica che l'utente corrente sia admin del gruppo
$ownerId = $dataB->getGroupOwner($groupId);
if ($ownerId !== $userId) {
    die("Non sei l'admin di questo gruppo.");
}

// Impedisci che l'admin si elimini da solo
if ($targetId === $userId) {
    die("Non puoi eliminare te stesso dal gruppo.");
}

try {
    // Rimuovi il partecipante dalla tabella group_members
    $stmt = $dataB->conn->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
    $stmt->bind_param('ii', $groupId, $targetId);
    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        die("Errore nella rimozione: $error");
    }
    $stmt->close();

    // Redirect alla pagina bets.php per il gruppo
    header("Location: ../public/bets.php?group_id=" . $groupId);
    exit;

} catch (Exception $e) {
    die("Errore: " . $e->getMessage());
}
