<?php
 /**
 * Iterator Sistemas Web
 *
 * NOTAS SOBRE LICEN�A
 *
 * Este arquivo de c�digo-fonte est� em vig�ncia dentro dos termos da EULA.
 * Ao fazer uso deste arquivo em seu produto, automaticamente voc� est� 
 * concordando com os termos do Contrato de Licen�a de Usu�rio Final(EULA)
 * propostos pela empresa Iterator Sistemas Web.
 *
 * =================================================================
 *                   M�DULO DE MOTOR DE IMPOSTOS
 * =================================================================
 * Este produto foi desenvolvido para o Ecommerce Magento de forma a
 * automatizar c�lculos de impostos existentes em opera��es fiscais.
 * Atrav�s deste m�dulo a loja virtual do contratante do servi�o
 * passar� a conter diversos c�lculos envolvendo documentos fiscais
 * em opera��es de entradas e tamb�m de sa�das de forma autom�tica.
 * =================================================================
 *
 * @category   Iterator
 * @package    Iterator_MotorImpostos
 * @author     Ricardo Auler Barrientos <contato@iterator.com.br>
 * @copyright  Copyright (c) Iterator Sistemas Web - CNPJ: 19.717.703/0001-63
 * @license    O Produto � protegido por leis de direitos autorais, bem como outras leis de propriedade intelectual.
 */

$installer = $this;
$installer->startSetup();

try {
$installer->run("
  CREATE  TABLE IF NOT EXISTS `{$installer->getTable('motorimpostos/impostoufpadrao')}` (
    `padrao_uf_id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
    `region_id` INT(10) UNSIGNED NOT NULL,
    `aliquota_icms` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_interestadual` DOUBLE(7,4) UNSIGNED NULL,
    `aliquota_fcp` DOUBLE(7,4) UNSIGNED NULL,
  PRIMARY KEY (`padrao_uf_id`),
  INDEX `fk_motorimpostos_imposto_uf_directory_country_region2_idx` (`region_id` ASC),
  CONSTRAINT `fk_motorimpostos_imposto_uf_directory_country_region2`
    FOREIGN KEY (`region_id`)
    REFERENCES `{$installer->getTable('directory/country_region')}` (`region_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
  ENGINE = InnoDB DEFAULT CHARSET=utf8;
  
  ALTER TABLE {$this->getTable('motorimpostos/impostouf')} ADD COLUMN `aliquota_interestadual` DOUBLE(7,4) UNSIGNED NULL AFTER `aliquota_icms`;
  ALTER TABLE {$this->getTable('motorimpostos/impostouf')} ADD COLUMN `aliquota_fcp` DOUBLE(7,4) UNSIGNED NULL AFTER `aliquota_interestadual`;
      
  ALTER TABLE {$this->getTable('motorimpostos/imposto')} ADD COLUMN `contribuinte_codigo` TINYINT(2) UNSIGNED NULL AFTER `icms_origem`;
");
} catch (Exception $e) {
    
}

$installer->endSetup();

?>