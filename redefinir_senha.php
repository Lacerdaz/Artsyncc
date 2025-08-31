<?php
$page_title = "Redefinir Senha";
include '../includes/header.php';
require_once '../db_connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_email'])) {
    $email = trim($_POST['email']);
    
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $token = bin2hex(random_bytes(50));
        $expira = new DateTime('now');
        $expira->add(new DateInterval('PT1H'));
        $data_expiracao = $expira->format('Y-m-d H:i:s');

        $stmt_insert = $conn->prepare("INSERT INTO redefinicao_senha (email, token, data_expiracao) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $email, $token, $data_expiracao);
        $stmt_insert->execute();

        $link_redefinicao = "http://" . $_SERVER['HTTP_HOST'] . "/pages/nova_senha.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'seu_email@gmail.com';
            $mail->Password   = 'sua_senha_de_app';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@artsync.com', 'artSync');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Redefinicao de Senha - artSync';
            $mail->Body    = "Olá,<br><br>Clique no link a seguir para redefinir sua senha: <a href=\'$link_redefinicao\'>$link_redefinicao</a><br><br>O link expira em 1 hora.<br><br>Atenciosamente,<br>Equipe artSync";
            $mail->AltBody = "Olá,\n\nCopie e cole o seguinte link no seu navegador para redefinir sua senha: $link_redefinicao\n\nO link expira em 1 hora.\n\nAtenciosamente,\nEquipe artSync";

            $mail->send();
            $mensagem = '<div class="mensagem sucesso">Um e-mail com as instruções foi enviado para você.</div>';
        } catch (Exception $e) {
            $mensagem = '<div class="mensagem erro">Não foi possível enviar o e-mail. Tente novamente.</div>';
        }
    } else {
        $mensagem = '<div class="mensagem sucesso">Se o e-mail estiver cadastrado, um link será enviado.</div>';
    }
    $stmt->close();
}
?>
<div class="page-content">
    <div class="container"><div class="form-container">
        <h1 class="logo">artSync</h1><h2 class="form-title">Redefinir Senha</h2>
        <p style="text-align: center; margin-bottom: 20px;">Digite seu e-mail e enviaremos um link para você criar uma nova senha.</p>
        <?php echo $mensagem; ?>
        <form action="redefinir_senha.php" method="POST" class="cadastro-form">
            <div class="input-group"><label for="email">Seu E-mail</label><input type="email" name="email" required></div>
            <button type="submit" name="enviar_email" class="btn-cadastrar">Enviar Link</button>
        </form>
    </div></div>
</div>
<?php include '../includes/footer.php'; ?>

