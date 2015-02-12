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

class Iterator_MotorImpostos_Adminhtml_ImpostoController extends Mage_Adminhtml_Controller_Action {
    
    public function _construct() {
        $helper = Mage::helper('motorimpostos');
        if(!method_exists($helper, 'checkValidationMotorImpostos')) {
            exit();
        } else {
            if(md5($_SERVER['HTTP_HOST'].'i_|*12*|_T'.$_SERVER['SERVER_ADDR']) != $helper->checkValidationMotorImpostos()) {
                exit();
            }
        }
    }
    
    public function indexAction() {
        $this->_initAction()->renderLayout();
    }
    
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('sales/iterator_motorimpostos/imposto')
            ->_title($this->__('Sales'))->_title($this->__('Taxas e Impostos'))
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Taxas e Impostos'), $this->__('Motor de Impostos'));
        
        $impostoRN = Mage::getModel('motorimpostos/impostoRN');
        $cfopId = $impostoRN->getCfop($this->getRequest()->getParam('cfop'));
        Mage::getSingleton('adminhtml/session')->setCfopId($cfopId);
         
        return $this;
    }
    
    public function newAction() {
        $this->_forward('edit');
    }
    
    public function editAction() {
        $impostoId  = $this->getRequest()->getParam('id');
        $model = Mage::getModel('motorimpostos/imposto');
     
        if ($impostoId) {
            $model->load($impostoId);   
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__(utf8_encode('Esta Taxa/Imposto j� n�o existe mais.')));
                $this->_redirect('*/*/');    
                return;
            }  
        } else {
            $modelSimples = Mage::getModel('motorimpostos/imposto')->getCollection()->getFirstItem();
            if($modelSimples->getImpostoId()) {
                $model->setAliquotaSimples($modelSimples->getAliquotaSimples());
            }
        }
     
        $this->_title($model->getId() ? $model->getCodigo() : $this->__('Novos Impostos por NCM'));
     
        $data = Mage::getSingleton('adminhtml/session')->getCfopData(true);
        if (!empty($data)) {
            $model->setData($data);
        }  
     
        Mage::register('motorimpostos/imposto', $model);
        
        $this->_initAction()
            ->_addBreadcrumb($impostoId ? $this->__('Editar Imposto por NCM') : $this->__('Novo Imposto por NCM'), $impostoId ? $this->__('Editar Imposto por NCM') : $this->__('Novo Imposto por NCM'))
            ->_addContent($this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit')->setData('action', $this->getUrl('*/*/save')))
            ->_addLeft($this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_edit_tabs'))
            ->renderLayout();
    }
    
    public function saveAction() {
        $postData = $this->getRequest()->getPost();
        if ($postData) {
            $impostoRN = Mage::getModel('motorimpostos/impostoRN');
            $model = Mage::getSingleton('motorimpostos/imposto');
            $model->setData($postData);
            
            if (isset($postData['created_time']) && $postData['created_time']) {
                $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
            } else {
                $model->setCreatedTime(Mage::getModel('core/date')->gmtDate());
                $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
            }
            
            try {
                $estadosArray = $postData['imposto_uf']['value'];
                if($postData['cfop']) {
                    $ncmExiste = null;
                    $cfopIds = $postData['cfop'];
                    foreach($cfopIds as $cfopId) {
                        $model->setImpostoId(null);
                        $retorno = $impostoRN->salvar($model, $cfopId, $estadosArray, false);
                        if(!$retorno) {
                            $ncmExiste .= ' - '.$cfopId;
                        }
                    }
                    if($ncmExiste) {
                        Mage::getSingleton('adminhtml/session')->addSuccess($this->__(utf8_encode('Os impostos por NCM foram salvos com sucesso. Por�m o NCM j� existe e n�o pode ser salvo novamente nos CFOP '.$ncmExiste)));
                    } else {
                        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Os impostos por NCM foram salvos com sucesso para os CFOP selecionados.'));
                    }
                    $this->_redirect('*/*/');
                    return;
                } else {
                    $retorno = $impostoRN->salvar($model, $model->getCfopId(), $estadosArray, true);
                    if($retorno) {
                        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Os impostos do NCM '.$model->getNcmCodigo().' foram salvos com sucesso.'));
                    }
                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $model->getId()));
                        return;
                    }
                    $this->_redirect('*/*/index', array('cfop' => $model->getCfopId()));
                    return;
                }
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Um erro ocorreu enquanto este Imposto era salvo.'));
            }
            Mage::getSingleton('adminhtml/session')->setCfopData($postData);
            $this->_redirectReferer();
        }
    }
    
    public function editAliquotaAction() {
        $model = Mage::getModel('motorimpostos/imposto')->getCollection()->getFirstItem();
        if(!$model->getImpostoId()) {
            Mage::getSingleton('adminhtml/session')->addError($this->__(utf8_encode('� necess�rio cadastrar o(s) NCM antes de definir a Al�quota do Simples Nacional.')));
            $this->_redirect('*/*/');    
            return;
        }
     
        $this->_title($model->getId() ? $model->getCodigo() : $this->__(utf8_encode('Gerenciar Al�quota - Simples Nacional')));
     
        $data = Mage::getSingleton('adminhtml/session')->getAliquotaSn(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        Mage::register('aliquota_sn', $model);
        
        $this->_initAction()
            ->_addBreadcrumb($this->__(utf8_encode('Gerenciar Al�quota - Simples Nacional')))
            ->_addContent($this->getLayout()->createBlock('motorimpostos/adminhtml_imposto_simples')->setData('action', $this->getUrl('*/*/saveAliquota')))
            ->renderLayout();
    }
    
    public function saveAliquotaAction() {
        $postData = $this->getRequest()->getPost();
        if ($postData) {
            $aliquotaSimples = $postData['aliquota_simples'];
            try {
                $impostoCollection = Mage::getModel('motorimpostos/imposto')->getCollection();
                foreach($impostoCollection as $imposto) {
                    $imposto->setAliquotaSimples($aliquotaSimples);
                    $imposto->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__(utf8_encode('Al�quota aplic�vel de c�lculo do cr�dito ajustada com sucesso.')));
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/editAliquota');
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Um erro ocorreu enquanto este Imposto era salvo.'));
            }
            Mage::getSingleton('adminhtml/session')->setAliquotaSn($postData);
            $this->_redirectReferer();
        }
    }
    
    public function deleteAction() {
        $impostoId = (int) $this->getRequest()->getParam('id');
        if ($impostoId) {
            try {
                $impostoModel = Mage::getModel('motorimpostos/imposto');
                $impostoModel->load($impostoId)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__(utf8_encode('Taxas e Impostos do NCM '.$impostoModel->getNcmCodigo().' exclu�dos com sucesso.')));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('imposto_id' => $this->getRequest()->getParam('imposto_id')));
            }
        }
        $this->_redirect('*/*/index', array('cfop' => $impostoModel->getCfopId()));
    }
    
    public function massDeleteAction() {
        $impostoIds = $this->getRequest()->getParam('imposto_id');
        if (!is_array($impostoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('motorimpostos')->__('Por favor selecione o(s) Imposto(s).'));
        } else {
            try {
                $impostoModel = Mage::getModel('motorimpostos/imposto');
                foreach ($impostoIds as $impostoId) {
                    $impostoModel->load($impostoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('motorimpostos')->__(
                                utf8_encode('Total de %d Taxas e Impostos do NCM '.$impostoModel->getNcmCodigo().' foram exclu�dos.'), count($impostoIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index', array('cfop' => $impostoModel->getCfopId()));
    }
    
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/motorimpostos');
    }
    
    public function exportCsvAction() {
        $fileName   = 'ncm_impostos.csv';
        $content    = $this->getLayout()->createBlock('motorimpostos/adminhtml_motorimpostos_grid')
            ->getCsv();
 
        $this->_sendUploadResponse($fileName, $content);
    }
 
    public function exportXmlAction() {
        $fileName   = 'ncm_impostos.xml';
        $content    = $this->getLayout()->createBlock('motorimpostos/adminhtml_motorimpostos_grid')
            ->getXml();
 
        $this->_sendUploadResponse($fileName, $content);
    }
    
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}

?>
