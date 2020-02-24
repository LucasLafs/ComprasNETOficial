<?php

require_once ("./conexao.php");

if($_REQUEST['act']){
    if ($_REQUEST['act'] == 'buscaCotacoes'){
        return buscaCotacoes();
    } else if ($_REQUEST['act'] == 'buscaItensLicitacao') {
        return buscaItensLicitacao();
    }else {
        echo "404 NOT FOUND";
    }
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
            }
        }
            //  echo json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    } else if ($_REQUEST['filtro'] == 'recomendadas'){
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
    } else if ($_REQUEST['fitro'] == 'estados'){
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