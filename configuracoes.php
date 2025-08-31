<?php
$page_title = t("configuracoes");
include '../includes/header.php';
require_once '../db_connection.php';
protect_page();

$mensagem = '';
$id_usuario = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_nova_senha = $_POST['confirmar_nova_senha'];

    if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_nova_senha)) {
        $mensagem = '<div class="mensagem erro">Todos os campos são obrigatórios.</div>';
    } elseif ($nova_senha !== $confirmar_nova_senha) {
        $mensagem = '<div class="mensagem erro">As novas senhas não coincidem.</div>';
    } elseif (strlen($nova_senha) < 6) {
        $mensagem = '<div class="mensagem erro">A nova senha deve ter no mínimo 6 caracteres.</div>';
    } else {
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($senha_atual, $user['senha'])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt_update->bind_param("si", $nova_senha_hash, $id_usuario);
            if ($stmt_update->execute()) {
                $mensagem = '<div class="mensagem sucesso">Senha alterada com sucesso!</div>';
            } else {
                $mensagem = '<div class="mensagem erro">Erro ao atualizar a senha.</div>';
            }
            $stmt_update->close();
        } else {
            $mensagem = '<div class="mensagem erro">A senha atual está incorreta.</div>';
        }
        $stmt->close();
    }
}
?>
<div class="page-content">
    <h1><?php echo t('configuracoes'); ?></h1>
    <?php echo $mensagem; ?>

    <div class="form-container-interno">
        <h2><?php echo t('alterar_senha'); ?></h2>
        <form action="configuracoes.php" method="POST" class="cadastro-form">
            <div class="input-group"><label for="senha_atual">Senha Atual</label><input type="password" name="senha_atual" required></div>
            <div class="input-group"><label for="nova_senha">Nova Senha</label><input type="password" name="nova_senha" required></div>
            <div class="input-group"><label for="confirmar_nova_senha">Confirmar Nova Senha</label><input type="password" name="confirmar_nova_senha" required></div>
            <button type="submit" name="alterar_senha" class="btn-cadastrar">Salvar Nova Senha</button>
        </form>
    </div>

    <div class="form-container-interno">
        <h2><?php echo t('tema_interface'); ?></h2>
        <div class="cadastro-form">
            <div class="input-group">
                <label for="tema">Tema</label>
                <select id="tema">
                    <option value="escuro">Escuro</option>
                    <option value="claro">Claro</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="form-container-interno">
        <h2><?php echo t('idioma_sistema'); ?></h2>
        <form action="configuracoes.php" method="POST" class="cadastro-form">
            <div class="input-group">
                <label for="idioma">Idioma</label>
                <select name="idioma" id="idioma">
                    <option value="pt_BR" <?php echo ($_COOKIE['idioma'] ?? 'pt_BR') == 'pt_BR' ? 'selected' : ''; ?>>Português (Brasil)</option>
                    <option value="en_US" <?php echo ($_COOKIE['idioma'] ?? '') == 'en_US' ? 'selected' : ''; ?>>English (US)</option>
                </select>
            </div>
            <button type="submit" name="salvar_idioma" class="btn-cadastrar"><?php echo t('salvar_idioma'); ?></button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

