<?php 
require_once ("../ajax/conexao.php");

if($_REQUEST['act']){
    if ( $_REQUEST['act'] == 'getLicitacoes'){
       return getLicitacoes();
    } else if ( $_REQUEST['act'] == 'getItensLicitacao'){
        return getItensLicitacao();
    } else {
        echo "404 Not Found";
    }
}

function getLicitacoes(){

    $con = bancoMysqli();
    $sql = "SELECT uasg, identificador, DATE_FORMAT(data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta, informacoes_gerais, objeto, situacao_aviso FROM licitacoes_cab limit 5000";
    $query = mysqli_query($con, $sql);
    if($query){
        if(mysqli_num_rows($query) > 0){
            
            $obj = [];
            while($licitacoes = mysqli_fetch_assoc($query)){

                $obj[] = [
                    $licitacoes['identificador'],  
                    $licitacoes['uasg'],
                    $licitacoes['data_entrega_proposta'],
                    $licitacoes['informacoes_gerais'],
                    $licitacoes['objeto'],
                    $licitacoes['situacao_aviso'],
                    '',
                ];
            }

            echo json_encode($obj);
        } else {
            require_once ("../api/request_licitacoes.php");
            // $_REQUEST['act'] = '&requestLicitacoes';
            requestLicGeraisComprasNet();
        }

    } else {
        echo 'Query Error';
    }
}

function getItensLicitacao(){

    $con = bancoMysqli();
    $identificador = $_REQUEST['identificador'];

    $sql = "SELECT 
        lic_uasg,
        lic_id,
        num_aviso,
        num_item_licitacao,
        cod_item_servico,
        cod_item_material,
        descricao_item,
        sustentavel,
        quantidade,
        unidade,
        cnpj_fornecedor,
        cpf_vencedor,
        beneficio,
        valor_estimado,
        decreto_7174,
        criterio_julgamento
        FROM
        licitacao_itens
        WHERE
        lic_id = '$identificador'
    ";
    $query = mysqli_query($con, $sql);
    if($query){
        if(mysqli_num_rows($query) > 0){

            $obj = [];
            while($itens = mysqli_fetch_assoc($query)){

                $obj[] = [
                    $itens['lic_id'],
                    $itens['lic_uasg'],  
                    $itens['num_aviso'],
                    $itens['descricao_item'],
                    $itens['cod_item_material'],
                    $itens['quantidade'],
                    $itens['unidade'],
                    $itens['valor_estimado'],
                ];
            }

            echo json_encode($obj);
        } else {
            echo 0;
        }
    }
    

}

?>
