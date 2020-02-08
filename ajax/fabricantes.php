<?php
require_once ("../ajax/conexao.php");

if ($_REQUEST['act']) {
    $request = $_REQUEST['act'];
    if ($request == 'cadastro') {
        return saveFabri($_REQUEST);
    }

}


function saveFabri($request)
{
    $nome = $request['nome'];
    $email = $request['email'];

    $con = bancoMysqli();

    $sql = "INSERT INTO fabricantes (nome_completo, email) VALUES ('$nome', '$email')";

    if (mysqli_query($con, $sql)) {

        return true;
    } else {
        echo $sql;
        return false;
    }

}