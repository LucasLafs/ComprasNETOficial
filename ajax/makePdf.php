<?php
require ("conexao.php");
require ("../vendor/autoload.php");


if($_REQUEST['act']){
  if ($_REQUEST['act'] == 'createPdf'){
    $idLicitacao = $_REQUEST['lic_id'];
    return preparePdf($idLicitacao);


  }
}

function preparePdf($idLicitacao)
{
  $con = bancoMysqli();

  $sql = "SELECT * FROM licitacoes_cab AS li
                        LEFT JOIN licitacao_itens AS i ON i.lic_id = li.identificador
                        WHERE li.identificador = $idLicitacao";

  $query = mysqli_query($con, $sql);

  $infos = [];

  while($itens = mysqli_fetch_assoc($query)) {
    $infos[] = $itens;

   /* echo "<pre>";
    print_r($infos);
    echo "</pre>";*/
  }

  makePdfBody($infos);


}


function makePdfBody($data)
{



  $html = "<fieldset>";
  $html .= "<h1>Dados Licitação: " . $data['identificador']. "</h1>";

  foreach ($data AS $campo => $value) {

    print_r($campo);
    print_r($value);
    //$html .= "<p><b>$campo: </b>$value</p>";


  }

  $html .= "</fieldset>";

  echo "<pre>";
  echo($html);
  echo "</pre>";

  exit;



/*      $html = "
    <fieldset>
    <h1>Recibo de Pagamento</h1>
    <p class='center sub-titulo'>
    Nº <strong>0001</strong> -
    VALOR <strong>R$ 700,00</strong>
    </p>
    <p>Recebi(emos) de <strong>Ebrahim Paula Leite</strong></p>
    <p>a quantia de <strong>Setecentos Reais</strong></p>
    <p>Correspondente a <strong>Serviços prestados ..<strong></p>
    <p>e para clareza firmo(amos) o presente.</p>
    <p class='direita'>Itapeva, 11 de Julho de 2017</p>
    <p>Assinatura ......................................................................................................................................</p>
    <p>Nome <strong>Alberto Nascimento Junior</strong> CPF/CNPJ: <strong>222.222.222-02</strong></p>
    <p>Endereço <strong>Rua Doutor Pinheiro, 144 - Centro, Itapeva - São Paulo</strong></p>
    </fieldset>
    <div class='creditos'>
    </div>
    ";*/

  $mpdf= new \Mpdf\Mpdf();
  $mpdf->SetDisplayMode('fullpage');
  $css = file_get_contents("../layout/css/geral.css");
  $mpdf->WriteHTML($css,1);
  $mpdf->WriteHTML($html);
  $mpdf->Output();


  exit;
}


