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

class Iterator_MotorImpostos_Model_Adminhtml_Regimetributacao {

    /**
     * Formato vetor de vetores
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Lucro Real')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Lucro Presumido')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Simples Nacional')),
        );
    }

    /**
     * Formato chave-valor
     *
     * @return array
     */
    public function toArray() {
        return array(
            0 => Mage::helper('adminhtml')->__('Lucro Real'),
            1 => Mage::helper('adminhtml')->__('Lucro Presumido'),
            2 => Mage::helper('adminhtml')->__('Simples Nacional'),
        );
    }
}