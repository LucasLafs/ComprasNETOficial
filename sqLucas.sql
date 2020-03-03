-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20-Fev-2020 às 05:14
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE SCHEMA IF NOT EXISTS `comprasnet_db` DEFAULT CHARACTER SET utf8;
USE `comprasnet_db` ;

--
-- Banco de dados: `comprasnet_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `conn_smtp`
--

CREATE TABLE `conn_smtp` (
  `id` int(11) NOT NULL,
  `remetente` varchar(45) NOT NULL,
  `server_smtp` varchar(45) DEFAULT NULL,
  `port_smtp` int(11) DEFAULT NULL,
  `usuario` varchar(45) DEFAULT NULL,
  `senha` varchar(45) NOT NULL,
  `cop_email` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `conn_smtp`
--

INSERT INTO `conn_smtp` (`id`, `remetente`, `server_smtp`, `port_smtp`, `usuario`, `senha`, `cop_email`) VALUES
(1, 'tanaiiir@gmail.com', 'smtp.gmail.com', 465, 'tanaiiir', 'arywivvkudppcwtl', 'l.francelino@outlook.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `email_enviados`
--

CREATE TABLE `email_enviados` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `fabricante_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `email_enviado` enum('Y','N') NOT NULL DEFAULT 'N',
  `resposta` varchar(85) DEFAULT NULL,
  `data_envio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fabricantes`
--

CREATE TABLE `fabricantes` (
  `id` int(11) NOT NULL,
  `nome` varchar(85) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `descricao` varchar(85) DEFAULT NULL,
  `cod_fabricante` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `licitacao_itens`
--

CREATE TABLE `licitacao_itens` (
  `id` int(11) NOT NULL,
  `lic_uasg` int(11) NOT NULL,
  `lic_id` bigint(20) NOT NULL,
  `num_aviso` int(11) DEFAULT NULL,
  `num_item_licitacao` int(11) DEFAULT NULL,
  `cod_item_servico` int(11) DEFAULT NULL,
  `cod_item_material` int(11) DEFAULT NULL,
  `descricao_item` varchar(9999) DEFAULT NULL,
  `sustentavel` int(11) DEFAULT NULL,
  `quantidade` varchar(45) DEFAULT NULL,
  `unidade` varchar(45) DEFAULT NULL,
  `cnpj_fornecedor` varchar(45) DEFAULT NULL,
  `cpf_vencedor` varchar(45) DEFAULT NULL,
  `beneficio` varchar(90) DEFAULT NULL,
  `valor_estimado` varchar(45) DEFAULT NULL,
  `decreto_7174` int(11) DEFAULT NULL,
  `criterio_julgamento` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `licitacao_orgao`
--

CREATE TABLE `licitacao_orgao` (
  `id` int(11) NOT NULL,
  `uasg` int(11) DEFAULT NULL,
  `lic_orgao` varchar(90) DEFAULT NULL,
  `lic_estado` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `licitacoes_cab`
--

CREATE TABLE `licitacoes_cab` (
  `id` int(11) NOT NULL,
  `uasg` int(11) NOT NULL,
  `identificador` bigint(20) NOT NULL,
  `cod_modalidade` int(11) DEFAULT NULL,
  `numero_aviso` int(11) DEFAULT NULL,
  `tipo_pregao` varchar(45) DEFAULT NULL,
  `numero_processo` varchar(20) DEFAULT NULL,
  `numero_itens` int(11) DEFAULT NULL,
  `situacao_aviso` varchar(45) DEFAULT NULL,
  `objeto` varchar(999) DEFAULT NULL,
  `informacoes_gerais` varchar(999) DEFAULT NULL,
  `tipo_recurso` varchar(45) DEFAULT NULL,
  `nome_responsavel` varchar(180) DEFAULT NULL,
  `funcao_responsavel` varchar(180) DEFAULT NULL,
  `data_entrega_edital` datetime DEFAULT NULL,
  `endereco_entrega_edital` varchar(180) DEFAULT NULL,
  `data_abertura_proposta` datetime DEFAULT NULL,
  `data_entrega_proposta` datetime DEFAULT NULL,
  `data_publicacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `materiais`
--

CREATE TABLE `materiais` (
  `id` int(11) NOT NULL,
  `cod_material` int(11) DEFAULT NULL,
  `descricao` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modalidades`
--

CREATE TABLE `modalidades` (
  `id` int(11) NOT NULL,
  `cod_modalidade` int(11) DEFAULT NULL,
  `descricao` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `comprasnet_db`.`timeout`
-- -----------------------------------------------------
CREATE TABLE `timeout` (
  `minutos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_futura`
--

CREATE TABLE `produtos_futura` (
  `id` int(11) NOT NULL,
  `fabricante_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `nome_portal` varchar(999) DEFAULT NULL,
  `num_item_licitacao` int(11) NOT NULL,
  `cod_jd_produto` int(11) DEFAULT NULL,
  `desc_licitacao_portal` varchar(9999) DEFAULT NULL,
  `quantidade_item_licitacao` int(11) DEFAULT NULL,
  `desc_licitacao_jd` text DEFAULT NULL,
  `cod_produto_jd` int(11) DEFAULT NULL,
  `quantidade_embalagem_produto_jd` int(11) DEFAULT NULL,
  `desc_produto_jd` varchar(9999) DEFAULT NULL,
  `cod_fabricante_jd` int(11) DEFAULT NULL,
  `nome_fabricante` varchar(120) DEFAULT NULL,
  `estoque_disp_jd` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `smtp_body`
--

CREATE TABLE `smtp_body` (
  `id` int(11) NOT NULL,
  `smtp_assunto` varchar(90) DEFAULT NULL,
  `smtp_corpo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `smtp_body`
--

INSERT INTO `smtp_body` (`id`, `smtp_assunto`, `smtp_corpo`) VALUES
(1, 'ORGÃO', '<p>Segue em anexo o Edital referente ao pregão em assunto.</p>\n                    <p>Abaixo o item e a estimativa de preço.</p><br>\n\n<tabela>\n\n <p>Solicitamos autorização para participar do referido Certame.</p>\n                       <p>Grata,</p>\n                       \n                        <small>--</small><br>\n                        <small>Elda Silva</small><br>\n                        <small>Auxiliar de Licitação</small><br>\n                        <small>Futura Distribuidora de Medicamentos e Produtos de Saúde</small><br>\n                        <small>Tel: 21-3311-5186</small>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(90) NOT NULL,
  `email` varchar(90) NOT NULL,
  `senha` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'tana', 'tana@tana.com', 'e10adc3949ba59abbe56e057f20f883e');
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(2, 'Lucas Silva', 'lucas@comprasnet.com', '78038d39420009e34aa4e3e63060ddeb');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `conn_smtp`
--
ALTER TABLE `conn_smtp`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `email_enviados`
--
ALTER TABLE `email_enviados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produto_idx` (`item_id`),
  ADD KEY `fk_fabricante_idx` (`fabricante_id`);

--
-- Índices para tabela `fabricantes`
--
ALTER TABLE `fabricantes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `licitacao_itens`
--
ALTER TABLE `licitacao_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_licitacao_itens_identificador_idx` (`lic_id`);

--
-- Índices para tabela `licitacao_orgao`
--
ALTER TABLE `licitacao_orgao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `licitacoes_cab`
--
ALTER TABLE `licitacoes_cab`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificador_UNIQUE` (`identificador`);

--
-- Índices para tabela `materiais`
--
ALTER TABLE `materiais`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `modalidades`
--
ALTER TABLE `modalidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `timeout`
--
ALTER TABLE `timeout`
  ADD PRIMARY KEY (`minutos`);

--
-- Índices para tabela `produtos_futura`
--
ALTER TABLE `produtos_futura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produtos_futura_licitacao_itens_idx` (`item_id`),
  ADD KEY `fk_produtos_futura_fabricantes_idx` (`fabricante_id`);

--
-- Índices para tabela `smtp_body`
--
ALTER TABLE `smtp_body`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `conn_smtp`
--
ALTER TABLE `conn_smtp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `email_enviados`
--
ALTER TABLE `email_enviados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fabricantes`
--
ALTER TABLE `fabricantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `licitacao_itens`
--
ALTER TABLE `licitacao_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `licitacao_orgao`
--
ALTER TABLE `licitacao_orgao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `licitacoes_cab`
--
ALTER TABLE `licitacoes_cab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `materiais`
--
ALTER TABLE `materiais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_futura`
--
ALTER TABLE `produtos_futura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `smtp_body`
--
ALTER TABLE `smtp_body`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `email_enviados`
--
ALTER TABLE `email_enviados`
  ADD CONSTRAINT `fk_fabricante` FOREIGN KEY (`fabricante_id`) REFERENCES `fabricantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto` FOREIGN KEY (`item_id`) REFERENCES `licitacao_itens` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `licitacao_itens`
--
ALTER TABLE `licitacao_itens`
  ADD CONSTRAINT `fk_licitacao_itens_identificador` FOREIGN KEY (`lic_id`) REFERENCES `licitacoes_cab` (`identificador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `materiais`
--
ALTER TABLE `materiais`
  ADD CONSTRAINT `fk_material_item` FOREIGN KEY (`id`) REFERENCES `licitacao_itens` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `modalidades`
--
ALTER TABLE `modalidades`
  ADD CONSTRAINT `fk_material_licitacao` FOREIGN KEY (`id`) REFERENCES `licitacoes_cab` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produtos_futura`
--
ALTER TABLE `produtos_futura`
  ADD CONSTRAINT `fk_produtos_futura_fabricantes` FOREIGN KEY (`fabricante_id`) REFERENCES `fabricantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produtos_futura_licitacao_itens` FOREIGN KEY (`item_id`) REFERENCES `licitacao_itens` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
