<?php 

set_time_limit(0);
ignore_user_abort(false);
require_once ("../ajax/conexao.php");//alterar
require_once ("request_produtos.php");//usr/bin/php7.0 test.php
require_once ("request_api_app.php");//usr/bin/php7.0 test.php

if($_REQUEST['act']){
    if ($_REQUEST['act'] == 'requestLicitacoes'){
        if (getLicitacoesApp()) {
            requestLicGeraisComprasNet();
        }

    } else if ($_REQUEST['act'] == 'requestLicitacoesApp') {
        getLicitacoesApp();
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
        $op = $time / 60;
        $hour = '*';
        $min = '*';

        if ($op > 1) {
            $hour = "*/" . round($time / 60);
        } else {
            $min = "*/" . $time; 
        }

        // $cmd = 'for i in `ps aux| grep timeout | awk \'{print$2}\' `; do kill -9 "$i" ; done ';
        $cmd = "sudo chown www-data /etc/crontab";
        shell_exec($cmd);
        file_put_contents('/etc/crontab', "# /etc/crontab: system-wide crontab\n# Unlike any other crontab you don't have to run the `crontab'\n# command to install the new version when you edit this file\n# and files in /etc/cron.d. These files also have username fields,\n# that none of the other crontabs do.\nSHELL=/bin/sh\nPATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin\n# m h dom mon dow user  command\n17 *    * * *   root    cd / && run-parts --report /etc/cron.hourly\n25 6    * * *   root    test -x /usr/sbin/anacron || ( cd / && run-parts --report /etc/cron.daily )\n47 6    * * 7   root    test -x /usr/sbin/anacron || ( cd / && run-parts --report /etc/cron.weekly )\n52 6    1 * *   root    test -x /usr/sbin/anacron || ( cd / && run-parts --report /etc/cron.monthly )\n$min $hour   * * *   root    /usr/bin/php7.0 /var/www/html/ComprasNET/api/timeout.php >> /var/log/comprasnet/getapi.log\n5 2,4,6,8,10,12,14,16,18,20,22  * * *   root    /bin/sh /var/www/html/ComprasNET/rotate.sh\n#");
        $cmd = "sudo chown root.root /etc/crontab";
        shell_exec($cmd);
        $cmd = "sudo systemctl restart cron";
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

    $verificaProcesso = shell_exec('ps aux | grep "ComprasNET/api/timeout.php" | grep -v "grep" | grep -v "/var/log/comprasnet" | awk \'{print$2}\'');
    if(strlen($verificaProcesso) > 8){
        echo "\n"; echo date('d-m-Y H:i:s'); echo " ===== Processo já em execução ===== \n";
        exit;
    }

    $con = bancoMysqli();
  

    $sql = "SELECT COUNT(*) as total FROM licitacoes_cab";
    $query = mysqli_query($con, $sql);
    $offset = mysqli_fetch_assoc($query);
    $offsetBanco = $offset['total'] ? $offset['total'] : 0;

    $offset = $offsetBanco;

    $sql = "SELECT identificador FROM licitacoes_cab ORDER BY id ASC LIMIT 1";
    $query = mysqli_query($con, $sql);

    if($query){
        $info = mysqli_fetch_assoc($query);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://compras.dados.gov.br/licitacoes/v1/licitacoes.json?order_by=data_entrega_proposta&offset=0"
        ]);


        $result = json_decode(curl_exec($curl));
        
        $licitacoes = $result->_embedded->licitacoes;

        $count = 0;

        foreach ($licitacoes AS $licitacao) {
            if ($licitacao->identificador != $info['identificador']) {
                $count++;
            } else {
                break;
            }
        }

        if ($count > 0) {
            $diff = $count;
            $offset = 0;            
        }
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://compras.dados.gov.br/licitacoes/v1/licitacoes.json?order_by=data_entrega_proposta&offset=" . $offset
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
            CURLOPT_URL => "http://compras.dados.gov.br/licitacoes/v1/licitacoes.json?order_by=data_entrega_proposta&offset=" . $i
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

          $sqlVerifica = "SELECT * FROM licitacoes_cab WHERE identificador = $identificador";
          if (mysqli_num_rows(mysqli_query($con, $sqlVerifica)) == 0) {

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
              } else {
                echo "\n";echo date('d-m-Y H:i:s'); echo " [====]  Identificador Licitação: " . $identificador ;echo ' [====] UASG: ' . $uasg ; echo "\n";
              }
            } else {
                echo "\n";echo date('d-m-Y H:i:s'); echo " [====]  Licitação já Cadastrada [====] "; echo "\n [====] Identificador Licitação: " . $identificador ;echo ' [====] UASG: ' . $uasg ; echo "\n";
            }

            $itens_licitacao = requestItensLicitacao($identificador);
            
            if(count($itens_licitacao) > 0 ){ //EX Count = 3
                $itens_licitacao = json_decode($itens_licitacao);

                foreach($itens_licitacao as $item_licitacao){
                    
                    $num_item_comprasnet = $item_licitacao->numero_item_licitacao;
                    $descricao_item = $item_licitacao->descricao_item;
                    $qtd_item_comprasnet = $item_licitacao->quantidade;
                    $un_item_comprasnet = $item_licitacao->unidade;
                    

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

                  $sqlVerificaItens = "SELECT id FROM licitacao_itens WHERE lic_id = $identificador AND num_item_licitacao = $num_item_comprasnet";
                  $queryCheckItens = mysqli_query($con, $sqlVerificaItens);

                  if (mysqli_num_rows($queryCheckItens) == 0) {

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
                        if (!mysqli_query($con, $sql)) {
                            echo "<br>";
                            echo "ERROR: " . mysqli_error($con);
                            echo "<br>";
                            echo $sql;
                            exit;
                        } else {
                            echo "\n";echo date('d-m-Y H:i:s');echo " [====] Cadastrando Itens do Portal Comprasnet da Licitacao: \n";  echo "\n Id Licitação: " . $identificador ;  echo "\n UASG: " . $uasg ; echo "\n Descrição Item: " . $descricao_item ; echo "\n Numero do item: " . $num_item_comprasnet ;echo "\n Quantidade do Item: " . $qtd_item_comprasnet . "\n";
                        }
                    $sql = 'SELECT MAX(id) as id FROM licitacao_itens';
                    $query = mysqli_query($con, $sql);
                    if($query){
                      $last_id = mysqli_fetch_assoc($query);
                      $last_id = $last_id['id'];
                    }

                  } else {
                    $item = mysqli_fetch_assoc($queryCheckItens);
                    $last_id = $item['id'];
                  }

                    if ($descricao_item != '' && $descricao_item != null && $descricao_item != 'null' ){

                        $ret = reqApiFutura($num_item_comprasnet, $descricao_item, $qtd_item_comprasnet, $un_item_comprasnet);

                        if(count($ret) > 0){
                            saveDadosFutura($last_id, $ret);
                        }
                    }

                }
            }
//               echo "Possui data";
//          } else {
//                echo "Não possui item";
//          }

            $orgao_licitacao = requestParseOrgaosGov($uasg);
            if(count($orgao_licitacao) > 0) {
                saveOrgao($uasg, $orgao_licitacao);
            }

            if (isset($diff)) {
                $count--;
            }

            if (isset($diff) && $count == 0) {
                break;
            }
         //logs
        }

        if (isset($diff) && $count == 0) {
            $i = $offsetBanco - $diff;
        }

        echo '<pre>'; echo "\n==== GRAVADO $i Licitacoes ====\n"; echo '</pre>';

        if (!isset($diff)) {
            $i += 500;
        } else {
            unset($diff);
        }
        
    }

    echo '1';
    exit;
    // print_r($licitacoes);
}

function saveDadosFutura($last_id, $ret) {
    $con = bancoMysqli();

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
                } else {
                    echo "\n";echo date('d-m-Y H:i:s');echo " [====] Cadastrando Fabricantes Medicamentos Futura: \n"; echo "\n Descrição Fabricante: " . $desc_fabricante ; echo "\n Código do Fabricante: " . $cod_fabricante . "\n";
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

function saveOrgao ($uasg, $orgao_licitacao) {

    $con = bancoMysqli();

    $orgao_licitacao = json_decode($orgao_licitacao);

    foreach ($orgao_licitacao AS $campo => $value) {

        if (!is_object($value)) {
            $value = $value != null ?  html_entity_decode($value) : null;
            $orgao_licitacao->$campo = $value != null ? "\"$value\"" : 'null';
        }

    }

    $sqlConsult = "SELECT * FROM licitacao_orgao WHERE uasg = $uasg AND lic_orgao = $orgao_licitacao->orgao";

    if (mysqli_num_rows(mysqli_query($con, $sqlConsult)) == 0 ) {
        $sql = "INSERT INTO licitacao_orgao (uasg, lic_orgao, lic_estado) VALUES ($uasg, $orgao_licitacao->orgao, $orgao_licitacao->estado)";
        if(!mysqli_query($con, $sql)){
            print_r(mysqli_error($con));
            echo $sql;
            exit;
        }
    }
}

function saveLicAndItem($licitacao, $app = false) {
    $con = bancoMysqli();

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

    $sqlVerifica = "SELECT * FROM licitacoes_cab WHERE identificador = $identificador";
    if (mysqli_num_rows(mysqli_query($con, $sqlVerifica)) == 0) {

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
        } else {
            echo "\n";echo date('d-m-Y H:i:s'); echo " [====]  Identificador Licitação: " . $identificador ;echo ' [====] UASG: ' . $uasg ; echo "\n";
        }
    } else {
        echo "\n";echo date('d-m-Y H:i:s'); echo " [====]  Licitação já Cadastrada [====] "; echo "\n [====] Identificador Licitação: " . $identificador ;echo ' [====] UASG: ' . $uasg ; echo "\n";
    }

    if (!$app) {
        $itens_licitacao = requestItensLicitacao($identificador);
    }

    if(count($itens_licitacao) > 0 ){ //EX Count = 3
        $itens_licitacao = json_decode($itens_licitacao);

        foreach($itens_licitacao as $item_licitacao){

            $num_item_comprasnet = $item_licitacao->numero_item_licitacao;
            $descricao_item = $item_licitacao->descricao_item;
            $qtd_item_comprasnet = $item_licitacao->quantidade;
            $un_item_comprasnet = $item_licitacao->unidade;



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

            $sqlVerificaItens = "SELECT id FROM licitacao_itens WHERE lic_id = $identificador AND num_item_licitacao = $num_item_comprasnet";
            $queryCheckItens = mysqli_query($con, $sqlVerificaItens);

            if (mysqli_num_rows($queryCheckItens) == 0) {

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
                if (!mysqli_query($con, $sql)) {
                    echo "<br>";
                    echo "ERROR: " . mysqli_error($con);
                    echo "<br>";
                    echo $sql;
                    exit;
                } else {
                    echo "\n";echo date('d-m-Y H:i:s');echo " [====] Cadastrando Itens do Portal Comprasnet da Licitacao: \n";  echo "\n Id Licitação: " . $identificador ;  echo "\n UASG: " . $uasg ; echo "\n Descrição Item: " . $descricao_item ; echo "\n Numero do item: " . $num_item_comprasnet ;echo "\n Quantidade do Item: " . $qtd_item_comprasnet . "\n";
                }
                $sql = 'SELECT MAX(id) as id FROM licitacao_itens';
                $query = mysqli_query($con, $sql);
                if($query){
                    $last_id = mysqli_fetch_assoc($query);
                    $last_id = $last_id['id'];
                }

            } else {
                $item = mysqli_fetch_assoc($queryCheckItens);
                $last_id = $item['id'];
            }

            if ($descricao_item != '' && $descricao_item != null && $descricao_item != 'null' ){

                $ret = reqApiFutura($num_item_comprasnet, $descricao_item, $qtd_item_comprasnet, $un_item_comprasnet);

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
                                } else {
                                    echo "\n";echo date('d-m-Y H:i:s');echo " [====] Cadastrando Fabricantes Medicamentos Futura: \n"; echo "\n Descrição Fabricante: " . $desc_fabricante ; echo "\n Código do Fabricante: " . $cod_fabricante . "\n";
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
    if(count($orgao_licitacao) > 0) {
        $orgao_licitacao = json_decode($orgao_licitacao);

        foreach ($orgao_licitacao AS $campo => $value) {

            if (!is_object($value)) {
                $value = $value != null ?  html_entity_decode($value) : null;
                $orgao_licitacao->$campo = $value != null ? "\"$value\"" : 'null';
            }

        }

        $sqlConsult = "SELECT * FROM licitacao_orgao WHERE uasg = $uasg AND lic_orgao = $orgao_licitacao->orgao";

        if (mysqli_num_rows(mysqli_query($con, $sqlConsult)) == 0 ) {
            $sql = "INSERT INTO licitacao_orgao (uasg, lic_orgao, lic_estado) VALUES ($uasg, $orgao_licitacao->orgao, $orgao_licitacao->estado)";
            if(!mysqli_query($con, $sql)){
                print_r(mysqli_error($con));
                echo $sql;
                exit;
            }
        }
    }

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
