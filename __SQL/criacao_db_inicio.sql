-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/04/2024 às 19:57
-- Versão do servidor: 8.0.30
-- Versão do PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `minhacarteira`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carteiras`
--

CREATE TABLE `carteiras` (
  `codigo` int NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `codigo_tipo_carteira` int DEFAULT NULL,
  `codigo_usuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `carteiras`
--

INSERT INTO `carteiras` (`codigo`, `descricao`, `codigo_tipo_carteira`, `codigo_usuario`) VALUES
(305, 'carteira ', NULL, 2),
(306, 'Conta Itaú Diego', NULL, 2),
(307, 'Pagamento Condomínio', NULL, 2),
(308, 'Teste', NULL, 2),
(309, 'Moradia', NULL, 2),
(310, 'Pagamento Cartão Magazine Luiza', 2, 2),
(311, 'Teste', 2, 2),
(312, 'Conta Itaú Diego', NULL, 2),
(313, 'radia', NULL, 2),
(314, 'ererer', NULL, 2),
(315, 'segue', 2, 2),
(316, 'Moradia', 54, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `codigo` int NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo` varchar(7) DEFAULT NULL,
  `codigo_usuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`codigo`, `descricao`, `tipo`, `codigo_usuario`) VALUES
(377, 'Moradia', 'Despesa', 2),
(378, 'Cartão de Crédito/Compras', 'Despesa', 2),
(382, 'Salário Estágio ', 'Receita', 2),
(383, 'Compras no Supermercado', 'Despesa', 2),
(385, 'Veículos', 'Despesa', 2),
(387, 'Salário Carla ', 'Receita', 2),
(388, 'Ticket', 'Receita', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lancamentos_despesas`
--

CREATE TABLE `lancamentos_despesas` (
  `codigo` int NOT NULL,
  `descricao` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `valor` decimal(10,0) DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `categoria` int DEFAULT NULL,
  `carteira` int DEFAULT NULL,
  `codigo_usuario` int DEFAULT NULL,
  `situacao` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `comprovante` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `lancamentos_despesas`
--

INSERT INTO `lancamentos_despesas` (`codigo`, `descricao`, `valor`, `data_pagamento`, `data_vencimento`, `categoria`, `carteira`, `codigo_usuario`, `situacao`, `comprovante`) VALUES
(34, 'Manutenção do Veículo', 150, '2024-04-14', '2024-04-16', 385, 306, 2, 'Em Aberto', ''),
(35, 'Pagamento Cartão Magazine Luiza', 462, '2024-04-17', '2024-04-16', 378, 306, 2, 'Pago', ''),
(36, 'Compras do Mês', 50, '2024-04-16', '2024-04-16', 383, 306, 2, 'Pago', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `lancamentos_receitas`
--

CREATE TABLE `lancamentos_receitas` (
  `codigo` int NOT NULL,
  `descricao` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `valor` int DEFAULT NULL,
  `data_credito` date NOT NULL,
  `categoria` int DEFAULT NULL,
  `carteira` int NOT NULL,
  `codigo_usuario` int DEFAULT NULL,
  `situacao` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `lancamentos_receitas`
--

INSERT INTO `lancamentos_receitas` (`codigo`, `descricao`, `valor`, `data_credito`, `categoria`, `carteira`, `codigo_usuario`, `situacao`) VALUES
(72, 'Recebimento Salário', 550, '2024-04-24', 387, 312, 2, 'Recebido'),
(73, 'Salário Estágio ', 67, '2024-04-24', 382, 306, 2, 'Recebido'),
(74, 'Recebimento de Ticket', 50, '2024-04-24', 388, 312, 2, 'Recebido');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_carteiras`
--

CREATE TABLE `tipos_carteiras` (
  `codigo` int NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `codigo_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `tipos_carteiras`
--

INSERT INTO `tipos_carteiras` (`codigo`, `descricao`, `codigo_usuario`) VALUES
(2, 'Conta Corrente', 2),
(3, 'Poupança', 2),
(12, 'Conta Itaú Diego', 2),
(17, 'Conta Salário Itaú Diego', 2),
(51, 'Pagamento Condomínio', 2),
(52, 'Tipo Novo de novo cacete', 2),
(53, 'Novo Tipo 03/09', 2),
(54, 'Novo Tipo', 2),
(55, 'Pagamento Condomínio', 2),
(56, 'Teste do teste', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `codigo` int NOT NULL,
  `nome` varchar(80) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nivel` varchar(80) DEFAULT NULL,
  `status` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`codigo`, `nome`, `email`, `senha`, `nivel`, `status`) VALUES
(2, 'Diego Aparecido Viola Pascoal', 'contatodiegopascoal@gmail.com', '$2y$10$EycESjLmiCFCZZiTGJo32uHXN7TJfRxTczaalkdkahdLY2rht3slK', 'Administrador', 'Ativo'),
(8, 'Cesar Ricardo Tavares', 'crtmanutencao@gmail.com', '$2y$10$AB/0Pq3XXmuO5RPAwmawsOdDiDNq94oLAr42OmwP2kaSaNCtSvN4S', 'Administrador', 'Ativado'),
(14, 'Eduardo Madeira', 'eduardo@fatec', '$2y$10$k.qGswwSLKEzQ06wbwRwh.wWjTLXGEij0ftiupxkrHF.7kpGSnFS.', 'Usuário', 'Desativado');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carteiras`
--
ALTER TABLE `carteiras`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codigo_tipo_carteira` (`codigo_tipo_carteira`),
  ADD KEY `codigo_usuario` (`codigo_usuario`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codigo_usuario` (`codigo_usuario`);

--
-- Índices de tabela `lancamentos_despesas`
--
ALTER TABLE `lancamentos_despesas`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codigo_usuario` (`codigo_usuario`),
  ADD KEY `carteira` (`carteira`),
  ADD KEY `categoria` (`categoria`);

--
-- Índices de tabela `lancamentos_receitas`
--
ALTER TABLE `lancamentos_receitas`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codigo_usuario` (`codigo_usuario`),
  ADD KEY `carteira` (`carteira`),
  ADD KEY `categoria` (`categoria`);

--
-- Índices de tabela `tipos_carteiras`
--
ALTER TABLE `tipos_carteiras`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codigo_usuario` (`codigo_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`codigo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carteiras`
--
ALTER TABLE `carteiras`
  MODIFY `codigo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `codigo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=389;

--
-- AUTO_INCREMENT de tabela `lancamentos_despesas`
--
ALTER TABLE `lancamentos_despesas`
  MODIFY `codigo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `lancamentos_receitas`
--
ALTER TABLE `lancamentos_receitas`
  MODIFY `codigo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `tipos_carteiras`
--
ALTER TABLE `tipos_carteiras`
  MODIFY `codigo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `codigo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carteiras`
--
ALTER TABLE `carteiras`
  ADD CONSTRAINT `carteiras_ibfk_1` FOREIGN KEY (`codigo_tipo_carteira`) REFERENCES `tipos_carteiras` (`codigo`),
  ADD CONSTRAINT `carteiras_ibfk_2` FOREIGN KEY (`codigo_usuario`) REFERENCES `usuarios` (`codigo`);

--
-- Restrições para tabelas `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`codigo_usuario`) REFERENCES `usuarios` (`codigo`);

--
-- Restrições para tabelas `lancamentos_despesas`
--
ALTER TABLE `lancamentos_despesas`
  ADD CONSTRAINT `lancamentos_despesas_ibfk_1` FOREIGN KEY (`codigo_usuario`) REFERENCES `usuarios` (`codigo`),
  ADD CONSTRAINT `lancamentos_despesas_ibfk_2` FOREIGN KEY (`carteira`) REFERENCES `carteiras` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lancamentos_despesas_ibfk_3` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `lancamentos_receitas`
--
ALTER TABLE `lancamentos_receitas`
  ADD CONSTRAINT `lancamentos_receitas_ibfk_1` FOREIGN KEY (`codigo_usuario`) REFERENCES `usuarios` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lancamentos_receitas_ibfk_2` FOREIGN KEY (`carteira`) REFERENCES `carteiras` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lancamentos_receitas_ibfk_3` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `tipos_carteiras`
--
ALTER TABLE `tipos_carteiras`
  ADD CONSTRAINT `tipos_carteiras_ibfk_1` FOREIGN KEY (`codigo_usuario`) REFERENCES `usuarios` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

