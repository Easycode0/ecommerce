<?php  

use \Hcode\Page;


$app->get('/', function() {
    

	$page = new Page();///Chamando o construct que vai add o heade na tela
	$page->setTpl("index");
	

});


?>