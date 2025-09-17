<?php
require_once 'includes/session_check.php';
require_once 'includes/db_connect.php';

$pageTitle = "Agenda";
$currentPage = "schedule"; // Variável para destacar o item de menu ativo
$user_id = $_SESSION['user_id'];
$feedback_message = '';
$feedback_type = '';

// Lógica para ADICIONAR um novo evento
if (isset($_POST['add_event'])) {
    $event_title = trim($_POST['event_title']);
    $event_date = trim($_POST['event_date']);
    $notes = trim($_POST['notes']);

    if (empty($event_title) || empty($event_date)) {
        $feedback_message = "Título e data do evento são obrigatórios.";
        $feedback_type = 'error';
    } else {
        $sql = "INSERT INTO schedule (user_id, event_title, event_date, notes) VALUES (:uid, :title, :date, :notes)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['uid' => $user_id, 'title' => $event_title, 'date' => $event_date, 'notes' => $notes])) {
            $feedback_message = "Evento agendado com sucesso!";
            $feedback_type = 'success';
        } else {
            $feedback_message = "Erro ao agendar o evento.";
            $feedback_type = 'error';
        }
    }
}

// Lógica para EXCLUIR um evento
if (isset($_POST['delete_event'])) {
    $event_id_to_delete = $_POST['event_id'];
    $sql = "DELETE FROM schedule WHERE id = :id AND user_id = :uid";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['id' => $event_id_to_delete, 'uid' => $user_id])) {
        $feedback_message = "Evento excluído com sucesso!";
        $feedback_type = 'success';
    } else {
        $feedback_message = "Erro ao excluir o evento.";
        $feedback_type = 'error';
    }
}

$events = $pdo->prepare("SELECT id, event_title, event_date, notes FROM schedule WHERE user_id = ? ORDER BY event_date ASC");
$events->execute([$user_id]);
$schedule_items = $events->fetchAll(PDO::FETCH_ASSOC);
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
            --success-color: #28a745;
            --error-color: #dc3545;
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
            overflow-x: hidden;
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
            gap: 10px;
            color: var(--secondary-text-color);
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: var(--glass-bg-light);
            border: 1px solid transparent;
            transition: all 0.3s ease;
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
            gap: 10px;
            color: var(--secondary-text-color);
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar-footer a:hover {
            color: var(--primary-text-color);
            background: rgba(220, 53, 69, 0.2);
        }

        .sidebar-footer i {
            width: 1.2em;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
            max-height: 100vh;
        }

        .main-header {
            padding-bottom: 40px;
            color: var(--primary-text-color);
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

        .card {
            background: var(--glass-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin-bottom: 30px;
        }

        .card h3 {
            font-weight: 500;
            margin-bottom: 25px;
            color: var(--primary-text-color);
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 400;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            color: var(--primary-text-color);
            font-family: 'Poppins', sans-serif;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .error-message,
        .success-message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: rgba(220, 53, 69, 0.2);
            color: #f8d7da;
            border: 1px solid var(--error-color);
        }

        .success-message {
            background-color: rgba(40, 167, 69, 0.2);
            color: #d4edda;
            border: 1px solid var(--success-color);
        }

        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px;
            background: var(--glass-bg-light);
            border-radius: 8px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .schedule-item:hover {
            border-color: var(--border-color);
            transform: translateY(-3px);
        }

        .item-date {
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px;
            min-width: 60px;
        }

        .item-date .day {
            font-size: 1.8em;
            font-weight: 600;
            color: var(--primary-text-color);
            display: block;
        }

        .item-date .month {
            text-transform: uppercase;
            font-size: 0.8em;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-details h4 {
            font-weight: 500;
            color: var(--primary-text-color);
            margin-bottom: 5px;
        }

        .item-details p {
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .btn-delete {
            background: none;
            border: none;
            color: var(--secondary-text-color);
            cursor: pointer;
            transition: color 0.3s ease;
            font-size: 1.2em;
        }

        .btn-delete:hover {
            color: var(--error-color);
        }

        /* Overlay de transição */
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
            0% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 0.7; }
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
                        <li><a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li><a href="schedule.php" class="<?php echo ($currentPage === 'schedule') ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Agenda</a></li>
                        <li><a href="portfolio.php" class="<?php echo ($currentPage === 'portfolio') ? 'active' : ''; ?>"><i class="fas fa-user-circle"></i> Portfólio</a></li>
                        <li><a href="career_ai.php" class="<?php echo ($currentPage === 'career_ai') ? 'active' : ''; ?>"><i class="fas fa-robot"></i> IA de Carreira</a></li>
                    </ul>
                </nav>
            </div>
            <div class="sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h2>Minha Agenda</h2>
                <p>Organize seus shows, ensaios e compromissos.</p>
            </header>

            <?php if ($feedback_message): ?>
                <div class="<?php echo $feedback_type === 'error' ? 'error-message' : 'success-message'; ?>">
                    <?php echo htmlspecialchars($feedback_message); ?>
                </div>
            <?php endif; ?>

            <section class="card">
                <h3>Agendar Novo Evento</h3>
                <form action="schedule.php" method="post">
                    <div class="input-group"><label for="event_title">Título do Evento</label><input type="text" id="event_title" name="event_title" required></div>
                    <div class="input-group"><label for="event_date">Data e Hora</label><input type="datetime-local" id="event_date" name="event_date" required></div>
                    <div class="input-group"><label for="notes">Anotações (opcional)</label><textarea id="notes" name="notes" rows="3"></textarea></div>
                    <button type="submit" name="add_event" class="btn">Agendar</button>
                </form>
            </section>

            <section class="card">
                <h3>Meus Compromissos</h3>
                <div class="schedule-list">
                    <?php if (empty($schedule_items)): ?>
                        <p>Você não tem nenhum evento agendado.</p>
                    <?php else: ?>
                        <?php foreach ($schedule_items as $item): ?>
                            <div class="schedule-item">
                                <div class="item-date">
                                    <span class="day"><?php echo date('d', strtotime($item['event_date'])); ?></span>
                                    <span class="month"><?php echo date('M', strtotime($item['event_date'])); ?></span>
                                </div>
                                <div class="item-details">
                                    <h4><?php echo htmlspecialchars($item['event_title']); ?></h4>
                                    <p><strong>Quando:</strong> <?php echo date('d/m/Y \à\s H:i', strtotime($item['event_date'])); ?></p>
                                    <?php if (!empty($item['notes'])): ?>
                                        <p><strong>Anotações:</strong> <?php echo htmlspecialchars($item['notes']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="item-action">
                                    <form action="schedule.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                        <input type="hidden" name="event_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="delete_event" class="btn-delete" title="Excluir"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.querySelectorAll('.sidebar nav a, .widget-link, .sidebar-footer a').forEach(link => {
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
    </script>
</body>

</html>