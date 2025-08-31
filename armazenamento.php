<?php
$page_title = t("armazenamento");
include '../includes/header.php';
require_once '../db_connection.php';
protect_page();

$id_usuario = $_SESSION['user_id'];
$mensagem = '';

if (isset($_GET['excluir_id'])) {
    $id_arquivo_excluir = intval($_GET['excluir_id']);
    $stmt = $conn->prepare("SELECT nome_armazenado FROM arquivos WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_arquivo_excluir, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) {
        $arquivo = $resultado->fetch_assoc();
        $caminho_arquivo = '../uploads/' . $arquivo['nome_armazenado'];
        if (file_exists($caminho_arquivo)) { unlink($caminho_arquivo); }
        $stmt_delete = $conn->prepare("DELETE FROM arquivos WHERE id = ?");
        $stmt_delete->bind_param("i", $id_arquivo_excluir);
        if ($stmt_delete->execute()) { set_notification('Arquivo excluído com sucesso!', 'sucesso'); }
        else { set_notification('Erro ao excluir o arquivo.', 'erro'); }
        $stmt_delete->close();
    } else { set_notification('Arquivo não encontrado ou você não tem permissão para excluí-lo.', 'erro'); }
    $stmt->close();
    header('Location: armazenamento.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["arquivo"])) {
    $arquivo = $_FILES["arquivo"];
    if ($arquivo["error"] === UPLOAD_ERR_OK) {
        $pasta_uploads = '../uploads/';
        $nome_original = basename($arquivo["name"]);
        $tipo_arquivo = $arquivo["type"];
        $tamanho_kb = round($arquivo["size"] / 1024);

        $extensao = pathinfo($nome_original, PATHINFO_EXTENSION);
        $nome_armazenado = uniqid('file_', true) . '.' . $extensao;
        $caminho_destino = $pasta_uploads . $nome_armazenado;

        if (move_uploaded_file($arquivo["tmp_name"], $caminho_destino)) {
            $stmt = $conn->prepare("INSERT INTO arquivos (id_usuario, nome_original, nome_armazenado, tipo_arquivo, tamanho_kb) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssi", $id_usuario, $nome_original, $nome_armazenado, $tipo_arquivo, $tamanho_kb);
            if ($stmt->execute()) { set_notification('Arquivo enviado com sucesso!', 'sucesso'); }
            else { set_notification('Erro ao salvar informações no banco de dados.', 'erro'); }
            $stmt->close();
        } else { set_notification('Falha ao mover o arquivo para o destino.', 'erro'); }
    } else { set_notification('Ocorreu um erro no upload do arquivo.', 'erro'); }
    header('Location: armazenamento.php');
    exit;
}

$arquivos_usuario = [];
$sql = "SELECT id, nome_original, nome_armazenado, tipo_arquivo, tamanho_kb, data_upload FROM arquivos WHERE id_usuario = ? ORDER BY data_upload DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
while ($linha = $resultado->fetch_assoc()) {
    $arquivos_usuario[] = $linha;
}
$stmt->close();
$conn->close();
?>

<div class="page-content">
    <h1><?php echo t("armazenamento"); ?></h1>
    <p>Gerencie seus arquivos, portfólio e documentos importantes.</p>

    <?php display_notification(); ?>

    <div class="form-container-interno">
        <h2>Enviar Novo Arquivo</h2>
        <form action="armazenamento.php" method="post" enctype="multipart/form-data" class="form-upload">
            <input type="file" name="arquivo" required>
            <button type="submit" class="btn-cadastrar">Enviar</button>
        </form>
    </div>

    <div class="lista-arquivos">
        <h2>Seus Arquivos</h2>
        <?php if (empty($arquivos_usuario)): ?>
            <p>Você ainda não enviou nenhum arquivo.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Arquivo</th>
                        <th>Tipo</th>
                        <th>Tamanho</th>
                        <th>Data de Upload</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arquivos_usuario as $arquivo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($arquivo['nome_original']); ?></td>
                        <td><?php echo htmlspecialchars($arquivo['tipo_arquivo']); ?></td>
                        <td><?php echo $arquivo['tamanho_kb']; ?> KB</td>
                        <td><?php echo date('d/m/Y H:i', strtotime($arquivo['data_upload'])); ?></td>
                        <td>
                            <a href="../uploads/<?php echo $arquivo['nome_armazenado']; ?>" target="_blank" class="btn-acao">Ver</a>
                            <a href="armazenamento.php?excluir_id=<?php echo $arquivo['id']; ?>" class="btn-acao btn-perigo" onclick="return confirm('Tem certeza que deseja excluir este arquivo?');">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

