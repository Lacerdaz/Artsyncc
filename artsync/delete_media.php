<?php
require_once 'includes/session_check.php'; 
require_once 'includes/db_connect.php';

if (isset($_GET['id'])) {
    $media_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // 1. Busca o caminho do arquivo no banco para poder deletá-lo do servidor
    $stmt = $pdo->prepare("SELECT file_path FROM portfolio WHERE id = :id AND user_id = :uid");
    $stmt->execute(['id' => $media_id, 'uid' => $user_id]);
    $item = $stmt->fetch();

    if ($item) {
        $file_path = $item['file_path'];

        // 2. Deleta o registro do banco de dados
        $delete_stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = :id AND user_id = :uid");
        $delete_stmt->execute(['id' => $media_id, 'uid' => $user_id]);

        // 3. Deleta o arquivo físico do servidor, se ele existir
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Armazena feedback na sessão (opcional, mas bom para UX)
        $_SESSION['feedback_message'] = "Mídia excluída com sucesso!";
        $_SESSION['feedback_type'] = "success";

    } else {
        $_SESSION['feedback_message'] = "Erro: Mídia não encontrada ou você não tem permissão para excluí-la.";
        $_SESSION['feedback_type'] = "error";
    }
}

header("Location: portfolio.php");
exit;
?>