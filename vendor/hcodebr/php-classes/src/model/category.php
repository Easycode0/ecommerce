<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;



/**
 * 
 */
class Category extends Model
{
	
	//listando usúarios
	public static function listAll()
	{
		$sql = new Sql();
		//Aqui foi buscado dados da tabela users e persons pera chave estrangeira idperson
		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
	}

	public function save()
	{
		//ESE METODO NÃO PODER SER STATIC PORQUE AS INFORMAÇOES MUDAM

		$sql = new Sql();
		//Vamos usar uma procedure para pega o id da tabela tb_person e depois jogar na tabele tb_users e puxar os dados da mesma e cadastrar na tb_persons
		$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory()
	
		));

		$this->setData($results[0]);
	}

	public function get($idcategory)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory",[
			":idcategory"=>$idcategory
		]);

		$this->setData($results[0]);

	}
	public function delete()
	{	
		$sql = new Sql();

		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory",[
			":idcategory"=>$this->getidcategory()
		]);

	}

			
}




?>