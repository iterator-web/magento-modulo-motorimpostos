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

window.onload=carregarIcmsCst;

Event.observe(document, 'change', respondToChange);

function respondToChange(event) {
    var elementId = event.element().id;
    if(elementId === 'aliquota_ir' || elementId === 'aliquota_csll' || elementId === 'aliquota_interestadual' || elementId === 'aliquota_pis' || elementId === 'aliquota_cofins' || 
            elementId === 'aliquota_ipi' || elementId === 'aliquota_ii' || elementId === 'aliquota_iss' || elementId === 'aliquota_simples' || elementId === 'aliquota_ibpt' 
            || elementId === 'reducao_bc' || elementId === 'reducao_bc_st') {
        converterFloat(''+elementId+'', document.getElementById(''+elementId+'').value);
    }
    if(elementId === 'ncm_codigo') {
        converterInt(''+elementId+'', document.getElementById(''+elementId+'').value);
    }
    if(elementId === 'icms_cst') {
        exibirModBcSt(document.getElementById(''+elementId+'').value);
    }
    if(elementId.indexOf('aliquota_icms') > -1 || elementId.indexOf('mva_original') > -1) {
        converterFloat(''+elementId+'', document.getElementById(''+elementId+'').value);
    }
}

function carregarIcmsCst() {
    var valor = document.getElementById('icms_cst').value;
    exibirModBcSt(valor);
}

function exibirModBcSt(cstCsosn) {
    if(cstCsosn === '0' || cstCsosn === '51') {
        $("icms_mod_bc").disabled=false;
        $("icms_mod_bc").setStyle({backgroundColor:"#FFF"});
        $("icms_mod_bc_st").value = "";
        $("icms_mod_bc_st").disabled=true;
        $("icms_mod_bc_st").setStyle({background:"none"});
        $("reducao_bc").value = "";
        $("reducao_bc").disabled=true;
        $("reducao_bc").setStyle({background:"none"});
        $("reducao_bc_st").value = "";
        $("reducao_bc_st").disabled=true;
        $("reducao_bc_st").setStyle({background:"none"});
    } else if(cstCsosn === '10') {
        $("icms_mod_bc").disabled=false;
        $("icms_mod_bc").setStyle({backgroundColor:"#FFF"});
        $("icms_mod_bc_st").disabled=false;
        $("icms_mod_bc_st").setStyle({backgroundColor:"#FFF"});
        $("reducao_bc").value = "";
        $("reducao_bc").disabled=true;
        $("reducao_bc").setStyle({background:"none"});
        $("reducao_bc_st").disabled=false;
        $("reducao_bc_st").setStyle({backgroundColor:"#FFF"});
    } else if(cstCsosn === '20') {
        $("icms_mod_bc").disabled=false;
        $("icms_mod_bc").setStyle({backgroundColor:"#FFF"});
        $("icms_mod_bc_st").value = "";
        $("icms_mod_bc_st").disabled=true;
        $("icms_mod_bc_st").setStyle({background:"none"});
        $("reducao_bc").disabled=false;
        $("reducao_bc").setStyle({backgroundColor:"#FFF"});
        $("reducao_bc_st").value = "";
        $("reducao_bc_st").disabled=true;
        $("reducao_bc_st").setStyle({background:"none"});
    } else if(cstCsosn === '30' || cstCsosn === '201' || cstCsosn === '202' || cstCsosn === '203') {
        $("icms_mod_bc").value = "";
        $("icms_mod_bc").disabled=true;
        $("icms_mod_bc").setStyle({background:"none"});
        $("icms_mod_bc_st").disabled=false;
        $("icms_mod_bc_st").setStyle({backgroundColor:"#FFF"});
        $("reducao_bc").value = "";
        $("reducao_bc").disabled=true;
        $("reducao_bc").setStyle({background:"none"});
        $("reducao_bc_st").disabled=false;
        $("reducao_bc_st").setStyle({backgroundColor:"#FFF"});
    } else if(cstCsosn === '40' || cstCsosn === '41' || cstCsosn === '50' || cstCsosn === '60' || cstCsosn === '101' || cstCsosn === '102' || cstCsosn === '103' || cstCsosn === '300' || cstCsosn === '400' || cstCsosn === '500') {
        $("icms_mod_bc").value = "";
        $("icms_mod_bc").disabled=true;
        $("icms_mod_bc").setStyle({background:"none"});
        $("icms_mod_bc_st").value = "";
        $("icms_mod_bc_st").disabled=true;
        $("icms_mod_bc_st").setStyle({background:"none"});
        $("reducao_bc").value = "";
        $("reducao_bc").disabled=true;
        $("reducao_bc").setStyle({background:"none"});
        $("reducao_bc_st").value = "";
        $("reducao_bc_st").disabled=true;
        $("reducao_bc_st").setStyle({background:"none"});
    } else if(cstCsosn === '70' || cstCsosn === '90' || cstCsosn === '900') {
        $("icms_mod_bc").disabled=false;
        $("icms_mod_bc").setStyle({backgroundColor:"#FFF"});
        $("icms_mod_bc_st").disabled=false;
        $("icms_mod_bc_st").setStyle({backgroundColor:"#FFF"});
        $("reducao_bc").disabled=false;
        $("reducao_bc").setStyle({backgroundColor:"#FFF"});
        $("reducao_bc_st").disabled=false;
        $("reducao_bc_st").setStyle({backgroundColor:"#FFF"});
    }
}

function converterFloat(campo, valor) {
    var valorFormatado = parseFloat(valor.replace(',', '.')).toFixed(4);
    if(valorFormatado === 'NaN') {
        $(''+campo+'').value = '';
    } else {
        $(''+campo+'').value = valorFormatado;
    }
}

function converterInt(campo, valor) {
    var valorFormatado = parseInt(valor);
    if(valorFormatado === 'NaN') {
        $(''+campo+'').value = '';
    } else {
        $(''+campo+'').value = valorFormatado;
    }
}