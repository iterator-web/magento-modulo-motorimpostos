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

class Iterator_MotorImpostos_Block_Adminhtml_Imposto_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $model = Mage::registry('motorimpostos/imposto');

        $form = new Varien_Data_Form();
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => utf8_encode('Informações Gerais'),
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
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informações de ICMS')),
            'class'     => 'fieldset',
        ));
        
        $fieldsetIcms->addField('icms_origem', 'select', array(
            'name'      => 'icms_origem',
            'label'     => 'Origem',
            'title'     => 'Origem',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8')),
               array('value' => 1, 'label' => utf8_encode('1 - Estrangeira - Importação direta, exceto a indicada no código 6')),
               array('value' => 2, 'label' => utf8_encode('2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7')),
               array('value' => 3, 'label' => utf8_encode('3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%')),
               array('value' => 4, 'label' => utf8_encode('4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes')),
               array('value' => 5, 'label' => utf8_encode('5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%')),
               array('value' => 6, 'label' => utf8_encode('6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural')),
               array('value' => 7, 'label' => utf8_encode('7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural')),
               array('value' => 8, 'label' => utf8_encode('8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%')),
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
                   array('value' => 101, 'label' => utf8_encode('101 - Tributada pelo Simples Nacional com permissão de crédito')),
                   array('value' => 102, 'label' => utf8_encode('102 - Tributada pelo Simples Nacional sem permissão de crédito')),
                   array('value' => 103, 'label' => utf8_encode('103 - Isenção do ICMS no Simples Nacional para faixa de receita bruta')),
                   array('value' => 201, 'label' => utf8_encode('201 - Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por Substituição Tributária')),
                   array('value' => 202, 'label' => utf8_encode('202 - Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por Substituição Tributária')),
                   array('value' => 203, 'label' => utf8_encode('203 - Isenção do ICMS nos Simples Nacional para faixa de receita bruta e com cobrança do ICMS por Substituição Tributária')),
                   array('value' => 300, 'label' => '300 - Imune'),
                   array('value' => 400, 'label' => utf8_encode('400 - Não tributada pelo Simples Nacional')),
                   array('value' => 500, 'label' => utf8_encode('500 - ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação')),
                   array('value' => 900, 'label' => '900 - Outros'),
                ),
                'required'  => true,
                'after_element_html' => utf8_encode('<p class="note">Código de Situação da Operação.</p>')
            ));
        } else {
            $fieldsetIcms->addField('icms_cst', 'select', array(
                'name'      => 'icms_cst',
                'label'     => 'CST',
                'title'     => 'CST',
                'values'    => array(
                   array('value' => 0, 'label' => '00 - Tributada integralmente'),
                   array('value' => 10, 'label' => utf8_encode('10 - Tributada e com cobrança do ICMS por substituição tributária')),
                   array('value' => 20, 'label' => utf8_encode('20 - Com redução de base de cálculo')),
                   array('value' => 30, 'label' => utf8_encode('30 - Isenta ou não tributada e com cobrança do ICMS por substituição tributária')),
                   array('value' => 40, 'label' => '40 - Isenta'),
                   array('value' => 41, 'label' => utf8_encode('41 - Não tributada')),
                   array('value' => 50, 'label' => utf8_encode('50 - Suspensão')),
                   array('value' => 51, 'label' => '51 - Diferimento'),
                   array('value' => 60, 'label' => utf8_encode('60 - ICMS cobrado anteriormente por substituição tributária')),
                   array('value' => 70, 'label' => utf8_encode('70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária')),
                   array('value' => 90, 'label' => '90 - Outros'),
                ),
                'required'  => false,
                'onload' => 'exibirModBcSt(this)',
                'after_element_html' => utf8_encode('<p class="note">Código de Situação Tributária.</p>')
            ));
        }
        
        $fieldsetIcms->addField('icms_mod_bc', 'select', array(
            'name'      => 'icms_mod_bc',
            'label'     => 'BC do ICMS',
            'title'     => 'BC do ICMS',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('0 - Margem Valor Agregado (%)')),
               array('value' => 1, 'label' => utf8_encode('1 - Pauta (Valor)')),
               array('value' => 2, 'label' => utf8_encode('2 - Preço Tabelado Máx. (valor)')),
               array('value' => 3, 'label' => utf8_encode('3 - Valor da operação')),
            ),
            'disabled'  => true,
            'style'     => ("background:none"),
            'required'  => false,
            'after_element_html' => utf8_encode('<p class="note">Modalidade de determinação da Base de Cálculo do ICMS.</p>')
        ));
        
        $fieldsetIcms->addField('icms_mod_bc_st', 'select', array(
            'name'      => 'icms_mod_bc_st',
            'label'     => 'BC do ICMS ST',
            'title'     => 'BC do ICMS ST',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('0 - Preço tabelado ou máximo sugerido')),
               array('value' => 1, 'label' => utf8_encode('1 - Lista Negativa (valor)')),
               array('value' => 2, 'label' => utf8_encode('2 - Lista Positiva (valor)')),
               array('value' => 3, 'label' => utf8_encode('3 - Lista Neutra (valor)')),
               array('value' => 4, 'label' => utf8_encode('4 - Margem Valor Agregado (%)')),
               array('value' => 5, 'label' => utf8_encode('5 - Pauta (valor)')),
            ),
            'disabled'  => true,
            'style'     => ("background:none"),
            'required'  => false,
            'after_element_html' => utf8_encode('<p class="note">Modalidade de determinação da Base de Cálculo do ICMS ST.</p>')
        ));
        
        $fieldsetIcms->addField('reducao_bc', 'text', array(
            'name'      => 'reducao_bc',
            'label'     => utf8_encode('Percentual da Redução de BC'),
            'title'     => utf8_encode('Percentual da Redução de BC'),
            'required'  => false,
            'style'     => ("background:none"),
            'class'     => 'validate-zero-or-greater',
            'after_element_html' => utf8_encode('<p class="note">Percentual da Redução da Base de Cálculo do ICMS.</p>')
        ));
        
        $fieldsetIpi = $form->addFieldset('base_fieldset_ipi', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informações de IPI')),
            'class'     => 'fieldset',
        ));
        
        $fieldsetIpi->addField('ipi_cst', 'select', array(
            'name'      => 'ipi_cst',
            'label'     => 'CST',
            'title'     => 'CST',
            'values'    => array(
               array('value' => 0, 'label' => utf8_encode('00 - Entrada com recuperação de crédito')),
               array('value' => 1, 'label' => utf8_encode('01 - Entrada tributada com alíquota zero')),
               array('value' => 2, 'label' => utf8_encode('02 - Entrada isenta')),
               array('value' => 3, 'label' => utf8_encode('03 - Entrada não-tributada')),
               array('value' => 4, 'label' => utf8_encode('04 - Entrada imune')),
               array('value' => 5, 'label' => utf8_encode('05 - Entrada com suspensão')),
               array('value' => 49, 'label' => utf8_encode('49 - Outras entradas')),
               array('value' => 50, 'label' => utf8_encode('50 - Saída tributada')),
               array('value' => 51, 'label' => utf8_encode('51 - Saída tributada com alíquota zero')),
               array('value' => 52, 'label' => utf8_encode('52 - Saída isenta')),
               array('value' => 53, 'label' => utf8_encode('53 - Saída não-tributada')),
               array('value' => 54, 'label' => utf8_encode('54 - Saída imune')),
               array('value' => 55, 'label' => utf8_encode('55 - Saída com suspensão')),
               array('value' => 99, 'label' => utf8_encode('99 - Outras saídas')),
            ),
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">Código da situação tributária do IPI.</p>')
        ));
        
        $fieldsetPisCofins = $form->addFieldset('base_fieldset_piscofins', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informações de PIS/COFINS')),
            'class'     => 'fieldset',
        ));
        
        $fieldsetPisCofins->addField('pis_cofins_cst', 'select', array(
            'name'      => 'pis_cofins_cst',
            'label'     => 'CST',
            'title'     => 'CST',
            'values'    => array(
               array('value' => 1, 'label' => utf8_encode('01 - Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo))')),
               array('value' => 2, 'label' => utf8_encode('02 - Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada))')),
               array('value' => 3, 'label' => utf8_encode('03 - Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)')),
               array('value' => 4, 'label' => utf8_encode('04 - Operação Tributável (tributação monofásica (alíquota zero))')),
               array('value' => 5, 'label' => utf8_encode('05 - Operação Tributável (Substituição Tributária)')),
               array('value' => 6, 'label' => utf8_encode('06 - Operação Tributável (alíquota zero)')),
               array('value' => 7, 'label' => utf8_encode('07 - Operação Isenta da Contribuição')),
               array('value' => 8, 'label' => utf8_encode('08 - Operação Sem Incidência da Contribuição')),
               array('value' => 9, 'label' => utf8_encode('09 - Operação com Suspensão da Contribuição')),
               array('value' => 49, 'label' => utf8_encode('49 - Outras Operações de Saída')),
               array('value' => 50, 'label' => utf8_encode('50 - Operação com Direito a Crédito - Vinculada Exclusivamente a Receita Tributada no Mercado Interno')),
               array('value' => 51, 'label' => utf8_encode('51 - Operação com Direito a Crédito - Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno')),
               array('value' => 52, 'label' => utf8_encode('52 - Operação com Direito a Crédito - Vinculada Exclusivamente a Receita de Exportação')),
               array('value' => 53, 'label' => utf8_encode('53 - Operação com Direito a Crédito - Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno')),
               array('value' => 54, 'label' => utf8_encode('54 - Operação com Direito a Crédito - Vinculada a Receitas Tributadas no Mercado Interno e de Exportação')),
               array('value' => 55, 'label' => utf8_encode('55 - Operação com Direito a Crédito - Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação')),
               array('value' => 56, 'label' => utf8_encode('56 - Operação com Direito a Crédito - Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação')),
               array('value' => 60, 'label' => utf8_encode('60 - Crédito Presumido - Operação de Aquisição Vinculada Exclusivamente a Receita Tributada no Mercado Interno')),
               array('value' => 61, 'label' => utf8_encode('61 - Crédito Presumido - Operação de Aquisição Vinculada Exclusivamente a Receita Não-Tributada no Mercado Interno')),
               array('value' => 62, 'label' => utf8_encode('62 - Crédito Presumido - Operação de Aquisição Vinculada Exclusivamente a Receita de Exportação')),
               array('value' => 63, 'label' => utf8_encode('63 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno')),
               array('value' => 64, 'label' => utf8_encode('64 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Tributadas no Mercado Interno e de Exportação')),
               array('value' => 65, 'label' => utf8_encode('65 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação')),
               array('value' => 66, 'label' => utf8_encode('66 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação')),
               array('value' => 67, 'label' => utf8_encode('67 - Crédito Presumido - Outras Operações')),
               array('value' => 70, 'label' => utf8_encode('70 - Operação de Aquisição sem Direito a Crédito')),
               array('value' => 71, 'label' => utf8_encode('71 - Operação de Aquisição com Isenção')),
               array('value' => 72, 'label' => utf8_encode('72 - Operação de Aquisição com Suspensão')),
               array('value' => 73, 'label' => utf8_encode('73 - Operação de Aquisição a Alíquota Zero')),
               array('value' => 74, 'label' => utf8_encode('74 - Operação de Aquisição; sem Incidência da Contribuição')),
               array('value' => 75, 'label' => utf8_encode('75 - Operação de Aquisição por Substituição Tributária')),
               array('value' => 98, 'label' => utf8_encode('98 - Outras Operações de Entrada')),
               array('value' => 99, 'label' => utf8_encode('99 - Outras Operações')),
            ),
            'required'  => true,
            'after_element_html' => utf8_encode('<p class="note">Código de Situação Tributária do PIS/COFINS.</p>')
        ));
        
        $fieldsetTipi = $form->addFieldset('base_fieldset_tipi', array(
            'legend'    => Mage::helper('motorimpostos')->__(utf8_encode('Informações da TIPI')),
            'class'     => 'fieldset',
        ));
        
        if($model->getExTipi() == '000') {
            $model->setExTipi('');
        }
        
        $fieldsetTipi->addField('ex_tipi', 'text', array(
            'name'      => 'ex_tipi',
            'label'     => utf8_encode('Código EX da TIPI'),
            'title'     => utf8_encode('Código EX da TIPI'),
            'maxlength' => '3',
            'required'  => false,
            'class'     => 'validate-number',
            'after_element_html' => utf8_encode('<p class="note">Preencher de acordo com o código EX da TIPI.</p>')
        ));
     
        $form->setValues($model->getData());
        $this->setForm($form);
    }

}
?>