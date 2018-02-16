-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 15-Fev-2018 às 22:14
-- Versão do servidor: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xp`
--
CREATE DATABASE IF NOT EXISTS `xp` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `xp`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(32) NOT NULL,
  `trimestre` char(6) NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `data_limite` date NOT NULL,
  `data_avaliacao` datetime DEFAULT NULL,
  `comentario_colaborador` text,
  `revisor_id` int(10) UNSIGNED DEFAULT NULL,
  `data_revisao` datetime DEFAULT NULL,
  `comentario_revisor` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Acionadores `avaliacao`
--
DELIMITER $$
CREATE TRIGGER `avaliacao_bi` BEFORE INSERT ON `avaliacao` FOR EACH ROW SET NEW.hash = MD5(CONCAT(RAND(), NOW()))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao_competencia`
--

CREATE TABLE `avaliacao_competencia` (
  `id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(32) NOT NULL,
  `avaliacao_id` int(10) UNSIGNED NOT NULL,
  `competencia_id` int(10) UNSIGNED NOT NULL,
  `nivel` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Acionadores `avaliacao_competencia`
--
DELIMITER $$
CREATE TRIGGER `avaliacao_competencia_bi` BEFORE INSERT ON `avaliacao_competencia` FOR EACH ROW SET NEW.hash = MD5(CONCAT(RAND(), NOW()))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `competencia`
--

CREATE TABLE `competencia` (
  `id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(32) NOT NULL,
  `uo_id` int(10) UNSIGNED NOT NULL,
  `ordem` int(11) UNSIGNED NOT NULL,
  `sigla` char(4) NOT NULL,
  `competencia` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `replicar` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Replicar para Unidades Subordinadas',
  `ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Acionadores `competencia`
--
DELIMITER $$
CREATE TRIGGER `competencia_bi` BEFORE INSERT ON `competencia` FOR EACH ROW BEGIN
  SET NEW.hash = MD5(CONCAT(RAND(), NOW()));
  SET NEW.ordem = (SELECT IFNULL(MAX(ordem), 0) + 1 FROM competencia WHERE uo_id = NEW.uo_id);
  SET NEW.sigla = UPPER(NEW.sigla);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `competencia_bu` BEFORE UPDATE ON `competencia` FOR EACH ROW SET NEW.sigla = UPPER(NEW.sigla)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `uo`
--

CREATE TABLE `uo` (
  `id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(32) NOT NULL,
  `uo_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'UO Superior',
  `sigla` varchar(32) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Unidade Organizacional';

--
-- Extraindo dados da tabela `uo`
--

INSERT INTO `uo` (`id`, `hash`, `uo_id`, `sigla`, `nome`, `ativo`) VALUES
(1, '8aab83f3d3926152c9bff5d7046b6ef3', NULL, 'COTI', 'Coordenadoria de Tecnologia da InformaÃ§Ã£o', 1),
(2, '4e60233b5433f9b22f05014e79ddf822', 1, 'GEPQ', 'GerÃªncia de Planejamento e Qualidade em TI', 1),
(3, '68153029e86a6c7a051968c2344f3082', 1, 'GARS', 'GerÃªncia de AnÃ¡lise de Requisitos de Sistemas de InformaÃ§Ãµes', 1),
(4, '22017740448dbc576e0b6613d722cd64', 1, 'GIMP', 'GerÃªncia de ImplementaÃ§Ã£o de Sistemas de InformaÃ§Ãµes', 1),
(5, 'cfe47263c9fbff0dfc199ce78fc6d113', 1, 'GSCC', 'GerÃªncia de Projetos e ManutenÃ§Ã£o do Sistema de Conta-Corrente', 1),
(6, 'a90ca1ed20136aaeac5689190ac35525', 1, 'GSTI', 'GerÃªncia de ServiÃ§os de Suporte e Atendimento em TI', 1),
(7, 'f66026f3e55d3f39ecc81f9be4b1b81f', 1, 'GERS', 'GerÃªncia de Riscos e SeguranÃ§a da InformaÃ§Ã£o', 1),
(8, '0dd67a955f8bd867d622123fd9d225a6', 1, 'GSUP', 'GerÃªncia de Infraestrutura em TI', 1);

--
-- Acionadores `uo`
--
DELIMITER $$
CREATE TRIGGER `uo_bi` BEFORE INSERT ON `uo` FOR EACH ROW BEGIN
  SET NEW.hash = MD5(CONCAT(RAND(), NOW()));
  SET NEW.sigla = UPPER(NEW.sigla);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `uo_bu` BEFORE UPDATE ON `uo` FOR EACH ROW SET NEW.sigla = UPPER(NEW.sigla)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(32) NOT NULL,
  `login` varchar(32) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `uo_id` int(11) UNSIGNED NOT NULL,
  `administrador` tinyint(1) NOT NULL DEFAULT '0',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `redefinir_senha` char(32) DEFAULT NULL COMMENT 'Hash para redefinir senha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `hash`, `login`, `nome`, `senha`, `uo_id`, `administrador`, `ativo`, `redefinir_senha`) VALUES
(1, '177f7e369d27a697ab29a8456877325f', 'admin', 'Administrador', 'f6fdffe48c908deb0f4c3bd36c032e72', 1, 1, 1, NULL);

--
-- Acionadores `usuario`
--
DELIMITER $$
CREATE TRIGGER `usuario_bi` BEFORE INSERT ON `usuario` FOR EACH ROW BEGIN
  SET NEW.hash = MD5(CONCAT(RAND(), NOW()));
  SET NEW.login = LCASE(NEW.login);
  SET NEW.senha = MD5(CONCAT(NEW.login, NEW.senha));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `usuario_bu` BEFORE UPDATE ON `usuario` FOR EACH ROW BEGIN
  SET NEW.login = LCASE(NEW.login);
  IF NEW.senha <> OLD.senha THEN
    SET NEW.senha = MD5(CONCAT(NEW.login, NEW.senha));
  END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `avaliacao_hash` (`hash`) USING BTREE,
  ADD UNIQUE KEY `avaliacao_uk` (`usuario_id`,`trimestre`) USING BTREE,
  ADD KEY `avaliacao_usuario_fk` (`usuario_id`) USING BTREE,
  ADD KEY `avaliacao_revisor_fk` (`revisor_id`);

--
-- Indexes for table `avaliacao_competencia`
--
ALTER TABLE `avaliacao_competencia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `avaliacao_competencia_hash` (`hash`) USING BTREE,
  ADD UNIQUE KEY `avaliacao_competencia_uk` (`avaliacao_id`,`competencia_id`),
  ADD KEY `avaliacao_competencia_fk` (`competencia_id`),
  ADD KEY `avaliacao_avaliacao_fk` (`avaliacao_id`) USING BTREE;

--
-- Indexes for table `competencia`
--
ALTER TABLE `competencia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `competencia_hash` (`hash`) USING BTREE,
  ADD UNIQUE KEY `competencia_uk` (`uo_id`,`sigla`) USING BTREE,
  ADD KEY `competencia_uo_fk` (`uo_id`);

--
-- Indexes for table `uo`
--
ALTER TABLE `uo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uo_uk` (`sigla`) USING BTREE,
  ADD UNIQUE KEY `uo_hash` (`hash`) USING BTREE,
  ADD KEY `uo_uo_fk` (`uo_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_hash` (`hash`) USING BTREE,
  ADD UNIQUE KEY `usuario_uk` (`login`),
  ADD KEY `usuario_uo_fk` (`uo_id`),
  ADD KEY `redefinir_senha` (`redefinir_senha`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avaliacao_competencia`
--
ALTER TABLE `avaliacao_competencia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `uo`
--
ALTER TABLE `uo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `avaliacao_revisor_fk` FOREIGN KEY (`revisor_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `avaliacao_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Limitadores para a tabela `avaliacao_competencia`
--
ALTER TABLE `avaliacao_competencia`
  ADD CONSTRAINT `avaliacao_avaliacao_fk` FOREIGN KEY (`avaliacao_id`) REFERENCES `avaliacao` (`id`),
  ADD CONSTRAINT `avaliacao_competencia_fk` FOREIGN KEY (`competencia_id`) REFERENCES `competencia` (`id`);

--
-- Limitadores para a tabela `competencia`
--
ALTER TABLE `competencia`
  ADD CONSTRAINT `competencia_uo_fk` FOREIGN KEY (`uo_id`) REFERENCES `uo` (`id`);

--
-- Limitadores para a tabela `uo`
--
ALTER TABLE `uo`
  ADD CONSTRAINT `uo_uo_fk` FOREIGN KEY (`uo_id`) REFERENCES `uo` (`id`);

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_uo_fk` FOREIGN KEY (`uo_id`) REFERENCES `uo` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
