<?php
$page_title = t("relatorios");
include '../includes/header.php';
protect_page();
?>
<div class="page-content">
    <h1>Emissão de Relatórios</h1>
    <p>Gere relatórios em PDF com os dados da sua conta para análises e compartilhamento.</p>

    <div class="form-container-interno">
        <h2>Relatório de Arquivos</h2>
        <p>Clique no botão abaixo para gerar um relatório em PDF com a lista de todos os seus arquivos cadastrados no sistema.</p>
        <br>
        <a href="gerar_relatorio.php" target="_blank" class="btn-cadastrar">Gerar Relatório PDF</a>
    </div>
    
</div>
<?php include '../includes/footer.php'; ?>

