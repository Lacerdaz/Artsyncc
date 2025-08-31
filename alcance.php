<?php
$page_title = t("alcance");
include '../includes/header.php';
protect_page();

$dados_seguidores = [
    'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
    'data' => [120, 150, 145, 180, 210, 250]
];
$dados_engajamento = [
    'labels' => ['Posts', 'Comentários', 'Curtidas', 'Compartilhamentos'],
    'data' => [30, 150, 800, 45]
];
?>
<div class="page-content">
    <h1><?php echo t("alcance"); ?></h1>
    <p>Monitore o crescimento e engajamento do seu perfil artístico.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Crescimento de Seguidores (Exemplo)</h3>
            <canvas id="graficoSeguidores"></canvas>
        </div>
        <div class="widget">
            <h3>Engajamento Mensal (Exemplo)</h3>
            <canvas id="graficoEngajamento"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxSeguidores = document.getElementById('graficoSeguidores').getContext('2d');
    new Chart(ctxSeguidores, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dados_seguidores['labels']); ?>,
            datasets: [{
                label: 'Seguidores',
                data: <?php echo json_encode($dados_seguidores['data']); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.1
            }]
        }
    });

    const ctxEngajamento = document.getElementById('graficoEngajamento').getContext('2d');
    new Chart(ctxEngajamento, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dados_engajamento['labels']); ?>,
            datasets: [{
                label: 'Total',
                data: <?php echo json_encode($dados_engajamento['data']); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)'
                ]
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>

