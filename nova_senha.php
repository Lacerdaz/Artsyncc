<?php
$page_title = "Criar Nova Senha";
include '../includes/header.php';
require_once '../db_connection.php';

$mensagem = '';
$token_valido = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $conn->prepare("SELECT email, data_expiracao FROM redefinicao_senha WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();
        if (new DateTime() < new DateTime($data['data_expiracao'])) {
            $token_valido = true;
            $email = $data['email'];
        } else {
            $mensagem = '<div class="mensagem erro">Token expirado. Solicite uma nova redefinição.</div>';
        }
    } else {
        $mensagem = '<div class="mensagem erro">Token inválido.</div>';
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['definir_nova_senha'])) {
    $token_form = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    $stmt = $conn->prepare("SELECT email FROM redefinicao_senha WHERE token = ? AND data_expiracao > NOW()");
    $stmt->bind_param("s", $token_form);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1 && $nova_senha === $confirmar_senha && strlen($nova_senha) >= 6) {
        $email = $result->fetch_assoc()['email'];
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
        $stmt_update->bind_param("ss", $nova_senha_hash, $email);
        $stmt_update->execute();

        $stmt_delete = $conn->prepare("DELETE FROM redefinicao_senha WHERE email = ?");
        $stmt_delete->bind_param("s", $email);
        $stmt_delete->execute();

        $mensagem = '<div class="mensagem sucesso">Senha alterada com sucesso! <a href="/login.php">Faça login agora</a>.</div>';
        $token_valido = false;
    } else {
        $mensagem = '<div class="mensagem erro">Ocorreu um erro. Verifique se as senhas coincidem ou solicite um novo link.</div>';
        $token_valido = true;
    }
}
?>
<div class="page-content">
    <div class="container"><div class="form-container">
        <h1 class="logo">artSync</h1><h2 class="form-title">Criar Nova Senha</h2>
        <?php echo $mensagem; ?>
        <?php if ($token_valido): ?>
        <form action="nova_senha.php" method="POST" class="cadastro-form">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div class="input-group"><label for="nova_senha">Nova Senha</label><input type="password" name="nova_senha" required></div>
            <div class="input-group"><label for="confirmar_senha">Confirmar Nova Senha</label><input type="password" name="confirmar_senha" required></div>
            <button type="submit" name="definir_nova_senha" class="btn-cadastrar">Salvar Senha</button>
        </form>
        <?php endif; ?>
    </div></div>
</div>
<?php include '../includes/footer.php'; ?>

