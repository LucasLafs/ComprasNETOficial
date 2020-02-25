<?php

require_once ("./conexao.php");

if($_REQUEST['act']){
    if ($_REQUEST['act'] == 'buscaCotacoes'){
        return buscaCotacoes();
    } else if ($_REQUEST['act'] == 'buscaItensLicitacao') {
        return buscaItensLicitacao();
    } else if ($_REQUEST['act'] == 'buscaContFiltros') {
        return buscaContFiltros();
    } else {
        echo "404 NOT FOUND";
    }
}

function buscaContFiltros(){

    $con = bancoMysqli();

    $date = date('Y-m-d', strtotime('01/01/1998'));
    $sql = "SELECT * FROM licitacoes_cab WHERE data_entrega_proposta > '$date'";
    $query = mysqli_query($con, $sql);
    if($query){
        $data['vigentes'] = mysqli_num_rows($query); 
    }

    $sql = "SELECT DISTINCT ee.item_id as ids_enviados, pf.item_id as nao_enviados, pf.item_id as itens_relacionados FROM email_enviados AS ee RIGHT JOIN produtos_futura AS pf 
            ON pf.item_id = ee.item_id 
            ORDER BY pf.item_id ASC";
    
    $query = mysqli_query($con, $sql);

    $ids_nao_enviados = array();
    $itens_relacionados = array();

    if($query){

        while ($rows = mysqli_fetch_assoc($query)){
            if($rows['ids_enviados'] != null){
                $ids_enviados[] = $rows['ids_enviados'];
            } else {
                $ids_nao_enviados[] = $rows['nao_enviados'];
            }
            $itens_relacionados[] = $rows['itens_relacionados'];
        }

        $data['nao-enviados'] = $ids_nao_enviados ? count($ids_nao_enviados) : 0;
        $data['recomendadas'] = $itens_relacionados ? count($itens_relacionados) : 0;
    }

    $sql = "SELECT DISTINCT lc.identificador as identificador, 
            lc.uasg as uasg, 
            DATE_FORMAT(lc.data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta_ord, 
            lc.informacoes_gerais as informacoes_gerais, 
            lc.objeto as objeto, 
            lc.situacao_aviso as situacao_aviso
            FROM licitacoes_cab AS lc LEFT JOIN licitacao_orgao AS lo ON lc.uasg = lo.uasg
            WHERE lo.lic_estado='SP' or lo.lic_estado='DF' or lo.lic_estado='RJ' ";

    $query = mysqli_query($con, $sql);
    if($query){
        $total = mysqli_num_rows($query);
    }

        $data['estados'] = $total;

    echo json_encode($data);
    exit;
}

function buscaCotacoes(){
    
    $con = bancoMysqli();

    if($_REQUEST['filtro'] == 'vigentes'){
        $date = date('Y-m-d', strtotime('01/01/1999'));
        // $date = '01/01/2005';
        // mysqli_query('SET CHARACTER SET utf8');

        $sql = "SELECT uasg, 
        identificador, 
        DATE_FORMAT(data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta_ord, 
        informacoes_gerais, 
        objeto, 
        situacao_aviso 
        FROM licitacoes_cab WHERE data_entrega_proposta > '$date' 
        order by data_entrega_proposta_ord limit 5000";

        $query = mysqli_query($con, $sql);
        if($query){
            $total = mysqli_num_rows($query);

            if( $total > 0 ){
                $obj = [];
                //$ret = array();
                
                while($ret = mysqli_fetch_assoc($query)){

                    $obj[] = $ret;

                    // $obj = [
                    //     $ret['identificador'],
                    //     '',
                    //     $ret['uasg'],
                    //     $ret['data_entrega_proposta_ord'],
                    //     $ret['informacoes_gerais'],
                    //     $ret['objeto'],
                    //     $ret['situacao_aviso'],
                    //     "<button class='btn btn-sm btn-edit'><i class='fa fa-print'></i></button>",


                    // ];

                }

                $data[0] = $obj;
                $data[1] = $total;
                echo json_encode($data);

            //  echo json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
                
            }
        }
    } else if ($_REQUEST['filtro'] == 'nao-enviados') {
        $date = date('Y-m-d', strtotime('01/01/1999'));
        // $date = '01/01/2005';
        // mysqli_query('SET CHARACTER SET utf8');

        $sql = "SELECT DISTINCT ee.item_id as ids_enviados, pf.item_id as nao_enviados FROM email_enviados AS ee RIGHT JOIN produtos_futura AS pf 
                ON pf.item_id = ee.item_id 
                ORDER BY pf.item_id ASC";
        
        $query = mysqli_query($con, $sql);

        if($query){

            while ($rows = mysqli_fetch_assoc($query)){
                if($rows['ids_enviados'] != null){
                    $ids_enviados[] = $rows['ids_enviados'];
                } else {
                    $ids_nao_enviados[] = $rows['nao_enviados'];
                }
            }

        }

        $sql = "SELECT DISTINCT lic.identificador as identificador,
        lic.uasg as uasg, 
        DATE_FORMAT(lic.data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta_ord, 
        lic.informacoes_gerais as informacoes_gerais, 
        lic.objeto as objeto, 
        lic.situacao_aviso as situacao_aviso 
        FROM licitacoes_cab as lic 
        LEFT JOIN licitacao_itens ON lic.identificador = licitacao_itens.lic_id WHERE licitacao_itens.id IN (" . implode(',', $ids_nao_enviados) . ") ";  // order by data_entrega_proposta_ord limit 5000";

        $query = mysqli_query($con, $sql);
        if($query){
            $total = mysqli_num_rows($query);

            if( $total > 0 ){
                $obj = [];
                //$ret = array();
                
                while($ret = mysqli_fetch_assoc($query)){

                    $obj[] = $ret;

                    // $obj = [
                    //     $ret['identificador'],
                    //     '',
                    //     $ret['uasg'],
                    //     $ret['data_entrega_proposta_ord'],
                    //     $ret['informacoes_gerais'],
                    //     $ret['objeto'],
                    //     $ret['situacao_aviso'],
                    //     "<button class='btn btn-sm btn-edit'><i class='fa fa-print'></i></button>",


                    // ];

                }

                $data[0] = $obj;
                $data[1] = $total;
                echo json_encode($data);
            }
        }
    } else if ($_REQUEST['filtro'] == 'recomendadas'){
        $date = date('Y-m-d', strtotime('01/01/1999'));

        $sql = "SELECT DISTINCT pf.item_id as ids_relacionados FROM email_enviados AS ee RIGHT JOIN produtos_futura AS pf 
                ON pf.item_id = ee.item_id 
                ORDER BY pf.item_id ASC";
        
        $query = mysqli_query($con, $sql);

        if($query){

            while ($rows = mysqli_fetch_assoc($query)){
                $ids_relacionados[] = $rows['ids_relacionados'];
            }

        }

        $sql = "SELECT DISTINCT lic.identificador as identificador,
        lic.uasg as uasg, 
        DATE_FORMAT(lic.data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta_ord, 
        lic.informacoes_gerais as informacoes_gerais, 
        lic.objeto as objeto, 
        lic.situacao_aviso as situacao_aviso 
        FROM licitacoes_cab as lic 
        LEFT JOIN licitacao_itens ON lic.identificador = licitacao_itens.lic_id WHERE licitacao_itens.id IN (" . implode(',', $ids_relacionados) . ") ";  // order by data_entrega_proposta_ord limit 5000";

        $query = mysqli_query($con, $sql);
        if($query){
            $total = mysqli_num_rows($query);

            if( $total > 0 ){
                $obj = [];
                //$ret = array();
                
                while($ret = mysqli_fetch_assoc($query)){

                    $obj[] = $ret;

                }

                $data[0] = $obj;
                $data[1] = $total;
                echo json_encode($data);
            }
        }
    } else if ($_REQUEST['filtro'] == 'estados'){
        $date = date('Y-m-d', strtotime('01/01/1999'));
        // $date = '01/01/2005';
        // mysqli_query('SET CHARACTER SET utf8');

        $sql = "SELECT DISTINCT lc.identificador as identificador, 
        lc.uasg as uasg, 
        DATE_FORMAT(lc.data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta_ord, 
        lc.informacoes_gerais as informacoes_gerais, 
        lc.objeto as objeto, 
        lc.situacao_aviso as situacao_aviso
        FROM licitacoes_cab AS lc LEFT JOIN licitacao_orgao AS lo ON lc.uasg = lo.uasg WHERE lo.lic_estado='SP' or lo.lic_estado='DF' or lo.lic_estado='RJ' ";

        $query = mysqli_query($con, $sql);
        if($query){
            $total = mysqli_num_rows($query);

            if( $total > 0 ){
                $obj = [];
                //$ret = array();
                
                while($ret = mysqli_fetch_assoc($query)){

                    $obj[] = $ret;

                }

                $data[0] = $obj;
                $data[1] = $total;
                echo json_encode($data);
            }
        }
        
    }

    exit;
}

function buscaItensLicitacao(){

    $con = bancoMysqli();
    $identificador = $_REQUEST['identificador'];

    $sql = "SELECT 
            i.id,
            lic_id,
            num_aviso,
            i.num_item_licitacao,
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
            criterio_julgamento,
            pf.cod_jd_produto AS cod_produto,
            pf.desc_produto_jd as desc_produto,
            f.Nome AS fabricante
            FROM
            licitacao_itens AS i
            LEFT JOIN produtos_futura AS pf ON pf.item_id = i.id
            LEFT JOIN fabricantes AS f ON f.id = pf.fabricante_id
            WHERE
            lic_id = '$identificador'
        ";
        $query = mysqli_query($con, $sql);
        if($query){
            if(mysqli_num_rows($query) > 0){

            $obj = [];
            $arr = [];
            $emailEnviados = [];
            $datas_envio = [];

            while($itens = mysqli_fetch_assoc($query)){

                $arr[] = $itens;
            }

            $obj['itens'] = $arr;

            //Buscando itens disponiveis para enviar e-mail
            $sql = "SELECT item_id FROM produtos_futura";
            $query = mysqli_query($con, $sql);

            $itensComProduto = [];
            while ($itens = mysqli_fetch_assoc($query)) {
                $itensComProduto[] =  $itens['item_id'];
            }

            $obj['itensComProduto'] = $itensComProduto;


            //Buscando quais itens já foram enviado e-mail
            $sql = "SELECT item_id, DATE_FORMAT(data_envio, '%d/%m/%Y %H:%i:%s') AS data_envio FROM email_enviados WHERE email_enviado = 'Y'";
            $query = mysqli_query($con, $sql);

            $emailEnviados = [];

            if (mysqli_num_rows($query) > 0) {
                while ($itens = mysqli_fetch_assoc($query)) {
                $item_id = $itens['item_id'];
                $emailEnviados[] =  $item_id;
                $datas_envio[$item_id] = $itens['data_envio'];
                }
            }

            $obj['email_enviados'] = $emailEnviados;
            $obj['datas_envio'] = $datas_envio;

            echo json_encode($obj);
        } else {
            echo 0;
        }
    }

}