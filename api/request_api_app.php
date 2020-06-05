<?php

set_time_limit(0);
ignore_user_abort(false);
require_once ("../ajax/conexao.php");
require_once ("request_produtos.php");
require_once ("request_licitacoes.php");

//getLicitacoesApp();

if($_REQUEST['act']){
    if ($_REQUEST['act'] == 'firebase'){
        return getFirebase();
    } else if ($_REQUEST['act'] == 'jwt') {
        return getTokenJwt();
    } else if ($_REQUEST['act'] == 'parame'){
        return parametrizacao();
    } else if ($_REQUEST['act'] == 'getlic'){
        return getLicitacoesApp();
    } else {
        echo "404 NOT FOUND";
    }
}

function getLicitacoesApp() {

    $ini = 1;
    $tamPag = 10;

    $url = "https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/v1/oportunidades/licitacoes?tamanhoPagina=$tamPag&primeiroRegistro=$ini";

    $result = makeCurlGet($url, 1);
    $data = explode("\n",$result);

    if (strpos($data[0], "Unauthorized")) {
        $result = makeCurlGet($url, 1, true);
    }

    $data = explode("\n",$result);

    $contentRange = explode("|", $data[16]);
    $total = $contentRange[1];

    $ini = 1;

    while ($ini <= $total) {
        $url = "https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/v1/oportunidades/licitacoes?tamanhoPagina=$tamPag&primeiroRegistro=$ini";

        $result = makeCurlGet($url, 0);

        $licitacoes = json_decode($result);

      /*  echo "<pre>";
        print_r($licitacoes);
        echo "</pre>";*/

        foreach($licitacoes AS $lic) {
            $uasg = $lic->numeroUasg;
            $modalidade = $lic->modalidade;
            $numero     = $lic->numero;
            $ano        = $lic->ano;

            $urlPart = sprintf("%06s%02s%05s%04s", $uasg, $modalidade, $numero, $ano);
            $identificador = $urlPart;

            $urlDetails = "https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/v1/licitacoes/$urlPart";

            $detalhesLic = json_decode(makeCurlGet($urlDetails));

             saveLici($identificador, $detalhesLic);

             $iniItens = 1;
             $urlItens = "https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/v1/oportunidades/licitacoes/$urlPart/itens?tamanhoPagina=$tamPag&primeiroRegistro=$iniItens";

             $itensLic = makeCurlGet($urlItens, 1);

             $dataItens = explode("\n",$itensLic);

             $contentItens = explode("|", $dataItens[16]);
             $totalItens = $contentItens[1];

             $con = bancoMysqli();
             $sql = "UPDATE licitacoes_cab SET numero_itens = $totalItens WHERE identificador = $identificador";
             mysqli_query($con, $sql);

            echo '\n total itens ' . $totalItens . '\n';

            $iniItens = 1;

            while ($iniItens <= $totalItens){
                 $urlItens = "https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/v1/oportunidades/licitacoes/$urlPart/itens?tamanhoPagina=$tamPag&primeiroRegistro=$iniItens";

                 $itensLic = json_decode(makeCurlGet($urlItens));

                 foreach($itensLic AS $item) {
                     processItemLic($identificador, $uasg, $item);
                     echo '<br>passando pelo each itens<br>';

                 }

                 $iniItens += 10;
             }

             $orgao_licitacao = requestParseOrgaosGov($uasg);
             if(count($orgao_licitacao) > 0) {
                 saveOrgao($uasg, $orgao_licitacao);
             }


            echo '<br>passando pelo each licitacoes<br>';
        }

        $ini += 10;
        echo "pag lic $ini <br>";
        echo "total lic $total <br>";
    }

    return true;
}

function processItemLic($identificador, $uasg, $item) {
    $con = bancoMysqli();

    $numItem = $item->numero;

    $sqlVerificaItens = "SELECT id FROM licitacao_itens WHERE lic_id = $identificador AND num_item_licitacao = $numItem";
    $queryCheckItens = mysqli_query($con, $sqlVerificaItens);

    $num_item_comprasnet = $numItem;
    $descricao_item = $item->descricaoDetalhada;
    $qtd_item_comprasnet = $item->quantidade;
    $un_item_comprasnet = mb_strtoupper($item->unidadeFornecimento);

    $field = "";
    $value = "";

    if ($item->decreto7174 != '') {
        $field = ", decreto_7174";
        $value = ', ' . $item->decreto7174;
    }

    if (mysqli_num_rows($queryCheckItens) == 0) {

        $sql = "INSERT INTO licitacao_itens (
                        lic_uasg,
                        lic_id,
                        num_item_licitacao,
                        descricao_item,
                        quantidade,
                        unidade
                        $field
                        ) VALUES (
                        $uasg,
                        $identificador,
                        $numItem,
                        '$descricao_item',
                        '$qtd_item_comprasnet',
                        '" . mb_strtoupper($un_item_comprasnet) . "'
                        $value
                        )
                    ";

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

        if ($descricao_item != '' && $descricao_item != null && $descricao_item != 'null' ){

            $ret = reqApiFutura($num_item_comprasnet, $descricao_item, $qtd_item_comprasnet, $un_item_comprasnet);

            if(count($ret) > 0){
                saveDadosFutura($last_id, $ret);
            }
        }

    }






}

function saveLici($identificador, $licitacao) {
    $con = bancoMysqli();

    $sqlVerifica = "SELECT * FROM licitacoes_cab WHERE identificador = $identificador";
    if (mysqli_num_rows(mysqli_query($con, $sqlVerifica)) == 0) {

        $sql = "INSERT INTO licitacoes_cab (
                  uasg, 
                  identificador, 
                  cod_modalidade,
                  objeto,
                  data_abertura_proposta,
                  data_entrega_proposta
                  ) VALUES (
                  $licitacao->numeroUasg, 
                  $identificador, 
                  $licitacao->modalidade,
                  '$licitacao->objeto',
                  '$licitacao->dataHoraAberturaSessaoPublica',
                  '$licitacao->dataHoraInicioEntregaProposta' 
                  )
              ";

        if (!mysqli_query($con, $sql)) {
            echo "ERROR: " . mysqli_error($con);
            echo "<br>";
            echo $sql;
            exit;
        } else {
            echo "\n";echo date('d-m-Y H:i:s'); echo " [====]  Identificador Licitação: " . $identificador ;echo ' [====] UASG: ' . $licitacao->numeroUasg ; echo "\n";
            return true;
        }
    } else {
        echo "\n";echo date('d-m-Y H:i:s'); echo " [====]  Licitação já Cadastrada [====] "; echo "\n [====] Identificador Licitação: " . $identificador ;echo ' [====] UASG: ' .  $licitacao->numeroUasg ; echo "\n";
        return false;
    }


}

function makeCurlGet($url, $header = 0, $new = false) {

    $token_jwt = getTokenJwt($new);

    parametrizacao($token_jwt);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,$url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, $header);

    $headers = [
        'authorization: JWT ' . $token_jwt,
        'content-type: application/json',
        'accept: application/json, text/plain, */*'
    ];


    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);

    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "  ===# ERROR: Não foi retornado informações da API do Medicamentos Futura. #=== \n";
        echo "  ===# Curl ERROR: " . $err . "#=== ";
        exit;
    }

    return $result;

}

/*function detalhesLicitacao($urlPart) {

}*/

function parametrizacao($token_jwt) {

    /*  echo "<br> Token JWT \/<br>";
    echo $token_jwt;*/

    $CODMATERIAIS = "6405,6410,6415,6420,6425,6430,6435,6440,6445,6450,6455,6460,6465,6470,6475,6495,6505,6508,6509,6510,6515,6520,6525,6530,6532,6540,6545,6550,6555";

    $LISTAUF = "DF,GO,MT,MS,AL,BA,CE,MA,PB,PE,PI,RN,SE,AC,AP,AM,PA,RO,RR,TO,ES,MG,RJ,SP,PR,RS,SC";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL,"https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/v1/oportunidades/parametrizacao");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS,
        '{"materiais":" ' . $CODMATERIAIS . '","ufs":"'. $LISTAUF .'"}');

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($curl, CURLOPT_HEADER, 1);


    $headers = [
        'authorization: JWT ' . $token_jwt,
        'content-type: application/json',
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    return true;

   /* echo "<pre>";
    print_r($result);
    echo "</pre>";*/
}

function getFireBase(){

    $con = bancoMysqli();
    $sql = "SELECT * FROM config_api_app";
    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) > 0) {
        $infos = mysqli_fetch_assoc($query);

        $firebase = $infos['firebase'];
        $token_JWT = $infos['token_jwt'];

        return $firebase;

        return;
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL,"https://android.clients.google.com/c2dm/register3");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS,
        "X-subtype=1023343371949&sender=1023343371949&X-app_ver=10050&X-osv=22&X-cliv=fiid-20.0.1&X-gmsv=201216008&X-appid=f6WpFcs2W2o&X-scope=*&X-gmp_app_id=1%3A1023343371949%3Aandroid%3A051650c153213ea0&X-app_ver_name=3.0.0&app=br.gov.serpro.comprasNetMobile&device=3701375969863611343&app_ver=10050&info=4xkbMaspnDoeENW7sZq6nvmCvIONGRc&gcm_ver=201216008&plat=0&cert=036d01cd5ded1fdae462c341065ccf44c3a4053c&target_ver=28");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'Authorization: AidLogin 3701375969863611343:1910495745030104839',
        'app: br.gov.serpro.comprasNetMobile',
        'gcm_ver: 201216008',
        'User-Agent: Android-GCM/1.5 (j3xlte LMY47V)',
        'content-type: application/x-www-form-urlencoded',
        'Connection: Keep-Alive'
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $parts = explode('=', $result);

    $token = $parts[1];

    $sql = "INSERT INTO config_api_app (firebase) VALUES ('$token')";
    mysqli_query($con, $sql);

    return $token;

}

function getTokenJwt($new = false) {
    $con = bancoMysqli();
    $sql = "SELECT * FROM config_api_app";
    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) > 0 && !$new) {
        $infos = mysqli_fetch_assoc($query);

        $token_JWT = $infos['token_jwt'];

        if ($token_JWT != '') {
            return $token_JWT;

        }
    }

    $firebase = getFirebase();

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL,"https://cnetmobile.estaleiro.serpro.gov.br/comprasnet-oportunidade/api/autenticacao/v2/login");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS,
        '{"tokenFirebase":"' . $firebase . '","tokenAutenticadorExterno":null}');

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'sec-fetch-mode: cors',
        'sec-fetch-dest: empty',
        'sec-fetch-site: cross-site',
        'content-type: application/json'
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $result = json_decode($result);

    $token_JWT = $result->tokenCnet;

    $sql = "UPDATE config_api_app SET token_jwt = '$token_JWT' WHERE firebase = '$firebase'";
    mysqli_query($con, $sql);

    return $token_JWT;


}