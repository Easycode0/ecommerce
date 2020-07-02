<?php  
use \Hcode\Model\User;
use \Hcode\Model\Cart;

function formatPrice($vlprice)
{

	if (!$vlprice > 0) $vlprice = 0;

	return number_format($vlprice, 2, ",", ".");

}

//checando o Login
function checkLogin($inadmin = true)
{
	return User::checkLogin($inadmin);

}

//Pega o nome do usuario logado
function getUserName()
{

	$user = User::getFromSession();

	return $user->getdesperson();
}
?>