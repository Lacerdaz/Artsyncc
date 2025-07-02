// Aguarda o carregamento completo da página
document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos do formulário
    const form = document.getElementById('cadastroForm');
    const nomeInput = document.getElementById('nome');
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');
    const loginLink = document.getElementById('loginLink');
    
    // Validação em tempo real
    function setupRealTimeValidation() {
      
        nomeInput.addEventListener('input', function() {
            validateName(this.value);
        });
        
        // Validação do email
        emailInput.addEventListener('input', function() {
            validateEmail(this.value);
        });
        
        // Validação da senha
        senhaInput.addEventListener('input', function() {
            validatePassword(this.value);
        });
    }
    
    // Função para validar nome
    function validateName(name) {
        const isValid = name.length >= 2;
        updateInputStyle(nomeInput, isValid);
        return isValid;
    }
    
    // Função para validar email
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(email);
        updateInputStyle(emailInput, isValid);
        return isValid;
    }
    
    // Função para validar senha
    function validatePassword(password) {
        const isValid = password.length >= 6;
        updateInputStyle(senhaInput, isValid);
        return isValid;
    }
    
    // Atualiza o estilo do input baseado na validação
    function updateInputStyle(input, isValid) {
        if (input.value.length > 0) {
            if (isValid) {
                input.style.borderColor = '#4CAF50';
                input.style.boxShadow = '0 0 5px rgba(76, 175, 80, 0.3)';
            } else {
                input.style.borderColor = '#f44336';
                input.style.boxShadow = '0 0 5px rgba(244, 67, 54, 0.3)';
            }
        } else {
            input.style.borderColor = '#ffffff';
            input.style.boxShadow = 'none';
        }
    }
    
    // Manipulador do envio do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Coleta os dados do formulário
        const formData = {
            nome: nomeInput.value.trim(),
            email: emailInput.value.trim(),
            senha: senhaInput.value
        };
        
        // Valida todos os campos
        const isNameValid = validateName(formData.nome);
        const isEmailValid = validateEmail(formData.email);
        const isPasswordValid = validatePassword(formData.senha);
        
        if (isNameValid && isEmailValid && isPasswordValid) {
            // Simula o envio do formulário
            showSuccessMessage();
            console.log('Dados do cadastro:', formData);
        } else {
            showErrorMessage();
        }
    });
    
    // Exibe mensagem de sucesso
    function showSuccessMessage() {
        const button = document.querySelector('.btn-cadastrar');
        const originalText = button.textContent;
        
        button.textContent = 'Cadastro realizado com sucesso!';
        button.style.backgroundColor = '#4CAF50';
        button.style.color = 'white';
        
        setTimeout(() => {
            button.textContent = originalText;
            button.style.backgroundColor = '#ffffff';
            button.style.color = '#1a1a1a';
            
            // Limpa o formulário
            form.reset();
            
            // Remove estilos de validação
            [nomeInput, emailInput, senhaInput].forEach(input => {
                input.style.borderColor = '#ffffff';
                input.style.boxShadow = 'none';
            });
        }, 3000);
    }
    
    // Exibe mensagem de erro
    function showErrorMessage() {
        const button = document.querySelector('.btn-cadastrar');
        const originalText = button.textContent;
        
        button.textContent = 'Verifique os dados e tente novamente';
        button.style.backgroundColor = '#f44336';
        button.style.color = 'white';
        
        // Adiciona animação de shake
        button.style.animation = 'shake 0.5s ease-in-out';
        
        setTimeout(() => {
            button.textContent = originalText;
            button.style.backgroundColor = '#ffffff';
            button.style.color = '#1a1a1a';
            button.style.animation = 'none';
        }, 3000);
    }
    
    // Manipulador do link de login
    loginLink.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Redirecionando para a página de login...');
        // Aqui você pode implementar a navegação para a página de login
    });
    
    // Efeitos de hover nos inputs
    function setupInputEffects() {
        const inputs = [nomeInput, emailInput, senhaInput];
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    }
    
    // Adiciona animação de shake ao CSS
    function addShakeAnimation() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Inicializa todas as funcionalidades
    setupRealTimeValidation();
    setupInputEffects();
    addShakeAnimation();
    
    // Adiciona placeholder dinâmico
    nomeInput.placeholder = 'Digite seu nome completo';
    emailInput.placeholder = 'Digite seu melhor e-mail';
    senhaInput.placeholder = 'Mínimo 6 caracteres';
    
    console.log('artSync - Sistema de cadastro inicializado com sucesso!');
});