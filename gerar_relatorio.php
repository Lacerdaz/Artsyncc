<?php
session_start();
require_once '../db_connection.php';
require_once '../includes/functions.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!is_logged_in()) {
    die("Acesso negado. Faça o login para continuar.");
}

$id_usuario = $_SESSION['user_id'];
$nome_usuario = $_SESSION['user_name'];

$arquivos_usuario = [];
$sql = "SELECT nome_original, tipo_arquivo, tamanho_kb, data_upload FROM arquivos WHERE id_usuario = ? ORDER BY data_upload DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
while ($linha = $resultado->fetch_assoc()) {
    $arquivos_usuario[] = $linha;
}
$stmt->close();
$conn->close();

$html = '
<html>
<head>
<style>
    body { font-family: sans-serif; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #f2f2f2; }
    .footer { text-align: center; font-size: 10px; color: #777; position: fixed; bottom: 0; width: 100%; }
</style>
</head>
<body>
    <h1>' . t('relatorios') . ' de Arquivos - artSync</h1>
    <p><strong>' . t('artista') . ':</strong> ' . htmlspecialchars($nome_usuario) . '</p>
    <p><strong>' . t('data_emissao') . ':</strong> ' . date('d/m/Y H:i') . '</p>
    <hr>
    <h2>' . t('lista_arquivos_cadastrados') . '</h2>
';

if (empty($arquivos_usuario)) {
    $html .= '<p>' . t('nenhum_arquivo_cadastrado') . '</p>';
} else {
    $html .= '
    <table>
        <thead>
            <tr>
                <th>' . t('nome_arquivo') . '</th>
                <th>' . t('tipo') . '</th>
                <th>' . t('tamanho') . ' (KB)</th>
                <th>' . t('data_upload') . '</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($arquivos_usuario as $arquivo) {
        $html .= '
            <tr>
                <td>' . htmlspecialchars($arquivo['nome_original']) . '</td>
                <td>' . htmlspecialchars($arquivo['tipo_arquivo']) . '</td>
                <td>' . $arquivo['tamanho_kb'] . '</td>
                <td>' . date('d/m/Y', strtotime($arquivo['data_upload'])) . '</td>
            </tr>';
    }
    $html .= '
        </tbody>
    </table>';
}

$html .= '
    <div class="footer">
        ' . t('relatorio_gerado_por') . ' artSync.
    </div>
</body>
</html>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);

$nome_arquivo_pdf = 'Relatorio_artSync_' . date('Y-m-d') . '.pdf';
$mpdf->Output($nome_arquivo_pdf, 'D');

exit;

