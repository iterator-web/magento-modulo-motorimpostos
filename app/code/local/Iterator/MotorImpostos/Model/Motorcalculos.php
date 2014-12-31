<?php
 /**
 * Iterator Sistemas Web
 *
 * NOTAS SOBRE LICENЧA
 *
 * Este arquivo de cѓdigo-fonte estс em vigъncia dentro dos termos da EULA.
 * Ao fazer uso deste arquivo em seu produto, automaticamente vocъ estс 
 * concordando com os termos do Contrato de Licenчa de Usuсrio Final(EULA)
 * propostos pela empresa Iterator Sistemas Web.
 *
 * =================================================================
 *                   MгDULO DE MOTOR DE IMPOSTOS
 * =================================================================
 * Este produto foi desenvolvido para o Ecommerce Magento de forma a
 * automatizar cсlculos de impostos existentes em operaчѕes fiscais.
 * Atravщs deste mѓdulo a loja virtual do contratante do serviчo
 * passarс a conter diversos cсlculos envolvendo documentos fiscais
 * em operaчѕes de entradas e tambщm de saэdas de forma automсtica.
 * =================================================================
 *
 * @category   Iterator
 * @package    Iterator_MotorImpostos
 * @author     Ricardo Auler Barrientos <contato@iterator.com.br>
 * @copyright  Copyright (c) Iterator Sistemas Web - CNPJ: 19.717.703/0001-63
 * @license    O Produto щ protegido por leis de direitos autorais, bem como outras leis de propriedade intelectual.
 */

class Iterator_MotorImpostos_Model_Motorcalculos extends Mage_Core_Model_Abstract {
    
    public function entradaIcmsSt($entradaItem) {
        $taxasImpostos = $this->getTaxasImpostos($entradaItem);
        $ivaAjustado = $this->calcularIvaAjustado($taxasImpostos);
        
        $resultadoProdMaisIpi = $entradaItem->getValorTotal() + $entradaItem->getValorIpi();
        $resultadoIvaVezesProdutoMaisIpi = $resultadoProdMaisIpi * ($ivaAjustado / 100);
        $resultadoBaseIcmsSt = $resultadoIvaVezesProdutoMaisIpi + $resultadoProdMaisIpi;
        $resultadoIcmsSt = (($resultadoBaseIcmsSt * ($taxasImpostos['aliquota_icms'] / 100)) - $entradaItem->getValorIcms());

        return $resultadoIcmsSt;
    }
    
    private function calcularIvaAjustado($taxasImpostos) {
        $resultado = ((1+($taxasImpostos['mva_original']/100))*(1-($taxasImpostos['aliquota_interestadual']/100))/(1-($taxasImpostos['aliquota_icms']/100))-1);
        $resultPorcentagem = round($resultado*100, 2);
        return $resultPorcentagem;
    }
    
    private function getTaxasImpostos($entradaItem) {
        $taxasImpostos = array();
        $ncm = $this->getProdutoNcm($entradaItem);
        $estadoDestino = Mage::getStoreConfig('tax/empresa/region_id');
        $fornecedorUf = $this->getFornecedorUf($entradaItem->getFornecedorId());
        $cfop = Mage::getModel('motorimpostos/cfop')->load('2403', 'codigo');
        if(!$cfop->getCfopId()) {
            echo 'Para calcular corretamente os valores referentes a substituiчуo tributсria, '
                    . 'щ necessсrio criar o CFOP "2403 - Compra para comercializaчуo em operaчуo com mercadoria sujeita ao regime de substituiчуo tributсria".'; 
            exit();
        }
        $impostoModel = $this->getImposto($cfop->getCfopId(), $ncm);
        $impostoUfFornecedorModel = $this->getImpostoUf($impostoModel->getId(), $fornecedorUf);
        $impostoUfEmpresaModel = $this->getImpostoUf($impostoModel->getId(), $estadoDestino);
        
        $taxasImpostos['aliquota_interestadual'] = $impostoModel->getAliquotaInterestadual();
        $taxasImpostos['mva_original'] = $impostoUfFornecedorModel->getMvaOriginal();
        $taxasImpostos['aliquota_icms'] = $impostoUfEmpresaModel->getAliquotaIcms();
        
        return $taxasImpostos;
    }
    
    private function getProdutoNcm($entradaItem) {
        $produtoId = preg_replace('/[^\d]/', '', $entradaItem->getProduto());
        $produto = Mage::getModel('catalog/product')->load($produtoId);
        $ncm = $produto->getResource()->getAttribute('ncm')->getFrontend()->getValue($produto);
        return $ncm;
    }
    
    private function getFornecedorUf($fornecedorId) {
        $fornecedor = Mage::getModel('controleestoque/fornecedor')->load($fornecedorId);
        return $fornecedor->getRegionId();
    }
    
    private function getImposto($cfopCodigo, $ncm) {
        $impostoModel = Mage::getModel('motorimpostos/imposto')->getCollection()
                ->addFieldToFilter('cfop_id', $cfopCodigo)
                ->addFieldToFilter('ncm_codigo', $ncm)
                ->getFirstItem();
        
        return $impostoModel;
    }
    
    private function getImpostoUf($impostoId, $regionId) {
        $impostoUfModel = Mage::getModel('motorimpostos/impostouf')->getCollection()
                ->addFieldToFilter('imposto_id', $impostoId)
                ->addFieldToFilter('region_id', $regionId)
                ->getFirstItem();
        
        return $impostoUfModel;
    }
}

?>