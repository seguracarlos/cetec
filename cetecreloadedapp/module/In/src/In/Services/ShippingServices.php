<?php
namespace In\Services;

use In\Model\ShippingModel;
use In\Model\UsersShippingModel;

use Zend\Mail;
//use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\SmtpOptions;

class ShippingServices
{
	private $shippingModel;
	private $usersShippingModel;
	
	/**
	 * @return \In\Model\ShippingModel
	 */
	public function getModel()
	{
		return $this->shippingModel = new ShippingModel();
	}
	
	/**
	 * @return \In\Model\UsersShippingModel
	 */
	public function getUsersShippingModel()
	{
		return $this->usersShippingModel = new UsersShippingModel();
	}
	
	/*
	 * Obtemos todos los registros
	 */
	public function fetchAll()
	{
		$rows = $this->getModel()->fetchAll();
		return $rows;
	}
	
	/*
	 * Obtener registro por id
	 */
	public function getRowById($id)
	{
		$id_operator      = "";
		$id_assistant     = "";
		$amount_operator  = "";
		$amount_assistant = "";
		$row              = $this->getModel()->getRowById($id);
		//echo "<pre>"; print_r($row);
		
			/*foreach ($row as $r2 ){
				if ($r2['type_user'] != 1){
					$ayudantes[] = array(
							'id_ayudante' => $r2['id_user']
					);
				}
			}*/

		//echo "<pre>"; print_r($ayudantes);
		
		foreach ($row as $r){
			
			if ($r['type_user'] == 1){
				$id_operator     = $r['id_user'];
				$amount_operator = $r['amount'];
				$ayudantes = array();
			}else if($r['type_user'] == 2){
				$ayudantes[] = array(
						'id_ayudante' => $r['id_user']
				);
				//$id_assistant     = $r['id_user'];
				$amount_assistant = $r['amount'];
			}
			
			$data = array(
					"id_shipping"          => $r['id_shipping'],
            		"company_ID"           => $r['company_ID'],
            		"id_destination"       => $r['id_destination'],
			        "promotional_finished" => $r['promotional_finished'],
			        "start_date"           => $r['start_date'],
					"end_date"             => $r['end_date'],
			        "description"          => $r['description'],
			        "id_truck"             => $r['id_truck'],
			        "chilled_dry"          => $r['chilled_dry'],
			        "type_destination"     => $r['type_destination'],
			        "direct_route"         => $r['direct_route'],
					"detail_table"         => $r['detail_table'],
					"detail_table2"        => $r['detail_table2'],
					"citys_delivery"       => $r['citys_delivery'],
					"consolidated"     => $r['consolidated'],
					
					"id_operator"      => $id_operator,
					"amount_operator"  => $amount_operator,
					"ids_assistants"   => json_encode($ayudantes),
					"amount_assistant" => $amount_assistant,
					
					"client_folio"     => $r['client_folio'],
					"starting_mileage" => $r['starting_mileage'],
					
					"internal_folio"   => str_pad($r['internal_folio'], 6, "0", STR_PAD_LEFT),
					"type_gasoline"    => $r['type_gasoline'],
					"place_origin"    => $r['place_origin'],
					
			);
		}
		//echo "<pre>"; print_r($data); exit;
		return $data;
	}
	
	
	/*
	 * Obtener detalle de registro por id
	 */
	public function getDetailRowById($id)
	{
		$id_operator      = "";
		$id_assistant     = "";
		$amount_operator  = "";
		$amount_assistant = "";
		$name_operator    = "";
		$name_assistant   = "";
		$row              = $this->getModel()->getDetailRowById($id);
	
		//echo "<pre>"; print_r($row);
		foreach ($row as $r){
				
			if ($r['type_user'] == 1){
				$id_operator     = $r['id_user'];
				$amount_operator = $r['amount'];
				$name_operator   = $r['name']." ".$r['surname']." ".$r['lastname'];
			}else if($r['type_user'] == 2){
				$id_assistant     = $r['id_user'];
				$amount_assistant = $r['amount'];
				$name_assistant   = $r['name']." ".$r['surname']." ".$r['lastname'];
			}
				
			$data = array(
					"id_shipping"          => $r['id_shipping'],
					"company_ID"           => $r['company_ID'],
					"id_destination"       => $r['id_destination'],
					"promotional_finished" => $r['promotional_finished'],
					"start_date"           => $r['start_date'],
					"description"          => $r['description'],
					"id_truck"             => $r['id_truck'],
					"chilled_dry"          => $r['chilled_dry'],
					"type_destination"     => $r['type_destination'],
					"direct_route"         => $r['direct_route'],
					"cost_journey"         => $r['cost_journey'],
						
					"id_operator"      => $id_operator,
					"amount_operator"  => $amount_operator,
					"name_operator"    => $name_operator,
					
					"id_assistant"     => $id_assistant,
					"amount_assistant" => $amount_assistant,
					"name_assistant"    => $name_assistant,
					
					"name_company"            => $r['name_company'],
					"name_destination"        => $r['name_destination'], 
					"description_destination" => $r['description_destination'],
			);
		}
		//echo "<pre>"; print_r($data); exit;
		return $data;
	}
	
	/*
	 * Agregar
	 */
	public function addRow($data)
	{	//echo "<pre>"; print_r($data); 
		// Creamos arreglo con la informacion del viaje
		$shipping      = $this->createShippingInfo($data);
		//echo "<pre>"; print_r($shipping); exit;
		// Guardamos la orden de envio
		$saveShapping  = $this->getModel()->addRow($shipping);
		
		// Creamos arreglo con los usuarios del viaje
		$usersShipping = $this->createUsersShipping($data, $saveShapping);
		//echo "<pre>"; print_r($usersShipping); exit;
		// Guardamos los usuarios del viaje
		$saveUsersShip = $this->getUsersShippingModel()->addRow($usersShipping);
			
		return $saveShapping;
	}
	
	/*
	 * Modificar
	 */
	public function edidtRow($data)
	{	
		//echo "<pre>"; print_r($data);
		// Creamos arreglo con la informacion del viaje
		$shipping     = $this->createShippingInfo($data);
		//echo "<pre>"; print_r($shipping);
		$editShapping = $this->getModel()->editRow($shipping);
		
		// Elimiminamos usuarios actuales y agregamos mas
		//$this->getUsersShippingModel()->deleteRow((int) $data['id_shipping']);
		//$usersShipping = $this->createUsersShipping($data, $data['id_shipping']);
		//echo "<pre>"; print_r($usersShipping);exit;
		//$saveUsersShip = $this->getUsersShippingModel()->addRow($usersShipping);
			
		return $editShapping;
	}
	
	/*
	 * Eliminar
	 */
	public function deleteRow($id)
	{
		$idRow     = (int) $id;
		$deleteRow = $this->getModel()->deleteRow($idRow);
		return $deleteRow; 
	}
	
	/*
	 * Obtenemos proyectos por id de compania
	 */
	public function getAllProjectsByIdCompany($id)
	{
		$idC  = (int) $id;
		$rows = $this->getProjectsModel()->getAllProjectsByIdCompany($idC);
		return $rows;
	}
	
	/*
	 * Datos del viaje
	 */
	public function createShippingInfo($data)
	{
		//echo "<pre>"; print_r($data); 
		$shipp = array(
				"id_shipping"          => $data['id_shipping'],
				"company_ID"           => $data['company_ID'],
				"id_destination"       => $data['id_destination'],
				"promotional_finished" => $data['promotional_finished'],
				"start_date"           => \Util_Tools::dateDBFormat("/", $data['start_date']),
				"description"          => $data['description'],
				"id_truck"             => $data['id_truck'],
				"chilled_dry"          => $data['chilled_dry'],
				"type_destination"     => $data['type_destination'],
				"direct_route"         => $data['direct_route'],
				//"cost_journey"         => \Util_Tools::notCurrencyOutPoint($data['cost_journey']),
				"detail_table"         => $data['detail_table'],
				"detail_table2"        => $data['detail_table2'],
				"citys_delivery"       => $data['citys_delivery'],
				"consolidated"         => $data['consolidated'],
				
				"client_folio"         => $data['client_folio'],
				"starting_mileage"     => $data['starting_mileage'],
				"end_date"             => (isset($data['end_date'])) ? $data['end_date'] : "0000-00-00",
				
				"internal_folio"	   => (isset($data['internal_folio']) ? $data['internal_folio'] : 0),
				"type_gasoline"	       => (isset($data['type_gasoline']) ? $data['type_gasoline'] : 0),
				"place_origin"	       => (isset($data['place_origin']) ? $data['place_origin'] : 0)
		);
		//echo "<pre>"; print_r($shipp); exit;
		return $shipp;
	}
	
	/*
	 * Creamos arreglo con los datos de los usuarios
	 */
	private function createUsersShipping($data, $id_shipping)
	{	
		$arreglo_final = array();
		$start_date    = \Util_Tools::dateDBFormat("/", $data['start_date']);
		//echo "<pre>"; print_r($data);
		//echo "<pre>"; print_r($start_date);
		
		$arrayOperador[] = array(
				"id_shipping"   => $id_shipping,
				"id_user"       => $data['id_operator'],
				"type_user"     => 1,
				"amount"        => \Util_Tools::notCurrencyOutPoint($data['amount_operator']),
				"date_shipping" => $start_date,
		);
		
		//echo "Array operador <pre>"; print_r($arrayOperador);
		
		// Validamos si hay ayudantes
		if($data['ids_ayudantes'] != ""){
			
			$idsAyudantes = explode(",", $data['ids_ayudantes']);
			
			foreach ($idsAyudantes as $id){
				$arrayAyudantes[] = array(
						"id_shipping"   => $id_shipping,
						"id_user"       => $id,
						"type_user"     => 2,
						"amount"        => \Util_Tools::notCurrencyOutPoint($data['amount_assistant']),
						"date_shipping" => $start_date,
				);
			}
			
			$resultado = array_merge($arreglo_final, $arrayOperador, $arrayAyudantes);
			//echo "Ids ayudantes <pre>"; print_r($idsAyudantes);
			//echo "Array ayudantes <pre>"; print_r($arrayAyudantes);
		}else{
			$arrayOperadorAyudante[] = array(
					"id_shipping"   => $id_shipping,
					"id_user"       => $data['id_operator'],
					"type_user"     => 2,
					"amount"        => \Util_Tools::notCurrencyOutPoint($data['amount_assistant']),
					"date_shipping" => $start_date,
			);
			$resultado = array_merge($arreglo_final, $arrayOperador, $arrayOperadorAyudante);
		}
		
		//echo "Array final <pre>"; print_r($resultado); exit;
		
		return $resultado;
	}
	
	/*
	 * Confirmar una orden de envio
	 */
	public function confirmShipping($data)
	{
		$id       = (int) $data['id'];
		$shipping = array(
				"id_shipping" => $id,
				"status"      => $data['status']
		);
		
		$row = $this->getModel()->confirmShipping($shipping);
		
		if ($row){
			$sendEmail = $this->sendEMail("Orden confirmada","Un viaje esta en curso.", "jorgemaldonado.enlasa@gmail.com", "jorgemaldonado.enlasa@gmail.com");	
		}
		
		return $row;
	}
	
	/*
	 * Todos los viajes activos
	 */
	public function getAllShippingsActive()
	{
		$rows = $this->getModel()->getAllShippingsActive();
		return $rows;
	}
	
	/*
	 * ENVIAR EMAIL
	 */
	public function sendEmailShipping()
	{
		$mail = new \Zend\Mail\Transport\Sendmail();
		
		// Creamos un nuevo mensaje
		$message = new \Zend\Mail\Message();
		$message->addTo("luisara18@gmail.com","luisara18@gmail.com");
		//$message->addCc("luisara18@gmail.com");
		$message->addFrom('luisara18@gmail.com', 'ENLASA');
		$message->setSubject('Orden de envio generada');
		
		// Agregamos Text Plano y HTML
		/*$textBody = new \Zend\Mime\Part('Mensaje de ZF2PDF');
		 $textBody->type = "text/plain";*/
		//$htmlBody = new \Zend\Mime\Part('<h1>PDF de cotizaci&oacute;n</h1>');
		//$htmlBody->type = "text/html";
		
		// Creamos un nuevo adjunto, con el PDF
		/*$attachment = new \Zend\Mime\Part($dompdf->output());
		$attachment->type = 'application/pdf';
		$attachment->filename = 'documentoPDF.pdf';
		$attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64; // Importante para obtener el adjunto
		$attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;*/
		
		// Agregamos el PDF al mensaje
		//$body = new \Zend\Mime\Message();
		//$body->setParts(array($textBody, $htmlBody, $attachment));
		//$body->setParts(array($htmlBody, $attachment));
		//$message->setBody($body);
		
		// Enviamos el mensaje
		$send_price = $mail->send($message);
		
		return true;
	}
	
	public function sendEMail($msgSubj,$msgText, $fromEmail, $toEmail) {
		$mail = new \Zend\Mail\Message();
		$mail->setFrom($fromEmail, $fromEmail);
		$mail->addTo($toEmail, $toEmail);
		$mail->addCc("luisara18@gmail.com", "luisara18@gmail.com");
		$mail->setSubject($msgSubj);
		$mail->setBody($msgText);
		$transport = new \Zend\Mail\Transport\Sendmail();
		$transport->send($mail);
		return true;
	}
	
	function sendMailEnlasa($htmlBody, $textBody, $subject, $from, $to)
	{
		$htmlPart = new \Zend\Mime\Part($htmlBody);
		$htmlPart->type = "text/html";
	
		$textPart = new \Zend\Mime\Part($textBody);
		$textPart->type = "text/plain";
	
		$body = new \Zend\Mime\Message();
		$body->setParts(array($textPart, $htmlPart));
	
		$message = new \Zend\Mail\Message();
		$message->setFrom($from);
		$message->addTo($to);
		$message->setSubject($subject);
	
		$message->setEncoding("UTF-8");
		$message->setBody($body);
		$message->getHeaders()->get('content-type')->setType('multipart/alternative');
	
		$transport = new \Zend\Mail\Transport\Sendmail();
		$transport->send($message);
		return true;
	}
	
	public function mensajito()
	{
		//mail("luisara18@gmail.com", "asunto", "comentario", "From:" . "luisara18gmail.com");
		// El mensaje
		/*$mensaje = "Línea 1\r\nLínea 2\r\nLínea 3";
		
		// Si cualquier línea es más larga de 70 caracteres, se debería usar wordwrap()
		$mensaje = wordwrap($mensaje, 70, "\r\n");
		
		// Enviarlo
		mail('joseluis.martinez@iofractal.com', 'Mi título', $mensaje);
		return "mensaje enviado";*/
		$message = new Message();
		$message->addTo('luisara18@gmail.com')
		        ->addFrom('luisara18@gmail.com')
		        ->setSubject('Greetings and Salutations!')
		        ->setBody("Sorry, I'm going to be late today!");
		
		$transport = new SendmailTransport();
		$transport->send($message);
	}
	
	/*
	 * OBTENER EL SUIGUIENTE NUMERO DE FOLIO INTERNO
	 */
	public function getNextFolioNumber()
	{
		$getNextFolioNumber = $this->getModel()->getNextFolioNumber();
		return $getNextFolioNumber[0]['maxId'] + 1;
	}
	
	/*
	 * OBTENER LISTA DE LOS CLIENTES DE LOS CLIENTES
	 */
	public function autoCompleteClientsEnd($data)
	{
		$clients = $this->getModel()->autoCompleteClientsEnd($data);
		return $clients;
	}
}