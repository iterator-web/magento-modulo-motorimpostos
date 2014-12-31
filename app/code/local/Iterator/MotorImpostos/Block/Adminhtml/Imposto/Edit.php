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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    public function __construct() {
     
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'motorimpostos';
        $this->_controller = 'adminhtml_imposto';
     
        $this->_updateButton('save', 'label', $this->__('Salvar Impostos'));
        $this->_updateButton('delete', 'label', $this->__('Excluir Impostos'));
        if( Mage::registry('motorimpostos/imposto') && Mage::registry('motorimpostos/imposto')->getId() ) {
            $this->_addButton('saveandcontinue', array(
                'label'     => Mage::helper('adminhtml')->__('Salvar e Continuar Editando'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save',
            ), -100);

            $this->_formScripts[] = "           
                function saveAndContinueEdit(){
                    editForm.submit($('edit_form').action+'back/edit/');
                }
            ";
        }
    }  
 
    public function getHeaderText() {
        if( Mage::registry('motorimpostos/imposto') && Mage::registry('motorimpostos/imposto')->getId() ) {
            return Mage::helper('motorimpostos')->__("Editar Impostos da NCM '%s'", $this->htmlEscape(Mage::registry('motorimpostos/imposto')->getNcmCodigo()));
        } else {
            return Mage::helper('motorimpostos')->__('Novos Impostos por NCM');
        }
    }  
}