-- 1. Criar o Banco de Dados
CREATE DATABASE IF NOT EXISTS ecoconecta;
USE ecoconecta;

-- 2. Tabela de Usuários
-- Armazena Doadores, Coletores e Admins
CREATE TABLE IF NOT EXISTS USUARIO (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    documento VARCHAR(20) NOT NULL UNIQUE, -- CPF ou CNPJ
    telefone VARCHAR(20) NOT NULL,
    endereco TEXT NOT NULL,
    tipo_usuario ENUM('Doador', 'Coletor', 'Admin') NOT NULL,
    senha VARCHAR(255) NOT NULL,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabela de Materiais Recicláveis
-- Categorias cadastradas pelo Administrador
CREATE TABLE IF NOT EXISTS MATERIAL_RECICLAVEL (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    nome_material VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabela de Doações
-- Registra o que está sendo doado e por quem
CREATE TABLE IF NOT EXISTS DOACAO (
    id_doacao INT AUTO_INCREMENT PRIMARY KEY,
    id_doador INT NOT NULL,
    id_material INT DEFAULT NULL, -- Relaciona com material cadastrado
    material_personalizado VARCHAR(100) DEFAULT NULL, -- Caso o usuário escolha "Outro"
    quantidade DECIMAL(10,2) NOT NULL,
    unidade_medida ENUM('kg', 'un') NOT NULL,
    endereco_manual TEXT, -- Endereço específico da coleta (pode ser o do perfil ou GPS)
    observacoes TEXT,
    status_doacao ENUM('Disponível', 'Reservado', 'Coletado', 'Cancelado') DEFAULT 'Disponível',
    id_coletor INT DEFAULT NULL, -- Quem reservou/coletou
    data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_doador) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_coletor) REFERENCES USUARIO(id_usuario) ON DELETE SET NULL,
    FOREIGN KEY (id_material) REFERENCES MATERIAL_RECICLAVEL(id_material) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Dados Iniciais para Teste

-- Inserir Categorias Básicas
INSERT INTO MATERIAL_RECICLAVEL (nome_material) VALUES 
('Papel/Papelão'),
('Plástico'),
('Vidro'),
('Metal/Alumínio'),
('Óleo de Cozinha'),
('Eletrônicos');

-- Inserir um Administrador Padrão (Senha: admin123)
-- Nota: Em produção, a senha deve ser gerada via password_hash no PHP.
-- Esta hash abaixo corresponde a 'admin123'
INSERT INTO USUARIO (nome, email, documento, telefone, endereco, tipo_usuario, senha) 
VALUES ('Administrador', 'admin@eco.com', '00000000000', '54999999999', 'Ibirubá, Centro', 'Admin', '$2y$10$7rLSvRl15Z5N.tADK/XU6uX6qJ.3C8K.t0/v6/v.v/v.v/v.v/v.v');