-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema comprasnet_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema comprasnet_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `comprasnet_db` DEFAULT CHARACTER SET utf8 ;
USE `comprasnet_db` ;

-- -----------------------------------------------------
-- Table `comprasnet_db`.`licitacoes_cab`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comprasnet_db`.`licitacoes_cab` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uasg` INT(11) NOT NULL,
  `identificador` BIGINT(20) NOT NULL,
  `cod_modalidade` INT(11) NULL DEFAULT NULL,
  `numero_aviso` INT(11) NULL DEFAULT NULL,
  `tipo_pregao` INT(11) NULL DEFAULT NULL,
  `numero_processo` VARCHAR(20) NULL DEFAULT NULL,
  `numero_itens` TINYINT(2) NULL DEFAULT NULL,
  `situacao_aviso` VARCHAR(45) NULL DEFAULT NULL,
  `objeto` VARCHAR(500) NULL DEFAULT NULL,
  `informacoes_gerais` VARCHAR(90) NULL DEFAULT NULL,
  `tipo_recurso` VARCHAR(45) NULL DEFAULT NULL,
  `nome_responsavel` VARCHAR(90) NULL DEFAULT NULL,
  `funcao_responsavel` VARCHAR(90) NULL DEFAULT NULL,
  `data_entrega_edital` DATETIME NULL DEFAULT NULL,
  `endereco_entrega_edital` VARCHAR(145) NULL DEFAULT NULL,
  `data_abertura_proposta` DATETIME NULL DEFAULT NULL,
  `data_entrega_proposta` DATETIME NULL DEFAULT NULL,
  `data_publicacao` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `identificador_UNIQUE` (`identificador` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 154939
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comprasnet_db`.`licitacao_itens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comprasnet_db`.`licitacao_itens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `lic_uasg` INT(11) NOT NULL,
  `lic_id` BIGINT(20) NOT NULL,
  `num_aviso` INT(11) NULL DEFAULT NULL,
  `num_item_licitacao` INT(11) NULL DEFAULT NULL,
  `cod_item_servico` INT(11) NULL DEFAULT NULL,
  `cod_item_material` INT(11) NULL DEFAULT NULL,
  `descricao_item` VARCHAR(500) NULL DEFAULT NULL,
  `sustentavel` TINYINT(4) NULL DEFAULT NULL,
  `quantidade` VARCHAR(45) NULL DEFAULT NULL,
  `unidade` VARCHAR(45) NULL DEFAULT NULL,
  `cnpj_fornecedor` VARCHAR(45) NULL DEFAULT NULL,
  `cpf_vencedor` VARCHAR(45) NULL DEFAULT NULL,
  `beneficio` VARCHAR(45) NULL DEFAULT NULL,
  `valor_estimado` VARCHAR(45) NULL DEFAULT NULL,
  `decreto_7174` TINYINT(4) NULL DEFAULT NULL,
  `criterio_julgamento` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_licitacao_itens_idx` (`lic_id` ASC),
  CONSTRAINT `fk_licitacao_itens_identificador`
    FOREIGN KEY (`lic_id`)
    REFERENCES `comprasnet_db`.`licitacoes_cab` (`identificador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comprasnet_db`.`materiais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comprasnet_db`.`materiais` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cod_material` INT(11) NULL DEFAULT NULL,
  `descricao` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_material_item`
    FOREIGN KEY (`id`)
    REFERENCES `comprasnet_db`.`licitacao_itens` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comprasnet_db`.`modalidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comprasnet_db`.`modalidades` (
  `id` INT(11) NOT NULL,
  `cod_modalidade` INT(11) NULL DEFAULT NULL,
  `descricao` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_material_licitacao`
    FOREIGN KEY (`id`)
    REFERENCES `comprasnet_db`.`licitacoes_cab` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comprasnet_db`.`produtos_cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comprasnet_db`.`produtos_cliente` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_portal` VARCHAR(90) NULL DEFAULT NULL,
  `num_item_licitacao` INT(11) NOT NULL,
  `cod_jd_produto` INT(11) NULL DEFAULT NULL,
  `desc_licitacao_portal` VARCHAR(90) NULL DEFAULT NULL,
  `quantidade_item_licitacao` INT(11) NULL DEFAULT NULL,
  `desc_licitacao_jd` VARCHAR(90) NULL DEFAULT NULL,
  `cod_produto_jd` INT(11) NULL DEFAULT NULL,
  `desc_produto_jd` VARCHAR(90) NULL DEFAULT NULL,
  `quantidade_embalagem_produto_jd` INT(11) NULL DEFAULT NULL,
  `cod_fabricante_jd` INT(11) NULL DEFAULT NULL,
  `nome_fabricante_` VARCHAR(90) NULL DEFAULT NULL,
  `estoque_disp_jd` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comprasnet_db`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comprasnet_db`.`usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(90) NOT NULL,
  `email` VARCHAR(90) NOT NULL,
  `senha` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
