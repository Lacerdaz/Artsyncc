<?php
session_start();
require_once 'includes/db_connect.php';

$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if (empty($email) || empty($password)) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        $sql = "SELECT id, artist_name, password FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['artist_name'] = $user['artist_name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Email ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtSync - Login</title>
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
            font-weight: 100;
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
            font-weight: 500;
            transition: border-color 0.3s ease;
        }

        .input-group input::placeholder {
            color: var(--placeholder-color);
            opacity: 4;
            font-weight: 200;
            font-family: 'Poppins', sans-serif;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--focus-color);
        }

        .options-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            font-size: 0.9em;
            color: var(--secondary-text-color);
            font-weight: 300;
        }

        .remember-me {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid var(--secondary-text-color);
            border-radius: 3px;
            margin-right: 8px;
            position: relative;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
        }

        .remember-me input[type="checkbox"]:checked {
            background-color: var(--primary-text-color);
            border-color: var(--primary-text-color);
        }

        .remember-me input[type="checkbox"]:checked::after {
            content: '✔';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--background-color);
            font-size: 12px;
            line-height: 1;
        }

        .options-group a:hover {
            text-decoration: underline;
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
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .btn:hover {
            background: var(--button-border);
            color: var(--background-color);
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
        <form action="login.php" method="post">
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <div class="input-group">
                <i class="fas fa-envelope icon"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock icon"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="options-group">
                <label class="remember-me">
                    <input type="checkbox" name="remember_me" id="remember_me">
                    Remember me
                </label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="switch-form">
            <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
        </div>
    </div>
</body>
</html>