<?php
require "conexao.php";
require ("PHPMailer/src/SMTP.php");
require ("PHPMailer/src/Exception.php");
require ("PHPMailer/src/PHPMailer.php");

//sendMail('teste', 'testando', 'tanaiiir@gmail.com');

if ($_REQUEST['act']) {
    $request = $_REQUEST['act'];
    if ($request == 'get_infos') {
        $id_item = $_REQUEST['id'];
        return getItensFabri($id_item);
    } else if ($request == 'sendEmail') {
        $idRef = $_REQUEST['id'];
        return prepareMail($idRef);
    }
}

function getItensFabri($id_item)
{
    $con = bancoMysqli();

    $sql = "SELECT 
                f.nome, 
                f.email, 
                pf.id,
                pf.descricao_cliente 
                FROM produto_fabricantes as pf
                INNER JOIN fabricantes AS f ON f.id = pf.fabricante_id
                WHERE item_id = $id_item";

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

function prepareMail($idRef)
{
    $con = bancoMysqli();

    $sql = "SELECT
                f.email, 
                i.num_item_licitacao AS item,
                i.unidade,
                i.quantidade,
                i.valor_estimado,
                i.quantidade * i.valor_estimado AS valor_total,
                pf.id,
                pf.descricao_cliente AS descricao
                FROM produto_fabricantes as pf
                INNER JOIN licitacao_itens AS i ON i.id = pf.item_id
                INNER JOIN fabricantes AS f ON f.id = pf.fabricante_id
                WHERE pf.id = $idRef";

    //echo $sql; exit;

    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) > 0) {
        $infos = mysqli_fetch_assoc($query);

        $body = "<p>Dantas, bom dia.</p>
                    <p>Segue em anexo o Edital referente ao pregão em assunto.</p>
                    <p>Abaixo o item e a estimativa de preço.</p><br>

                    <table width='950' style='text-align: center; font-size: 15px; border: 1px solid black;'>
                        
                         <tr>
                            <td width='10%' style='background: #ff9d00'>ORGAO</td>
                            <td style='background: #ff9d00' colspan='5'><b>TOTAL GLOBAL</b></td>                           
                        </tr>
                        <tr>
                            <td style='background: #ff9d00'>PREGRAO</td>
                            <td style='background: #ff9d00' colspan='5'><b>TOTAL GLOBAL</b></td>                           
                        </tr>
                        <tr>
                            <td style='background: #ff9d00'>DATA E HORA PREVISTA PARA A LICITACAO</td>
                            <td style='background: #ff9d00' colspan='5'><b>28/02/2020 as 15:40 h</b></td>                           
                        </tr>
                        
                        <tr>
                            <td style='background: #f5f5f5;'>Item</td>
                            <td style='background: #f5f5f5;'>Descricao</td>
                            <td style='background: #f5f5f5;'>Unidade de Fornecimento</td>
                            <td style='background: #f5f5f5;'>Quantidade</td>
                            <td style='background: #f5f5f5;'>Valor Unitario Estimado</td>
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
                    
                    <p>Solicitamos autorização para participar do referido Certame.</p>
                       <p>Grata,</p> 
                       
                        <small>--</small><br>
                        <small>Elda Silva</small><br>
                        <small>Auxiliar de Licitação</small><br>
                        <small>Futura Distribuidora de Medicamentos e Produtos de Saúde</small><br>
                        <small>Tel: 21-3311-5186</small>
                    ";
        if (sendMail('testando', $body, $infos['email'])) {
            echo json_encode(true);
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
    $Mailer->Charset = 'UTF-8';

    //Configurações
    $Mailer->SMTPAuth = true;
    $Mailer->SMTPSecure = 'ssl';

    //nome do servidor
    $Mailer->Host = $mailConfs['server_smtp'];
    //Porta de saida de e-mail
    $Mailer->Port = $mailConfs['port_smtp'];

    //Dados do e-mail de saida - autenticação
    /*$Mailer->Username = 'l.francelino@outlook.com';
    $Mailer->Password = '@clamaLUVI1873';*/
    $Mailer->Username = $mailConfs['usuario'];
    $Mailer->Password = $mailConfs['senha'];

    //E-mail remetente (deve ser o mesmo de quem fez a autenticação)
    //$Mailer->From = 'l.francelino@outlook.com';
    $Mailer->From = $mailConfs['remetente'];

    //Nome do Remetente
    $Mailer->FromName = 'ComprasNET';

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