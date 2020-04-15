<?php 

session_start();
require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;



$app = new Slim();

$app->config('debug', true);

//ta debug irar dizer os erros

$app->get('/', function() {
    

	$page = new Page();///Chamando o construct que vai add o heade na tela
	$page->setTpl("index");
	

});
$app->get('/admin', function() {
    
	User::verifyLogin();

	$page = new PageAdmin();///Chamando o construct que vai add o heade na tela

	$page->setTpl("index");
	

});

$app->get('/admin/login', function() {
	//Como não tem o Header e o footer padrão é precisso desabilitar a chamada do header e do footer do metodo setTpl//
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});

$app->post('/admin/login', function(){

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});


$app->get('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");
	exit;

});


$app->run();

 ?>