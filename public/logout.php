<?php
session_start();

// Elimina tutte le variabili di sessione
$_SESSION = [];

// Distrugge la sessione
session_destroy();

// (Opzionale) elimina il cookie di sessione se esiste
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect alla home o alla pagina di login
header("Location: ../public/index.php");
exit;
