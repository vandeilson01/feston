<?php
namespace App\Libraries;

use CodeIgniter\Libraries; 

// Importar as classes 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carregar o autoloader do composer
//require 'vendor/autoload.php';


class PHPMailerLib {

    //public function load(){
        //require_once APPPATH.'ThirdParty/PHPMailer/src/Exception.php';
        //require_once APPPATH.'ThirdParty/PHPMailer/src/PHPMailer.php';
        //require_once APPPATH.'ThirdParty/PHPMailer/src/SMTP.php';
        
        //$mail = new PHPMailer;
        //return $mail;
    //}

    private $mailer;

    public function __construct()
	{
		$this->mailer = new PHPMailer(true);
    }


	public function send( $args = array() )
	{
		/*
		 * CONFIG PHPMAILER
		**/
			$config = new \Config\AppSettings();
			//$cfg_info_base = $config->getInfosBase();
			//$cfg_info_smpt = $config->getInfosSMTP();

			$cfg_info_base = [];

			$site_name = "JAFeston"; //(isset($cfg_info_base["site_name"]) ? $cfg_info_base["site_name"] : "");


			// ------------------------------------------------
			// gmail
			// ------------------------------------------------			
			$lbl_email		= 'xxxxxxxxxxxxxxxxxxx';		// email: sender
			$lbl_host		= 'xxxxxxxxxxxxxxxxxxx';		// host
			$lbl_user		= 'xxxxxxxxxxxxxxxxxxx';		// conta para autenticação
			$lbl_password	= 'xxxxxxxxxxxxxxxxxxx';		// senha de autenticação


			$lbl_port = '587';
			$lbl_ssl = false;
			$lbl_tls = true;

			$cfg_info_smpt = [
				"sender_name" => $site_name,
				"sender_mail" => $lbl_email,
				"smtp_host" => $lbl_host,
				"smtp_port" => $lbl_port,
				"smtp_user" => $lbl_user,
				"smtp_pass" => $lbl_password,
				"smtp_auth" => true,
				"smtp_ssl" => (($lbl_ssl == 1) ? true : false),
				"smtp_tls" => (($lbl_tls == 1) ? true : false),
				"mail_debug" => true,
				"test_localhost" => false,
			];


			$enviar_para = array();
			$enviar_para_temp = (isset($args["enviar_para"]) ? $args["enviar_para"] : array());
			$enviar_para = array_merge($enviar_para, $enviar_para_temp);
			$enviar_para = array_unique($enviar_para);

			$anexos = (isset($args["anexos"]) ? $args["anexos"] : array());

			$dataParser = [
				'base_url'			=> base_url(),
				'site_url'			=> site_url(),
				'data_envio'		=> date("d/m/Y H:i:s"),
				'ip_visitante'		=> $_SERVER["REMOTE_ADDR"]
			];
			$dataParser = array_merge($dataParser, $cfg_info_base);

			$fileFields = (isset($args["fields"]) ? $args["fields"] : array());
			$dataParser = array_merge($dataParser, $fileFields);

			$strSubject = (isset($args["subject"]) ? $args["subject"] : "");
			$strSubject = "[". $site_name ."] : ". $strSubject; 

			$strBody = "<!-- não informado -->";
			$template = (isset($args["template"]) ? $args["template"] : "");
			if( !empty($template) )
			{
				//$parser = new \CodeIgniter\View\Parser();
				$parser = \Config\Services::parser();

				//$strBody = $parser->setData($dataParser)->render('emails/cadastro');
				//$strBody = $parser->render('emails/cadastro', $dataParser);
				$strBody = $parser->setData($dataParser)->render($template);
			}
			$body = (isset($args["body"]) ? $args["body"] : "");
			if( !empty($body) )
			{
				$strBody = $body;
			}


		//try {
			$mail = $this->mailer;
			$mail->CharSet = 'UTF-8';
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			foreach ($enviar_para as $userEmail)
			{
				if( $cfg_info_smpt['smtp_auth'] == true ){
					$mail->isSMTP();
					$mail->SMTPAuth = true;
					if( $cfg_info_smpt['smtp_tls'] == true){ $mail->SMTPSecure = 'tls'; };
					if( $cfg_info_smpt['smtp_ssl'] == true){ $mail->SMTPSecure = 'ssl'; };
					$mail->Host = $cfg_info_smpt['smtp_host'];
					$mail->Port = $cfg_info_smpt['smtp_port'];

					$mail->Username = $cfg_info_smpt['smtp_user'];
					$mail->Password = $cfg_info_smpt['smtp_pass'];
				}else{
					$mail->isMail();
				}


				$SMTPDebug = 0;
				//$SMTPDebug = (($cfg_info_smpt['mail_debug'] == true) ? 2 : 0);
				$mail->SMTPDebug = $SMTPDebug;
				$mail->Debugoutput = 'html';


				//Recipients
				$mail->setFrom($cfg_info_smpt['sender_mail'], $cfg_info_smpt['sender_name']);

				$mail->addAddress($userEmail, $userEmail);
				$mail->addReplyTo($userEmail, $userEmail);

				//$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
				//$mail->addAddress('ellen@example.com');               //Name is optional
				//$mail->addReplyTo('info@example.com', 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
				foreach($anexos as $valAnexo){
					$mail->addAttachment($valAnexo);
				}

				//Content
				$mail->isHTML(true);                                  //Set email format to HTML
				$mail->Subject = $strSubject; //." | ". date("d/m/Y H:i:s");
				$mail->Body = $strBody;
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				if ( !$mail->send() ) {
					//echo 'error';
				}else{
					//echo 'success '. $userEmail;
					//echo( $strBody );
				}
			}

		//} catch (Exception $e) {

			//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

		//}

	}

}