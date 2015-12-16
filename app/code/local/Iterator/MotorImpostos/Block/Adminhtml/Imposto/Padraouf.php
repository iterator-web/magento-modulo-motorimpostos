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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Padraouf extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    public function __construct() {
     
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'motorimpostos';
        $this->_controller = 'adminhtml_imposto_padraouf';
     
        $this->_updateButton('save', 'label', $this->__(utf8_encode('Salvar Impostos Por UF')));
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Salvar e Continuar Editando'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "           
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/editPadraoUf/');
            }
        ";
    }  
 
    public function getHeaderText() {
        return Mage::helper('motorimpostos')->__(utf8_encode('Gerenciar Al�quota - Simples Nacional'));
    }  
}