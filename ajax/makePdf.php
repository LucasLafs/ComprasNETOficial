<?php
require ("conexao.php");
require ("../vendor/autoload.php");


if($_REQUEST['act']){
  $idLicitacao = $_REQUEST['lic_id'];
  prepare($idLicitacao);
}

function prepare($idLicitacao)
{
  $con = bancoMysqli();

  $infosLic = "SELECT * FROM licitacoes_cab WHERE identificador = $idLicitacao";

  //echo $infosLic;
  $queryLic = mysqli_query($con, $infosLic);

  $lic = mysqli_fetch_object($queryLic);

  $sql = "SELECT * FROM licitacao_itens AS i WHERE i.lic_id = $idLicitacao";

  $query = mysqli_query($con, $sql);

  $infos['infosLic'] = $lic;

  while($itens = mysqli_fetch_object($query)) {
    $infos['itens'][] = $itens;
  }


  if ($_REQUEST['act'] == 'createPdf'){
    return makePdfBody($infos);
  } else if ($_REQUEST['act'] == 'imprimir') {
    echo imprimir($infos);
  }

}


function makePdfBody($data)
{
  $html = "<fieldset>";
  $html .= "<h1>Licitação: " . $data['infosLic']->identificador. "</h1>";

  foreach ($data['infosLic'] AS $campo => $value) {

    $newString = $campo;

    if (strpos($campo, '_')) {
      $parts = explode('_', $campo);
      $newString = ucfirst($parts[0]) . " ";
      $newString .= ucfirst($parts[1]);

      if (isset($parts[2])) {
        $newString .= " " . ucfirst($parts[2]);
      }
    }

    $campo = ucfirst($newString);

    $html .= "<p><b>$campo: </b>$value</p>";
  }


  $html .= "<br><h1>Itens da Licitação </h1>";

  foreach ($data['itens'] AS $campos) {

    foreach ($campos AS $field => $value) {

      $newString = $field;

      if (strpos($field, '_')) {
        $parts = explode('_', $field);
        $newString = ucfirst($parts[0]) . " ";
        $newString .= ucfirst($parts[1]);

        if (isset($parts[2])) {
          $newString .= " " . ucfirst($parts[2]);
        }
      }

      $field = ucfirst($newString);


      $html .= "<p><b>$field: </b>$value</p>";
    }
  }

  //exit;



  $html .= "</fieldset>";

  //echo $html;


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

function imprimir ($data)
{

  $html = "<style>@media print {
@page { margin: 0; }
body { margin: 1.6cm; }
}</style>";

  $html .= "<fieldset>";
  $html .= "<h1>Licitação: " . $data['infosLic']->identificador. "</h1>";

  foreach ($data['infosLic'] AS $campo => $value) {

    $newString = $campo;

    if (strpos($campo, '_')) {
      $parts = explode('_', $campo);
      $newString = ucfirst($parts[0]) . " ";
      $newString .= ucfirst($parts[1]);

      if (isset($parts[2])) {
        $newString .= " " . ucfirst($parts[2]);
      }
    }

    $campo = ucfirst($newString);

    $html .= "<p><b>$campo: </b>$value</p>";
  }


  $html .= "<br><h1>Itens da Licitação </h1>";

  foreach ($data['itens'] AS $campos) {

    foreach ($campos AS $field => $value) {

      $newString = $field;

      if (strpos($field, '_')) {
        $parts = explode('_', $field);
        $newString = ucfirst($parts[0]) . " ";
        $newString .= ucfirst($parts[1]);

        if (isset($parts[2])) {
          $newString .= " " . ucfirst($parts[2]);
        }
      }

      $field = ucfirst($newString);


      $html .= "<p><b>$field: </b>$value</p>";
    }
  }

  $html .= "</fieldset>";

  echo $html;


  echo "<script type='text/javascript'>print()</script>";

  exit;

}


