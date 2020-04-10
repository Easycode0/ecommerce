<?php 


require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;



$app = new Slim();

$app->config('debug', true);//ta debug irar dizer os erros

$app->get('/', function() {
    

	$page = new Page();///Chamando o construct que vai add o heade na tela

	$page->setTpl("index");
	

});

$app->run();

 ?>