Este repositório contem nosso trabalho PIT, para entrega de forma acadêmica para a conclusão de CURSO

## Integrantes do Grupo

- **Ravi Braga** - Matrícula: 22300198
- **Igor Ceolin** - Matrícula: 22300139
- **Cauã Lacerda** - Matrícula: 22301429
- **Gabriel Alves** - Matrícula: 22301577
- **Davi Torquato** - Matrícula: 22300333
Como Executar nosso Website:
1. Visão Geral do Projeto
Art Sync é uma aplicação web projetada para fornecer a artistas independentes as ferramentas necessárias para gerenciar suas carreiras. A plataforma centraliza funcionalidades essenciais, desde a organização de eventos até a análise de performance, em um ambiente gratuito e acessível.

O sistema é construído sobre uma base de PHP e MySQL, com uma interface de usuário reativa desenvolvida com JavaScript, HTML e CSS.

2. Guia de Instalação e Execução
Para executar este projeto em um ambiente de desenvolvimento local, siga as etapas detalhadas abaixo.

Etapa A: Configuração do Ambiente do Servidor
O projeto requer um servidor local que suporte PHP e um banco de dados MySQL. A solução recomendada é o XAMPP.

Instale o XAMPP:

Faça o download a partir do site oficial.

Siga as instruções de instalação para o seu sistema operacional (Windows, macOS ou Linux).

Posicione os Arquivos do Projeto:

Após a instalação, localize a pasta htdocs no diretório principal do XAMPP.

Mova a pasta do projeto artsync para dentro de htdocs.

Inicie os Serviços:

Abra o Painel de Controle do XAMPP.

Inicie os módulos Apache e MySQL.

Etapa B: Estruturação do Banco de Dados
Acesso ao phpMyAdmin:

Com os serviços do XAMPP em execução, acesse http://localhost/phpmyadmin/ em seu navegador.

Criação do Banco de Dados:

No painel esquerdo, clique em "Novo".

Nome do Banco de Dados: artsync_db

Agrupamento: utf8mb4_general_ci

Clique em "Criar".

Importação das Tabelas:

Selecione o banco de dados artsync_db recém-criado.

Navegue até a aba "SQL".

Copie e execute o script SQL abaixo para criar todas as tabelas e relacionamentos necessários.

SQL

CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  artist_name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE schedule (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  event_title varchar(255) NOT NULL,
  event_date datetime NOT NULL,
  notes text DEFAULT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT schedule_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE portfolio (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  description text DEFAULT NULL,
  file_path varchar(255) NOT NULL,
  media_type varchar(50) NOT NULL,
  uploaded_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT portfolio_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
Etapa C: Configuração da Aplicação
Conexão com o Banco de Dados:

Navegue até a pasta includes e abra o arquivo db_connect.php.

Verifique se as credenciais correspondem à sua configuração padrão do XAMPP.

PHP

$host = 'localhost';
$dbname = 'artsync_db';
$user = 'root';
$pass = ''; // A senha padrão do XAMPP é vazia
Chave de API do Google Gemini:

Para a funcionalidade da IA de Carreira, é necessária uma chave de API do Google Gemini.

Gere sua chave no Google AI Studio.

Abra o arquivo career_ai.php.

Localize a linha $api_key = 'SUA_CHAVE_DE_API_AQUI'; e insira sua chave.

Etapa D: Execução
Acesse a Aplicação:

Com o Apache e o MySQL ativos, abra seu navegador.

Acesse [http://localhost/artsyncc-main/artsync/](http://localhost/artsyncc-main/artsync/).

Utilização:

A página inicial será exibida. A partir dela, é possível criar uma conta de artista, fazer login e acessar o dashboard para utilizar todas as funcionalidades.
