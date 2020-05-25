<?php  

use \Hcode\PageAdmin;
use \Hcode\Model\User;

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



?>