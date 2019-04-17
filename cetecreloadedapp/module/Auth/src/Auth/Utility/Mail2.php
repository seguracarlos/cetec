<?php
namespace Auth\Utility;

use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\View\Model\ViewModel;


class Mail2
{

    private $config;

    public function __construct()
    {
        $this->config = new Mail\Transport\SmtpOptions(array(
            'name' => 'localhost',
            'host' => 'horusrobot.mx',
            'port' => 465,
            'connection_class' => 'login',
            'connection_config' => array(
                'username' => 'cetec@horusrobot.mx',
                'password' => 'GFLw{x3)9G)8',
                'ssl' => 'ssl'
            )
        ));
    }
    
    public function sendPassLink($toEmail,$token)
    {

    	$url = $token;//$this->getRequest()->getBaseUrl() . "/registrationusers/register/registrationform/" . $token;
    	$subject = 'Pasos a seguir para recuperar tu contraseña';
    	
    	$emailText = '
    			<p>Has solicitado una recuperación de contraseña para tú Bachillerato en linea.
				Puedes continuar con la recuperación dando clic <a class="btn green" href="'.$url.'">aquí</a></p>
    	
    			<p>Saludos,</p>
    	
    			<p><b>El equipo de CTEC EN LÍNEA</b></p>
    	
    			<p>No responder a este correo.</p>';

    	$result = $this->generateTemplateStudent($emailText, $subject, $toEmail);

    }
    
    // Envia SOLO el link de la ficha de registro
    public function sendLink($toEmail, $token)
    {
        
        $toEmail = $toEmail;
        $url = $token;//$this->getRequest()->getBaseUrl() . "/registrationusers/register/registrationform/" . $token;
        
        // subject
        $subject = 'Ficha de Inscripción';
        
        $emailText = '
        
        			<p>Has solicitado una ficha de registro para cursar tú Bachillerato en línea.</p>
       	
        			<p>Puedes continuar con el registro dando clic <a class="btn green" href="'.$url.'">aquí</a></p>
        		
					<p>Saludos, </p>

       				<p><b>El equipo de CTEC EN LÍNEA.</b></p>
        
       			    <p>No responder a este correo.</p>';

        $result = $this->generateTemplateStudent($emailText, $subject, $toEmail);
    }
    
    // Envia la informacion que el alumno tiene que llevar a las oficinas de cetec y le dice que espere de
    // 48 a 72 horas para mas informacion(cuando lo activan)
    public function sendInfoMail($toEmail)
    {
      
        $subject = 'Pasos a seguir para iniciar tú Bachillerato.';
        
        $emailText = ('
        			<p>Bienvenido/a a este tu bachillerato en l&iacute;nea.</p>	
        				  
        		    <p>Estos son los pasos a seguir para completar tu registro en la modalidad de Bachillerato en l&iacute;nea</p>

		           	<p>Llevar la siguiente documentaci&oacute;n:</p>	   
					
        			<ul>
	        			<li>Acta de Nacimiento (Original y Copia)</li>
						<li>Certificado de Secundaria (Original y Copia)</li>
						<li>Fotos (6) tama&ntilde;o infantil blanco y negro, papel mate, de frente en cuadro</li>
						<li>Fotos (6) tama&ntilde;o credencial blanco y negro, papel mate, de frente en Óvalo</li>
						<li>CURP (Copia)</li>
						<li>Identificación oficial con fotografía  (Copia)</li>
					   	<li>Comprobante de domicilio (Copia)</li>
					</ul>

        			<p>A nuestras oficinas, ubicadas en: Ribera de San Cosme 7, Col Santa María la Ribera C.P. 06400, Delegación Cuauhtémoc, Ciudad de México</p>

        			<p>Estos documentos son requisitos indispensables de la Secretaría de Educación Pública (DGETI <b>201010001890</b>).</p> 
        			
        			<p>Realizar el pago en cualquier sucursal BBVA BANCOMER el importe de la inscripción y la primera colegiatura, a la cuenta: 0452217252 y clabe: 012180004522172524 a nombre de Data Base Administration SA de CV y enviar el comprobante de pago a pagos@ctecenlinea.mx</p>
        					
        			<p>Una vez realizado el pago de 24 a 72 horas recibirás un correo donde te indicaremos que t&uacute; cuenta ha sido activada y podrás comenzar a estudiar t&uacute; Bachillerato.	</p>
        				
        			<p>Deseamos que a la brevedad puedas comenzar tus estudios y lograr este nuevo objetivo que te has fijado.</p>
        				  
        			<p>¡¡¡Felicidades!!!</p>
        					
        			<p>Atentamente,</p>
        		
        			<p><b>El equipo de CTEC EN LÍNEA</b></p>

        			<p>No responder a este correo. Favor de hacerlo a la dirección de correo indicada.</p>');


        $result = $this->generateTemplateStudent($emailText, $subject, $toEmail);
    }
    
    // envia al alumno el link al login indicandole que ya puede iniciar el trimestre
    public function sendActivationLink($status,$toEmail,$initial)
    {
		
    	$subject = 'Notificación de la Cuenta';
    	
        if ($status == 1) {
        	if($initial == 0){
        		$emailText = '
	        				<p>Felicidades, tu cuenta ha sido habilitada, ya puedes iniciar sesión y comenzar tu Bachillerato desde el siguiente link:</p>
        		
	        				<p>www.horusrobot.mx/cetecreloaded/</p>
        		
	        				<p>En caso de que no puedas acceder ponte en contacto con nosotros a través de un correo a: controlescolar@ctecenlinea.mx o llámanos al siguiente número: (55) 56064524.</p>
        		
	        				<p>Te deseamos todo el éxito y que obtengas tu certificado en un plazo de 18 meses.</p>
        		
	        				<p>Saludos,<p/>
        		
	        				<p><b>El equipo de CTEC EN LÍNEA.</b></p>
	     
	        				<p>No responder a este correo.<p/>';
        		
        	}else if($initial == 1){
        		$emailText = '
	        				<p>Tu cuenta ha sido habilitada, ya puedes iniciar sesión desde el siguiente link:</p>
        		
	        				<p>www.horusrobot.mx/cetecreloaded/</p>
        		
	        				<p>En caso de que no puedas acceder ponte en contacto con nosotros a través de un correo a: controlescolar@ctecenlinea.mx o llámanos al siguiente número: (55) 56064524.</p>
        		
	        				<p>Saludos,<p/>
        		
	        				<p>El equipo de CTEC EN LÍNEA.</p>
	     
	        				<p>No responder a este correo.<p/>';
        		
        	}
        }
        
        if ($status == 0) {
        	$emailText = '
							<p>Tu cuenta ha sido deshabilitada por favor ponte en contacto con nosotros para más información al correo controlescolar@ctecenlinea.mx o llámanos al siguiente número: (55) 56064524.</p>
	        	
							<p>Atentamente,</p>
	        	
	            			<p><b>El equipo de CTEC EN LÍNEA.</b></p>
	        	
							<p>No responder a este correo.</p>';
        }
        
        $result = $this->generateTemplateStudent($emailText, $subject, $toEmail);
    }
    
    public function sendEvaluationInfo($toEmail,$score,$trim,$attemps,$username){
	
    	$realAttemps = 3-count($attemps);
    	$subject = 'Resultados de Evaluación';
    	$emailText = "";
    	if($score>=6.0){
    		$emailText = "
		    			<p>Tu calificación fue:".$score." ,Felicidades has aprobado el trimestre ".$trim.".</p>
		    			
		    			<p>Puedes dar inicio al siguiente trimestre, con esto has dado un gran paso para terminar tu bachillerato, FELICIDADES.
						Realiza tu pago correspondiente y continúa estudiando, estás más cerca de la meta.
		    			</p>
		    							
						<p>Saludos,</p>
		    					
		    			<p><b>El equipo de CTEC EN LÍNEA.</b></p>
		    					
		    			<p>No responder a este correo.</p>";
    			 
    	}elseif($score<6.0 && $realAttemps >=1 && $realAttemps <= 3){
    		$emailText = "
		    			<p>Tu calificación fue:".$score." y se requiere una minima de 6 para aprobar, puedes volver a realizar el examen</p>
						
		    			<p>Recuerda que sólo tienes tres oportunidades para aprobar el examen, en caso contrario deberás repetir el trimestre.</p>
						
		    			<p>Saludos,</p>
						
		    			<p>El equipo de CTEC EN LÍNEA.</p>
		    					
						<p>No responder a este correo.</p>";
    	}elseif($score<6.0 && $realAttemps == 0){
    		$emailText = "
    				<p>Tu calificación fue:".$score." y se requiere una minima de 6 para aprobar.</p>
    				
    				<p>Ya no tienes oportunidades para realizar el examen, ponte en contacto con nosotros para más informes</p>
    				
    				<p>Saludos,</p>
						
		    		<p><b>El equipo de CTEC EN LÍNEA.</b></p>
		    					
					<p>No responder a este correo.</p>";
    		
    		$emailTextAdmin ='
    					<p>El alumno:<b>'.$username.'</b> presentó su examen por tercera vez y no aprobó.</p>
    					<p>Estas fueron sus calificaciones en los tres intentos:</p>
    					<table style="width:100%" >
    						<tr>
	    						<th>Calificación</th>
	    						<th>Fecha en que realizó el Examen</th>	
	    					<tr>
    						<tr>
    							<td style="text-align:center;"><b>'.$attemps[0]['totalscore'].'</td>
    							<td style="text-align:center;"></b> Fecha:'.$attemps[0]['date'].'</td>
    						</tr>
    						<tr>
    							<td style="text-align:center;"><b>'.$attemps[1]['totalscore'].'</td>
    							<td style="text-align:center;"></b> Fecha:'.$attemps[1]['date'].'</td>
    						</tr>
    						<tr>
    							 <td style="text-align:center;"><b>'.$attemps[2]['totalscore'].'</td>
    							<td style="text-align:center;"></b> Fecha:'.$attemps[2]['date'].'</td>
    						</tr>
    					</table>
    					
    					';
    		$subjectAdmin ='Alumno Reprobado';
    		$this->generateTemplateAdmin($emailTextAdmin, $subjectAdmin);
    	}
    	
    	
    	$result = $this->generateTemplateStudent($emailText, $subject, $toEmail);
    }
    
    public function sendEvaluationInfo2($toEmail){

    	$subject = 'Evaluación activa';
    	 
    	$emailText = "
    			
		    		<p>Has cumplido con las 80 horas de estudio necesarias para realizar tu examen del trimestre y pasar al siguiente.</p>
					<p>Tu examen está habilitado, para presentarlo ingresa a tu cuenta en la sección de exámenes.</p>
					
		    		<p>Saludos,</p>
		    		
		    		<p><b>El equipo de CTEC EN LÍNEA</b></p>
					
		    		<p>No responder a este correo</p>";
    	
    	$result = $this->generateTemplateStudent($emailText, $subject, $toEmail);
    	 
    }
    
    public function sendRegistrationNotification($dataUser,$dataAdress,$billingDetails){
		
    	$subject = 'Nueva Solicitud de Inscripción';
    	 
    	$emailText = ('<p>
    				'.$dataUser['name']." ".$dataUser['lastname']." ".$dataUser['surname'].' completó su ficha de registro con los siguientes datos:
    				<br/>
    				<h4>Datos Generales</h4>
    				<br/>CURP:'.$dataUser['curp'].'
    				<br/>Fecha de Nacimiento:'.$dataUser['datebirth'].'
    				<br/>Ciudad de Nacimiento:'.$dataUser['city_birth'].'
    				<br/>Estado de Nacimiento:'.$dataUser['state_birth'].'
    				<br/>Secundaria:'.$dataUser['highschool'].'
    				<br/>Fecha del certificado de Secundaria:'.$dataUser['date_certificate'].'
    				<br/>Teléfono:'.$dataUser['phone'].'
    				<br/>Teléfono Celular:'.$dataUser['cellphone'].'
    				
    				<h4>Domicilio</h4>
    				<br/>Calle:'.$dataAdress['calle'].'
    				<br/>Numero Interior:'.$dataAdress['numInt'].'
    				<br/>Numero Exterior:'.$dataAdress['numExt'].'
    				<br/>Colonia:'.$dataAdress['colonia'].'
    				<br/>Delegación:'.$dataAdress['delegacion'].'
    				<br/>Ciudad:'.$dataAdress['ciudad'].'
    				<br/>CP:'.$dataAdress['cp'].'
    				<br/>Pais:'.$dataAdress['pais'].'
    				<br/><br/>
	    			</p>');
    	$factDetailsEmpty = 1;
    	foreach($billingDetails as $value){
    		if($value == "")
	    		$factDetailsEmpty++;
    	}
    	$emailText.='<h4>Datos de Facturación(Opcionales)</h4>';
    	
    	if($factDetailsEmpty<=1){
    		$emailText.='Nombre:'.$billingDetails['name'].'
					<br/>Apellido Materno:'.$billingDetails['surname'].'
					<br/>Apellido Paterno:'.$billingDetails['lastname'].'
					<br/>RFC:'.$billingDetails['rfc'].'
					<br/>Calle:'.$billingDetails['calle'].'
					<br/>Numero Exterior:'.$billingDetails['num_ext'].'
					<br/>Numero Interior:'.$billingDetails['num_int'].'
					<br/>Colonia:'.$billingDetails['colonia'].'
					<br/>CP:'.$billingDetails['cp'].'
					<br/>Delegación:'.$billingDetails['delegacion'].'
					<br/>Ciudad:'.$billingDetails['ciudad'].'
					<br/>Pais:'.$billingDetails['pais'].'';
    	}else{
    		$emailText.='No se agregaron Datos de Facturación</h4>';
    	}
    	
    	$result = $this->generateTemplateAdmin($emailText, $subject);

    }
    
    public function generateTemplateStudent($emailText, $subject,$email)
    {
    	$view       = new \Zend\View\Renderer\PhpRenderer();
    	$resolver   = new \Zend\View\Resolver\TemplateMapResolver();
    	$transport = new Mail\Transport\Smtp($this->config);
		
    	$emailEncodingText = utf8_encode($emailText);
    	$emailEncodingSubject =  utf8_encode($subject);
    	
    	$resolver->setMap(array(
    			'mailTemplate' => realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../../view/mail/mailTemplate.phtml')
    	));
    	//$imgMail = realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../../view/mail/slide1.jpg');
    	$view->setResolver($resolver);
    	
    	$viewModel  = new ViewModel();
    	$viewModel->setTemplate('mailTemplate')->setVariables(array(
    			'message'  => $emailEncodingText,
    			//'mailImage' => $imgMail
    	));
    	
    	$bodyPart = new \Zend\Mime\Message();
    	$bodyMessage    = new \Zend\Mime\Part($view->render($viewModel));
    	$bodyMessage->type = 'text/html';
    	$bodyPart->setParts(array($bodyMessage));
    	
    	$message        = new \Zend\Mail\Message();
    	$message->addFrom('notificaciones@ctecenlinea.mx', 'Bachillerato CTEC')
    	->addTo($email)
    	->setSubject($emailEncodingSubject)
    	->setBody($bodyPart)
    	->setEncoding('UTF-8');
    	$transport->send($message);
    	return $transport;
    }
    
    public function generateTemplateAdmin($emailText, $subject){
    	$view       = new \Zend\View\Renderer\PhpRenderer();
    	$resolver   = new \Zend\View\Resolver\TemplateMapResolver();
    	$transport = new Mail\Transport\Smtp($this->config);
    	 
    	$emailEncodingText = utf8_encode($emailText);
    	$emailEncodingSubject =  utf8_encode($subject);
    	 
    	$resolver->setMap(array(
    			'mailTemplate' => realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../../view/mail/mailTemplate.phtml')
    	));
    	$view->setResolver($resolver);
    	 
    	$viewModel  = new ViewModel();
    	$viewModel->setTemplate('mailTemplate')->setVariables(array(
    			'message'  => utf8_encode($emailEncodingText)
    	));
    	 
    	$bodyPart = new \Zend\Mime\Message();
    	$bodyMessage    = new \Zend\Mime\Part($view->render($viewModel));
    	$bodyMessage->type = 'text/html';
    	$bodyPart->setParts(array($bodyMessage));

        //changeTo:serviciosescolares@ctecenlinea.com
    	$message        = new \Zend\Mail\Message();
    	$headers = $message->getHeaders();
    	$headers->removeHeader('Content-Type');
    	$headers->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');
    	$message->addFrom('notificaciones@ctecenlinea.mx', 'Bachillerato CTEC')
    	->addTo("serviciosescolares@ctecenlinea.com")
    	->setSubject($emailEncodingSubject)
    	->setBody($bodyPart)
    	->setEncoding('UTF-8');
    	$transport->send($message);
    	return $transport;
    }
}