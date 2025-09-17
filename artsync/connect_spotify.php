<?php
require_once 'includes/session_check.php';

// SEUS DADOS CORRIGIDOS E ATUALIZADOS
$client_id = 'f3f45ed93a9f49379d873ed413ccd0c3';
$redirect_uri = 'https://localhost/artsync/spotify_callback.php'; // Corrigido para https

// Escopos são as permissões que estamos pedindo
$scopes = 'user-read-private user-read-email';

$auth_url = 'https://accounts.spotify.com/authorize' .
  '?response_type=code' .
  '&client_id=' . $client_id .
  '&scope=' . urlencode($scopes) .
  '&redirect_uri=' . urlencode($redirect_uri);

header('Location: ' . $auth_url);
exit;
?>