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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $model = Mage::registry('motorimpostos/imposto');

        $form = new Varien_Data_Form();
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informa��es Gerais'),
            'class'     => 'fieldset',
        ));
     
        if ($model->getId()) {
            $fieldset->addField('imposto_id', 'hidden', array(
                'name' => 'imposto_id',
            ));
            
            $fieldset->addField('created_time', 'hidden', array(
                'name' => 'created_time',
            ));
            
            $fieldset->addField('cfop_id', 'hidden', array(
                'name' => 'cfop_id',
            ));
        }  else {
            $fieldset->addField('cfop', 'multiselect', array(
                'label'     => 'CFOP',
                'title'     => 'CFOP',
                'name'      => 'cfop',
                'values'    => Mage::getResourceModel('motorimpostos/cfop_collection')->toOptionArrayMulti(),
                'required'  => true,
                'after_element_html' => utf8_encode('<p class="note">Criar taxas e impostos por NCM para o(s) CFOP selecionado(s).</p>')
            ));
        }
     
        $fieldset->addField('ncm_codigo', 'text', array(
            'name'      => 'ncm_codigo',
            'label'     => 'NCM/SH',
            'title'     => 'NCM/SH',
            'maxlength' => '8',
            'required'  => true,
            'class'     => 'validate-number',
        ));
        
        $fieldsetIcms = $form->addFieldset('base_fieldset_icms', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informa��es de ICMS')),
            'class'     => 'fieldset',
        ));
        
        $fieldsetIcms->addField('icms_origem', 'select', array(
            'name'      => 'icms_origem',
            'label'     => 'Origem',
            'title'     => 'Origem',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('0 - Nacional, exceto as indicadas nos c�digos 3, 4, 5 e 8')),
               array('value' => 1, 'label' => utf8_encode('1 - Estrangeira - Importa��o direta, exceto a indicada no c�digo 6')),
               array('value' => 2, 'label' => utf8_encode('2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no c�digo 7')),
               array('value' => 3, 'label' => utf8_encode('3 - Nacional, mercadoria ou bem com Conte�do de Importa��o superior a 40% e inferior ou igual a 70%')),
               array('value' => 4, 'label' => utf8_encode('4 - Nacional, cuja produ��o tenha sido feita em conformidade com os processos produtivos b�sicos de que tratam as legisla��es citadas nos Ajustes')),
               array('value' => 5, 'label' => utf8_encode('5 - Nacional, mercadoria ou bem com Conte�do de Importa��o inferior ou igual a 40%')),
               array('value' => 6, 'label' => utf8_encode('6 - Estrangeira - Importa��o direta, sem similar nacional, constante em lista da CAMEX e g�s natural')),
               array('value' => 7, 'label' => utf8_encode('7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e g�s natural')),
               array('value' => 8, 'label' => utf8_encode('8 - Nacional, mercadoria ou bem com Conte�do de Importa��o superior a 70%')),
            ),
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">Origem da mercadoria.</p>')
        ));
        
        $regimeTributacao = Mage::getStoreConfig('tax/empresa/regimetributacao');
        
        if($regimeTributacao == 2) {
            $fieldsetIcms->addField('icms_cst', 'select', array(
                'name'      => 'icms_cst',
                'label'     => 'CSOSN',
                'title'     => 'CSOSN',
                'values'    => array(
                   array('value' => 101, 'label' => utf8_encode('101 - Tributada pelo Simples Nacional com permiss�o de cr�dito')),
                   array('value' => 102, 'label' => utf8_encode('102 - Tributada pelo Simples Nacional sem permiss�o de cr�dito')),
                   array('value' => 103, 'label' => utf8_encode('103 - Isen��o do ICMS no Simples Nacional para faixa de receita bruta')),
                   array('value' => 201, 'label' => utf8_encode('201 - Tributada pelo Simples Nacional com permiss�o de cr�dito e com cobran�a do ICMS por Substitui��o Tribut�ria')),
                   array('value' => 202, 'label' => utf8_encode('202 - Tributada pelo Simples Nacional sem permiss�o de cr�dito e com cobran�a do ICMS por Substitui��o Tribut�ria')),
                   array('value' => 203, 'label' => utf8_encode('203 - Isen��o do ICMS nos Simples Nacional para faixa de receita bruta e com cobran�a do ICMS por Substitui��o Tribut�ria')),
                   array('value' => 300, 'label' => '300 - Imune'),
                   array('value' => 400, 'label' => utf8_encode('400 - N�o tributada pelo Simples Nacional')),
                   array('value' => 500, 'label' => utf8_encode('500 - ICMS cobrado anteriormente por substitui��o tribut�ria (substitu�do) ou por antecipa��o')),
                   array('value' => 900, 'label' => '900 - Outros'),
                ),
                'required'  => true,
                'after_element_html' => utf8_encode('<p class="note">C�digo de Situa��o da Opera��o.</p>')
            ));
        } else {
            $fieldsetIcms->addField('icms_cst', 'select', array(
                'name'      => 'icms_cst',
                'label'     => 'CST',
                'title'     => 'CST',
                'values'    => array(
                   array('value' => 0, 'label' => '00 - Tributada integralmente'),
                   array('value' => 10, 'label' => utf8_encode('10 - Tributada e com cobran�a do ICMS por substitui��o tribut�ria')),
                   array('value' => 20, 'label' => utf8_encode('20 - Com redu��o de base de c�lculo')),
                   array('value' => 30, 'label' => utf8_encode('30 - Isenta ou n�o tributada e com cobran�a do ICMS por substitui��o tribut�ria')),
                   array('value' => 40, 'label' => '40 - Isenta'),
                   array('value' => 41, 'label' => utf8_encode('41 - N�o tributada')),
                   array('value' => 50, 'label' => utf8_encode('50 - Suspens�o')),
                   array('value' => 51, 'label' => '51 - Diferimento'),
                   array('value' => 60, 'label' => utf8_encode('60 - ICMS cobrado anteriormente por substitui��o tribut�ria')),
                   array('value' => 70, 'label' => utf8_encode('70 - Com redu��o de base de c�lculo e cobran�a do ICMS por substitui��o tribut�ria')),
                   array('value' => 90, 'label' => '90 - Outros'),
                ),
                'required'  => false,
                'onload' => 'exibirModBcSt(this)',
                'after_element_html' => utf8_encode('<p class="note">C�digo de Situa��o Tribut�ria.</p>')
            ));
        }
        
        $fieldsetIcms->addField('icms_mod_bc', 'select', array(
            'name'      => 'icms_mod_bc',
            'label'     => 'BC do ICMS',
            'title'     => 'BC do ICMS',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('0 - Margem Valor Agregado (%)')),
               array('value' => 1, 'label' => utf8_encode('1 - Pauta (Valor)')),
               array('value' => 2, 'label' => utf8_encode('2 - Pre�o Tabelado M�x. (valor)')),
               array('value' => 3, 'label' => utf8_encode('3 - Valor da opera��o')),
            ),
            'disabled'  => true,
            'style'     => ("background:none"),
            'required'  => false,
            'after_element_html' => utf8_encode('<p class="note">Modalidade de determina��o da Base de C�lculo do ICMS.</p>')
        ));
        
        $fieldsetIcms->addField('icms_mod_bc_st', 'select', array(
            'name'      => 'icms_mod_bc_st',
            'label'     => 'BC do ICMS ST',
            'title'     => 'BC do ICMS ST',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('0 - Pre�o tabelado ou m�ximo sugerido')),
               array('value' => 1, 'label' => utf8_encode('1 - Lista Negativa (valor)')),
               array('value' => 2, 'label' => utf8_encode('2 - Lista Positiva (valor)')),
               array('value' => 3, 'label' => utf8_encode('3 - Lista Neutra (valor)')),
               array('value' => 4, 'label' => utf8_encode('4 - Margem Valor Agregado (%)')),
               array('value' => 5, 'label' => utf8_encode('5 - Pauta (valor)')),
            ),
            'disabled'  => true,
            'style'     => ("background:none"),
            'required'  => false,
            'after_element_html' => utf8_encode('<p class="note">Modalidade de determina��o da Base de C�lculo do ICMS ST.</p>')
        ));
        
        $fieldsetIcms->addField('reducao_bc', 'text', array(
            'name'      => 'reducao_bc',
            'label'     => utf8_encode('Percentual da Redu��o de BC'),
            'title'     => utf8_encode('Percentual da Redu��o de BC'),
            'required'  => false,
            'style'     => ("background:none"),
            'class'     => 'validate-zero-or-greater',
            'after_element_html' => utf8_encode('<p class="note">Percentual da Redu��o da Base de C�lculo do ICMS.</p>')
        ));
        
        $fieldsetIpi = $form->addFieldset('base_fieldset_ipi', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informa��es de IPI')),
            'class'     => 'fieldset',
        ));
        
        $fieldsetIpi->addField('ipi_cst', 'select', array(
            'name'      => 'ipi_cst',
            'label'     => 'CST',
            'title'     => 'CST',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('00 - Entrada com recupera��o de cr�dito')),
               array('value' => 1, 'label' => utf8_encode('01 - Entrada tributada com al�quota zero')),
               array('value' => 2, 'label' => utf8_encode('02 - Entrada isenta')),
               array('value' => 3, 'label' => utf8_encode('03 - Entrada n�o-tributada')),
               array('value' => 4, 'label' => utf8_encode('04 - Entrada imune')),
               array('value' => 5, 'label' => utf8_encode('05 - Entrada com suspens�o')),
               array('value' => 49, 'label' => utf8_encode('49 - Outras entradas')),
               array('value' => 50, 'label' => utf8_encode('50 - Sa�da tributada')),
               array('value' => 51, 'label' => utf8_encode('51 - Sa�da tributada com al�quota zero')),
               array('value' => 52, 'label' => utf8_encode('52 - Sa�da isenta')),
               array('value' => 53, 'label' => utf8_encode('53 - Sa�da n�o-tributada')),
               array('value' => 54, 'label' => utf8_encode('54 - Sa�da imune')),
               array('value' => 55, 'label' => utf8_encode('55 - Sa�da com suspens�o')),
               array('value' => 99, 'label' => utf8_encode('99 - Outras sa�das')),
            ),
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">C�digo da situa��o tribut�ria do IPI.</p>')
        ));
        
        $fieldsetPisCofins = $form->addFieldset('base_fieldset_piscofins', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informa��es de PIS/COFINS')),
            'class'     => 'fieldset',
        ));
        
        $fieldsetPisCofins->addField('pis_cofins_cst', 'select', array(
            'name'      => 'pis_cofins_cst',
            'label'     => 'CST',
            'title'     => 'CST',
            'values'    => array(
               array('value' => 1, 'label' => utf8_encode('01 - Opera��o Tribut�vel (base de c�lculo = valor da opera��o al�quota normal (cumulativo/n�o cumulativo))')),
               array('value' => 2, 'label' => utf8_encode('02 - Opera��o Tribut�vel (base de c�lculo = valor da opera��o (al�quota diferenciada))')),
               array('value' => 3, 'label' => utf8_encode('03 - Opera��o Tribut�vel (base de c�lculo = quantidade vendida x al�quota por unidade de produto)')),
               array('value' => 4, 'label' => utf8_encode('04 - Opera��o Tribut�vel (tributa��o monof�sica (al�quota zero))')),
               array('value' => 5, 'label' => utf8_encode('05 - Opera��o Tribut�vel (Substitui��o Tribut�ria)')),
               array('value' => 6, 'label' => utf8_encode('06 - Opera��o Tribut�vel (al�quota zero)')),
               array('value' => 7, 'label' => utf8_encode('07 - Opera��o Isenta da Contribui��o')),
               array('value' => 8, 'label' => utf8_encode('08 - Opera��o Sem Incid�ncia da Contribui��o')),
               array('value' => 9, 'label' => utf8_encode('09 - Opera��o com Suspens�o da Contribui��o')),
               array('value' => 49, 'label' => utf8_encode('49 - Outras Opera��es de Sa�da')),
               array('value' => 50, 'label' => utf8_encode('50 - Opera��o com Direito a Cr�dito - Vinculada Exclusivamente a Receita Tributada no Mercado Interno')),
               array('value' => 51, 'label' => utf8_encode('51 - Opera��o com Direito a Cr�dito - Vinculada Exclusivamente a Receita N�o Tributada no Mercado Interno')),
               array('value' => 52, 'label' => utf8_encode('52 - Opera��o com Direito a Cr�dito - Vinculada Exclusivamente a Receita de Exporta��o')),
               array('value' => 53, 'label' => utf8_encode('53 - Opera��o com Direito a Cr�dito - Vinculada a Receitas Tributadas e N�o-Tributadas no Mercado Interno')),
               array('value' => 54, 'label' => utf8_encode('54 - Opera��o com Direito a Cr�dito - Vinculada a Receitas Tributadas no Mercado Interno e de Exporta��o')),
               array('value' => 55, 'label' => utf8_encode('55 - Opera��o com Direito a Cr�dito - Vinculada a Receitas N�o-Tributadas no Mercado Interno e de Exporta��o')),
               array('value' => 56, 'label' => utf8_encode('56 - Opera��o com Direito a Cr�dito - Vinculada a Receitas Tributadas e N�o-Tributadas no Mercado Interno, e de Exporta��o')),
               array('value' => 60, 'label' => utf8_encode('60 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada Exclusivamente a Receita Tributada no Mercado Interno')),
               array('value' => 61, 'label' => utf8_encode('61 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada Exclusivamente a Receita N�o-Tributada no Mercado Interno')),
               array('value' => 62, 'label' => utf8_encode('62 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada Exclusivamente a Receita de Exporta��o')),
               array('value' => 63, 'label' => utf8_encode('63 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada a Receitas Tributadas e N�o-Tributadas no Mercado Interno')),
               array('value' => 64, 'label' => utf8_encode('64 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada a Receitas Tributadas no Mercado Interno e de Exporta��o')),
               array('value' => 65, 'label' => utf8_encode('65 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada a Receitas N�o-Tributadas no Mercado Interno e de Exporta��o')),
               array('value' => 66, 'label' => utf8_encode('66 - Cr�dito Presumido - Opera��o de Aquisi��o Vinculada a Receitas Tributadas e N�o-Tributadas no Mercado Interno, e de Exporta��o')),
               array('value' => 67, 'label' => utf8_encode('67 - Cr�dito Presumido - Outras Opera��es')),
               array('value' => 70, 'label' => utf8_encode('70 - Opera��o de Aquisi��o sem Direito a Cr�dito')),
               array('value' => 71, 'label' => utf8_encode('71 - Opera��o de Aquisi��o com Isen��o')),
               array('value' => 72, 'label' => utf8_encode('72 - Opera��o de Aquisi��o com Suspens�o')),
               array('value' => 73, 'label' => utf8_encode('73 - Opera��o de Aquisi��o a Al�quota Zero')),
               array('value' => 74, 'label' => utf8_encode('74 - Opera��o de Aquisi��o; sem Incid�ncia da Contribui��o')),
               array('value' => 75, 'label' => utf8_encode('75 - Opera��o de Aquisi��o por Substitui��o Tribut�ria')),
               array('value' => 98, 'label' => utf8_encode('98 - Outras Opera��es de Entrada')),
               array('value' => 99, 'label' => utf8_encode('99 - Outras Opera��es')),
            ),
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">C�digo de Situa��o Tribut�ria do PIS/COFINS.</p>')
        ));
        
        $fieldsetTipi = $form->addFieldset('base_fieldset_tipi', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informa��es da TIPI')),
            'class'     => 'fieldset',
        ));
        
        if($model->getExTipi() == '000') {
            $model->setExTipi('');
        }
        
        $fieldsetTipi->addField('ex_tipi', 'text', array(
            'name'      => 'ex_tipi',
            'label'     => utf8_encode('C�digo EX da TIPI'),
            'title'     => utf8_encode('C�digo EX da TIPI'),
            'maxlength' => '3',
            'required'  => false,
            'class'     => 'validate-number',
            'after_element_html' => utf8_encode('<p class="note">Preencher de acordo com o c�digo EX da TIPI.</p>')
        ));
     
        $form->setValues($model->getData());
        $this->setForm($form);
    }

}
?>