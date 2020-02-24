<?php

function bancoMysqli()
{
	$servidor = 'localhost';
	$usuario = 'root';
	$senha = '';
	$banco = 'comprasnet_db';
	$con = mysqli_connect($servidor,$usuario,$senha,$banco);
	//mysqli_set_charset($con,"utf8");
	if (!mysqli_set_charset($con, 'utf8')) {
    printf('Error ao usar utf8: %s', mysqli_error($con));
    exit;
}
	return $con;
}



?>