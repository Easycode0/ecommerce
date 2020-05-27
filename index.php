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

require_once("functions.php");
require_once("site.php");//Toda rota que tem a ver com o site vai ficar nesse arquivo
require_once("admin.php");
require_once("admin-users.php");
require_once("admin-categories.php");
require_once("admin-products.php");

$app->run();

 ?>