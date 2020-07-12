<?php  

namespace Hcode;

use Rain\Tpl;

class Mailer{
	const USERNAME = 'nadaalvesgaw@gmail.com';
	const PASSWORD = 'danda123456';
	const NAME_FROM = 'Hcode Store';

	PRIVATE $mail;
	public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
	{
					/**
			 * Este exemplo mostra as configurações a serem usadas ao enviar pelos servidores do Gmail do Google.
			 * Isso usa autenticação tradicional de identificação e senha - veja o gmail_xoauth.phps
			 * exemplo para ver como usar o XOAUTH2.
			 * A seção IMAP mostra como salvar esta mensagem na pasta 'E-mail enviado' usando comandos IMAP.
			 */

			// Importar classes do PHPMailer para o espaço de nomes global
			//require_once 'vendor/autoload.php';
			//use  PHPMailer \ PHPMailer \ PHPMailer;

			$config = array(

				"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
				"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
				"debug"         => false // set to false to improve the speed
			 );

	   		Tpl::configure( $config );

       		$tpl =  new Tpl;

       		foreach ($data as $key => $value) {
       					$tpl->assign($key,$value);
       				}

       		$html = $tpl->draw($tplName, true);


			// Crie uma nova instância do PHPMailer
			$this->mail = new \PHPMailer;

			// Diga ao PHPMailer para usar SMTP
			$this->mail->isSMTP();


			// Ativar depuração SMTP
			// SMTP :: DEBUG_OFF = desativado (para uso em produção)
			// SMTP :: DEBUG_CLIENT = mensagens do cliente
			// SMTP :: DEBUG_SERVER = mensagens do cliente e do servidor
			$this->mail->SMTPDebug = 0;

			// Define o nome do host do servidor de email
			$this->mail->Host='smtp.gmail.com';
			// usar
			//$this->mail -> Host = gethostbyname('smtp.gmail.com');
			// se sua rede não suportar SMTP sobre IPv6

			// Defina o número da porta SMTP - 587 para TLS autenticado, também conhecido como envio SMTP RFC4409
			$this->mail->Port = 587 ;

			// Defina o mecanismo de criptografia a usar - STARTTLS ou SMTPS
			$this->mail->SMTPSecure = "tls";//PHPMailer :: ENCRYPTION_STARTTLS;
			// Se deve usar autenticação SMTP
			$this->mail->SMTPAuth = true ;

			// Nome de usuário a ser usado para autenticação SMTP - use o endereço de e-mail completo para o gmail'nadaalvesgaw@gmail.com'
			$this->mail->Username =   Mailer::USERNAME;

			// Senha a serusada para autenticação SMTP 'danda123456'
			$this->mail->Password =	 Mailer::PASSWORD;

			// Define de quem a mensagem deve ser enviada
			$this->mail-> setFrom (Mailer::USERNAME , Mailer::NAME_FROM);

			// Defina um endereço de resposta alternativo
			$this->mail->addReplyTo ( Mailer::USERNAME , 'Recebir o curso de php 7');

			// Defina para quem a mensagem deve ser enviada
			$this->mail->addAddress ($toAddress, $toName);

			// Define a linha de assunto
			$this->mail->Subject = $subject;

			// Leia um corpo da mensagem HTML de um arquivo externo, converta imagens referenciadas em incorporadas,
			// converte HTML em um corpo alternativo básico de texto sem formatação
			$this->mail->msgHTML ($html);

			// Substitua o corpo do texto sem formatação por um criado manualmente
			$this->mail->AltBody = 'Este é um corpo de mensagem em texto sem formatação' ;

			// Anexar um arquivo de imagem
			//$this->mail -> addAttachment ( 'images/phpmailer_mini.png' );
				// envia a mensagem, verifica se há erros
			/*if (!$this->mail->send ()) {
			    echo  'Erro na correspondência:' . $this->mail -> ErrorInfo ;
			}else{
			    echo  'Mensagem enviada!' ;
			    // Seção 2: IMAP
			    // Descomente-os para salvar sua mensagem na pasta 'E-mails enviados'.
			    if (save_mail ($this->mail)) {
			     echo "Mensagem salva!";
			    }
			}*/

			// Seção 2: IMAP
			// Os comandos IMAP requerem a extensão PHP IMAP, encontrada em: https://php.net/manual/en/imap.setup.php
			// Função para chamar que usa as funções imap _ * () do PHP para salvar mensagens: https://php.net/manual/en/book.imap.php
			// Você pode usar imap_getmailboxes ($imapStream, '/ imap / ssl', '*') para obter uma lista de pastas ou rótulos disponíveis, isso pode
			// será útil se você estiver tentando fazer isso funcionar em um servidor IMAP que não seja do Gmail.
			/*function  save_mail ( $this->mail )
			{
			    // Você pode alterar 'Email enviado' para qualquer outra pasta ou marca
			    $path = '{imap.gmail.com:993/imap/ssl}[Gmail{/Envia Mail' ;

			    // Diga ao seu servidor para abrir uma conexão IMAP usando o mesmo nome de usuário e senha que você usou para SMTP
			    $imapStream = imap_open ( $caminho , $this->mail -> Username , $this->mail -> senha );

			    $resultado = imap_append ( $imapStream , $caminho , $this->mail -> getSentMIMEMessage ());
			    imap_close ( $imapStream );

			    //return  $resultado ;*/
		
	}

	public function send()
	{

		return $this->mail->send();	
	}

}


	