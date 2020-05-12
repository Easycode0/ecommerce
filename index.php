<?php 

session_start();
require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\model\User;
use \Hcode\model\Category;



$app = new Slim();

$app->config('debug', true);

//ta debug irar dizer os erros

$app->get('/', function() {
    

	$page = new Page();///Chamando o construct que vai add o heade na tela
	$page->setTpl("index");
	

});
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

$app->get('/admin/users', function(){

	User::verifyLogin();//Metodo que verifica se a pessoa está logado

	$users = User::listAll();//metodo criado que lista todos os usuarios

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users,
	));

});
$app->get('/admin/users/create', function(){

	User::verifyLogin();//Metodo que verifica se a pessoa está logado

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

$app->get('/admin/users/:iduser/delete', function($iduser){
	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

	
});

$app->get('/admin/users/:iduser', function($iduser){
	User::verifyLogin();//Metodo que verifica se a pessoa está logado
	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

 	$page ->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));

});


/////Essa rota vai salva no banco de dados  o úsuario//
$app->post('/admin/users/create', function(){
	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header('Location: /admin/users');
	exit;
});
///Salvar a edição
$app->post('/admin/users/:iduser', function($iduser){	
	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");

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

$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTpl("categories", [
			'categories'=>$categories
	]);


});

$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");
});

$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;
});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header('Location: /admin/categories');
	exit;
});

$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		'category'=>$category->getValues()
	]);
});

$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();
	
	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;
	
});

$app->get("/categories/:idcategory", function($idcategory){
	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", [
		'category'=>$category->getValues(),
		'products'=>[]
	]);

});

$app->run();

 ?>