<?php
require_once 'includes/session_check.php';
require_once 'includes/db_connect.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Atualiza o evento para 'lido', garantindo que pertence ao usuário logado
    $stmt = $pdo->prepare("UPDATE schedule SET is_read = 1 WHERE id = :id AND user_id = :uid");
    $stmt->execute(['id' => $event_id, 'uid' => $user_id]);
}

// Redireciona o usuário para a página de onde ele veio
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>