<?php  
//Classe usada para gerenciar telas
//Especificando onde a clase está
namespace Hcode;



use Rain\Tpl;

////////Criando a classe

class Page{

    private $tpl;
    private $options = [];
    private $defaults = [
        "header"=>true,
        "footer"=>true,
        "data"=>[]


    ];

	public function __construct($opts = array(), $tpl_dir = "/views/"){
        //Função array_marge é usada para sobrescrever array
        $this->options = array_merge($this->defaults, $opts);

	   $config = array(

				"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
				"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
				"debug"         => false // set to false to improve the speed
			 );

	   Tpl::configure( $config );

       $this->tpl =  new Tpl;

       /*foreach ($this->options["data"] as $key => $value) {
           $this->tpl->assign($key, $value);
       }*/


       $this->setData($this->options["data"]);

       //desenhando tamplate na tela do header
      if($this->options["header"] === true) $this->tpl->draw("header");


	}

    private function setData($data = array()){
        //Essa função passa os dados para o tamplete
        foreach ($data as $key => $value) {
           $this->tpl->assign($key, $value);
       }
    }

    public function setTpl($name, $data = array(), $returnHTML = false){
         //Chamando o metodo setData
        $this->setData($data);
        //Desenhando template de conteudo
       return $this->tpl->draw($name, $returnHTML);
    }

	public function __destruct(){

        //desenhando template do footer
        if($this->options["footer"] === true) $this->tpl->draw("footer");
	}
}


?>