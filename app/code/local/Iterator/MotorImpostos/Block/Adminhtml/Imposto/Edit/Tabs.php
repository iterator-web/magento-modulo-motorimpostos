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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('imposto_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('motorimpostos')->__(utf8_encode('Informa��es dos Impostos do NCM')));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('motorimpostos')->__('Geral'),
            'title' => Mage::helper('motorimpostos')->__('Geral'),
            'content' => $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_form')->toHtml(),
        ));

        $this->addTab('aliquota_section', array(
            'label' => Mage::helper('motorimpostos')->__(utf8_encode('Al�quotas')),
            'title' => Mage::helper('motorimpostos')->__(utf8_encode('Al�quotas')),
            'content' => $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_aliquota')->toHtml(),
        ));
        
        $this->addTab('interestadual_section', array(
            'label' => Mage::helper('motorimpostos')->__('Interestaduais'),
            'title' => Mage::helper('motorimpostos')->__('Interestaduais'),
            'content' => $this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tab_interestadual')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
