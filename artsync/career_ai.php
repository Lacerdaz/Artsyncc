<?php
$pageTitle = "IA de Carreira";
$currentPage = "career_ai";
require_once 'includes/session_check.php';

$ai_response = '';
$user_question = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_question'])) {
    $user_question = trim($_POST['user_question']);

    if (!empty($user_question)) {
        $api_key = 'AIzaSyCH6kDwEjEBWMztJVcK6pnxHKlmb2nuUYI';
        $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $api_key;

        $prompt = "Você é um mentor de carreiras para artistas musicais independentes. Dê conselhos práticos, curtos e inspiradores em português do Brasil. Não use markdown ou formatação especial. A pergunta do artista é: " . $user_question;

        $data = ['contents' => [['parts' => [['text' => $prompt]]]]];

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $result = json_decode($response, true);
            $ai_response = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Desculpe, não consegui gerar uma resposta. Tente novamente.";
            $ai_response = nl2br(htmlspecialchars($ai_response));
        } else {
            $ai_response = "Ocorreu um erro ao contatar a IA. Por favor, verifique sua chave de API.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtSync - <?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-text-color: #ffffff;
            --secondary-text-color: #bbbbbb;
            --background-color: #05050a;
            --glass-bg: rgba(26, 27, 31, 0.7);
            --glass-bg-light: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.1);
            --accent-color: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            color: var(--secondary-text-color);
            background-color: var(--background-color);
            overflow: hidden;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: rgba(10, 10, 15, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-right: 1px solid var(--border-color);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease, padding 0.3s ease;
        }

        .sidebar.hidden {
            width: 0;
            padding: 30px 0;
            overflow: hidden;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar .logo img {
            height: 50px;
        }

        .sidebar nav {
            flex-grow: 1;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav li a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--secondary-text-color);
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: var(--glass-bg-light);
            border: 1px solid transparent;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sidebar nav li a:hover {
            background: var(--glass-bg);
            border-color: var(--border-color);
            color: var(--primary-text-color);
        }

        .sidebar nav li a.active {
            background: var(--accent-color);
            color: #000;
            font-weight: 500;
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--secondary-text-color);
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sidebar-footer a:hover {
            color: var(--primary-text-color);
            background: rgba(220, 53, 69, 0.2);
        }

        .sidebar-footer i {
            width: 1.2em;
            text-align: center;
            flex-shrink: 0;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            max-height: 100vh;
        }

        .main-header {
            padding-bottom: 20px;
            color: var(--primary-text-color);
            transition: opacity 0.3s ease;
        }

        .main-header.hidden {
            display: none;
        }

        .main-header h2 {
            font-weight: 500;
            font-size: 2em;
            margin-bottom: 8px;
        }

        .main-header p {
            font-size: 1.1em;
            color: var(--secondary-text-color);
            font-weight: 300;
        }

        .chat-card {
            position: relative;
            flex-grow: 1;
            background: var(--glass-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .chat-card.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 0;
            z-index: 9990;
        }

        .conversation-history {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 25px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .conversation-history::-webkit-scrollbar {
            display: none;
        }

        .chat-bubble {
            display: flex;
            gap: 15px;
            max-width: 80%;
            line-height: 1.6;
        }

        .chat-bubble p {
            padding: 15px 20px;
            border-radius: 18px;
            word-wrap: break-word;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
            flex-shrink: 0;
        }

        .user-bubble {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .user-bubble p {
            background-color: var(--accent-color);
            color: #000;
            border-bottom-right-radius: 4px;
        }

        .user-avatar {
            background-color: var(--secondary-text-color);
            color: #000;
        }

        .ai-bubble {
            align-self: flex-start;
        }

        .ai-bubble p {
            background: rgba(10, 10, 15, 0.8);
            color: var(--primary-text-color);
            border-bottom-left-radius: 4px;
        }

        .ai-avatar {
            background-color: transparent;
            font-size: 1.5em;
        }

        .chat-input-area {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            background: rgba(10, 10, 15, 0.5);
        }

        #ai-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #ai-form .input-wrapper {
            display: flex;
            align-items: center;
            flex-grow: 1;
            position: relative;
        }

        #ai-form textarea {
            width: 100%;
            border: 1px solid var(--border-color);
            background: var(--glass-bg-light);
            resize: none;
            color: var(--primary-text-color);
            font-family: 'Poppins', sans-serif;
            font-size: 1em;
            padding: 12px 50px 12px 20px;
            border-radius: 24px;
            height: 48px;
            max-height: 120px;
            overflow-y: auto;
            transition: height 0.2s ease;
        }

        #ai-form textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        #page-transition {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        #page-transition.active {
            opacity: 1;
            pointer-events: all;
        }

        .logo-animation img {
            width: 120px;
            height: auto;
            opacity: 0;
            animation: pulse 1.2s infinite ease-in-out;
        }

        #page-transition.active .logo-animation img {
            opacity: 1;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.7;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0.7;
            }
        }

        @keyframes pulse-button {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.3);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        #ai-form .submit-btn {
            background: linear-gradient(45deg, #e0e0e0, #ffffff);
            color: #000;
            border: none;
            width: 48px;
            height: 48px;
            font-size: 1.2em;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
            animation: pulse-button 2s infinite;
        }

        #ai-form .submit-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            animation-play-state: paused;
        }

        .upload-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary-text-color);
            font-size: 1.2em;
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 5px;
        }

        .upload-btn:hover {
            color: var(--primary-text-color);
        }

        #fullscreen-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: var(--secondary-text-color);
            font-size: 1.2em;
            cursor: pointer;
            transition: color 0.3s ease;
            z-index: 10;
        }

        #fullscreen-btn:hover {
            color: var(--primary-text-color);
        }
    </style>
</head>

<body>
    <div id="page-transition">
        <div class="logo-animation">
            <img src="images/artsync.png" alt="Logo ArtSync">
        </div>
    </div>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div>
                <div class="logo"><img src="images/artsync.png" alt="Art Sync Logo"></div>
                <nav>
                    <ul>
                        <li><a href="dashboard.php"
                                class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>"><i
                                    class="fas fa-home"></i> Dashboard</a></li>
                        <li><a href="portfolio.php"
                                class="<?php echo ($currentPage === 'portfolio') ? 'active' : ''; ?>"><i
                                    class="fas fa-user-circle"></i> Portfólio</a></li>
                        <li><a href="schedule.php"
                                class="<?php echo ($currentPage === 'schedule') ? 'active' : ''; ?>"><i
                                    class="fas fa-calendar-alt"></i> Agenda</a></li>
                        <li><a href="career_ai.php"
                                class="<?php echo ($currentPage === 'career_ai') ? 'active' : ''; ?>"><i
                                    class="fas fa-robot"></i> IA de Carreira</a></li>
                    </ul>
                </nav>
            </div>
            <div class="sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h2>IA de Carreira</h2>
                <p>Converse com um mentor de IA para impulsionar sua carreira musical.</p>
            </header>

            <section class="chat-card">
                <button id="fullscreen-btn" title="Maximizar Chat"><i class="fas fa-expand-alt"></i></button>
                <div class="conversation-history">
                    <?php
                    $name_parts = explode(' ', $_SESSION['artist_name']);
                    $initials = '';
                    foreach ($name_parts as $part) {
                        $initials .= strtoupper(substr($part, 0, 1));
                    }
                    $avatar_initials = substr($initials, 0, 2);
                    ?>
                    <?php if (empty($user_question)): ?>
                        <div class="chat-bubble ai-bubble">
                            <div class="avatar ai-avatar">✨</div>
                            <p>Olá! Sou seu mentor de carreira. Como posso te ajudar a brilhar hoje?</p>
                        </div>
                    <?php else: ?>
                        <div class="chat-bubble user-bubble">
                            <div class="avatar user-avatar"><?php echo $avatar_initials; ?></div>
                            <p><?php echo htmlspecialchars($user_question); ?></p>
                        </div>
                        <div class="chat-bubble ai-bubble">
                            <div class="avatar ai-avatar">✨</div>
                            <p><?php echo $ai_response; ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="chat-input-area">
                    <form action="career_ai.php" method="post" id="ai-form">
                        <div class="input-wrapper">
                            <textarea id="user_question" name="user_question" rows="1" required
                                placeholder="Faça sua pergunta..."></textarea>
                            <button type="button" id="upload-btn" class="upload-btn" title="Anexar arquivo"><i
                                    class="fas fa-paperclip"></i></button>
                        </div>
                        <input type="file" id="file-upload" name="media_file" style="display: none;">
                        <button type="submit" name="ask_ai" class="submit-btn" title="Enviar Pergunta"><i
                                class="fas fa-arrow-up"></i></button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script de transição de página
            document.querySelectorAll('.sidebar nav a, .sidebar-footer a').forEach(link => {
                link.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href && !href.startsWith('http') && !href.startsWith('#')) {
                        e.preventDefault();
                        const target = this.href;
                        const transition = document.getElementById('page-transition');
                        transition.classList.add('active');
                        setTimeout(() => {
                            window.location.href = target;
                        }, 800);
                    }
                });
            });

            const textarea = document.getElementById('user_question');
            const aiForm = document.getElementById('ai-form');

            if (textarea) {
                textarea.addEventListener('input', () => {
                    textarea.style.height = 'auto';
                    textarea.style.height = `${textarea.scrollHeight}px`;
                });
            }

            if (textarea && aiForm) {
                textarea.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault();
                        aiForm.submit();
                    }
                });
            }

            // SCRIPT PARA MAXIMIZAR O CHAT
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const chatCard = document.querySelector('.chat-card');
            const sidebar = document.querySelector('.sidebar');
            const mainHeader = document.querySelector('.main-header');

            if (fullscreenBtn && chatCard && sidebar && mainHeader) {
                fullscreenBtn.addEventListener('click', () => {
                    const isFullscreen = chatCard.classList.toggle('fullscreen');
                    sidebar.classList.toggle('hidden');
                    mainHeader.classList.toggle('hidden');

                    const icon = fullscreenBtn.querySelector('i');
                    if (isFullscreen) {
                        icon.classList.remove('fa-expand-alt');
                        icon.classList.add('fa-compress-alt');
                        fullscreenBtn.title = "Restaurar Chat";
                    } else {
                        icon.classList.remove('fa-compress-alt');
                        icon.classList.add('fa-expand-alt');
                        fullscreenBtn.title = "Maximizar Chat";
                    }
                });
            }

            // SCRIPT PARA BOTÃO DE UPLOAD
            const uploadBtn = document.getElementById('upload-btn');
            const fileInput = document.getElementById('file-upload');

            if (uploadBtn && fileInput) {
                uploadBtn.addEventListener('click', () => {
                    fileInput.click(); // Abre a janela de seleção de arquivos
                });

                // Opcional: Mostra o nome do arquivo selecionado (pode ser expandido depois)
                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length > 0) {
                        console.log('Arquivo selecionado:', fileInput.files[0].name);
                        // Aqui você pode, por exemplo, exibir o nome do arquivo no chat
                    }
                });
            }
        });
    </script>
</body>

</html>