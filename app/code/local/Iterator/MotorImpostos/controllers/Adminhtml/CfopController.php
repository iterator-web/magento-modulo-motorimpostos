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

class Iterator_MotorImpostos_Adminhtml_CfopController extends Mage_Adminhtml_Controller_Action {
    
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
            ->_setActiveMenu('sales/iterator_motorimpostos/cfop')
            ->_title($this->__('Sales'))->_title($this->__('CFOP'))
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Taxas e Impostos'), $this->__('CFOP'));
         
        return $this;
    }
    
    public function newAction() {
        $this->_forward('edit');
    }
    
    public function editAction() {
        $cfopId  = $this->getRequest()->getParam('id');
        $model = Mage::getModel('motorimpostos/cfop');
     
        if ($cfopId) {
            $model->load($cfopId);   
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__(utf8_encode('Este CFOP já não existe mais.')));
                $this->_redirect('*/*/');    
                return;
            }  
        }  
     
        $this->_title($model->getId() ? $model->getCodigo() : $this->__('Novo CFOP'));
     
        $data = Mage::getSingleton('adminhtml/session')->getCfopData(true);
        if (!empty($data)) {
            $model->setData($data);
        }  
     
        Mage::register('motorimpostos/cfop', $model);
     
        $this->_initAction()
            ->_addBreadcrumb($cfopId ? $this->__('Editar CFOP') : $this->__('Novo CFOP'), $cfopId ? $this->__('Editar CFOP') : $this->__('Novo CFOP'))
            ->_addContent($this->getLayout()->createBlock('motorimpostos/adminhtml_cfop_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }
    
    public function saveAction() {
        $postData = $this->getRequest()->getPost();
        if ($postData) {
            $model = Mage::getSingleton('motorimpostos/cfop');
            $model->setData($postData);
            
            if (isset($postData['created_time']) && $postData['created_time']) {
                $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
            } else {
                $model->setCreatedTime(Mage::getModel('core/date')->gmtDate());
                $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
            }
            
            try {
                $model->save();
                
                $cfopIdDuplicar = $postData['cfop_duplicar'];
                if($cfopIdDuplicar != '0') {
                    $cfopRN = Mage::getModel('motorimpostos/cfopRN');
                    $cfopRN->duplicar($model->getCfopId(), $cfopIdDuplicar);
                }
                
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('O CFOP foi salvo com sucesso.'));
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                if(strpos($e, 'cfop_codigo_UNIQUE') !== false) {
                    Mage::getSingleton('adminhtml/session')->addError($this->__(utf8_encode('O Código é um campo que deve ser único e o valor informado já está em uso em outro cadastro de CFOP.')));
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('Um erro ocorreu enquanto este CFOP era salvo.'));
                }
            }
            Mage::getSingleton('adminhtml/session')->setCfopData($postData);
            $this->_redirectReferer();
        }
    }
    
    public function deleteAction() {
        $cfopId = (int) $this->getRequest()->getParam('id');
        if ($cfopId) {
            try {
                $cfopModel = Mage::getModel('motorimpostos/cfop');
                $cfopModel->load($cfopId)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__(utf8_encode('CFOP excluído com sucesso.')));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('cfop_id' => $this->getRequest()->getParam('cfop_id')));
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction() {
        $cfopIds = $this->getRequest()->getParam('cfop_id');
        if (!is_array($cfopIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('motorimpostos')->__('Por favor selecione o(s) CFOP.'));
        } else {
            try {
                $cfopModel = Mage::getModel('motorimpostos/cfop');
                foreach ($cfopIds as $cfopId) {
                    $cfopModel->load($cfopId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('motorimpostos')->__(
                                utf8_encode('Total de %d CFOP foram excluídos.'), count($cfopIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
    
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/motorimpostos');
    }
    
    public function exportCsvAction() {
        $fileName   = 'cfop.csv';
        $content    = $this->getLayout()->createBlock('motorimpostos/adminhtml_cfop_grid')
            ->getCsv();
 
        $this->_sendUploadResponse($fileName, $content);
    }
 
    public function exportXmlAction() {
        $fileName   = 'cfop.xml';
        $content    = $this->getLayout()->createBlock('motorimpostos/adminhtml_cfop_grid')
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
