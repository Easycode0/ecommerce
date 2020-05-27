<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;



/**
 * 
 */
class Product extends Model
{
	
	//listando usúarios
	public static function listAll()
	{
		$sql = new Sql();
		//Aqui foi buscado dados da tabela products e é ordenado pela coluna desproduct
		return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
	}

	public static function checkList($list)
		{

			foreach ($list as &$row) {
				
				$p = new Product();
				$p->setData($row);
				$row = $p->getValues();

			}

			return $list;

		}

	public function save()
	{
		//ESE METODO NÃO PODER SER STATIC PORQUE AS INFORMAÇOES MUDAM

		$sql = new Sql();
		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(

				":idproduct"=>$this->getidproduct(),
				":desproduct"=>$this->getdesproduct(),
				":vlprice"=>$this->getvlprice(),				
				":vlwidth"=>$this->getvlwidth(),
				":vlheight"=>$this->getvlheight(),
				":vllength"=>$this->getvllength(),
				":vlweight"=>$this->getvlweight(),
				":desurl"=>$this->getdesurl()


			));

		$this->setData($results[0]);
		//var_dump($results);

	}

	public function get($idproduct)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct",[
			":idproduct"=>$idproduct
		]);

		$this->setData($results[0]);

	}
	public function delete()
	{	
		$sql = new Sql();

		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct",[
			":idproduct"=>$this->getidproduct()
		]);

	}
	public function checkPhoto()
	{

		if (file_exists(
           $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
           "res" . DIRECTORY_SEPARATOR .
           "site" . DIRECTORY_SEPARATOR .
           "img" . DIRECTORY_SEPARATOR .
           "products" . DIRECTORY_SEPARATOR .
           $this->getidproduct() . ".jpg"
		)) {
			$url =  "/res/site/img/products/" . $this->getidproduct() . ".jpg";
		}else{
			$url = "/res/site/img/products/product.jpg";
		}
		return $this->setdesphoto($url);
	}
	public function getValues()
	{

		$this->checkPhoto();//chamando o metodo que verifica se tem a foto
		$values = parent::getValues();

		return $values;
	}
	public function setPhoto($file)
	{

		$extension = explode('.', $file['name']);//Verificano o timpo da img 
		$extension = end($extension);//End para dizer que a extensão é só a ultima posição do array 

		switch ($extension) {
			case 'jpg':
			case 'jpeg':
				$image = imagecreatefromjpeg($file['tmp_name']);
			break;

			case 'gif':
				$image = imagecreatefromgif($file['tmp_name']);	
			break;

			case 'png':
				$image = imagecreatefrompng($file['tmp_name']);	
			break;	
		}

		$destinoImg =  $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
           "res" . DIRECTORY_SEPARATOR .
           "site" . DIRECTORY_SEPARATOR .
           "img" . DIRECTORY_SEPARATOR .
           "products" . DIRECTORY_SEPARATOR .
           $this->getidproduct() . ".jpg";

         imagejpeg($image, $destinoImg);

         imagedestroy($image);		

         $this->checkPhoto();

	}
		
}




?>