-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/05/2026 às 19:46
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `trainops`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acompanhamento_treinamento_usuario`
--

CREATE TABLE `acompanhamento_treinamento_usuario` (
  `id_gastos` int(11) NOT NULL,
  `id_orcamento` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_usuario_logado` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_tipo_treinamento` int(11) NOT NULL,
  `instituicao` varchar(60) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `inscricao` varchar(60) NOT NULL,
  `hospedagem` varchar(60) NOT NULL,
  `passagem` varchar(60) NOT NULL,
  `translado` varchar(60) NOT NULL,
  `diaria` varchar(60) NOT NULL,
  `valor_total` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `acompanhamento_treinamento_usuario`
--

INSERT INTO `acompanhamento_treinamento_usuario` (`id_gastos`, `id_orcamento`, `id_usuario`, `id_usuario_logado`, `id_curso`, `id_tipo_treinamento`, `instituicao`, `data_inicio`, `data_fim`, `inscricao`, `hospedagem`, `passagem`, `translado`, `diaria`, `valor_total`) VALUES
(1, 3, 2, 1, 1, 0, 'SEST', '2026-05-02', '2026-05-12', '200', '1000', '2000', '1000', '300', '4500.00'),
(2, 3, 3, 1, 3, 0, 'PV', '2026-05-15', '2026-05-15', '2000', '2000', '500', '200', '100', '4700.00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `nome` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `nome`) VALUES
(1, 'RECURSOS HUMANOS'),
(3, 'teste'),
(4, 'Assistente Administrativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `nome_curso` varchar(60) NOT NULL,
  `carga_horaria` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curso`
--

INSERT INTO `curso` (`id_curso`, `id_tipo`, `nome_curso`, `carga_horaria`) VALUES
(1, 4, 'Excel', '40'),
(3, 5, 'Word_X', '60');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento_anual`
--

CREATE TABLE `orcamento_anual` (
  `id_orcamento` int(11) NOT NULL,
  `ano` int(11) DEFAULT NULL,
  `valor_total` float DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `orcamento_anual`
--

INSERT INTO `orcamento_anual` (`id_orcamento`, `ano`, `valor_total`, `id_usuario`) VALUES
(3, 2026, 200000, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_treinamento`
--

CREATE TABLE `tipos_treinamento` (
  `id_tipo` int(11) NOT NULL,
  `descricao` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipos_treinamento`
--

INSERT INTO `tipos_treinamento` (`id_tipo`, `descricao`) VALUES
(4, 'Aperfeiçoamento'),
(5, 'Qualificação');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(60) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `id_cargo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `id_cargo`) VALUES
(1, 'PRIMATA BATATA', 'primatabatata@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1),
(2, 'marilda', 'marilda@marilda', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(3, 'Tereza Brasil Cheguei', 'terezabrasil@terezabrasil.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 4);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acompanhamento_treinamento_usuario`
--
ALTER TABLE `acompanhamento_treinamento_usuario`
  ADD PRIMARY KEY (`id_gastos`),
  ADD KEY `acompanhamento_treinamento_usuario_ibfk_2` (`id_orcamento`),
  ADD KEY `acompanhamento_treinamento_usuario_ibfk_3` (`id_usuario`),
  ADD KEY `fk_curso_usuario` (`id_curso`);

--
-- Índices de tabela `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Índices de tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `fk_curso_treinamento` (`id_tipo`);

--
-- Índices de tabela `orcamento_anual`
--
ALTER TABLE `orcamento_anual`
  ADD PRIMARY KEY (`id_orcamento`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `tipos_treinamento`
--
ALTER TABLE `tipos_treinamento`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `usuario_ibfk_1` (`id_cargo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acompanhamento_treinamento_usuario`
--
ALTER TABLE `acompanhamento_treinamento_usuario`
  MODIFY `id_gastos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `orcamento_anual`
--
ALTER TABLE `orcamento_anual`
  MODIFY `id_orcamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tipos_treinamento`
--
ALTER TABLE `tipos_treinamento`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `acompanhamento_treinamento_usuario`
--
ALTER TABLE `acompanhamento_treinamento_usuario`
  ADD CONSTRAINT `acompanhamento_treinamento_usuario_ibfk_2` FOREIGN KEY (`id_orcamento`) REFERENCES `orcamento_anual` (`id_orcamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `acompanhamento_treinamento_usuario_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_curso_usuario` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `fk_curso_treinamento` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_treinamento` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `orcamento_anual`
--
ALTER TABLE `orcamento_anual`
  ADD CONSTRAINT `orcamento_anual_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
