<?php
// Este script deve ser incluído no topo de todas as páginas protegidas
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se a variável de sessão do usuário não estiver definida, redireciona para a página de login
if (!isset($_SESSION['user_id'])) {
    // Código CORRIGIDO
header("Location: /artsync/index.php");
    exit;
}
?>