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