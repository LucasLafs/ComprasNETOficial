<?php 

set_time_limit(0);
require_once ("../ajax/conexao.php");
require_once ("./request_produtos.php");

if($_REQUEST['act']){
    if ($_REQUEST['act'] == 'requestLicitacoes'){
        requestLicGeraisComprasNet();
    } else if ($_REQUEST['act'] == 'requestItensLicitacao') {
        return requestItensLicitacao();
    } else if ($_REQUEST['act'] == 'getTimeout'){
        return getTimeout();
    } else if ($_REQUEST['act'] == 'saveTimeout'){
        return saveTimeout();
    } else {
        echo "404 NOT FOUND";
    }
}

function saveTimeout(){

    $time = $_REQUEST['time'];

    $con = bancoMysqli();
    $sql = "DELETE FROM timeout";
    if (!mysqli_query($con, $sql)) {
        echo "ERROR: " . mysqli_error($con);
        echo "<br>";
        echo $sql;
    }

    $sql = "INSERT INTO timeout (minutos) VALUES ($time)";
    if (!mysqli_query($con, $sql)) {
        echo "ERROR: " . mysqli_error($con);
        echo "<br>";
        echo $sql;
    } else {

        
        $cmd = "";
        shell_exec($cmd);

        echo '1';
        exit;
    }
}

function getTimeout(){

    $con = bancoMysqli();
    $sql = "SELECT minutos FROM timeout";

    $query = mysqli_query($con, $sql);
    $obj = array();
    if($query){

        while($row = mysqli_fetch_assoc($query)){
            $obj[] = $row['minutos'];
        }

        echo json_encode($obj);
    } else {
        echo '0';
        exit;
    }
}

function requestLicGeraisComprasNet(){
    libxml_use_internal_errors(true);   
    // header("Vary: Origin");

    $con = bancoMysqli();
    $sql = "SELECT COUNT(*) as total FROM licitacoes_cab";
    $query = mysqli_query($con, $sql);
    $offset = mysqli_fetch_assoc($query);
    $offset = $offset['total'] ? $offset['total'] : 0;

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://compras.dados.gov.br/licitacoes/v1/licitacoes.json?offset=" . $offset
    ]);

    $offset_total = json_decode(curl_exec($curl));
    // $licitacoes = $offset_total->_embedded->licitacoes;
    
    // echo json_encode($licitacoes);
    // exit;
    if($offset_total){
        $offset_total = $offset_total->count;
    } else {
        echo 'API (COMPRASNET) FORA DO AR';
        exit;
    }
    // $offset_total = 5000;
    $i = $offset;
    // $i = 474;


    while ($i < $offset_total) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://compras.dados.gov.br/licitacoes/v1/licitacoes.json?offset=" . $i
        ]);
    
        $result = json_decode(curl_exec($curl));
        
        $licitacoes = $result->_embedded->licitacoes;

        foreach($licitacoes as $licitacao){
            
            $identificador = $licitacao->identificador;
            $uasg = $licitacao->uasg;

            foreach ($licitacao AS $campo => $value) {

                if (!is_object($value)) {
                    // $licitacao->$campo = $value != null ? "'$value'" : 'null';
                    if($value != null){
                        $value = str_replace("\"", "'", $value);
                        $value = str_replace("\\", "/", $value);
                        $licitacao->$campo = '"' . "$value" . '"';
                    } else {
                        $licitacao->$campo = 'null';
                    }
                }

            }

           // echo "tipo __ pregaaao " . $licitacao->tipo_pregao;

            $sql = "INSERT INTO licitacoes_cab (
                uasg, 
                identificador, 
                cod_modalidade,
                numero_aviso,
                tipo_pregao,
                numero_processo,
                numero_itens,
                situacao_aviso,
                objeto,
                informacoes_gerais,
                tipo_recurso,
                nome_responsavel,
                funcao_responsavel,
                data_entrega_edital,
                endereco_entrega_edital,
                data_abertura_proposta,
                data_entrega_proposta,
                data_publicacao
                ) VALUES (
                $licitacao->uasg, 
                $licitacao->identificador, 
                $licitacao->modalidade,
                $licitacao->numero_aviso,
                $licitacao->tipo_pregao,
                $licitacao->numero_processo,
                $licitacao->numero_itens,
                $licitacao->situacao_aviso,
                $licitacao->objeto,
                $licitacao->informacoes_gerais,
                $licitacao->tipo_recurso,
                $licitacao->nome_responsavel,
                $licitacao->funcao_responsavel,
                $licitacao->data_entrega_edital,
                $licitacao->endereco_entrega_edital,
                $licitacao->data_abertura_proposta,
                $licitacao->data_entrega_proposta,
                $licitacao->data_publicacao
                )
            ";

            if (!mysqli_query($con, $sql)) {
                echo "ERROR: " . mysqli_error($con);
                echo "<br>";
                echo $sql;
                exit;
            }

            $itens_licitacao = requestItensLicitacao($identificador);
            
            if(count($itens_licitacao) > 0 ){ //EX Count = 3
                $itens_licitacao = json_decode($itens_licitacao);

                foreach($itens_licitacao as $item_licitacao){
                    
                    $num_item_comprasnet = $item_licitacao->numero_item_licitacao;
                    $descricao_item = $item_licitacao->descricao_item;
                    $qtd_item_comprasnet = $item_licitacao->quantidade;

                    foreach($item_licitacao AS $campo => $value ){
                        // relacionamentos serão feitos pela Lic_id;
                        if (!is_object($value)) {
                            // $item_licitacao->$campo = $value != null ? "\"$value\"" : 'null';
                            if($value != null){
                                $value = str_replace("\"", "'", $value);
                                $value = str_replace("\\", "/", $value);
                                $item_licitacao->$campo = '"' . "$value" . '"';
                            } else {
                                $item_licitacao->$campo = 'null';
                            }
                        
                        }
                    }
                
                    $sql = "INSERT INTO licitacao_itens (
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
                        ) VALUES (
                        $item_licitacao->uasg,
                        $item_licitacao->numero_licitacao,
                        $item_licitacao->numero_aviso,
                        $item_licitacao->numero_item_licitacao,
                        $item_licitacao->codigo_item_servico,
                        $item_licitacao->codigo_item_material,
                        $item_licitacao->descricao_item,
                        $item_licitacao->sustentavel,
                        $item_licitacao->quantidade,
                        $item_licitacao->unidade,
                        $item_licitacao->cnpj_fornecedor,
                        $item_licitacao->cpfVencedor,
                        $item_licitacao->beneficio,
                        $item_licitacao->valor_estimado,
                        $item_licitacao->decreto_7174,
                        $item_licitacao->criterio_julgamento
                        )
                    ";

                    // echo $sql;
                    if(!mysqli_query($con, $sql)){
                        echo "<br>";
                        echo "ERROR: " . mysqli_error($con);
                        echo "<br>";
                        echo $sql;
                        exit;
                    } 

                    if ($descricao_item != '' && $descricao_item != null && $descricao_item != 'null' ){

                        $sql = 'SELECT MAX(id) as id FROM licitacao_itens';
                        $query = mysqli_query($con, $sql);
                        if($query){
                            $last_id = mysqli_fetch_assoc($query);
                            $last_id = $last_id['id'];
                        }
//{ "body": {"lista" : [ ["COMPRASNET",2,"algodao preto torcido nr. 3-0 agulha 1/2 12000000ui",3000, ""] ] } }
//15305402000011999
                        $ret = reqApiFutura($num_item_comprasnet, $descricao_item, $qtd_item_comprasnet );

                        if(count($ret) > 0){
                            // print_r($ret);
                            foreach($ret as $arrays){
                                foreach($arrays as $array => $value){
                                    if($value != null){
                                        $value = str_replace("\"", "'", $value);
                                        $value = str_replace("\\", "/", $value);
                                        $arrays[$array] = '"' . "$value" . '"';
                                    } else {
                                        $arrays[$array] = 'null';
                                    }
                                }
                                

                                if($arrays[9] && $arrays[10]){

                                    $desc_fabricante = $arrays[10];
                                    $cod_fabricante = $arrays[9];

                                    $sql = "SELECT id FROM fabricantes WHERE cod_fabricante = $cod_fabricante";
                                    $query = mysqli_query($con, $sql);

                                    if (mysqli_num_rows($query) == 0) {
                                      $sql = "INSERT INTO fabricantes (
                                        nome,
                                        email,
                                        descricao,
                                        cod_fabricante
                                    ) VALUES (
                                        $desc_fabricante,
                                        '',
                                        $desc_fabricante,
                                        $cod_fabricante
                                    )
                                    ";

                                      if(!mysqli_query($con, $sql)){
                                        echo "<br>";
                                        echo "ERROR: " . mysqli_error($con);
                                        echo $sql;
                                        exit;
                                      }

                                      $sql = 'SELECT MAX(id) as id FROM fabricantes';

                                      if($query = mysqli_query($con, $sql)){
                                        $last_fab_id = mysqli_fetch_assoc($query);
                                        $last_fab_id = $last_fab_id['id'];
                                      }

                                    } else {
                                      $fabri = mysqli_fetch_assoc($query);
                                      $last_fab_id = $fabri['id'];
                                    }
                                }

                                $str = implode(',' , $arrays);
                                $sql = "INSERT INTO produtos_futura (
                                    item_id,
                                    fabricante_id,
                                    nome_portal,
                                    num_item_licitacao,
                                    cod_jd_produto,
                                    desc_licitacao_portal,
                                    quantidade_item_licitacao,
                                    desc_licitacao_jd,
                                    cod_produto_jd,
                                    quantidade_embalagem_produto_jd,
                                    desc_produto_jd,
                                    cod_fabricante_jd,
                                    nome_fabricante,
                                    estoque_disp_jd
                                ) VALUES (
                                    $last_id, $last_fab_id, $str
                                )
                                ";

                                if(!mysqli_query($con, $sql)){
                                    echo "<br>";
                                    echo "ERROR: " . mysqli_error($con);
                                    echo $sql;
                                    exit;
                                }

                            }
                        }

                    }

                }
            }
//               echo "Possui data";
//          } else {
//                echo "Não possui item";
//          }

            $orgao_licitacao = requestParseOrgaosGov($uasg);
            print_r($orgao_licitacao);
            if(count($orgao_licitacao) > 0) {
                $orgao_licitacao = json_decode($orgao_licitacao);

                foreach ($orgao_licitacao AS $campo => $value) {

                    if (!is_object($value)) {
                        $orgao_licitacao->$campo = $value != null ? "\"$value\"" : 'null';
                    }

                }
                
                $sql = "INSERT INTO licitacao_orgao (uasg, lic_orgao, lic_estado) VALUES ($uasg, $orgao_licitacao->orgao, $orgao_licitacao->estado)";
                if(!mysqli_query($con, $sql)){
                    print_r(mysqli_error($con));
                    echo $sql;
                    exit;
                }
            }

        }
        echo '<pre>'; echo $i . ' UASG: ' . $uasg ; echo '</pre>';
        $i += 500;
    }

    echo '1';
    exit;
    // print_r($licitacoes);
}

function requestItensLicitacao($identificador = '') {

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://compras.dados.gov.br/licitacoes/doc/licitacao/$identificador/itens.json"
    ]);


    $result = json_decode(curl_exec($curl));
        
    $itens = $result->_embedded->itensLicitacao;

    return json_encode($itens);

}

function requestParseOrgaosGov($uasg){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://comprasnet.gov.br/ConsultaLicitacoes/Pesquisar_UASG.asp?codUasg=" . $uasg
    ]);

    $result = curl_exec($curl);
    if($result){
        libxml_use_internal_errors(true);   

        $document = new DOMDocument();
        $document->loadHTML($result);
        $document = $document->saveHTML();

        $parse = 0;
        while ($parse < 3){
            $doc = $document;

            $doc = explode('<table border="0" width="100%" cellspacing="1" cellpadding="2" class="td"><tr bgcolor="#FFFFFF">', $doc);

            echo $parse;
            if (isset($doc[$parse])){
            $doc = explode('bgcolor="#FFFFFF" class="tex3a" align="center"', $doc[$parse]);
            }

            if(isset($doc[1])){
            $doc = explode('<td>', $doc[1]);
            }

            $data = array();

            if(isset($doc['2'])){
                $uasg = explode('</td>', $doc[2]);
                $data['uasg'] = trim($uasg[0]);
            }

            if(isset($doc[3])){
                $orgao = explode('</td>', $doc[3]);
                $data['orgao'] = trim($orgao[0]);
            }

            if(isset($doc[4])){
                $doc = explode('</td>', $doc[4]);
                $data['estado'] = trim($doc[0]);
            }

            $parse++;
            if(count($data) > 0){
                if ($data['orgao'] != '' && $data['orgao'] != 'undefined' && $data['estado'] != 'null'){
                    return json_encode($data);
                }
            }
        }
    }
}
