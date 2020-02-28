<?php
require "conexao.php";
require ("PHPMailer/src/SMTP.php");
require ("PHPMailer/src/Exception.php");
require ("PHPMailer/src/PHPMailer.php");
date_default_timezone_set("America/Sao_Paulo");
header('Content-type: text/html; charset=utf-8');

//sendMail('teste', 'testando', 'tanaiiir@gmail.com');

if ($_REQUEST['act']) {
    $request = $_REQUEST['act'];
    if ($request == 'get_infos') {
        $id_item = $_REQUEST['id'];
        $id_fabri = $_REQUEST['idFabricante'];
        $pf_id = $_REQUEST['pf_id'];
        return getItensFabri($id_item, $id_fabri, $pf_id);
    } else if ($request == 'sendEmail') {
        if (isset($_REQUEST['item_id'])) {
          $item_id = $_REQUEST['item_id'];
          return prepareMail(0, $item_id);
        }
        $idRef = $_REQUEST['id'];
        return prepareMail($idRef);
    }
}

function getItensFabri($id_item, $id_fabricante, $pf_id)
{
    $con = bancoMysqli();

    $sql = "SELECT 
                f.nome, 
                f.email, 
                pf.id
                FROM produtos_futura as pf
                INNER JOIN fabricantes AS f ON f.id = pf.fabricante_id
                WHERE item_id = $id_item AND fabricante_id = $id_fabricante AND pf.id = $pf_id";

    $query = mysqli_query($con, $sql);
    $rows = mysqli_num_rows($query);

    if ($rows > 0) {
        $obj = [];

        while ($infos = mysqli_fetch_assoc($query)) {
            $obj[] = $infos;
        }
        echo json_encode($obj);
    } else {
        echo json_encode(0);
    }
}



function getConfs()
{
    $con = bancoMysqli();
    $sql = 'SELECT remetente, server_smtp, port_smtp, usuario, senha, cop_email FROM conn_smtp';

    $query = mysqli_query($con, $sql);
    $rows = mysqli_num_rows($query);

    if ($rows > 0) {
        $conf_conta = mysqli_fetch_assoc($query);
        return $conf_conta;
    } else {
        echo "0";
    }

}

function getBody()
{
    $con = bancoMysqli();

    $sql = "SELECT * FROM smtp_body";
    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) > 0) {
      $confs = mysqli_fetch_assoc($query);

      return $confs;

    }
}

function prepareMail($idRef, $item_id = 0)
{
    $con = bancoMysqli();

    $produto_id = $idRef;
    $where = "pf.id = $idRef";
    if ($idRef == 0) {
      $where = "i.id = $item_id";
    }

    $sql = "SELECT
                f.id AS fabricante_id,
                f.nome,
                f.email,
                i.lic_uasg,
                i.id AS item_id, 
                i.num_item_licitacao AS item,
                i.unidade,
                i.quantidade,
                i.valor_estimado,
                i.quantidade * i.valor_estimado AS valor_total,
                pf.id AS produto_id,
                pf.desc_licitacao_jd AS descricao,
                o.lic_orgao AS orgao,
                DATE_FORMAT(lic.data_entrega_proposta, '%d/%m/%Y %H:%i') AS data_entrega
                FROM produtos_futura as pf
                INNER JOIN licitacao_itens AS i ON i.id = pf.item_id
                LEFT JOIN licitacao_orgao AS o ON o.uasg = i.lic_uasg
                INNER JOIN fabricantes AS f ON f.id = pf.fabricante_id
                INNER JOIN licitacoes_cab AS lic ON lic.identificador = i.lic_id
                WHERE $where";

    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) > 0) {
        $infos = mysqli_fetch_assoc($query);

        if ($idRef == 0) {
          $produto_id = $infos['produto_id'];
        }

        $conf_body = getBody();
        $crash_text =  explode('<tabela>', $conf_body['smtp_corpo']);

        $antes = $crash_text[0];
        $depois = $crash_text[1];


        $body = "<p>".$infos['nome'].", bom dia.</p>
                    $antes

                    <table width='950' style='text-align: center; font-size: 15px; border: 1px solid black;'>
                    
                         <tr>
                            <td style='background: #ff9d00'>UASG</td>
                            <td style='background: #ff9d00' colspan='5'>".$infos['lic_uasg']. "</td>                           
                        </tr>
                        
                         <tr>
                            <td width='10%' style='background: #ff9d00'>ORGÃO</td>
                            <td style='background: #ff9d00' colspan='5'>" . $infos['orgao'] . "</td>                           
                        </tr>
                   
                        <tr>
                            <td style='background: #ff9d00'>DATA E HORA PREVISTA PARA A LICITAÇÃO</td>
                            <td style='background: #ff9d00' colspan='5'><b>".$infos['data_entrega']." h</b></td>                           
                        </tr>
                        
                        <tr>
                            <td style='background: #f5f5f5;'>Item</td>
                            <td style='background: #f5f5f5;'>Descrição</td>
                            <td style='background: #f5f5f5;'>Unidade de Fornecimento</td>
                            <td style='background: #f5f5f5;'>Quantidade</td>
                            <td style='background: #f5f5f5;'>Valor Unitário Estimado</td>
                            <td style='background: #f5f5f5;'>Valor Total</td>
                        </tr>
                        <tr>
                            <td>".$infos['item']."</td>
                            <td>".$infos['descricao']."</td>
                            <td>".$infos['unidade']."</td>
                            <td>".$infos['quantidade']."</td>
                            <td>".number_format($infos['valor_estimado'], 2, ',', '.')."</td>
                            <td>R$ ".number_format($infos['valor_total'],2,',', '.')."</td>
                        </tr>
                        <tr>
                            <td colspan='5'><b>TOTAL GLOBAL</b></td>
                            <td>R$ ".number_format($infos['valor_total'], 2, ',', '.')."</td>
                        </tr>
                       
                    </table> <br>
                    
                    $depois
                    ";

        $assunto = $conf_body['smtp_assunto'] != "" ? $conf_body['smtp_assunto'] : $infos['orgao'];

        if (sendMail($assunto, $body, $infos['email'])) {

          $sql = "SELECT * FROM email_enviados WHERE produto_id = $produto_id AND item_id = " . $infos['item_id'] . " AND fabricante_id =  " . $infos['fabricante_id'];

          $query = mysqli_query($con, $sql);

          if (mysqli_num_rows($query) == 0) {

            $sql = "INSERT INTO email_enviados (item_id, 
                                              fabricante_id, 
                                              produto_id, 
                                              email_enviado, 
                                              resposta,
                                              data_envio) 
                                      VALUES (".$infos['item_id'].",
                                                ".$infos['fabricante_id'].",
                                                $produto_id,
                                                'Y',
                                                'OK',
                                                '" . date("Y-m-d H:i:s") . "')";

            if (!mysqli_query($con, $sql)) {
              echo 'não cadastrou';
            }
          } else {
            $sql = "UPDATE email_enviados SET data_envio = '" . date("Y-m-d H:i:s") . "' WHERE produto_id = $produto_id AND item_id = " . $infos['item_id'] . " AND fabricante_id =  " . $infos['fabricante_id'];
            echo $sql;
            mysqli_query($con, $sql);
          }

          
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(false);
        }
    } else {
        echo $sql;
    }


}

function sendMail($subject, $body, $para, $cc = '')
{
    $mailConfs = getConfs();

    $Mailer = new \PHPMailer\PHPMailer\PHPMailer();

    //Define que será usado SMTP
    $Mailer->IsSMTP();
    $Mailer->SMTPDebug = 1;

    //Enviar e-mail em HTML
    $Mailer->isHTML(true);

    //Aceitar carasteres especiais
    $Mailer->Charset = 'utf8';

    //Configurações
    $Mailer->SMTPAuth = true;
    $Mailer->SMTPSecure = 'ssl';

    //nome do servidor
    $Mailer->Host = $mailConfs['server_smtp'];
    //Porta de saida de e-mail
    $Mailer->Port = $mailConfs['port_smtp'];

    //Dados do e-mail de saida - autenticação
    $Mailer->Username = $mailConfs['usuario'];
    $Mailer->Password = $mailConfs['senha'];

    //E-mail remetente (deve ser o mesmo de quem fez a autenticação)
    $Mailer->From = $mailConfs['remetente'];

    //Nome do Remetente
    $Mailer->FromName = 'ComprasNET';

    //E-mail em cópia
    $Mailer->AddCC($mailConfs['cop_email']);
    //Assunto da mensagem
    $Mailer->Subject = $subject;

    //Corpo da Mensagem
    $Mailer->Body = $body;

    //Corpo da mensagem em texto
    $Mailer->AltBody = $body;

    //Destinatario
    $Mailer->AddAddress($para);

    if($Mailer->Send()){
        return true;
        echo "E-mail enviado com sucesso";
    }else{
        return false;
        echo "Erro no envio do e-mail: " . $Mailer->ErrorInfo;
    }
}
