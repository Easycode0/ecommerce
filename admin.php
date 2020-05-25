<?php  

use \Hcode\PageAdmin;
use \Hcode\model\User;


$app->get('/admin', function() {
    
	User::verifyLogin();//Metodo que verifica se a pessoa está logado

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

//Rota da pagina esquece a senha
$app->get('/admin/forgot', function(){
	//Renderizando template do forgot
	$page = new PageAdmin([
		"header" =>false,
		"footer" =>false

	]);	

	$page->setTpl("forgot");


});
//Enviando o email que o úsuario vai digitar via post
$app->post('/admin/forgot', function(){

	//$_POST['email'];
	//Chamando o metodo que verifica o email
	$user = User::getForgot($_POST['email']);

	header("Location: /admin/forgot/sent");
	exit;
});

$app->get("/admin/forgot/sent", function(){
	//Renderizando template do forgot_sent
	$page = new PageAdmin([
		"header" =>false,
		"footer" =>false

	]);	

	$page->setTpl("forgot-sent");
});

$app->get("/admin/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET['code']);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("forgot-reset",array(
		"name"=>$user['desperson'],
		"code"=>$_GET['code']

	));
});

$app->post("/admin/forgot/reset", function()
{

	$forgot = User::validForgotDecrypt($_POST['code']);

	User::setForgotUsed($forgot['idrecovery']);

	$user = new User();

	$user->get((int)$forgot['iduser']);

	$password = password_hash($_POST['password'], PASSWORD_DEFAULT, [
			"cost"=>12//cost é o tanto de processamento que o servido vai usar para criar o hash da senha
	]);

	$user->setPassword($password);


	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("forgot-reset-success");
});



?>