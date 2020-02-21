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
    $sql = "SELECT uasg, identificador, DATE_FORMAT(data_entrega_proposta, '%d/%m/%Y') AS data_entrega_proposta, informacoes_gerais, objeto, situacao_aviso FROM licitacoes_cab order by data_entrega_proposta limit 5000";
    $query = mysqli_query($con, $sql);
    if($query){
        $offset = mysqli_num_rows($query);
        if( $offset > 0){

            $obj = [];
            while($licitacoes = mysqli_fetch_assoc($query)){

                $obj[] = [
                    $licitacoes['identificador'],
                    '',
                    $licitacoes['uasg'],
                    $licitacoes['data_entrega_proposta'],
                    $licitacoes['informacoes_gerais'],
                    $licitacoes['objeto'],
                    $licitacoes['situacao_aviso'],
                    "<button target title='Gerar PDF' style='float:left; margin-left: -20px;  min-width: 33px;' class='btn btn-sm btn-edit pdfLicitacao' id='".$licitacoes['identificador']."'><i class='far fa-file-pdf'></i></button>
                    <button title='Imprimir' style='float:right; margin-right: -4px;' class='btn btn-sm btn-edit printLicitacao' id='".$licitacoes['identificador']."'><i class='fa fa-print'></i></button>"
                ];
            }

            echo json_encode($obj);
        }
        //  else {
            // require_once ("../api/request_licitacoes.php");
            // $_REQUEST['act'] = '&requestLicitacoes';
            // requestLicGeraisComprasNet();
        // }

    } else {
        echo 'Query Error';
    }
}

function getItensLicitacao(){

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



