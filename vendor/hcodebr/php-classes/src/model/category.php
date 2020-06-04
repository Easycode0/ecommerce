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

		Category::updateFile();
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

		Category::updateFile();

	}

	public static function updateFile()
	{
		$categories = Category::listAll();//O metedo listAll traz todas as categorias que estão no banco de dados

		$html = [];//Dessa forma eu disse que a variavel html é um array

		foreach ($categories as $row) { 
			array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');//passando dados para a matriz que será mostrado no template categories-menu.html
		}

		file_put_contents($_SERVER['DOCUMENT_ROOT']  . DIRECTORY_SEPARATOR . "views" .  DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));//DIRECTORY_SEPARATOR significa uma / //// implode tranforma array em string
	}

	public function getProducts($related = true)
	{
		$sql = new Sql();

		if ($related === true) {
			return $sql->select("
				SELECT * FROM tb_products WHERE idproduct IN(
					SELECT a.idproduct FROM tb_products a
					INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);
				", [
					':idcategory'=>$this->getidcategory()
				]);
		} else {
			return $sql->select("
				SELECT * FROM tb_products WHERE idproduct NOT IN(
					SELECT a.idproduct FROM tb_products a
					INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);
				", [
				':idcategory'=>$this->getidcategory()
				]);
		} 
	}

	public function addProduct(Product $product)
	{

	$sql = new  Sql();

	$sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES(:idcategory, :idproduct)",[
		':idcategory'=>$this->getidcategory(),
		':idproduct'=>$product->getidproduct()
	]);

	}

	public function removeProduct(Product $product)
	{

	$sql = new  Sql();

	$sql->query("DELETE FROM  tb_productscategories where idproduct =  :idproduct and idcategory = :idcategory"  ,[
		':idcategory'=>$this->getidcategory(),
		':idproduct'=>$product->getidproduct()
	]);
	var_dump($product);

}

			
}




?>