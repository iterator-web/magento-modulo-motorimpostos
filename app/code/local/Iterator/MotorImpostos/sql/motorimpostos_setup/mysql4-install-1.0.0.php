<?php
 /**
 * Iterator Sistemas Web
 *
 * NOTAS SOBRE LICENÇA
 *
 * Este arquivo de código-fonte está em vigência dentro dos termos da EULA.
 * Ao fazer uso deste arquivo em seu produto, automaticamente você está 
 * concordando com os termos do Contrato de Licença de Usuário Final(EULA)
 * propostos pela empresa Iterator Sistemas Web.
 *
 * =================================================================
 *                   MÓDULO DE MOTOR DE IMPOSTOS
 * =================================================================
 * Este produto foi desenvolvido para o Ecommerce Magento de forma a
 * automatizar cálculos de impostos existentes em operações fiscais.
 * Através deste módulo a loja virtual do contratante do serviço
 * passará a conter diversos cálculos envolvendo documentos fiscais
 * em operações de entradas e também de saídas de forma automática.
 * =================================================================
 *
 * @category   Iterator
 * @package    Iterator_MotorImpostos
 * @author     Ricardo Auler Barrientos <contato@iterator.com.br>
 * @copyright  Copyright (c) Iterator Sistemas Web - CNPJ: 19.717.703/0001-63
 * @license    O Produto é protegido por leis de direitos autorais, bem como outras leis de propriedade intelectual.
 */

$installer = $this;
$installer->startSetup();
$installer->run("
   CREATE  TABLE IF NOT EXISTS `{$installer->getTable('motorimpostos/imposto')}` (
    `imposto_id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ncm_codigo` INT(8) UNSIGNED NOT NULL,
    `cfop_codigo` INT(4) UNSIGNED NULL,
    `cfop_st_codigo` INT(4) UNSIGNED NULL,
    `icms_cst` TINYINT(3) UNSIGNED NULL,
    `icms_cst_st` TINYINT(3) UNSIGNED NULL,
    `icms_origem` TINYINT(2) UNSIGNED NULL,
    `icms_mod_bc` TINYINT(2) UNSIGNED NULL,
    `icms_mod_bc_st` TINYINT(2) UNSIGNED NULL,
    `ipi_cst` TINYINT(3) UNSIGNED NULL,
    `pis_cofins_cst` TINYINT(3) UNSIGNED NULL,
    `aliquota_ir` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_csll` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_pis` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_cofins` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_ipi` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_ii` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_iss` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_interestadual` DOUBLE(7,4) UNSIGNED NULL,
    PRIMARY KEY (`imposto_id`),
    UNIQUE INDEX `ncm_codigo_UNIQUE` (`ncm_codigo` ASC))
  ENGINE = InnoDB CHARSET=utf8;
  
  CREATE  TABLE IF NOT EXISTS `{$installer->getTable('motorimpostos/impostouf')}` (
    `imposto_uf_id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
    `imposto_id` INT(12) UNSIGNED NOT NULL,
    `region_id` INT(10) UNSIGNED NOT NULL,
    `aliquota_icms` DOUBLE(7,4) UNSIGNED NULL,
    `mva_original` DOUBLE(7,4) UNSIGNED NULL,
    PRIMARY KEY (`imposto_uf_id`),
    INDEX `fk_motorimpostos_imposto_uf_motorimpostos_imposto_idx` (`imposto_id` ASC),
    INDEX `fk_motorimpostos_imposto_uf_directory_country_region1_idx` (`region_id` ASC),
    CONSTRAINT `fk_motorimpostos_imposto_uf_motorimpostos_imposto`
      FOREIGN KEY (`imposto_id`)
      REFERENCES `{$installer->getTable('motorimpostos/imposto')}` (`imposto_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    CONSTRAINT `fk_motorimpostos_imposto_uf_directory_country_region1`
      FOREIGN KEY (`region_id`)
      REFERENCES `{$installer->getTable('directory/country_region')}` (`region_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE)
  ENGINE = InnoDB CHARSET=utf8;
  ");

$installer->endSetup();

?>
