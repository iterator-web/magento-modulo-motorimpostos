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

class Iterator_MotorImpostos_Model_ImpostoRN extends Mage_Core_Model_Abstract {
    
    public function salvar($imposto, $cfopId, $estadosArray, $update) {
        $impostos = Mage::getModel('motorimpostos/imposto')->getCollection()
                ->addFieldToFilter('cfop_id', array('eq' => $cfopId))
                ->addFieldToFilter('ncm_codigo', array('eq' => $imposto->getNcmCodigo()));
        if($impostos->getSize() > 0 && !$update) {
            return false;
        } else {
            $this->setBcIcms($imposto);
            $imposto->setCfopId($cfopId);
            $imposto->save();
            
            for($i=0; $i<count($estadosArray); $i++) {
                $impostoUf = Mage::getModel('motorimpostos/impostouf')->getCollection()
                        ->addFieldToFilter('imposto_id', array('eq' => $imposto->getId()))
                        ->addFieldToFilter('region_id', array('eq' => $estadosArray['option_'.$i]['region_id']))
                        ->getFirstItem();
                $impostoUf->setImpostoId($imposto->getImpostoId());
                $impostoUf->setRegionId($estadosArray['option_'.$i]['region_id']);
                $impostoUf->setAliquotaIcms($estadosArray['option_'.$i]['aliquota_icms']);
                $impostoUf->setMvaOriginal($estadosArray['option_'.$i]['mva_original']);
                $impostoUf->save();
            }
            return true;
        }
    }
    
    public function getCfop($cfopId) {
        $model = Mage::getModel('motorimpostos/cfop')->load($cfopId);
        if($model->getCfopId()) {
            return $model->getCfopId();
        } else {
            return $this->getPrimeiroCfopId();
        }
    }
    
    private function getPrimeiroCfopId() {
        $cfopFirstItem = Mage::getResourceModel('motorimpostos/cfop_collection')->getFirstItem();
        return $cfopFirstItem->getCfopId();
    }
    
    private function setBcIcms($imposto) {
        $cstCsosn = $imposto->getIcmsCst();
        if($cstCsosn === '0' || $cstCsosn === '20' || $cstCsosn === '51') {
            $imposto->setIcmsModBcSt(null);
        } else if($cstCsosn === '30' || $cstCsosn === '201' || $cstCsosn === '202' || $cstCsosn === '203') {
            $imposto->setIcmsModBc(null);
        } else if($cstCsosn === '40' || $cstCsosn === '41' || cstCsosn === '50' || $cstCsosn === '60' || $cstCsosn === '101' || $cstCsosn === '102' || $cstCsosn === '103' || $cstCsosn === '300' || $cstCsosn === '400') {
            $imposto->setIcmsModBc(null);
            $imposto->setIcmsModBcSt(null);
        } else if($cstCsosn === '10' || $cstCsosn === '70' || $cstCsosn === '90' || $cstCsosn === '900') {}
    }
}

?>