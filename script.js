// Aguarda o carregamento completo da página
document.addEventListener('DOMContentLoaded', function() {

    // --- LÓGICA PARA TROCA DE TEMA (CLARO/ESCURO) ---
    function aplicarTema() {
        const temaSalvo = localStorage.getItem('theme');
        if (temaSalvo === 'light') {
            document.body.classList.add('light-theme');
        } else {
            document.body.classList.remove('light-theme');
        }
    }

    function alternarTema(temaEscolhido) {
        if (temaEscolhido === 'light') {
            document.body.classList.add('light-theme');
            localStorage.setItem('theme', 'light');
        } else {
            document.body.classList.remove('light-theme');
            localStorage.setItem('theme', 'dark');
        }
    }

    aplicarTema(); // Aplica o tema salvo ao carregar a página

    const themeSelect = document.getElementById('themeSelect');
    if (themeSelect) {
        themeSelect.value = localStorage.getItem('theme') || 'dark'; // Define o valor inicial do select
        themeSelect.addEventListener('change', function() {
            alternarTema(this.value);
        });
    }

    // --- LÓGICA PARA IDIOMA (EXEMPLO) ---
    const languageSelect = document.getElementById('languageSelect');
    if (languageSelect) {
        // Aqui você implementaria a lógica para carregar e salvar o idioma preferido
        // Por simplicidade, vamos apenas exibir um alerta.
        languageSelect.addEventListener('change', function() {
            alert('Idioma alterado para: ' + this.value + '. (Funcionalidade de tradução completa em desenvolvimento)');
            // Em um projeto real, você recarregaria o conteúdo com o novo idioma
        });
    }

    // --- VALIDAÇÃO E MANIPULAÇÃO DE FORMULÁRIOS (ADAPTADO DO CADASTRO) ---

    // Formulário de Cadastro (register.html)
    const cadastroForm = document.getElementById('cadastroForm');
    if (cadastroForm) {
        const nomeInput = document.getElementById('nome');
        const emailInput = document.getElementById('email');
        const senhaInput = document.getElementById('senha');

        cadastroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const isNameValid = nomeInput.value.trim().length >= 2;
            const isEmailValid = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(emailInput.value.trim());
            const isPasswordValid = senhaInput.value.length >= 6;

            if (isNameValid && isEmailValid && isPasswordValid) {
                alert('Cadastro simulado com sucesso! Redirecionando para o login.');
                window.location.href = 'login.html';
            } else {
                alert('Por favor, preencha todos os campos corretamente.');
            }
        });
    }

    // Formulário de Login (login.html)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('senha').value;

            if (email === 'teste@teste.com' && senha === '123456') { // Credenciais de exemplo
                alert('Login simulado com sucesso! Redirecionando para o Dashboard.');
                window.location.href = 'index.html';
            } else {
                alert('E-mail ou senha incorretos. Tente novamente.');
            }
        });
    }

    // Formulário de Redefinição de Senha (reset_password.html)
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value.trim();
            if (/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                alert('Link de redefinição de senha simulado enviado para ' + email + '.');
            } else {
                alert('Por favor, insira um e-mail válido.');
            }
        });
    }

    // Formulário de Upload (storage.html)
    const uploadForm = document.querySelector('.upload-form');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const fileInput = document.getElementById('fileUpload');
            if (fileInput.files.length > 0) {
                const fileName = fileInput.files[0].name;
                const fileList = document.querySelector('.file-list');
                const newFileItem = document.createElement('li');
                newFileItem.innerHTML = `${fileName} <a href="#">Ver</a> <a href="#" class="delete-file">Excluir</a>`;
                fileList.appendChild(newFileItem);
                alert(`Arquivo '${fileName}' simulado como enviado.`);
                fileInput.value = ''; // Limpa o input
            } else {
                alert('Por favor, selecione um arquivo para upload.');
            }
        });

        // Delegação de evento para exclusão de arquivos
        document.querySelector('.file-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-file')) {
                if (confirm('Tem certeza que deseja excluir este arquivo?')) {
                    e.target.closest('li').remove();
                    alert('Arquivo simulado como excluído.');
                }
            }
        });
    }

    // Formulário de Busca (search.html)
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchInput = document.getElementById('searchInput').value.trim();
            const searchResultsList = document.querySelector('.search-results-list');
            searchResultsList.innerHTML = ''; // Limpa resultados anteriores

            if (searchInput) {
                // Simula resultados de busca
                const dummyResults = [
                    'Documento_Artista.pdf',
                    'Portifolio_2024.zip',
                    'Musica_Nova.mp3',
                    'Contrato_Show.docx'
                ];
                const filteredResults = dummyResults.filter(file => file.toLowerCase().includes(searchInput.toLowerCase()));

                if (filteredResults.length > 0) {
                    filteredResults.forEach(result => {
                        const listItem = document.createElement('li');
                        listItem.textContent = result;
                        searchResultsList.appendChild(listItem);
                    });
                } else {
                    const listItem = document.createElement('li');
                    listItem.textContent = 'Nenhum resultado encontrado para "' + searchInput + '".';
                    searchResultsList.appendChild(listItem);
                }
            } else {
                const listItem = document.createElement('li');
                listItem.textContent = 'Digite um termo na caixa de busca para começar.';
                searchResultsList.appendChild(listItem);
            }
        });
    }

    // Formulário de Alterar Senha (settings.html)
    const settingsForm = document.querySelector('.settings-form');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            if (currentPassword && newPassword && confirmNewPassword) {
                if (newPassword === confirmNewPassword) {
                    if (newPassword.length >= 6) {
                        alert('Senha alterada simuladamente com sucesso!');
                        settingsForm.reset();
                    } else {
                        alert('A nova senha deve ter pelo menos 6 caracteres.');
                    }
                } else {
                    alert('As novas senhas não coincidem.');
                }
            } else {
                alert('Por favor, preencha todos os campos de senha.');
            }
        });
    }

    // Botão Gerar Relatório PDF (reports.html)
    const generateReportBtn = document.querySelector('.dashboard-container .btn-cadastrar');
    if (generateReportBtn) {
        generateReportBtn.addEventListener('click', function() {
            alert('Geração de relatório PDF simulada. (Em um projeto real, um PDF seria baixado)');
        });
    }

    console.log('artSync - Frontend inicializado com sucesso!');
});




    // --- GRÁFICOS PARA A PÁGINA DE ALCANCE ---
    if (document.getElementById('followersChart')) {
        const followersCtx = document.getElementById('followersChart').getContext('2d');
        new Chart(followersCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set'],
                datasets: [{
                    label: 'Seguidores',
                    data: [1200, 1350, 1500, 1800, 2000, 2200, 2300, 2400, 2500],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ccc'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            color: '#ccc'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#ccc'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }

    if (document.getElementById('engagementChart')) {
        const engagementCtx = document.getElementById('engagementChart').getContext('2d');
        new Chart(engagementCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set'],
                datasets: [{
                    label: 'Taxa de Engajamento (%)',
                    data: [12.5, 14.2, 13.8, 15.1, 16.3, 15.8, 17.2, 16.9, 15.8],
                    backgroundColor: 'rgba(76, 175, 80, 0.8)',
                    borderColor: '#4CAF50',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ccc'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ccc',
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#ccc'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }

    // --- GERAÇÃO DE PDF REAL ---
    function generatePDF() {
        // Simula a coleta de dados do usuário
        const userData = {
            nome: 'Usuário artSync',
            email: 'usuario@artsync.com',
            seguidores: '2.5K',
            engajamento: '15.8%',
            posts: '342',
            arquivos: [
                'Documento_Artista.pdf',
                'Portifolio_2024.zip',
                'Musica_Nova.mp3',
                'Contrato_Show.docx'
            ]
        };

        // Cria o conteúdo HTML para o PDF
        const htmlContent = `
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
                    .section { margin-bottom: 25px; }
                    .section h2 { color: #4CAF50; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
                    .stats-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .stats-table th, .stats-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .stats-table th { background-color: #f2f2f2; }
                    .file-list { list-style-type: none; padding: 0; }
                    .file-list li { padding: 5px 0; border-bottom: 1px solid #eee; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>artSync - Relatório de Atividades</h1>
                    <p>Gerado em: ${new Date().toLocaleDateString('pt-BR')}</p>
                </div>
                
                <div class="section">
                    <h2>Informações do Usuário</h2>
                    <p><strong>Nome:</strong> ${userData.nome}</p>
                    <p><strong>Email:</strong> ${userData.email}</p>
                </div>
                
                <div class="section">
                    <h2>Estatísticas de Alcance</h2>
                    <table class="stats-table">
                        <tr><th>Métrica</th><th>Valor</th></tr>
                        <tr><td>Seguidores</td><td>${userData.seguidores}</td></tr>
                        <tr><td>Taxa de Engajamento</td><td>${userData.engajamento}</td></tr>
                        <tr><td>Posts este mês</td><td>${userData.posts}</td></tr>
                    </table>
                </div>
                
                <div class="section">
                    <h2>Arquivos Armazenados</h2>
                    <ul class="file-list">
                        ${userData.arquivos.map(arquivo => `<li>${arquivo}</li>`).join('')}
                    </ul>
                </div>
            </body>
            </html>
        `;

        // Cria um blob com o conteúdo HTML
        const blob = new Blob([htmlContent], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        
        // Cria um link para download
        const link = document.createElement('a');
        link.href = url;
        link.download = `artSync_relatorio_${new Date().toISOString().split('T')[0]}.html`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        alert('Relatório HTML gerado e baixado com sucesso! (Em um ambiente real, seria um PDF)');
    }

    // Conecta a função ao botão de gerar relatório
    const generateReportBtn = document.querySelector('.dashboard-container .btn-cadastrar');
    if (generateReportBtn && generateReportBtn.textContent.includes('Gerar Relatório')) {
        generateReportBtn.removeEventListener('click', function() {});
        generateReportBtn.addEventListener('click', generatePDF);
    }

    // --- MÉTRICAS DE USO PARA O DASHBOARD ---
    function updateDashboardMetrics() {
        const metricsWidget = document.querySelector('.widget h3');
        if (metricsWidget && metricsWidget.textContent === 'Métricas de Uso') {
            const widget = metricsWidget.parentElement;
            
            // Simula dados de métricas em tempo real
            const metrics = {
                sessoes: Math.floor(Math.random() * 50) + 100,
                tempoMedio: Math.floor(Math.random() * 30) + 15,
                paginasVistas: Math.floor(Math.random() * 200) + 300,
                bounceRate: (Math.random() * 20 + 25).toFixed(1)
            };

            widget.innerHTML = `
                <h3>Métricas de Uso</h3>
                <div class="metrics-container">
                    <div class="metric-card">
                        <span class="metric-value">${metrics.sessoes}</span>
                        <span class="metric-label">Sessões hoje</span>
                        <span class="metric-change positive">+12%</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-value">${metrics.tempoMedio}min</span>
                        <span class="metric-label">Tempo médio</span>
                        <span class="metric-change positive">+5%</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-value">${metrics.paginasVistas}</span>
                        <span class="metric-label">Páginas vistas</span>
                        <span class="metric-change negative">-3%</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-value">${metrics.bounceRate}%</span>
                        <span class="metric-label">Taxa de rejeição</span>
                        <span class="metric-change positive">-8%</span>
                    </div>
                </div>
            `;
        }
    }

    // Atualiza métricas se estivermos no dashboard
    if (window.location.pathname.includes('index.html') || window.location.pathname === '/') {
        updateDashboardMetrics();
        // Atualiza as métricas a cada 30 segundos
        setInterval(updateDashboardMetrics, 30000);
    }

    // --- SISTEMA DE NOTIFICAÇÕES MELHORADO ---
    function enhanceNotifications() {
        const notificationsList = document.querySelector('.notification-list');
        if (notificationsList) {
            // Substitui a lista simples por notificações mais ricas
            notificationsList.innerHTML = `
                <div class="notification-item unread">
                    <div class="notification-icon">📊</div>
                    <div class="notification-content">
                        <div class="notification-title">Relatório Mensal Disponível</div>
                        <div class="notification-text">Seu relatório mensal de alcance está pronto para download.</div>
                        <div class="notification-date">25/08/2025</div>
                    </div>
                    <div class="notification-actions">
                        <button onclick="markAsRead(this)">Marcar como lida</button>
                    </div>
                </div>
                <div class="notification-item">
                    <div class="notification-icon">🎨</div>
                    <div class="notification-content">
                        <div class="notification-title">Novo Recurso</div>
                        <div class="notification-text">Nova funcionalidade de personalização de interface disponível.</div>
                        <div class="notification-date">20/08/2025</div>
                    </div>
                    <div class="notification-actions">
                        <button onclick="markAsRead(this)">Marcar como lida</button>
                    </div>
                </div>
                <div class="notification-item">
                    <div class="notification-icon">💾</div>
                    <div class="notification-content">
                        <div class="notification-title">Lembrete de Backup</div>
                        <div class="notification-text">Não se esqueça de fazer backup dos seus arquivos importantes.</div>
                        <div class="notification-date">15/08/2025</div>
                    </div>
                    <div class="notification-actions">
                        <button onclick="markAsRead(this)">Marcar como lida</button>
                    </div>
                </div>
            `;
        }
    }

    // Função para marcar notificação como lida
    window.markAsRead = function(button) {
        const notificationItem = button.closest('.notification-item');
        notificationItem.classList.remove('unread');
        button.textContent = 'Lida';
        button.disabled = true;
    };

    // Aplica melhorias nas notificações
    if (window.location.pathname.includes('notifications.html')) {
        enhanceNotifications();
    }

    console.log('artSync - Funcionalidades avançadas carregadas!');
;


    // --- SISTEMA DE TRADUÇÃO ---
    const translations = {
        'pt-BR': {
            nav_dashboard: 'Dashboard',
            nav_reach: 'Alcance',
            nav_storage: 'Armazenamento',
            nav_reports: 'Relatórios',
            nav_search: 'Busca',
            nav_settings: 'Configurações',
            nav_notifications: 'Notificações',
            nav_logout: 'Sair',
            settings_title: 'Configurações',
            settings_subtitle: 'Personalize sua experiência no artSync.',
            change_password: 'Alterar Senha',
            current_password: 'Senha Atual',
            new_password: 'Nova Senha',
            confirm_password: 'Confirmar Nova Senha',
            save_password: 'Salvar Senha',
            interface_theme: 'Tema da Interface',
            theme: 'Tema',
            theme_dark: 'Escuro',
            theme_light: 'Claro',
            accent_color: 'Cor de Destaque',
            font_size: 'Tamanho da Fonte',
            system_language: 'Idioma do Sistema',
            language: 'Idioma',
            language_note: 'Alterações de idioma serão aplicadas após recarregar a página.',
            display_preferences: 'Preferências de Exibição',
            show_animations: 'Mostrar animações',
            compact_mode: 'Modo compacto',
            show_tooltips: 'Mostrar dicas',
            auto_save: 'Salvamento automático',
            reset_settings: 'Redefinir Configurações',
            reset_warning: 'Isso restaurará todas as configurações para os valores padrão.',
            reset_all: 'Redefinir Tudo',
            rights_reserved: 'Todos os direitos reservados.'
        },
        'en-US': {
            nav_dashboard: 'Dashboard',
            nav_reach: 'Reach',
            nav_storage: 'Storage',
            nav_reports: 'Reports',
            nav_search: 'Search',
            nav_settings: 'Settings',
            nav_notifications: 'Notifications',
            nav_logout: 'Logout',
            settings_title: 'Settings',
            settings_subtitle: 'Customize your artSync experience.',
            change_password: 'Change Password',
            current_password: 'Current Password',
            new_password: 'New Password',
            confirm_password: 'Confirm New Password',
            save_password: 'Save Password',
            interface_theme: 'Interface Theme',
            theme: 'Theme',
            theme_dark: 'Dark',
            theme_light: 'Light',
            accent_color: 'Accent Color',
            font_size: 'Font Size',
            system_language: 'System Language',
            language: 'Language',
            language_note: 'Language changes will be applied after reloading the page.',
            display_preferences: 'Display Preferences',
            show_animations: 'Show animations',
            compact_mode: 'Compact mode',
            show_tooltips: 'Show tooltips',
            auto_save: 'Auto save',
            reset_settings: 'Reset Settings',
            reset_warning: 'This will restore all settings to default values.',
            reset_all: 'Reset All',
            rights_reserved: 'All rights reserved.'
        },
        'es-ES': {
            nav_dashboard: 'Panel',
            nav_reach: 'Alcance',
            nav_storage: 'Almacenamiento',
            nav_reports: 'Informes',
            nav_search: 'Buscar',
            nav_settings: 'Configuración',
            nav_notifications: 'Notificaciones',
            nav_logout: 'Salir',
            settings_title: 'Configuración',
            settings_subtitle: 'Personaliza tu experiencia en artSync.',
            change_password: 'Cambiar Contraseña',
            current_password: 'Contraseña Actual',
            new_password: 'Nueva Contraseña',
            confirm_password: 'Confirmar Nueva Contraseña',
            save_password: 'Guardar Contraseña',
            interface_theme: 'Tema de Interfaz',
            theme: 'Tema',
            theme_dark: 'Oscuro',
            theme_light: 'Claro',
            accent_color: 'Color de Acento',
            font_size: 'Tamaño de Fuente',
            system_language: 'Idioma del Sistema',
            language: 'Idioma',
            language_note: 'Los cambios de idioma se aplicarán después de recargar la página.',
            display_preferences: 'Preferencias de Visualización',
            show_animations: 'Mostrar animaciones',
            compact_mode: 'Modo compacto',
            show_tooltips: 'Mostrar consejos',
            auto_save: 'Guardado automático',
            reset_settings: 'Restablecer Configuración',
            reset_warning: 'Esto restaurará todas las configuraciones a los valores predeterminados.',
            reset_all: 'Restablecer Todo',
            rights_reserved: 'Todos los derechos reservados.'
        },
        'fr-FR': {
            nav_dashboard: 'Tableau de bord',
            nav_reach: 'Portée',
            nav_storage: 'Stockage',
            nav_reports: 'Rapports',
            nav_search: 'Recherche',
            nav_settings: 'Paramètres',
            nav_notifications: 'Notifications',
            nav_logout: 'Déconnexion',
            settings_title: 'Paramètres',
            settings_subtitle: 'Personnalisez votre expérience artSync.',
            change_password: 'Changer le mot de passe',
            current_password: 'Mot de passe actuel',
            new_password: 'Nouveau mot de passe',
            confirm_password: 'Confirmer le nouveau mot de passe',
            save_password: 'Enregistrer le mot de passe',
            interface_theme: 'Thème de l\'interface',
            theme: 'Thème',
            theme_dark: 'Sombre',
            theme_light: 'Clair',
            accent_color: 'Couleur d\'accent',
            font_size: 'Taille de police',
            system_language: 'Langue du système',
            language: 'Langue',
            language_note: 'Les changements de langue seront appliqués après le rechargement de la page.',
            display_preferences: 'Préférences d\'affichage',
            show_animations: 'Afficher les animations',
            compact_mode: 'Mode compact',
            show_tooltips: 'Afficher les conseils',
            auto_save: 'Sauvegarde automatique',
            reset_settings: 'Réinitialiser les paramètres',
            reset_warning: 'Cela restaurera tous les paramètres aux valeurs par défaut.',
            reset_all: 'Tout réinitialiser',
            rights_reserved: 'Tous droits réservés.'
        }
    };

    function translatePage(language) {
        const elements = document.querySelectorAll('[data-translate]');
        const langData = translations[language] || translations['pt-BR'];
        
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            if (langData[key]) {
                element.textContent = langData[key];
            }
        });
        
        localStorage.setItem('language', language);
    }

    // --- PERSONALIZAÇÃO AVANÇADA ---
    function applyAdvancedCustomization() {
        // Aplicar tema salvo
        const savedTheme = localStorage.getItem('theme') || 'dark';
        const savedAccentColor = localStorage.getItem('accentColor') || '#4CAF50';
        const savedFontSize = localStorage.getItem('fontSize') || 'medium';
        const savedLanguage = localStorage.getItem('language') || 'pt-BR';
        
        // Aplicar tema
        document.body.className = '';
        if (savedTheme === 'light') {
            document.body.classList.add('light-theme');
        } else if (savedTheme === 'blue') {
            document.body.classList.add('blue-theme');
        } else if (savedTheme === 'green') {
            document.body.classList.add('green-theme');
        }
        
        // Aplicar cor de destaque
        document.documentElement.style.setProperty('--accent-color', savedAccentColor);
        
        // Aplicar tamanho da fonte
        if (savedFontSize === 'small') {
            document.body.classList.add('font-small');
        } else if (savedFontSize === 'large') {
            document.body.classList.add('font-large');
        }
        
        // Aplicar preferências de exibição
        const showAnimations = localStorage.getItem('showAnimations') !== 'false';
        const compactMode = localStorage.getItem('compactMode') === 'true';
        const showTooltips = localStorage.getItem('showTooltips') !== 'false';
        
        if (!showAnimations) {
            document.body.classList.add('no-animations');
        }
        
        if (compactMode) {
            document.body.classList.add('compact-mode');
        }
        
        // Aplicar idioma
        translatePage(savedLanguage);
        
        // Atualizar controles na página de configurações
        updateSettingsControls(savedTheme, savedAccentColor, savedFontSize, savedLanguage);
    }

    function updateSettingsControls(theme, accentColor, fontSize, language) {
        const themeSelect = document.getElementById('themeSelect');
        const accentColorSelect = document.getElementById('accentColorSelect');
        const fontSizeSelect = document.getElementById('fontSizeSelect');
        const languageSelect = document.getElementById('languageSelect');
        
        if (themeSelect) themeSelect.value = theme;
        if (accentColorSelect) accentColorSelect.value = accentColor;
        if (fontSizeSelect) fontSizeSelect.value = fontSize;
        if (languageSelect) languageSelect.value = language;
        
        // Atualizar checkboxes
        const showAnimationsCheck = document.getElementById('showAnimations');
        const compactModeCheck = document.getElementById('compactMode');
        const showTooltipsCheck = document.getElementById('showTooltips');
        const autoSaveCheck = document.getElementById('autoSave');
        
        if (showAnimationsCheck) showAnimationsCheck.checked = localStorage.getItem('showAnimations') !== 'false';
        if (compactModeCheck) compactModeCheck.checked = localStorage.getItem('compactMode') === 'true';
        if (showTooltipsCheck) showTooltipsCheck.checked = localStorage.getItem('showTooltips') !== 'false';
        if (autoSaveCheck) autoSaveCheck.checked = localStorage.getItem('autoSave') !== 'false';
    }

    // --- EVENT LISTENERS PARA CONFIGURAÇÕES AVANÇADAS ---
    const accentColorSelect = document.getElementById('accentColorSelect');
    if (accentColorSelect) {
        accentColorSelect.addEventListener('change', function() {
            localStorage.setItem('accentColor', this.value);
            document.documentElement.style.setProperty('--accent-color', this.value);
        });
    }

    const fontSizeSelect = document.getElementById('fontSizeSelect');
    if (fontSizeSelect) {
        fontSizeSelect.addEventListener('change', function() {
            localStorage.setItem('fontSize', this.value);
            document.body.classList.remove('font-small', 'font-large');
            if (this.value === 'small') {
                document.body.classList.add('font-small');
            } else if (this.value === 'large') {
                document.body.classList.add('font-large');
            }
        });
    }

    // Event listeners para checkboxes
    const showAnimationsCheck = document.getElementById('showAnimations');
    if (showAnimationsCheck) {
        showAnimationsCheck.addEventListener('change', function() {
            localStorage.setItem('showAnimations', this.checked);
            if (this.checked) {
                document.body.classList.remove('no-animations');
            } else {
                document.body.classList.add('no-animations');
            }
        });
    }

    const compactModeCheck = document.getElementById('compactMode');
    if (compactModeCheck) {
        compactModeCheck.addEventListener('change', function() {
            localStorage.setItem('compactMode', this.checked);
            if (this.checked) {
                document.body.classList.add('compact-mode');
            } else {
                document.body.classList.remove('compact-mode');
            }
        });
    }

    const showTooltipsCheck = document.getElementById('showTooltips');
    if (showTooltipsCheck) {
        showTooltipsCheck.addEventListener('change', function() {
            localStorage.setItem('showTooltips', this.checked);
        });
    }

    const autoSaveCheck = document.getElementById('autoSave');
    if (autoSaveCheck) {
        autoSaveCheck.addEventListener('change', function() {
            localStorage.setItem('autoSave', this.checked);
        });
    }

    // Atualizar o event listener do idioma
    if (languageSelect) {
        languageSelect.addEventListener('change', function() {
            localStorage.setItem('language', this.value);
            translatePage(this.value);
            alert('Idioma alterado! Recarregue a página para ver todas as mudanças.');
        });
    }

    // Função para redefinir todas as configurações
    window.resetAllSettings = function() {
        if (confirm('Tem certeza que deseja redefinir todas as configurações?')) {
            localStorage.removeItem('theme');
            localStorage.removeItem('accentColor');
            localStorage.removeItem('fontSize');
            localStorage.removeItem('language');
            localStorage.removeItem('showAnimations');
            localStorage.removeItem('compactMode');
            localStorage.removeItem('showTooltips');
            localStorage.removeItem('autoSave');
            
            alert('Configurações redefinidas! Recarregando a página...');
            window.location.reload();
        }
    };

    // Aplicar personalizações ao carregar a página
    applyAdvancedCustomization();

    console.log('artSync - Sistema de personalização avançada carregado!');
;

