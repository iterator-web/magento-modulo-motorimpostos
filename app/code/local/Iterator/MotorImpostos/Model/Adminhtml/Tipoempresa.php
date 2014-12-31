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

class Iterator_MotorImpostos_Model_Adminhtml_Tipoempresa {

    /**
     * Formato vetor de vetores
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Sociedade Limitada')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Empresário Individual'))),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Empresa Individual de Responsabilidade Limitada (EIRELI)')),
            array('value' => 3, 'label'=>Mage::helper('adminhtml')->__('Microempreendedor Individual')),
            array('value' => 4, 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Sociedade Empresária'))),
            array('value' => 5, 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Sociedade Anônima'))),
            array('value' => 6, 'label'=>Mage::helper('adminhtml')->__('Sociedade em Comandita Simples')),
            array('value' => 7, 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Sociedade em Comandita por Ações'))),
            array('value' => 8, 'label'=>Mage::helper('adminhtml')->__('Sociedade Simples')),
            array('value' => 9, 'label'=>Mage::helper('adminhtml')->__('Sem Fins Lucrativos')),
            array('value' => 10, 'label'=>Mage::helper('adminhtml')->__('Sociedade em Nome Coletivo')),
        );
    }

    /**
     * Formato chave-valor
     *
     * @return array
     */
    public function toArray() {
        return array(
            0 => Mage::helper('adminhtml')->__('Sociedade Limitada'),
            1 => Mage::helper('adminhtml')->__(utf8_encode('Empresário Individual')),
            2 => Mage::helper('adminhtml')->__('Empresa Individual de Responsabilidade Limitada (EIRELI)'),
            3 => Mage::helper('adminhtml')->__('Microempreendedor Individual'),
            4 => Mage::helper('adminhtml')->__(utf8_encode('Sociedade Empresária')),
            5 => Mage::helper('adminhtml')->__(utf8_encode('Sociedade Anônima')),
            6 => Mage::helper('adminhtml')->__('Sociedade em Comandita Simples'),
            7 => Mage::helper('adminhtml')->__(utf8_encode('Sociedade em Comandita por Ações')),
            8 => Mage::helper('adminhtml')->__('Sociedade Simples'),
            9 => Mage::helper('adminhtml')->__('Sem Fins Lucrativos'),
            10 => Mage::helper('adminhtml')->__('Sociedade em Nome Coletivo'),
        );
    }
}