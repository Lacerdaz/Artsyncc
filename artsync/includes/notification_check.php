<?php
// Inicia a sessão se ainda não foi iniciada (segurança)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$notifications = [];
$notification_count = 0;

// Só busca notificações se o usuário estiver logado
if (isset($_SESSION['user_id'])) {
    require_once 'db_connect.php'; // Garante a conexão com o BD

    // Busca eventos não lidos que ocorrerão nas próximas 24 horas
    $stmt = $pdo->prepare(
        "SELECT id, event_title, event_date FROM schedule 
         WHERE user_id = :uid AND is_read = 0 AND event_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
         ORDER BY event_date ASC"
    );
    $stmt->execute(['uid' => $_SESSION['user_id']]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $notification_count = count($notifications);
}
?>