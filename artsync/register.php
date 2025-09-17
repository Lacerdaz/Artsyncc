<?php
require_once 'includes/db_connect.php';

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $artist_name = trim($_POST['artist_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($artist_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Todos os campos são obrigatórios.";
    } elseif ($password !== $confirm_password) {
        $error_message = "As senhas não coincidem.";
    } elseif (strlen($password) < 6) {
        $error_message = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error_message = "Este email já está cadastrado.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO users (artist_name, email, password) VALUES (:artist_name, :email, :password)";
            $stmt_insert = $pdo->prepare($sql_insert);
            
            if ($stmt_insert->execute(['artist_name' => $artist_name, 'email' => $email, 'password' => $hashed_password])) {
                $success_message = "Cadastro realizado com sucesso! Você já pode fazer o <a href='login.php'>login</a>.";
            } else {
                $error_message = "Ocorreu um erro ao tentar cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtSync - Cadastro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;500&display=swap');

        :root {
            --primary-text-color: #ffffff;
            --secondary-text-color: #bbbbbb;
            --background-color: #000000; /* Fundo preto */
            --border-color: #666666;
            --focus-color: #ffffff;
            --button-bg: transparent;
            --button-border: #ffffff;
            --error-color: #dc3545;
            --success-color: #28a745;
            --placeholder-color: #666666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 100;
            color: var(--secondary-text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            overflow: hidden;
            background-color: var(--background-color);
            /* GRADIENTE LEVE SOBRE O FUNDO PRETO */
            background: linear-gradient(to top, #111111, #000000);
        }

        a {
            color: var(--secondary-text-color);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        a:hover {
            color: var(--primary-text-color);
        }

        .form-container {
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            margin-top: -60px; /* Compensa a remoção do h1 para manter o alinhamento */
        }
        
        .error-message, .success-message {
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 0.9em;
            font-weight: 300;
        }
        
        .error-message {
            background-color: transparent;
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }
        
        .success-message {
            background-color: transparent;
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }
        
        .success-message a {
            color: var(--success-color);
            font-weight: 500;
            text-decoration: underline;
        }
        
        .success-message a:hover {
            color: #d4edda;
        }

        .input-group {
            margin-bottom: 30px;
            text-align: left;
            position: relative;
            font-weight: 300;
        }

        .input-group .icon {
            position: absolute;
            left: 0;
            top: 12px;
            color: var(--secondary-text-color);
            font-size: 1.2em;
        }

        .input-group input {
            width: 100%;
            padding: 12px 0 12px 35px;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid var(--border-color);
            color: var(--primary-text-color);
            font-size: 1.1em;
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            transition: border-color 0.3s ease;
        }

        .input-group input::placeholder {
            color: var(--placeholder-color);
            opacity: 1;
            font-family: 'Poppins', sans-serif;
            font-weight: 100;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--focus-color);
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--button-border);
            border-radius: 0;
            background: var(--button-bg);
            color: var(--button-border);
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .btn:hover {
            background: var(--button-border);
            color: #222222;
        }

        .switch-form {
            margin-top: 40px;
            font-size: 0.9em;
            font-weight: 300;
        }

        .switch-form a {
            font-weight: 500;
            color: var(--primary-text-color);
        }

        .switch-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="centered-body">
    <div class="form-container">
        <form action="register.php" method="post" novalidate>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <div class="input-group">
                <i class="fas fa-user icon"></i>
                <input type="text" id="artist_name" name="artist_name" placeholder="Nome Artístico" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope icon"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock icon"></i>
                <input type="password" id="password" name="password" placeholder="Senha (mín. 6 caracteres)" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock icon"></i>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Senha" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="switch-form">
            <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
    </div>
</body>
</html>