<?php
require_once __DIR__.'/config.php';
class Db
{
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', '', 'scoreunibet');

        if ($this->conn->connect_error)
            echo "filed to connect to mySql: " . $this->conn->connect_error;
    }
    /*
    public function controllologin($user, $pass)
    {
        $user = $this->conn->real_escape_string($user);
        $pass = md5($this->conn->real_escape_string($pass));
        $sql = sprintf(
            "select * from users where username='%s' and password_hash='%s'",
            $user,
            $pass
        );
        echo $sql;
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0)
            return -1;
        $row = $result->fetch_assoc();
        $_SESSION["idP"] = $row['id'];
        return $row['type'];
    }
    */

    public function controllologin($user, $pass)
{
    // Query: selezioniamo anche la colonna 'type' se esiste (COALESCE evita NULL)
    $sql = "SELECT id, password_hash, COALESCE(type, '') AS type FROM users WHERE username = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        error_log("controllologin prepare failed: " . $this->conn->error);
        return -1;
    }

    // Bind input e esecuzione
    $stmt->bind_param('s', $user);
    if (!$stmt->execute()) {
        error_log("controllologin execute failed: " . $stmt->error);
        $stmt->close();
        return -1;
    }

    // Bind dei risultati (compatibile anche senza mysqlnd/get_result)
    $stmt->bind_result($dbId, $dbHash, $dbType);
    if (!$stmt->fetch()) {
        // nessun utente trovato
        $stmt->close();
        return -1;
    }
    // Chiudiamo lo statement (i valori rimangono nelle variabili)
    $stmt->close();

    // Sicurezza: assicurati che ci sia un hash
    if (empty($dbHash)) {
        return -1;
    }

    // Verifica password
    if (!password_verify($pass, $dbHash)) {
        return -1;
    }

    // Se l'hash è obsoleto, rigeneralo
    if (password_needs_rehash($dbHash, PASSWORD_DEFAULT)) {
        $newHash = password_hash($pass, PASSWORD_DEFAULT);
        $upd = $this->conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        if ($upd) {
            $upd->bind_param('si', $newHash, $dbId);
            if (!$upd->execute()) {
                error_log("controllologin rehash update failed: " . $upd->error);
            }
            $upd->close();
        }
    }

    // Imposta sessione e ritorna type se presente, altrimenti id
    $_SESSION['idP'] = (int)$dbId;
    $dbType = (string)$dbType;
    return 1;
}


    /*
    public function insUtente($login, $pass, $email, $cr, $tipo)
    {
        $sql = sprintf(
            "insert into users values(null, '%s', '%s', '%s', '%s', %s, '%s')",
            $login,
            $email,
            md5($pass),
            $cr,
            date('Y-m-d'),
            $tipo
        );
        if ($this->conn->query($sql))
            return 1;
        else
            return 0;
    }
    */
    public function insUtente($username, $pass, $email, $credits = 1000)
    {
        $password_hash = password_hash($pass, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO users (username, email, password_hash, credits, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param('sssds', $username, $email, $password_hash, $credits, $created_at);
        $ok = $stmt->execute();
        if (!$ok) {
            error_log("insUtente error: " . $stmt->error);
            $stmt->close();
            return false;
        }
        $newId = $stmt->insert_id;
        $stmt->close();
        return $newId;
    }



    public function creatnwtocken() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($chars);
        $token = '';

        for ($i = 0; $i < 8; $i++) {
            $token .= $chars[rand(0, $len - 1)];
        }

        return $token;
    }

    public function getIdGbytocken($token){

        //function get grup_id by token
        $sql = sprintf("SELECT * FROM groups WHERE invite_token='%s'", $token);
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0) {
            return -1;
        }
        $row = $result->fetch_assoc();
        return $row['id'];
    }


    public function adPartecipanteG($idU, $role, $token){
        $idG= $this->getIdGbytocken($token);
        echo $idG;
        $sql = sprintf(
            "insert into group_members values(null, %s, %s, '%s', %s)",
            $idG,
            $idU,
            $role,
            date('Y-m-d')
        );
        if ($this->conn->query($sql))
            return 1;
        else
            return -1;
    }

    public function adGroup($name, $color, $id)
    {
        $token = $this->creatnwtocken();
        $sql = sprintf(
            "insert into groups values(null, %s, '%s', '%s', %s, '%s', %s, %s, '%s');",
            $id,
            $name,
            $token,
            date('Y-m-d'),
            $color,
            '0-0-0',
            0,
            'open'
        );
        echo $sql;
        if ($this->conn->query($sql)){
            $this->adPartecipanteG($id, "admin", $token);
            return 1;
        } else
            return 0;
    }

    public function getGroupMembers1($groupId) {
    $sql = "SELECT u.id, u.username, gm.role
            FROM group_members gm
            JOIN users u ON gm.user_id = u.id
            WHERE gm.group_id = ?
            UNION
            SELECT u.id, u.username, 'admin' AS role
            FROM groups g
            JOIN users u ON g.owner_id = u.id
            WHERE g.id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('ii', $groupId, $groupId);
    $stmt->execute();
    $res = $stmt->get_result();
    $members = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $members;
}

    public function visGruppi($id)
{
    $sql = sprintf("SELECT DISTINCT g.id, g.name AS group_name, g.invite_token AS token, g.status AS group_status, g.colore
    FROM groups g
    LEFT JOIN group_members gm ON gm.group_id = g.id
    WHERE (gm.user_id = %s OR g.owner_id = %s) AND g.status = 'open';",
    $id,
    $id);

    $result = $this->conn->query($sql);

    if ($result->num_rows == 0) {
        return -1;
    }

    $r = "<table class='table table-hover table-striped mt-3'>";
    $r .= "<thead>";
    $r .= "<tr>";
    $r .= "<th>name</th>";
    $r .= "<th>token</th>";
    $r .= "<th scope='col'>color</th>";
    $r .= "<th scope='col'>copia token</th>";   // nuova colonna
    $r .= "</tr>";
    $r .= "</thead>";
    $r .= "<tbody>";

    while ($row = $result->fetch_assoc()) {
        $safeId    = (int)$row['id'];
        $safeName  = htmlspecialchars($row['group_name'], ENT_QUOTES, 'UTF-8');
        $safeToken = htmlspecialchars($row['token'], ENT_QUOTES, 'UTF-8');
        $safeColor = htmlspecialchars($row['colore'], ENT_QUOTES, 'UTF-8');

        $r .= "<tr class='gruppo' onclick=\"window.location='bets.php?group_id={$safeId}'\" style='cursor:pointer'>";
        $r .= "<td>{$safeName}</td>";
        $r .= "<td>{$safeToken}</td>";
        $r .= "<td class='color-cell'><span class='color-dot' style='background: {$safeColor}'></span></td>";
        // pulsante copia token
        $r .= "<td><button type='button' class='copy-btn' onclick=\"event.stopPropagation(); copyToken('{$safeToken}')\">Copia</button></td>";
        $r .= "</tr>";
    }

    $r .= "</tbody>";
    $r .= "</table>";

    // aggiungiamo lo script JS per la copia
    $r .= "<script>
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(function() {
                alert('Token copiato: ' + token);
            }, function(err) {
                alert('Errore nella copia del token');
            });
        }
    </script>";

    return $r;
}

    public function visPartecipanti($groupId, $isAdmin, $currentUserId)
{
    $sql = "SELECT u.id, u.username, gm.role
            FROM group_members gm
            JOIN users u ON gm.user_id = u.id
            WHERE gm.group_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('i', $groupId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        return "<p>Nessun partecipante trovato.</p>";
    }

    $r = "<table class='table table-hover table-striped mt-3'>";
    $r .= "<thead>";
    $r .= "<tr>";
    $r .= "<th>Nome</th>";
    $r .= "<th>Ruolo</th>";
    $r .= "<th>Elimina partecipante</th>";
    $r .= "</tr>";
    $r .= "</thead>";
    $r .= "<tbody>";

    while ($row = $res->fetch_assoc()) {
        $safeId   = (int)$row['id'];
        $safeName = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
        $safeRole = htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8');

        $r .= "<tr>";
        $r .= "<td>{$safeName}</td>";
        $r .= "<td>{$safeRole}</td>";

        // Solo admin può eliminare e non se stesso
        if ($isAdmin && $safeId != $currentUserId) {
            $r .= "<td>
                        <form method='post' action='../includes/removeMember.php' style='margin:0;'>
                            <input type='hidden' name='group_id' value='{$groupId}'>
                            <input type='hidden' name='user_id' value='{$safeId}'>
                            <button type='submit' class='copy-btn'>🗑️</button>
                        </form>
                    </td>";
        } else {
            $r .= "<td>non sei autorizzato a rimuovere membri</td>";
        }

        $r .= "</tr>";
    }

    $r .= "</tbody>";
    $r .= "</table>";

    $stmt->close();
    return $r;
}

    public function getmoneyByIdU($idU){
        $sql = sprintf("SELECT credits FROM users WHERE id = %s;", $idU);
        $result = $this->conn->query($sql);

        if ($result->num_rows == 0)
            return -1;
        else { 
            $row = $result->fetch_assoc();
            return $row['credits'];
        }
    }

    public function getGroupMembers($groupId) {
        $sql = "SELECT u.id, u.username, gm.role 
                FROM group_members gm
                JOIN users u ON gm.user_id = u.id
                WHERE gm.group_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $res = $stmt->get_result();
        $members = [];
        while ($row = $res->fetch_assoc()) {
            $members[] = $row;
        }
        $stmt->close();
        return $members;
    }

    //piazzamento scommessa
    public function placeBet($groupId, $userId, $targetUserId, $predictedGrade, $amount){
        try {
            $this->conn->begin_transaction();

            // 1. Lock saldo utente
            $stmt = $this->conn->prepare("SELECT credits FROM users WHERE id = ? FOR UPDATE");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) {
                $stmt->close();
                $this->conn->rollback();
                return ['success' => false, 'error' => 'Utente non trovato'];
            }
            $row = $res->fetch_assoc();
            $stmt->close();

            $credits = (float)$row['credits'];
            if ($credits < $amount) {
                $this->conn->rollback();
                return ['success' => false, 'error' => 'Crediti insufficienti'];
            }

            // 2. Inserisci la scommessa
            $placedAt = date('Y-m-d H:i:s');
            $stmt = $this->conn->prepare(
                "INSERT INTO bets (group_id, user_id, target_user_id, predicted_grade, amount, placed_at)
                VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('iiisds', $groupId, $userId, $targetUserId, $predictedGrade, $amount, $placedAt);
            if (!$stmt->execute()) {
                $error = $stmt->error;
                $stmt->close();
                $this->conn->rollback();
                return ['success' => false, 'error' => "Errore inserimento bet: $error"];
            }
            $betId = $stmt->insert_id;
            $stmt->close();

            // 3. Inserisci transazione
            $stmt = $this->conn->prepare(
                "INSERT INTO transactions (user_id, type, amount, description, created_at, related_bet_id, group_id)
                VALUES (?, 'bet_place', ?, ?, ?, ?, ?)"
            );
            $desc = "Scommessa piazzata sul gruppo/esame $groupId (utente $targetUserId)";
            $createdAt = $placedAt;
            $stmt->bind_param('idssii', $userId, $amount, $desc, $createdAt, $betId, $groupId);
            if (!$stmt->execute()) {
                $error = $stmt->error;
                $stmt->close();
                $this->conn->rollback();
                return ['success' => false, 'error' => "Errore inserimento transazione: $error"];
            }
            $stmt->close();

            // 4. Aggiorna saldo utente
            $stmt = $this->conn->prepare("UPDATE users SET credits = credits - ? WHERE id = ?");
            $stmt->bind_param('di', $amount, $userId);
            if (!$stmt->execute()) {
                $error = $stmt->error;
                $stmt->close();
                $this->conn->rollback();
                return ['success' => false, 'error' => "Errore aggiornamento saldo: $error"];
            }
            $stmt->close();

            // 5. Commit
            $this->conn->commit();
            return ['success' => true, 'bet_id' => $betId];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
        
        
    }
    
    //ottieni l'id dell admin del gruppo
    public function getGroupOwner($groupId) {
        $stmt = $this->conn->prepare("SELECT owner_id FROM groups WHERE id = ?");
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $stmt->close();
            return null;
        }
        $row = $res->fetch_assoc();
        $stmt->close();
        return (int)$row['owner_id'];
    }

    

    public function closeExam($groupId, $finalGrade)
{
    try {
        $this->conn->begin_transaction();

        // 1. Aggiorna gruppo
        $closedAt = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare(
            "UPDATE groups SET final_grade = ?, closed_at = ?, status = 'closed' WHERE id = ?"
        );
        $stmt->bind_param('ssi', $finalGrade, $closedAt, $groupId);
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            $this->conn->rollback();
            return ['success' => false, 'error' => "Errore aggiornamento gruppo: $error"];
        }
        $stmt->close();

        // 2. Recupera tutte le scommesse del gruppo
        $stmt = $this->conn->prepare(
            "SELECT id, user_id, target_user_id, predicted_grade, amount FROM bets WHERE group_id = ?"
        );
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $res = $stmt->get_result();
        $bets = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // 3. Valuta ogni scommessa
        foreach ($bets as $bet) {
            $predicted = $bet['predicted_grade'];
            $amount    = (float)$bet['amount'];

            // Converti i voti in numeri (gestendo "30L" come 31 per comodità)
            $finalVal     = ($finalGrade === '30L') ? 31 : (int)$finalGrade;
            $predictedVal = ($predicted === '30L') ? 31 : (int)$predicted;

            $isWinner = 0;
            $payout   = 0;

            if ($predictedVal === $finalVal) {
                // Esatto → raddoppio
                $isWinner = 1;
                $payout   = $amount * 2;
            } elseif (abs($predictedVal - $finalVal) <= 2) {
                // Entro ±2 → incremento del 75%
                $isWinner = 1;
                $payout   = $amount * 1.75;
            } else {
                // Scommessa persa
                $isWinner = 0;
                $payout   = 0;
            }

            // Aggiorna bet
            $stmt = $this->conn->prepare(
                "UPDATE bets SET is_winner = ?, payout = ? WHERE id = ?"
            );
            $stmt->bind_param('idi', $isWinner, $payout, $bet['id']);
            if (!$stmt->execute()) {
                $error = $stmt->error;
                $stmt->close();
                $this->conn->rollback();
                return ['success' => false, 'error' => "Errore aggiornamento bet: $error"];
            }
            $stmt->close();

            // Aggiorna saldo utente se vincente
            if ($isWinner && $payout > 0) {
                $stmt = $this->conn->prepare("UPDATE users SET credits = credits + ? WHERE id = ?");
                $stmt->bind_param('di', $payout, $bet['user_id']);
                if (!$stmt->execute()) {
                    $error = $stmt->error;
                    $stmt->close();
                    $this->conn->rollback();
                    return ['success' => false, 'error' => "Errore aggiornamento saldo: $error"];
                }
                $stmt->close();
            }

            // Inserisci transazione
            $type = $isWinner ? 'bet_win' : 'bet_loss';
            $desc = $isWinner
                ? "Scommessa vinta sul gruppo $groupId"
                : "Scommessa persa sul gruppo $groupId";
            $createdAt = $closedAt;

            $stmt = $this->conn->prepare(
                "INSERT INTO transactions (user_id, type, amount, description, created_at, related_bet_id, group_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('isdsiii', $bet['user_id'], $type, $payout, $desc, $createdAt, $bet['id'], $groupId);
            if (!$stmt->execute()) {
                $error = $stmt->error;
                $stmt->close();
                $this->conn->rollback();
                return ['success' => false, 'error' => "Errore inserimento transazione: $error"];
            }
            $stmt->close();
        }

        // 4. Commit finale
        $this->conn->commit();
        return ['success' => true];

    } catch (Exception $e) {
        $this->conn->rollback();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}


// Fine class Db
}
$dataB = new Db();
?>