<?php
require_once 'includes/session_check.php';
require_once 'includes/db_connect.php';

// SEUS DADOS CORRIGIDOS E ATUALIZADOS
$client_id = 'f3f45ed93a9f49379d873ed413ccd0c3';
$client_secret = '70e5e9ccf4bc425485077e2179c9bfd8'; // Você precisará copiar e colar seu Client Secret aqui
$redirect_uri = 'https://localhost/artsync/spotify_callback.php'; // Corrigido para https

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // =================================================================
    // PASSO AVANÇADO: TROCAR O CÓDIGO PELO ACCESS TOKEN
    // Esta parte ainda está como exemplo, mas agora a primeira conexão vai funcionar.
    // =================================================================

    echo "<h1>Conexão com Spotify bem-sucedida!</h1>";
    echo "<p>A autenticação funcionou! O Spotify nos enviou de volta para a URI correta.</p>";
    echo "<p>Seu código de autorização é: " . htmlspecialchars($code) . "</p>";
    echo "<a href='dashboard.php'>Voltar para o Dashboard</a>";

} else {
    echo "<h1>Ocorreu um erro na autenticação com o Spotify.</h1>";
    echo "<a href='dashboard.php'>Voltar para o Dashboard</a>";
}
?>