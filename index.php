<?php 

require_once("vendor/autoload.php");

$app = new \Slim\Slim();

$app->config('debug', true);//ta debug irar dizer os erros

$app->get('/', function() {
    //Chamando classe sql
	$sql = new Hcode\DB\Sql();

	//executando query

	$results = $sql->select("SELECT * FROM tb_users");

	echo json_encode($results);

});

$app->run();

 ?>