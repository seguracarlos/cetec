<?php


namespace Gutenberg;

use BaseController;
use Application_Service_Impl_MinuteServiceImpl;
use Application_Form_MinuteForm;
use Application_Entity_MinuteEntity;
use Zend_Mail_Transport_Smtp;
use Zend_Mail;
use Zend_View;
use minutesService;
use Exception;
use Zend_Loader_Autoloader;
use Zend_Pdf;
use Zend_Pdf_Page;
use Zend_Pdf_Font;
use Zend_Pdf_Image;
use Zend_Pdf_Style;
use Zend_Pdf_Color_RGB;
use Zend_Pdf_Color_Rgb;
use Zend_Pdf_Exception;



include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/MinutesService.php';
include_once APPLICATION_PATH . '/services/ACLCheckerService.php';
require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';
require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Pdf.php';

class MinuteController extends BaseController{

	function init(){
		BaseController::init();
	}
	
	public function indexAction(){
		$serviceMunites = new Application_Service_Impl_MinuteServiceImpl();
		$listado = $serviceMunites->listMinutes();
		if ($listado != null) {
			$this->view->minutes = $listado;
		}else{
			$this->view->minutes = 0;
		}
	}
	
	public function addAction(){
		$form = new Application_Form_MinuteForm();
		$serviceMinute = new Application_Service_Impl_MinuteServiceImpl();
	
		if($this->getRequest()->isPost()){
			
			$formData = $this->getRequest()->getPost();
			 
			if($form->isValid($formData)){
				 
				$idminute = $serviceMinute->saveMinutes($formData);
				if($idminute != null){
					$this->_redirect("/Gutenberg/minute/index");
				}
			}
		}
	
		$this->view->form = $form;
	}
	
	public function updateAction(){
		$form = new Application_Form_MinuteForm();
		$minuteService = new Application_Service_Impl_MinuteServiceImpl();
		$minute = new Application_Entity_MinuteEntity();
	
		$idminute = $this->_getParam('c');
		if($this->getRequest()->isPost()){
			$formData = $this->_getAllParams();
			$formData['idminutes'] = $idminute;
	
			if($form->isValid($formData)){
				 
				$minuteEdit = $minuteService->updateMinutes($formData);
				if($minuteEdit == true){
					$this->_redirect("/Gutenberg/minute/index");
				}else{
					$this->_redirect("/Gutenberg/minute/update?c=".$idcomponent);
				}
			}
		}else{
	
			$minute = $minuteService->getMinutesById($idminute);
			$form->populate($minute[0]);
			$this->view->form = $form;
		}
	}
	
	public function deleteAction(){
		$deleteParam = $this->_getParam("erase");
	
		if ($this->getRequest()->isPost()) {
			$serviceMinute = new Application_Service_Impl_MinuteServiceImpl();
			$serviceMinute->deleteMinutes($deleteParam);
		}
		exit();
	}
	
	public function detailsAction(){
		$minuteService = new Application_Service_Impl_MinuteServiceImpl();
		$minute = new Application_Entity_MinuteEntity();
		$id_minute = $this->_getParam('c');
		
		$minute = $minuteService->getMinutesById($id_minute);
		$this->view->minute = $minute[0];
	}

	public function sendmailAction(){


		if(!$this->_hasParam('idminutes')){
			return $this->_redirect('/Gutenberg/minute/index');
		}
		//llega el post
		if ($this->getRequest()->isPost()) {
			//llega de el post la class boton
			$del = $this->getRequest()->getPost('boton');
			//llega la cadena de emails
			$email = $this->getRequest()->getPost('email');
			//llega proyecto seleccionado
			$proyecto = $this->getRequest()->getPost('proyectos');
			//llega del post boton el boton de Si
			if ($del == 'Si') {

				//si la cadena de email viene vasia o el proyecto seleccionado
				if("," == $email || "" == $proyecto){
					$this->view->mensaje = "Debes de seleccionar al menos un destinatario y un proyecto";
				}else{

					//Configuraci�n SMTP
					$host = 'smtp.gmail.com';
					$param = array(
							'auth' => 'login',
							'ssl' => 'ssl',
							'port' => '465',
							'username' => 'iofractalconsultoria@gmail.com',
							'password' => 'IOfracta'
					);
					 
					//crea transport
					$tr = new Zend_Mail_Transport_Smtp($host, $param);
					Zend_Mail::setDefaultTransport($tr);

					//crea la vista para el correo
					$myView = new Zend_View();
					$myView->addScriptPath('../application/views/scripts/minute/');

					//envia informacion de la minuta para enviar por correo
					$form = new Application_Form_MinuteForm();
					$minutaNuevaAccion = new minutesService();
					 
					$row = $minutaNuevaAccion->getRowsD($this->_getParam('idminutes'));
					if($row){
						$form->populate($row->toArray());
					}
					$myView->proy = $proyecto;
					$myView->form = $form;
					$this->view->form = $form;
					 
					$minData = new minutesService();
					$time = $minData->getTime($this->_getParam('idminutes'));
					$actionPreviousMa = $minData->getPreviousMinute($time);
					 
					if($actionPreviousMa){
						$myView->form2 = $actionPreviousMa;
						$this->view->form2 = $actionPreviousMa;
					}else{
						$myView->form2 = "No existe minuta anterior";
						$this->view->form2 = "No existe minuta anterior";
					}
					 
					//genera la vista en sendmail para eviar por mail
					$html_body = $myView->render('sendmail.phtml');

					//Creamos email
					$mail = new Zend_Mail();
					$mail->setFrom('iofractalconsultoria@gmail.com', 'IOfractal');
					$mail->setSubject('Minuta del dia para el proyecto '.$proyecto);
					$mail->setBodyHtml($html_body);

					try {
						//quita la ultima coma de la cadena
						$email = trim($email, ',');
						//separa cade de emails por comas
						$cadenaSep = explode(',',$email);
						foreach ($cadenaSep as $emailss){
							//agrega destinatarios por correos
							$mail->addTo($emailss, "Fractaler");
						}
						//enviar correo
						$mail->send();
						$this->view->mensaje = "Correo enviado con exito";
						$sent = true;
					}catch (Exception $e){
						$sent = false;
						$this->view->mensaje = "Error al enviar correo intentalo mas tarde";
					}

					//Devolvemos si hemos tenido �xito
					return $sent;
					 
				}
			}else{
				//si el boton que llego por post es No regresa a index
				$this->_redirect('/minute/ver');
			}
		}else{
			//no se recibe nada en el post
			$this->view->mensaje = "No se recibe nada";
		}
	}//termina accion de sendmail
	/*--------------------------------------------------------------------
	 * ------------------  action de exportar a pdf ----------------------
	* -------------------------------------------------------------------
	+----------------------+
	* |  -----      -----    |
	* | ---           ___    |
	* | ___________  |___|   |
	* | ---- ----            |
	* | --- ---------        |
	* | ___________________  |
	* |                      |
	* | ___________________  |
	* | ___________________  |
	* |                      |
	* | ___________________  |
	* |                      |
	* +----------------------+
	*/

	function exportpdfAction(){

		// register auto-loader
		$loader = Zend_Loader_Autoloader::getInstance();

		try {

			// create PDF
			$pdf = new Zend_Pdf();

			// create A4 page
			$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

			// define font resource
			$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

			//define image resource
			$arbol = Zend_Pdf_Image::imageWithPath('img/IOFractal.png');
			$horus = Zend_Pdf_Image::imageWithPath('img/horus.png');
			// write image to page
			/*
			 * 1 param: de izquierda para derecha
			* 2 param: de abajo para arriba
			* 3 param: de derecha a izquierda
			* 4 param: de arriba para abajo
			*/
			$page->drawImage($arbol, 360, 620, 530, 770);
			$page->drawImage($horus, 40, 30, 140, 60);

			$minData = new minutesService();
			$id = $this->_getParam('idminutes');
			$objective = $minData->getObjective($this->_getParam('idminutes'));
			$time = $minData->getTime($this->_getParam('idminutes'));
			$duracion = $minData->getHora($this->_getParam('idminutes'));
			$phone = $minData->getPhone($this->_getParam('idminutes'));
			$convened = $minData->getConvened($this->_getParam('idminutes'));
			$timeKeeper = $minData->getTimeKeeper($this->_getParam('idminutes'));
			$attendees = $minData->getAttendees($this->_getParam('idminutes'));
			$location = $minData->getLocation($this->_getParam('idminutes'));
			$meetingType = $minData->getMeetingType($this->_getParam('idminutes'));
			$moderator = $minData->getModerator($this->_getParam('idminutes'));
			$annotations = $minData->getAnnotations($this->_getParam('idminutes'));
			$agendaItems = $minData->getAgendaItems($this->_getParam('idminutes'));
			$newActionItems = $minData->getNewActionItem($this->_getParam('idminutes'));
			$otherNotes = $minData->getOtherNotes($this->_getParam('idminutes'));
			$actionPrevious = $minData->getPreviousMinute($time);

			//letras de color verde
			$letraVerde = new Zend_Pdf_Style();
			$letraVerde->setFillColor(new Zend_Pdf_Color_RGB(0, 10, 0));
			$letraVerde->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 12);
			//letras de color azul
			$letraAzul = new Zend_Pdf_Style();
			$letraAzul->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 10));
			$letraAzul->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 12);
			//letras de color negro
			$letraNegra = new Zend_Pdf_Style();
			$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
			$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);

			$page->setStyle($letraVerde);
			$page->drawText('Folio ', 40, 760);
			$page->drawText($id, 70, 760);
			//fecha del dia en que es creada la minuta en pdf
			$fecha = date('M j Y');
			$hora = date('h:i:s');
			//dibuja fecha
			$page->drawText($fecha, 360, 600);
			$page->drawText($hora, 360, 590);
			$page->drawText('GRAD - Green Rapid Application Development', 40, 780);
			$page->drawText('powered by IOFractal', 400, 780);
			$page->setStyle($letraAzul);
			$page->drawText('INFORMACION GENERAL - ', 40, 740);
			$page->drawText('_______________________________________________', 30, 735);
			$page->setStyle($letraNegra);
			//objetivo
			$page->drawText('Objetivo: ', 40, 710);
			$startActionObj = 710;
			$cadeObj = nl2br($objective);
			$lineasobj = explode('<br />', $cadeObj);

			foreach ($lineasobj as $lineobj) {
				$lineobj = ltrim($lineobj);
				$page->drawText($lineobj, 90, $startActionObj);
				$startActionObj = $startActionObj - 20;
			}
			//$page->drawText($objective, 90, 710);
			//time
			$page->drawText('Fecha: ', 40, $startActionObj);
			$startActionTime = $startActionObj;
			$cadeTime = nl2br($time);
			$lineasTime = explode('<br />', $cadeTime);

			foreach ($lineasTime as $lineTime) {
				$lineTime = ltrim($lineTime);
				$page->drawText($lineTime, 90, $startActionTime);
				$startActionTime = $startActionTime - 20;
			}
			//$page->drawText($time, 90, 690);
			 
			//hora
			$page->drawText('Duracion: ', 40, $startActionTime);
			$startActionHora = $startActionTime;
			$cadeHora = nl2br($duracion);
			$lineasHora = explode('<br />', $cadeHora);

			foreach ($lineasHora as $lineHora) {
				$lineHora = ltrim($lineHora);
				$page->drawText($lineHora, 90, $startActionTime);
				$startActionHora = $startActionHora - 20;
			}
			//$page->drawText($hora, 90, 690);
			//phone
			$page->drawText('Telefono: ', 40, $startActionHora);
			$startActionPhone = $startActionHora;
			$cadePhone = nl2br($phone);
			$lineasPhone = explode('<br />', $cadePhone);

			foreach ($lineasPhone as $linePhone) {
				$linePhone = ltrim($linePhone);
				$page->drawText($linePhone, 90, $startActionPhone);
				$startActionPhone = $startActionPhone - 20;
			}
			//$page->drawText($phone, 90, 670);
			//convened
			$page->drawText('Convoco: ', 40, $startActionPhone);
			$startActionConve = $startActionPhone;
			$cadeConve = nl2br($convened);
			$lineasConve = explode('<br />', $cadeConve);

			foreach ($lineasConve as $lineConve) {
				$lineConve = ltrim($lineConve);
				$page->drawText($lineConve, 90, $startActionConve);
				$startActionConve = $startActionConve - 20;
			}
			//$page->drawText($convened, 90, 650);
			//timekeeper
			$page->drawText('Cronometrador', 40, $startActionConve);
			$startActionTiKe = $startActionConve;
			$cadeTiKe = nl2br($timeKeeper);
			$lineasTiKe = explode('<br />', $cadeTiKe);

			foreach ($lineasTiKe as $lineTiKe) {
				$lineTiKe = ltrim($lineTiKe);
				$page->drawText($lineTiKe, 106, $startActionTiKe);
				$startActionTiKe = $startActionTiKe - 20;
			}
			//$page->drawText($timeKeeper, 90, 630);
			//attendees
			$page->drawText('Participantes: ', 200, 710);
			$startActionAsis = 710;
			$cadeAsis = nl2br($attendees);
			$lineasAsis = explode('<br />', $cadeAsis);

			foreach ($lineasAsis as $lineAsis) {
				$lineAsis = ltrim($lineAsis);
				$page->drawText($lineAsis, 260, $startActionAsis);
				$startActionAsis = $startActionAsis - 20;
			}
			//$page->drawText($attendees, 260, 710);
			//location
			$page->drawText('Ubicacion: ', 200, $startActionAsis);
			$startActionLoca = $startActionAsis;
			$cadeLoca = nl2br($location);
			$lineasLoca = explode('<br />', $cadeLoca);

			foreach ($lineasLoca as $lineLoca) {
				$lineLoca = ltrim($lineLoca);
				$page->drawText($lineLoca, 250, $startActionLoca);
				$startActionLoca = $startActionLoca - 20;
			}
			//$page->drawText($location, 250, 670);
			//meetingtype
			$page->drawText('Tipo de reunion: ', 200, $startActionLoca);
			$startActionMeTy = $startActionLoca;
			$cadeMeTy = nl2br($meetingType);
			$lineasMeTy = explode('<br />', $cadeMeTy);

			foreach ($lineasMeTy as $lineMeTy) {
				$lineMeTy = ltrim($lineMeTy);
				$page->drawText($lineMeTy, 265, $startActionMeTy);
				$startActionMeTy = $startActionMeTy - 20;
			}
			//$page->drawText($meetingType, 250, 650);
			//moderador
			$page->drawText('Moderador: ', 200, $startActionMeTy);
			$startActionModer = $startActionMeTy;
			$cadeModer = nl2br($moderator);
			$lineasModer = explode('<br />', $cadeModer);

			foreach ($lineasModer as $lineModer) {
				$lineModer = ltrim($lineModer);
				$page->drawText($lineModer, 250, $startActionModer);
				$startActionModer = $startActionModer - 20;
			}
			//$page->drawText($moderator, 250, 630);
			//anotaciones
			$page->drawText('Anotaciones: ', 40, $startActionTiKe);
			$startActionAnot = $startActionTiKe;
			if("" == $annotations){
				$page->drawText("no hay anotaciones", 90, $startActionAnot);
			}else{
				$cadeNew4 = nl2br($annotations);
				$lineas4 = explode('<br />', $cadeNew4);

				foreach ($lineas4 as $line4) {
					$line4 = ltrim($line4);
					$page->drawText($line4, 90, $startActionAnot);
					$startActionAnot = $startActionAnot - 15;
				}
			}
			//$page->drawText($annotations, 260, 600);
			$page->setStyle($letraAzul);
			$startParte2 = $startActionAnot-30;
			$page->drawText('___ Preparacion para la reunion _____________________________________________', 30,  $startParte2);
			$starActionPleaseR =  $startParte2-15;
			$starActionPleaseB =  $starActionPleaseR-15;
			$page->drawText('Por favor, lea: ', 40,  $starActionPleaseR);
			$page->drawText('Por favor, traiga:', 40,  $starActionPleaseB);
			 
			//ACTION ITEMS FROM PREVIOUS MEETING
			$page->setStyle($letraAzul);
			$starActionItemsPrev = $starActionPleaseB-20;
			$page->drawText('ACCIONES DE LA REUNION ANTERIOR', 40,  $starActionItemsPrev);
			$page->drawText('_________________________________________________________________', 30,  $starActionItemsPrev-7);
			$page->setStyle($letraNegra);
			//donde empiezan las lineas
			$startActionPrev =  $starActionItemsPrev-20;
			if("" == $actionPrevious){
				$page->drawText("No existen minutas anteriores", 40, $startActionPrev);
				$startActionPrev = $startActionPrev - 10;
			}else{
				//agrega cada salto de linea un <br />
				$cadeNew3 = nl2br($actionPrevious);
				//seapra la cadena donde encuantre <br />
				$lineas3 = explode('<br />', $cadeNew3);
				//incia contador de lineas para escribirlas
				foreach ($lineas3 as $line3) {
					//limpia la linea
					$line3 = ltrim($line3);
					//escribe la linea
					$page->drawText($line3, 40, $startActionPrev);
					//da el salto de linea para comenzar a escribir
					$startActionPrev = $startActionPrev - 10;
				}
			}
			//$page->drawText($actionPrevious, 40, 460);
			//AGENDA ITEMS
			$page->setStyle($letraAzul);
			$page->drawText('ORDEN DEL DIA', 40, $startActionPrev-10);
			$page->drawText('_________________________________________________________________', 30, $startActionPrev-12);
			$page->setStyle($letraNegra);
			$startAgenda = $startActionPrev-23;
			if("" == $agendaItems){
				$page->drawText("No hay nada agendado", 40, $startAgenda);
				$startAgenda = $startAgenda - 10;
			}else{
				$cadeNew2 = nl2br($agendaItems);
				$lineas2 = explode('<br />', $cadeNew2);
				foreach ($lineas2 as $line2) {
					$line2 = ltrim($line2);
					$page->drawText($line2, 40, $startAgenda);
					$startAgenda = $startAgenda - 10;
				}
			}
			//$page->drawText($agendaItems, 40, 370);
			//new actio item
			$page->setStyle($letraAzul);
			$page->drawText('NUEVOS TEMAS DE ACCION', 40, $startAgenda-10);
			$page->drawText('_________________________________________________________________', 30, $startAgenda-12);
			$page->setStyle($letraNegra);
			$start = $startAgenda-23;
			if("" == $newActionItems){
				$page->drawText("no hay acciones nuevas");
				$start = $start - 8;
			}else{
				$cadeNew = nl2br($newActionItems);
				$lineas = explode('<br />', $cadeNew);

				foreach ($lineas as $line) {
					$line = ltrim($line);
					$page->drawText($line, 40, $start);
					$start = $start - 8;
				}
			}
			//$page->drawText($newActionItems, 40, 250);
			//other view
			$page->setStyle($letraAzul);
			$page->drawText('OTRAS NOTAS', 40, $start-10);
			$page->drawText('_________________________________________________________________', 30, $start-12);
			$page->setStyle($letraNegra);
			$startOther = $start-23;
			if("" == $otherNotes){
				$page->drawText("No hay otras notas", 40, $startOther);
			}else{
				$cadeNew1 = nl2br($otherNotes);
				$lineas1 = explode('<br />', $cadeNew1);

				foreach ($lineas1 as $line1) {
					$line1 = ltrim($line1);
					$page->drawText($line1, 40, $startOther);
					$startOther = $startOther - 8;
				}
			}
			//$page->drawText($otherNotes, 40, 120);
			 

			//footter
			$footerLetra = new Zend_Pdf_Style();
			$footerLetra->setFillColor(new Zend_Pdf_Color_RGB(0, 10, 0));
			$footerLetra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 9);
			$page->setStyle($footerLetra);
			$page->drawText('IOFractal Gente que <conecta> empresas � 2011 iofractal.com', 280, 30);

			$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
			$page->drawLine(10, 820, ($page->getWidth()-10), 820);
			$page->drawLine(10, 25, ($page->getWidth()-10), 25);
			 
			// add page to document
			$pdf->pages[] = $page;

			// save as file
			$pdf->save('Minuta_convocada_por_'.$convened.'.pdf');
			$this->view->mensaje = "Minuta en PDF creada con exito";
		} catch (Zend_Pdf_Exception $e) {
			die ('PDF error: ' . $e->getMessage());
			$this->view->mensaje = "no Correcto pdf";
		} catch (Exception $e) {
			die ('Application error: ' . $e->getMessage());
		}

	}
}