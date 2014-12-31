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

class Iterator_MotorImpostos_Block_Adminhtml_Cfopswitcher extends Mage_Adminhtml_Block_Widget
{
    public function __construct() {
        parent::__construct();
        $this->setTemplate('iterator_motorimpostos/cfop_switcher.phtml');
    }
    
    public function getListaCfop() {
        $cfopCollection = Mage::getResourceModel('motorimpostos/cfop_collection');
        return $cfopCollection;
    }
    
    public function getSelectedCfop() {
        return Mage::getSingleton('adminhtml/session')->getCfopId();
    }
    
    public function getSwitchUrl() {
        return Mage::helper('adminhtml')->getUrl('*/imposto/index/');
    }
}
?>
