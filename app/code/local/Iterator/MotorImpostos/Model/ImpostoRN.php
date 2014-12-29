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