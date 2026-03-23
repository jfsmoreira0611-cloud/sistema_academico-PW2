-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Mar-2026 às 11:29
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_academico`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fichas_aluno`
--

CREATE TABLE `fichas_aluno` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `curso_pretendido_id` int(11) DEFAULT NULL,
  `estado` enum('rascunho','submetida','aprovada','rejeitada') DEFAULT 'rascunho',
  `observacoes` text DEFAULT NULL,
  `data_submissao` datetime DEFAULT NULL,
  `data_validacao` datetime DEFAULT NULL,
  `gestor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `matriculas`
--

CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `data_matricula` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `pauta_id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `nota` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pautas`
--

CREATE TABLE `pautas` (
  `id` int(11) NOT NULL,
  `uc_id` int(11) NOT NULL,
  `ano_letivo` varchar(9) NOT NULL,
  `epoca` enum('normal','recurso','especial') NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `funcionario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_matricula`
--

CREATE TABLE `pedidos_matricula` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `estado` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `data_pedido` datetime DEFAULT current_timestamp(),
  `data_decisao` datetime DEFAULT NULL,
  `funcionario_id` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `planos_estudos`
--

CREATE TABLE `planos_estudos` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `uc_id` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `semestre` tinyint(4) NOT NULL CHECK (`semestre` in (1,2))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidades_curriculares`
--

CREATE TABLE `unidades_curriculares` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `ects` int(11) DEFAULT 6,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `role` enum('aluno','funcionario','gestor') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `fichas_aluno`
--
ALTER TABLE `fichas_aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilizador_id` (`utilizador_id`),
  ADD KEY `curso_pretendido_id` (`curso_pretendido_id`),
  ADD KEY `gestor_id` (`gestor_id`);

--
-- Índices para tabela `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_matricula` (`utilizador_id`,`curso_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices para tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nota` (`pauta_id`,`utilizador_id`),
  ADD KEY `utilizador_id` (`utilizador_id`);

--
-- Índices para tabela `pautas`
--
ALTER TABLE `pautas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uc_id` (`uc_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices para tabela `pedidos_matricula`
--
ALTER TABLE `pedidos_matricula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilizador_id` (`utilizador_id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices para tabela `planos_estudos`
--
ALTER TABLE `planos_estudos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_plano` (`curso_id`,`uc_id`,`ano`,`semestre`),
  ADD KEY `uc_id` (`uc_id`);

--
-- Índices para tabela `unidades_curriculares`
--
ALTER TABLE `unidades_curriculares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fichas_aluno`
--
ALTER TABLE `fichas_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pautas`
--
ALTER TABLE `pautas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos_matricula`
--
ALTER TABLE `pedidos_matricula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planos_estudos`
--
ALTER TABLE `planos_estudos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `unidades_curriculares`
--
ALTER TABLE `unidades_curriculares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `fichas_aluno`
--
ALTER TABLE `fichas_aluno`
  ADD CONSTRAINT `fichas_aluno_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fichas_aluno_ibfk_2` FOREIGN KEY (`curso_pretendido_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `fichas_aluno_ibfk_3` FOREIGN KEY (`gestor_id`) REFERENCES `utilizadores` (`id`);

--
-- Limitadores para a tabela `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Limitadores para a tabela `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`pauta_id`) REFERENCES `pautas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`);

--
-- Limitadores para a tabela `pautas`
--
ALTER TABLE `pautas`
  ADD CONSTRAINT `pautas_ibfk_1` FOREIGN KEY (`uc_id`) REFERENCES `unidades_curriculares` (`id`),
  ADD CONSTRAINT `pautas_ibfk_2` FOREIGN KEY (`funcionario_id`) REFERENCES `utilizadores` (`id`);

--
-- Limitadores para a tabela `pedidos_matricula`
--
ALTER TABLE `pedidos_matricula`
  ADD CONSTRAINT `pedidos_matricula_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`),
  ADD CONSTRAINT `pedidos_matricula_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `pedidos_matricula_ibfk_3` FOREIGN KEY (`funcionario_id`) REFERENCES `utilizadores` (`id`);

--
-- Limitadores para a tabela `planos_estudos`
--
ALTER TABLE `planos_estudos`
  ADD CONSTRAINT `planos_estudos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `planos_estudos_ibfk_2` FOREIGN KEY (`uc_id`) REFERENCES `unidades_curriculares` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
