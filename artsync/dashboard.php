<?php
// Título que aparecerá na aba do navegador
$pageTitle = "Dashboard";

// Identificador da página para o menu lateral saber qual item destacar
$currentPage = "dashboard";

// Inclui os scripts de segurança. O restante do HTML/CSS está neste arquivo.
require_once 'includes/session_check.php';
// require_once 'includes/header.php'; // O conteúdo do header está embutido abaixo
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtSync - <?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-text-color: #ffffff;
            --secondary-text-color: #bbbbbb;
            --background-color: #05050a;
            --glass-bg: rgba(26, 27, 31, 0.7);
            /* Vidro um pouco mais opaco para destaque */
            --glass-bg-light: rgba(255, 255, 255, 0.05);
            /* Vidro sutil para botões */
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
            overflow-x: hidden;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: rgba(10, 10, 15, 0.5);
            /* Vidro mais translúcido */
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
            /* Faz a navegação ocupar o espaço disponível */
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav li a {
            display: flex;
            /* Para alinhar ícone e texto */
            align-items: center;
            gap: 10px;
            color: var(--secondary-text-color);
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: var(--glass-bg-light);
            /* Fundo de vidro padrão */
            border: 1px solid transparent;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
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

        /* ESTILO DO BOTÃO SAIR */
        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--secondary-text-color);
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            transition: var(--background-color) 0.3s ease, color 0.3s ease;
        }

        .sidebar-footer a:hover {
            color: var(--primary-text-color);
            background: rgba(220, 53, 69, 0.2);
            /* Fundo vermelho sutil no hover */
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

        .card,
        .widget {
            background: var(--glass-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 25px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .chart-container {
            margin-bottom: 30px;
            position: relative;
            height: 450px;
        }

        .chart-container h3 {
            font-weight: 500;
            margin-bottom: 20px;
            color: var(--primary-text-color);
        }

        /* GRID DOS WIDGETS ATUALIZADO */
        .widgets-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr;
            /* Coluna do meio 50% maior */
            gap: 30px;
        }

        .widget {
            height: 100%;
        }

        .widget h3 {
            font-size: 1.2em;
            font-weight: 500;
            color: var(--primary-text-color);
            margin-bottom: 10px;
        }

        .widget p {
            font-weight: 300;
            line-height: 1.5;
        }

        .widget-link {
            text-decoration: none;
            color: inherit;
            display: flex;
        }

        .widget-link:hover .widget {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Responsividade para o grid de widgets */
        @media (max-width: 992px) {
            .widgets-grid {
                grid-template-columns: 1fr;
                /* Coluna única em telas menores */
            }
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

        /* Quando ativado */
        #page-transition.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Logo com efeito pulse */
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

        /* Quando ativado */
        #page-transition.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Logo com efeito pulse */
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
                <div class="logo">
                    <img src="images/artsync.png" alt="Art Sync Logo">
                </div>
                <nav>
                    <ul>
                        <li><a href="dashboard.php"
                                class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>"><i
                                    class="fas fa-home"></i> Dashboard</a></li>
                        <li><a href="schedule.php"
                                class="<?php echo ($currentPage === 'schedule') ? 'active' : ''; ?>"><i
                                    class="fas fa-calendar-alt"></i> Agenda</a></li>
                        <li><a href="./uploads/portfolio.php"
                                class="<?php echo ($currentPage === 'portfolio') ? 'active' : ''; ?>"><i
                                    class="fas fa-user-circle"></i> Portfólio</a></li>
                        <li><a href="career_ai.php"><i class="fas fa-robot"></i> IA de Carreira</a></li>
                    </ul>
                </nav>
            </div>

            <div class="sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h2>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['artist_name'] ?? 'Artista'); ?>!</h2>
                <p>Aqui está um resumo da sua carreira.</p>
            </header>

            <div class="card chart-container">
                <h3>Visão Geral de Streams (Últimos 30 dias)</h3>
                <canvas id="streamsChart"></canvas>
            </div>

            <div class="widgets-grid">
                <a href="schedule.php" class="widget-link">
                    <div class="widget">
                        <h3>Próximos Eventos</h3>
                        <p>Acompanhe seus próximos shows e compromissos.</p>
                    </div>
                </a>

                <a href="connect_spotify.php" class="widget-link">
                    <div class="widget">
                        <h3>Métricas do Spotify</h3>
                        <p>Integração pendente.</p>
                    </div>
                </a>

                <a href="career_ai.php" class="widget-link">
                    <div class="widget">
                        <h3>IA de Carreira</h3>
                        <p>Receba dicas para sua carreira.</p>
                    </div>
                </a>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('streamsChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 0.3)');
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

            const streamsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6'],
                    datasets: [{
                        label: 'Streams',
                        data: [120, 190, 300, 500, 200, 300],
                        backgroundColor: gradient,
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.sidebar nav a, .widget-link, .sidebar-footer a').forEach(link => {
            link.addEventListener('click', function (e) {
                // Evita animação em links externos (ex: http, https) 
                const href = this.getAttribute('href');
                if (href && !href.startsWith('http') && !href.startsWith('#')) {
                    e.preventDefault(); // Cancela a navegação imediata
                    const target = this.href;

                    const transition = document.getElementById('page-transition');
                    transition.classList.add('active');

                    // Espera a animação rodar antes de ir pra página
                    setTimeout(() => {
                        window.location.href = target;
                    }, 800); // tempo de animação (pode ajustar entre 500-1200ms)
                }
            });
        });
    </script>



</body>

</html>