<?php  

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;


$app->get("/admin/products", function(){

		User::verifyLogin();//Verifica  login
		$products = Product::listAll();//Traz todos os produtos

		$page = new PageAdmin();
		$page->setTpl("products", [
				"products"=>$products
		]);
});

$app->get("/admin/products/create", function(){

		User::verifyLogin();

		$page = new PageAdmin();
		$page->setTpl("products-create");
});

$app->post("/admin/products/create", function(){

		User::verifyLogin();

		$product = new Product();

		$product->setData($_POST);

		$product->save();
		//var_dump($product);
		header("Location: /admin/products");
		exit;
		
});

$app->get("/admin/products/:idproduct", function($idproduct){

		User::verifyLogin();

 		$product = new Product();//carregando os dados do produto

 		$product->get((int)$idproduct);

		$page = new PageAdmin();
		$page->setTpl("products-update",[
			'product'=>$product->getValues()

		]);
});

$app->post("/admin/products/:idproduct", function($idproduct){

		User::verifyLogin();

 		$product = new Product();//carregando os dados do produto

 		$product->get((int)$idproduct);

 		$product->setData($_POST);

 		$product->save();
 					
 		$product->setPhoto($_FILES['file']);//upload da foto

 		header('Location: /admin/products');
 		exit;

	
});

$app->get("/admin/products/:idproduct/delete", function($idproduct){

		User::verifyLogin();

 		$product = new Product();//carregando os dados do produto

 		$product->get((int)$idproduct);

		$product->delete();

		header('Location: /admin/products');
 		exit;
});






?>