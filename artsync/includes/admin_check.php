<?php
// ATENÇÃO: Este arquivo agora assume que a sessão JÁ FOI INICIADA
// e que a conexão com o banco ($pdo) JÁ EXISTE.

// Busca o status de admin do usuário logado no banco de dados
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Se o usuário não for encontrado ou não for admin, redireciona para o dashboard comum
if (!$user || $user['is_admin'] == 0) {
    // Usamos o caminho absoluto para garantir que funcione de qualquer lugar
    header("Location: /artsync/dashboard.php");
    exit;
}
?>