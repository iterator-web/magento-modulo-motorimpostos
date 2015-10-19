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
        $ivaAjustado = $this->calcularIvaAjustado($taxasImpostos['mva_original'], $taxasImpostos['aliquota_interestadual'], $taxasImpostos['aliquota_icms']);
        ($entradaItem->getBaseIcms() > 0 ? $bcIcmsProprio = $entradaItem->getBaseIcms() : $bcIcmsProprio = $entradaItem->getValorTotal());
        $icmsSt = $this->calcularSubstituicaoTributaria($bcIcmsProprio, $entradaItem->getValorIpi(), $ivaAjustado, $taxasImpostos['aliquota_icms'], $entradaItem->getValorIcms(), false, null);
        return $icmsSt['valor'];
    }
    
    public function entradaIcmsDa($entradaItem, $valoresAdicionaisItem) {
        $taxasImpostos = $this->getTaxasImpostos($entradaItem);
        $valorDiferencial = (double)$taxasImpostos['aliquota_icms'] - (double)$taxasImpostos['aliquota_interestadual'];
        $valorTotal = $entradaItem->getValorTotal() + $entradaItem->getValorIpi() + $valoresAdicionaisItem - $entradaItem->getDesconto();
        return ($valorTotal * $valorDiferencial) / 100;
    }
    
    public function getDadosNcm($cfop, $ncm, $origem) {
        $cfop = Mage::getModel('motorimpostos/cfop')->load($cfop, 'codigo');
        if($cfop->getCfopId()) {
            $impostoModel = Mage::getModel('motorimpostos/imposto')->getCollection()
                ->addFieldToFilter('cfop_id', $cfop->getCfopId())->addFieldToFilter('ncm_codigo', $ncm)->addFieldToFilter('icms_origem', $origem)->getFirstItem();
            if(!$impostoModel->getExTipi() || $impostoModel->getExTipi() == '000') {
                $impostoModel->setExTipi(null);
            }
        }
        return $impostoModel;
    }
    
    public function impostosAproximados($impostoModel, $vProd) {
        $aliquotaIbpt = $impostoModel->getAliquotaIbpt();
        $vTotTrib = ($vProd * $aliquotaIbpt) / 100;
        return $vTotTrib;
    }
    
    public function setImpostosProdutoNfe($nfeProduto, $impostoModel, $estadoEmitente, $estadoDestinatario) {
        $impostosRetorno = array();
        $impostosRetorno = $this->setProdutoIpi($impostoModel, $nfeProduto, $impostosRetorno);
        $impostosRetorno = $this->setProdutoIcms($impostoModel, $nfeProduto, $estadoEmitente, $estadoDestinatario, $impostosRetorno);
        $this->setProdutoPisCofins($impostoModel, $nfeProduto, 'pis');
        $this->setProdutoPisCofins($impostoModel, $nfeProduto, 'cofins');
        
        return $impostosRetorno;
    }
    
    private function calcularIvaAjustado($mvaOriginal, $aliquotaInterestadual, $aliquotaIcms) {
        $resultado = ((1+($mvaOriginal/100))*(1-($aliquotaInterestadual/100))/(1-($aliquotaIcms/100))-1);
        $resultPorcentagem = round($resultado*100, 2);
        return $resultPorcentagem;
    }
    
    private function calcularSubstituicaoTributaria($bcIcmsProprio, $valorIpi, $ivaAjustado, $aliquotaIcms, $valorIcmsProprio, $temReducao, $reducaoBcSt) {
        $icmsSt = array();
        $resultadoBcIcmsProprioMaisIpi = $bcIcmsProprio + $valorIpi;
        $resultadoIvaVezesProdutoMaisIpi = $resultadoBcIcmsProprioMaisIpi * ($ivaAjustado / 100);
        $resultadoBaseIcmsSt = $resultadoIvaVezesProdutoMaisIpi + $resultadoBcIcmsProprioMaisIpi;
        if($temReducao) {
            $resultadoBaseIcmsSt = $resultadoBaseIcmsSt * (100 - $reducaoBcSt) / 100;
        }
        $resultadoIcmsSt = (($resultadoBaseIcmsSt * ($aliquotaIcms / 100)) - $valorIcmsProprio);
        $icmsSt['bc'] = $resultadoBaseIcmsSt;
        $icmsSt['valor'] = $resultadoIcmsSt;
        
        return $icmsSt;
    }
    
    private function getTaxasImpostos($entradaItem) {
        $taxasImpostos = array();
        $ncm = $this->getProdutoNcm($entradaItem);
        $origem = $this->getProdutoOrigem($entradaItem);
        $estadoDestino = Mage::getStoreConfig('tax/empresa/region_id');
        $fornecedorUf = $this->getFornecedorUf($entradaItem->getFornecedorId());
        $cfop = Mage::getModel('motorimpostos/cfop')->load('2403', 'codigo');
        if(!$cfop->getCfopId()) {
            echo 'Para calcular corretamente os valores referentes a substituiчуo tributсria, '
                    . 'щ necessсrio criar o CFOP "2403 - Compra para comercializaчуo em operaчуo com mercadoria sujeita ao regime de substituiчуo tributсria".'; 
            exit();
        }
        $impostoModel = $this->getImposto($cfop->getCfopId(), $ncm, $origem);
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
    
    private function getProdutoOrigem($entradaItem) {
        $produtoId = preg_replace('/[^\d]/', '', $entradaItem->getProduto());
        $produto = Mage::getModel('catalog/product')->load($produtoId);
        $origem = substr($produto->getResource()->getAttribute('origem')->getFrontend()->getValue($produto),0,1);
        return $origem;
    }
    
    private function getFornecedorUf($fornecedorId) {
        $fornecedor = Mage::getModel('controleestoque/fornecedor')->load($fornecedorId);
        return $fornecedor->getRegionId();
    }
    
    private function getImposto($cfopCodigo, $ncm, $origem) {
        $impostoModel = Mage::getModel('motorimpostos/imposto')->getCollection()
                ->addFieldToFilter('cfop_id', $cfopCodigo)
                ->addFieldToFilter('ncm_codigo', $ncm)
                ->addFieldToFilter('icms_origem', $origem)
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
    
    private function setProdutoIpi($impostoModel, $nfeProduto, $impostosRetorno) {
        $regimeTributacao = Mage::getStoreConfig('tax/empresa/regimetributacao');
        $nfeProdutoId = $nfeProduto->getProdutoId();
        $ipiCst = $impostoModel->getIpiCst();
        $nfeProdutoImposto = Mage::getModel('nfe/nfeprodutoimposto');
        $nfeProdutoImposto->setProdutoId($nfeProdutoId);
        $nfeProdutoImposto->setTipoImposto('ipi');
        $nfeProdutoImposto->setCEnq('999');
        $nfeProdutoImposto->setCst($ipiCst);
        if($regimeTributacao != '2') {
            if($ipiCst == '0' || $ipiCst == '49' || $ipiCst == '50' || $ipiCst == '99') {
                // cst, vBC, pIPI, qUnid, vUnid, vIPI
                $ipiVBC = $this->getBaseCalculoIpi($nfeProduto);
                $pIPI = $impostoModel->getAliquotaIpi();
                $vIPI = ($ipiVBC * $pIPI) / 100;
                $nfeProdutoImposto->setVBc($ipiVBC);
                $nfeProdutoImposto->setPIpi($pIPI);
                $nfeProdutoImposto->setVIpi($vIPI);
            } /* else if($ipiCst == '1' || $ipiCst == '2' || $ipiCst == '3' || $ipiCst == '4' || $ipiCst == '5' || 
                    $ipiCst == '51' || $ipiCst == '52' || $ipiCst == '53' || $ipiCst == '54' || $ipiCst == '55') {
                // cst
            } */
        }
        $nfeProdutoImposto->save();
        
        $impostosRetorno['vIPI'] = $vIPI;
        
        return $impostosRetorno;
    }
    
    private function getBaseCalculoIpi($nfeProduto) {
        $vProd = $nfeProduto->getVProd();
        $nfeProduto->getVOutro() ? $vOutro = $nfeProduto->getVOutro() : $vOutro = 0;
        $nfeProduto->getVSeg() ? $vSeg = $nfeProduto->getVSeg() : $vSeg = 0;
        $vBC = $vProd + $vOutro + $vSeg;
        
        return $vBC;
    }
    
    private function setProdutoIcms($impostoModel, $nfeProduto, $estadoEmitente, $estadoDestinatario, $impostosRetorno) {
        $nfeProdutoId = $nfeProduto->getProdutoId();
        $cstCsosn = $impostoModel->getIcmsCst();
        $nfeProdutoImposto = Mage::getModel('nfe/nfeprodutoimposto');
        $nfeProdutoImposto->setProdutoId($nfeProdutoId);
        $nfeProdutoImposto->setTipoImposto('icms');
        $nfeProdutoImposto->setOrig($impostoModel->getIcmsOrigem());
        if($cstCsosn == '0') {
            // cst, modBC, vBC, pICMS, vICMS
            $nfeProdutoImposto->setCst($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $impostoUfEmitenteModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoEmitente);
            $vICMS = ($icmsVBC * $impostoUfEmitenteModel->getAliquotaIcms()) / 100;
            $nfeProdutoImposto->setModBc($impostoModel->getIcmsModBc());
            $nfeProdutoImposto->setVBc($icmsVBC);
            $nfeProdutoImposto->setPIcms($impostoUfEmitenteModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcms($vICMS);
        } else if($cstCsosn == '10') {
            // cst, modBC, vBC, pICMS, vICMS, modBCST, pMVAST, pRedBCST, vBCST, pICMSST, vICMSST. pRedBC, pBCOp, UFST
            $nfeProdutoImposto->setCst($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $impostoUfEmitenteModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoEmitente);
            $impostoUfDestinatarioModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoDestinatario);
            $vICMS = ($icmsVBC * $impostoUfEmitenteModel->getAliquotaIcms()) / 100;
            $ivaAjustado = $impostoUfEmitenteModel->getMvaOriginal();
            if($estadoEmitente != $estadoDestinatario) {
                $ivaAjustado = $this->calcularIvaAjustado($impostoUfEmitenteModel->getMvaOriginal(), $impostoModel->getAliquotaInterestadual(), $impostoUfDestinatarioModel->getAliquotaIcms());
            }
            $reducaoBcSt = null;
            $temReducaoSt = false;
            if($impostoModel->getReducaoBcSt() > 0) {
                $reducaoBcSt = $impostoModel->getReducaoBcSt();
                $temReducaoSt = true;
            }
            $icmsSt = $this->calcularSubstituicaoTributaria($icmsVBC, $produtoIpi, $ivaAjustado, $impostoUfDestinatarioModel->getAliquotaIcms(), $vICMS, $temReducaoSt, $reducaoBcSt);
            $nfeProdutoImposto->setModBc($impostoModel->getIcmsModBc());
            $nfeProdutoImposto->setVBc($icmsVBC);
            $nfeProdutoImposto->setPIcms($impostoUfEmitenteModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcms($vICMS);
            $nfeProdutoImposto->setModBcSt($impostoModel->getIcmsModBcSt());
            $nfeProdutoImposto->setPMvaSt($impostoUfEmitenteModel->getMvaOriginal());
            $nfeProdutoImposto->setPRedBcSt($reducaoBcSt);
            $nfeProdutoImposto->setVBcSt($icmsSt['bc']);
            $nfeProdutoImposto->setPIcmsSt($impostoUfDestinatarioModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcmsSt($icmsSt['valor']);
        } else if($cstCsosn == '20' || $cstCsosn == '51') {
            // cst, modBC, pRedBC, vBC, pICMS, vICMS
            $nfeProdutoImposto->setCst($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $reducaoBc = null;
            if($impostoModel->getReducaoBc() > 0) {
                $reducaoBc = $impostoModel->getReducaoBc();
                $icmsVBC = $icmsVBC * (1 - ($reducaoBc / 100));
            }
            $impostoUfEmitenteModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoEmitente);
            $vICMS = ($icmsVBC * $impostoUfEmitenteModel->getAliquotaIcms()) / 100;
            $nfeProdutoImposto->setModBc($impostoModel->getIcmsModBc());
            $nfeProdutoImposto->setPRedBc($reducaoBc);
            $nfeProdutoImposto->setVBc($icmsVBC);
            $nfeProdutoImposto->setPIcms($impostoUfEmitenteModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcms($vICMS);
            if($cstCsosn == '20') {
                // vICMSDeson, motDesICMS
                $nfeProdutoImposto->setCst($cstCsosn);
            } else if($cstCsosn == '51') {
                // vICMSOp, pDif, vICMSDif
                $nfeProdutoImposto->setCst($cstCsosn);
            }
        } else if($cstCsosn == '30') {
            // cst, modBCST, pMVAST, pRedBCST, vBCST, pICMSST, vICMSST, vICMSDeson, motDesICMS
            $nfeProdutoImposto->setCst($cstCsosn);
        } else if($cstCsosn == '40' || $cstCsosn == '41' || $cstCsosn == '50') {
            // cst, vICMSDeson, motDesICMS
            $nfeProdutoImposto->setCst($cstCsosn);
            if($cstCsosn == '41') {
                // vBCSTRet, vICMSSTRet, vBCSTDest, vICMSSTDest
                $nfeProdutoImposto->setCst($cstCsosn);
            }
        } else if($cstCsosn == '60') {
            // cst, vBCSTRet, vICMSSTRet
            $nfeProdutoImposto->setCst($cstCsosn);
        } else if($cstCsosn == '70' || $cstCsosn == '90') {
            // cst, modBC, pRedBC, vBC, pICMS, vICMS, modBCST, pMVAST, pRedBCST, vBCST, pICMSST, vICMSST, vICMSDeson, motDesICMS
            $nfeProdutoImposto->setCst($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $reducaoBc = null;
            if($impostoModel->getReducaoBc() > 0) {
                $reducaoBc = $impostoModel->getReducaoBc();
                $icmsVBC = $icmsVBC * (1 - ($reducaoBc / 100));
            }
            $impostoUfEmitenteModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoEmitente);
            $impostoUfDestinatarioModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoDestinatario);
            $vICMS = ($icmsVBC * $impostoUfEmitenteModel->getAliquotaIcms()) / 100;
            $ivaAjustado = $impostoUfEmitenteModel->getMvaOriginal();
            if($estadoEmitente != $estadoDestinatario) {
                $ivaAjustado = $this->calcularIvaAjustado($impostoUfEmitenteModel->getMvaOriginal(), $impostoModel->getAliquotaInterestadual(), $impostoUfDestinatarioModel->getAliquotaIcms());
            }
            $reducaoBcSt = null;
            $temReducaoSt = false;
            if($impostoModel->getReducaoBcSt() > 0) {
                $reducaoBcSt = $impostoModel->getReducaoBcSt();
                $temReducaoSt = true;
            }
            $icmsSt = $this->calcularSubstituicaoTributaria($icmsVBC, $produtoIpi, $ivaAjustado, $impostoUfDestinatarioModel->getAliquotaIcms(), $vICMS, $temReducaoSt, $reducaoBcSt);
            $nfeProdutoImposto->setModBc($impostoModel->getIcmsModBc());
            $nfeProdutoImposto->setPRedBc($reducaoBc);
            $nfeProdutoImposto->setVBc($icmsVBC);
            $nfeProdutoImposto->setPIcms($impostoUfEmitenteModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcms($vICMS);
            $nfeProdutoImposto->setModBcSt($impostoModel->getIcmsModBcSt());
            $nfeProdutoImposto->setPMvaSt($impostoUfEmitenteModel->getMvaOriginal());
            $nfeProdutoImposto->setPRedBcSt($reducaoBcSt);
            $nfeProdutoImposto->setVBcSt($icmsSt['bc']);
            $nfeProdutoImposto->setPIcmsSt($impostoUfDestinatarioModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcmsSt($icmsSt['valor']);
            if($cstCsosn == '90') {
                // pBCOp, UFST
                $nfeProdutoImposto->setCst($cstCsosn);
            }
        } else if($cstCsosn == '101') {
            // cso_sn, pCredSN, vCredICMSSN
            $nfeProdutoImposto->setCsoSn($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $vCredICMSSN = ($icmsVBC * $impostoModel->getAliquotaSimples()) / 100;
            $nfeProdutoImposto->setPCredSn($impostoModel->getAliquotaSimples());
            $nfeProdutoImposto->setVCredIcmsSn($vCredICMSSN);
        } else if($cstCsosn == '102' || $cstCsosn == '103' || $cstCsosn == '300' || $cstCsosn == '400') {
            // cso_sn
            $nfeProdutoImposto->setCsoSn($cstCsosn);
        } else if($cstCsosn == '201' || $cstCsosn == '202' || $cstCsosn == '203') {
            // cso_sn, modBCST, pMVAST, pRedBCST, vBCST, pICMSST, vICMSST
            $nfeProdutoImposto->setCsoSn($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $impostoUfEmitenteModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoEmitente);
            $impostoUfDestinatarioModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoDestinatario);
            $vICMS = ($icmsVBC * $impostoUfEmitenteModel->getAliquotaIcms()) / 100;
            $ivaAjustado = $impostoUfEmitenteModel->getMvaOriginal();
            if($estadoEmitente != $estadoDestinatario) {
                $ivaAjustado = $this->calcularIvaAjustado($impostoUfEmitenteModel->getMvaOriginal(), $impostoModel->getAliquotaInterestadual(), $impostoUfDestinatarioModel->getAliquotaIcms());
            }
            $reducaoBcSt = null;
            $temReducaoSt = false;
            if($impostoModel->getReducaoBcSt() > 0) {
                $reducaoBcSt = $impostoModel->getReducaoBcSt();
                $temReducaoSt = true;
            }
            $icmsSt = $this->calcularSubstituicaoTributaria($icmsVBC, $produtoIpi, $ivaAjustado, $impostoUfDestinatarioModel->getAliquotaIcms(), $vICMS, $temReducaoSt, $reducaoBcSt);
            $nfeProdutoImposto->setModBcSt($impostoModel->getIcmsModBcSt());
            $nfeProdutoImposto->setPMvaSt($impostoUfEmitenteModel->getMvaOriginal());
            $nfeProdutoImposto->setPRedBcSt($reducaoBcSt);
            $nfeProdutoImposto->setVBcSt($icmsSt['bc']);
            $nfeProdutoImposto->setPIcmsSt($impostoUfDestinatarioModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcmsSt($icmsSt['valor']);
            if($cstCsosn == '201') {
                // pCredSN, vCrediICMSSN
                $nfeProdutoImposto->setCsoSn($cstCsosn);
                $vCredICMSSN = ($icmsVBC * $impostoModel->getAliquotaSimples()) / 100;
                $nfeProdutoImposto->setPCredSn($impostoModel->getAliquotaSimples());
                $nfeProdutoImposto->setVCredIcmsSn($vCredICMSSN);
            }
        } else if($cstCsosn == '500') {
            // cso_sn, vBCSTRet, vICMSSTRet
            $nfeProdutoImposto->setCsoSn($cstCsosn);
        } else if($cstCsosn == '900') {
            // cso_sn, modBC, vBC, pRedBC, pICMS, vICMS, modBCST, pMVAST, pRedBCST, vBCST, pICMSST, vICMSST, pCredSN, vCrediICMSSN
            $nfeProdutoImposto->setCsoSn($cstCsosn);
            $produtoIpi = $this->getProdutoImposto($nfeProdutoId, 'ipi');
            $icmsVBC = $this->getBaseCalculoIcms($nfeProduto, $produtoIpi);
            $reducaoBc = null;
            if($impostoModel->getReducaoBc() > 0) {
                $reducaoBc = $impostoModel->getReducaoBc();
                $icmsVBC = $icmsVBC * (1 - ($reducaoBc / 100));
            }
            $impostoUfEmitenteModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoEmitente);
            $impostoUfDestinatarioModel = $this->getImpostoUf($impostoModel->getImpostoId(), $estadoDestinatario);
            $vCredICMSSN = ($icmsVBC * $impostoModel->getAliquotaSimples()) / 100;
            $vICMS = ($icmsVBC * $impostoUfEmitenteModel->getAliquotaIcms()) / 100;
            $ivaAjustado = $impostoUfEmitenteModel->getMvaOriginal();
            if($estadoEmitente != $estadoDestinatario) {
                $ivaAjustado = $this->calcularIvaAjustado($impostoUfEmitenteModel->getMvaOriginal(), $impostoModel->getAliquotaInterestadual(), $impostoUfDestinatarioModel->getAliquotaIcms());
            }
            $reducaoBcSt = null;
            $temReducaoSt = false;
            if($impostoModel->getReducaoBcSt() > 0) {
                $reducaoBcSt = $impostoModel->getReducaoBcSt();
                $temReducaoSt = true;
            }
            $icmsSt = $this->calcularSubstituicaoTributaria($icmsVBC, $produtoIpi, $ivaAjustado, $impostoUfDestinatarioModel->getAliquotaIcms(), $vICMS, $temReducaoSt, $reducaoBcSt);
            $nfeProdutoImposto->setModBc($impostoModel->getIcmsModBc());
            $nfeProdutoImposto->setPRedBc($reducaoBc);
            $nfeProdutoImposto->setVBc($icmsVBC);
            $nfeProdutoImposto->setPIcms($impostoUfEmitenteModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcms($vICMS);
            $nfeProdutoImposto->setModBcSt($impostoModel->getIcmsModBcSt());
            $nfeProdutoImposto->setPMvaSt($impostoUfEmitenteModel->getMvaOriginal());
            $nfeProdutoImposto->setPRedBcSt($reducaoBcSt);
            $nfeProdutoImposto->setVBcSt($icmsSt['bc']);
            $nfeProdutoImposto->setPIcmsSt($impostoUfDestinatarioModel->getAliquotaIcms());
            $nfeProdutoImposto->setVIcmsSt($icmsSt['valor']);
            $nfeProdutoImposto->setPCredSn($impostoModel->getAliquotaSimples());
            $nfeProdutoImposto->setVCredIcmsSn($vCredICMSSN);
        }
        $nfeProdutoImposto->save();
        
        $impostosRetorno['vBC'] = $nfeProdutoImposto->getVBc();
        $impostosRetorno['vICMS'] = $nfeProdutoImposto->getVIcms();
        $impostosRetorno['vBCST'] = $nfeProdutoImposto->getVBcSt();
        $impostosRetorno['vST'] = $nfeProdutoImposto->getVIcmsSt();
        $impostosRetorno['vCredICMSSN'] = $nfeProdutoImposto->getVCredIcmsSn();
        
        return $impostosRetorno;
    }
    
    private function getBaseCalculoIcms($nfeProduto, $produtoIpi) {
        $vProd = $nfeProduto->getVProd();
        $produtoIpi->getVIpi() ? $vIPI = $produtoIpi->getVIpi() : $vIPI = 0;
        $nfeProduto->getVFrete() ? $vFrete = $nfeProduto->getVFrete() : $vFrete = 0;
        $nfeProduto->getVSeg() ? $vSeg = $nfeProduto->getVSeg() : $vSeg = 0;
        $nfeProduto->getVDesc() ? $vDesc = $nfeProduto->getVDesc() : $vDesc = 0;
        $nfeProduto->getVOutro() ? $vOutro = $nfeProduto->getVOutro() : $vOutro = 0;
        $vBC = $vProd + $vFrete + $vSeg + $vOutro + $vIPI - $vDesc;
        
        return $vBC;
    }
    
    private function getProdutoImposto($produtoId, $tipo) {
        $produtoImposto = Mage::getModel('nfe/nfeprodutoimposto')->getCollection()
                ->addFieldToFilter('produto_id', $produtoId)
                ->addFieldToFilter('tipo_imposto', $tipo)
                ->getFirstItem();
        
        return $produtoImposto;
    }
    
    private function setProdutoPisCofins($impostoModel, $nfeProduto, $tipo) {
        $nfeProdutoId = $nfeProduto->getProdutoId();
        $pisCofinsCst = $impostoModel->getPisCofinsCst();
        $nfeProdutoImposto = Mage::getModel('nfe/nfeprodutoimposto');
        $nfeProdutoImposto->setProdutoId($nfeProdutoId);
        $nfeProdutoImposto->setTipoImposto($tipo);
        $nfeProdutoImposto->setCst($pisCofinsCst);
        if($pisCofinsCst == '1' || $pisCofinsCst == '2') {
            // cst, vBC, pPIS, vPIS, pCOFINS, vCOFINS
        } else if($pisCofinsCst == '3') {
            // cst, qBCProd, vAliqProd, vPIS, vCOFINS
        } /* else if($pisCofinsCst == '4' || $pisCofinsCst == '5' || $pisCofinsCst == '6' || $pisCofinsCst == '7' || $pisCofinsCst == '8' || $pisCofinsCst == '9') {
            // cst
        } */ 
        else if($pisCofinsCst == '49' || $pisCofinsCst == '50' || $pisCofinsCst == '51' || $pisCofinsCst == '52' || $pisCofinsCst == '53' || $pisCofinsCst == '54' ||
                $pisCofinsCst == '55' || $pisCofinsCst == '56' || $pisCofinsCst == '60' || $pisCofinsCst == '61' || $pisCofinsCst == '62' || $pisCofinsCst == '63' ||
                $pisCofinsCst == '64' || $pisCofinsCst == '65' || $pisCofinsCst == '66' || $pisCofinsCst == '67' || $pisCofinsCst == '70' || $pisCofinsCst == '71' ||
                $pisCofinsCst == '72' || $pisCofinsCst == '73' || $pisCofinsCst == '74' || $pisCofinsCst == '75' || $pisCofinsCst == '98' || $pisCofinsCst == '99') {
            // cst, vBC, pPIS, qBCProd, vAliqProd, vPIS, pCOFINS, vCOFINS
        }
        $nfeProdutoImposto->save();
    }
}

?>