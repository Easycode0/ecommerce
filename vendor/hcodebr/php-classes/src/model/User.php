<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;


/**
 * 
 */
class User extends Model
{
	const SESSION = "User";
	
	public static function login($login, $password)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login

		));
		//Verificando se foi encontrado um login
		if (count($results)===0){
			throw new \Exception("Usúario inexistente ou senha invalida.");
		}
		$data = $results[0];
		//Verifica se o hash fornecido corresponde com o password fornecido.
		if(password_verify($password, $data["despassword"])===true){
			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();//constante
			return $user;

		} else{
			throw new \Exception("Usúario inexistente ou senha invalida.");
		}
	}

	public static function verifyLogin($inadmin = true)
	{
		if (!isset($_SESSION[User::SESSION]) 
			||
			 !$_SESSION[uSER::SESSION]
			||
			 !(int)$_SESSION[User::SESSION]["iduser"]>0 
			||(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin) {
			header("Location: /admin/login");
			exit;
		}
	}
	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;//Desssa forma limpa só a session do usuario logado
	}
}




?>