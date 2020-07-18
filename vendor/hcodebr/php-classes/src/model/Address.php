<?php  

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

/**
 * 
 */
class Address extends Model
{	
	const SESSION_ERROR = "AddressError";

	public static function getCEP($nrcep)
	{
		///webservice de cep
		$nrcep = str_replace("-", " ", $nrcep);

		//Informa o php que o sistema irar rastreia uma url
		$ch = curl_init();

		//Opções pela qual ele ira fazer a chamada

		curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$data = json_decode(curl_exec($ch), true);

		curl_close($ch);	

		return $data;
	}

	public function loadFromCEP($nrcep)
	{
		$data = Address::getCEP($nrcep);

		if (isset($data['logradouro']) && $data['logradouro']) {
			
			$this->setdesaddress($data['logradouro']);
			$this->setdescomplement($data['complemento']);
			$this->setdesdistrict($data['bairro']);
			$this->setdescity($data['localidade']);
			$this->setdesstate($data['uf']);
			$this->setdescountry('Brasil');
			$this->setdeszipcode($nrcep);

		}
	}

	public function save()
	{	
		$sql =  new Sql();

		$results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :descomplement, :descity, :desstate,
		:descountry, :deszipcode, :desdistrict)", [
			':idaddress'=>$this->getidaddress(),
			':idperson'=>$this->getidperson(),
			':desaddress'=>utf8_decode($this->getdesaddress()),
			':descomplement'=>utf8_decode($this->getdescomplement()),
			':descity'=>utf8_decode($this->getdescity()),
			':desstate'=>utf8_decode($this->getdesstate()),
			':descountry'=>utf8_decode($this->getcountry()),
			':deszipcode'=>$this->getdeszipcode(),
			':desdistrict'=>$this->getdesdistrict(),
		]);

		if (count($results)>0) {
			$this->setData($results[0]);
		}  
	}

	//Inserindo mensagens de error 
		public static function setMsgError($msg)
		{

			$_SESSION[Address::SESSION_ERROR] = $msg;

		}

		//Pegando mensagem de error
		public static function getMsgError()
		{
			// Validando mensegens de error
			$msg =  (isset($_SESSION[Address::SESSION_ERROR])) ? $_SESSION[Address::SESSION_ERROR] : "";
			// Para o erro não ficar pra sempre sendo  mostrado no carrinho 
			Address::clearMsgError();

			return $msg;

		}

		//Limpando mensagem de error
		public static function clearMsgError()
		{

			$_SESSION[Address::SESSION_ERROR] = NULL;

		}

			
}




?>