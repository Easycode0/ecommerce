<?php  
namespace Hcode;

class Model{
	//atributo values
	private $values = [];

	public function __call($name, $args)
	{
		// substrretorna a parte stringespecificada pelos parâmetros starte length.
		$method = substr($name, 0, 3);//usado para saber se e o metodo set ou get
		$fieldName = substr($name, 3, strlen($name));//Retorna o comprimento do dado string.

		switch ($method) {
			case 'get':
				return $this->values[$fieldName];
				break;
			case 'set':
			//args é o valor passado no produto
				$this->values[$fieldName] = $args[0];
				break;
			
		}



	}

	public function setData($data = array())
	{
		foreach ($data as $key => $value) {
			$this->{"set".$key}($value);
		}
	}

	public function getValues(){
		return $this->values;
	}
}


?>