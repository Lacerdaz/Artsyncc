<?php
// Configurações do Banco de Dados
$db_host = 'localhost'; // Geralmente 'localhost'
$db_name = 'artsync_db'; // O nome do banco que você criou
$db_user = 'root';      // Usuário padrão do XAMPP/WAMP
$db_pass = '';          // Senha padrão do XAMPP/WAMP (geralmente vazia)

// Criar a conexão
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    // Definir o modo de erro do PDO para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Se a conexão falhar, exibe uma mensagem de erro e encerra o script
    die("ERRO: Não foi possível conectar ao banco de dados. " . $e->getMessage());
}

// Iniciar a sessão em todas as páginas que incluírem este arquivo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>