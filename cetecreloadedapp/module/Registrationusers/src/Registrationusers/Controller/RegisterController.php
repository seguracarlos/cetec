<?php
namespace Registrationusers\Controller;

use Iofractal\Controller\BaseController;
use Zend\View\Model\ViewModel;
//Incluir formularios
use Registrationusers\Form\RegisterForm;
//Componente para cifrar contraseï¿½as
use Zend\Crypt\Password\Bcrypt;
use Auth\Utility\UserPassword;

use Registrationusers\Service\RegisterService;
use Registrationusers\Service\FichaRegistroService;
use System\Form\EditProfileForm;
use Registrationusers\Model\AdressModel;
use Registrationusers\Model\BillDetailsModel;


use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message;
use Auth\Utility\Mail2;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\SmtpOptions;



class RegisterController extends BaseController
{
	
	private $registerService;
	private $fichaService;
	
	public function getRegisterService()
	{
		return $this->registerService = new RegisterService();
	}
	
	public function getFichaRegistroService()
	{

		return $this->fichaService = new FichaRegistroService();
	}

	/*public function indexAction()
	{
		$layout = $this->layout();
		$layout->setTemplate('layout/layoutLogin');
		
	}*/
	
	public function indexAction()
	{
		$layout = $this->layout();
		$layout->setTemplate('layout/layoutLogin');
		$form=new RegisterForm("form");
		$mail = new \Auth\Utility\Mail2();
		$request         = $this->getRequest();
		$vista=array("form"=>$form);
		
		
		if($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				//Cargamos el modelo
				//$this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
				//$usuarios=new UsuariosModel($this->dbAdapter);
				//Recogemos los datos del formulario
				//$email=$this->request->getPost("email");
		
				/*
				 Ciframos la contrase�a
				 para la maxima seguridad le aplicamos un salt
				 y hacemos el hash del hash 5 veces
				 (por defecto vienen mas de 10 pero es mas lento)
				 */
		
				/*$bcrypt = new Bcrypt(array(
				 'salt' => 'aleatorio_salt_pruebas_victor',
				'cost' => 5));
		
				$securePass = $bcrypt->create($this->request->getPost("password"));
				$password=$securePass;
				$nombre=$this->request->getPost("nombre");
				$apellido=$this->request->getPost("apellido");
				*/
		
				//Insertamos en la bd
				//$insert=$usuarios->addUsuario($email, $password, $nombre, $apellido);
				//Mensajes flash $this->flashMessenger()->addMenssage("mensaje");
		
				$formData   = $request->getPost()->toArray();
				$insert   = $this->getRegisterService()->addRow($formData);
				$token = $this->getFichaRegistroService()->addToken($insert);
				if($insert==true){
					//ENVIA MAIL CON URL DEL TOKEN RELACIONADA AL USUARIO QUE SE DIO DE ALTA
					//$this->sendMail1($formData['email'],$token['token']);
					//https://www.horusrobot.mx/cetecreloaded/registrationusers/register/registrationform/00f7f06b728fc98ff5ebbfefb38d1297
						
					//$tok = $this->getRequest()->getBaseUrl() . "/registrationusers/register/registrationform/" . $token['token'];
		
					$tok = "https://www.horusrobot.mx/cetecreloaded/registrationusers/register/registrationform/" . $token['token'];
		
		
					$mail->sendLink($formData['email'],$tok);
					$this->flashMessenger()->setNamespace("add_correcto")->addMessage("Te hemos enviado un mail con la liga de tu ficha de registro, verifica tu bandeja de entrada y la de correos no deseados.");
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/login');
				}else{
					$checkUserData = $this->getRegisterService()->getRowByMail($formData['email']);
					if(($checkUserData[0]['name'] != "" && $checkUserData[0]['surname'] != "" && $checkUserData[0]['lastname'] != "")){
						$this->flashMessenger()->setNamespace("duplicado")->addMessage("Correo duplicado ingresa otro");
						return $this->redirect()->refresh();
					}else{
						$tok = "https://www.horusrobot.mx/cetecreloaded/registrationusers/register/registrationform/" . $token['token'];
						$mail->sendLink($formData['email'],$tok);
						$this->flashMessenger()->setNamespace("duplicado")->addMessage("El correo ingresado ya ha sido registrado, te hemos reenviado un mail con la liga de tu ficha de registro.");
						return $this->redirect()->refresh();
					}
				}
			}else{
				$err=$form->getMessages();
				$vista=array("form"=>$form,'url'=>$this->getRequest()->getBaseUrl(),"error"=>$err);
			}
		}
		
		return new ViewModel($vista);
	}
	
	public function registrationformAction(){
        $layout = $this->layout();
        $layout->setTemplate('layout/layoutLogin');
        $addressModel = new AdressModel();
        $billModel = new BillDetailsModel();
        $mail = new Mail2();
        $userDetails = $this->getRegisterService();
        	
        	if($this->getRequest()->isPost()){
        		$formData = $this->getRequest()->getPost();
        		$dataAdress = array(
        		         				'calle' => $formData['calle'],
        								'numInt' => $formData['numInt'],
             		         			'numExt' => $formData['numExt'],
        								'colonia' => $formData['colonia'],
  					      				'delegacion' => $formData['delegacion'],
	 									'ciudad'=> $formData['ciudad'],
        								'cp' => $formData['cp'],
           								'pais'  => $formData['pais'],
        		         				'id_user' => $formData['id_user']
        		         		);
        		$addAddress = $addressModel->addRow($dataAdress);
        		
        		if($formData['numIntFact']!=""){
        			$numInt = $formData['numIntFact'];
        		}else{
        			$numInt = "0";
        		}
        		
        		$billingDetails = array(
        					'name' => $formData['nombreFact'],
        					'surname' => $formData['apellidomFact'],
        					'lastname' => $formData['apellidopFact'],
        				    'rfc'      => $formData['rfcFact'],
        					'calle' => $formData['calleFact'],
        				    'num_ext' => $formData['numExtFact'],
        					'num_int' => $numInt,
        					'colonia' => $formData['coloniaFact'],
        					'cp'      => $formData['cpFact'],
        					'delegacion'=> $formData['delegacionFact'],
        					'ciudad' => $formData['ciudadFact'],
        					'pais'  => $formData['paisFact'],
        					'id_student' => $formData['id_user'] 
        		);
        		
        		$flag=0;
        		foreach($billingDetails as $details){
        			if($details ==""){
        				$flag=1;
        			}
        		}
        		
        		if($flag==0){
        			$addbillDetails = $billModel->addRow($billingDetails);	
        		}
        		
        		$invert = explode("-",$formData['fecha']);
        		$fecha_invert = $invert[2]."-".$invert[1]."-".$invert[0];
        		
        		$invert2 = explode("-",$formData['fechaCert']);
        		$fecha_invert2 = $invert2[2]."-".$invert2[1]."-".$invert2[0];
        		$dataUser = array(
        						 'name' => $formData['nombre'],
        						 'surname' => $formData['apellidom'],
        						 'lastname' => $formData['apellidop'],
        						 'sexo'     => $formData['sexo'],
        						 'curp' => $formData['curp'],
      				             'user_id' => $formData['id_user'],
        						 'trim'    => "1",
        						 'datebirth' => $fecha_invert,
        						 'city_birth' => $formData['ciudad_nacimiento'],
		        				 'state_birth' => $formData['edo_nacimiento'],
        						 'highschool'  => $formData['secundaria'],
        						 'date_certificate' => $fecha_invert2,
        						 'phone'            => $formData['telefonoCasa'],
        						 'cellphone'        => $formData['celular']
        					);
        		
        		$updateuser = $userDetails->editRow($dataUser);
        		if($updateuser){
        			$deleteToken =  $this->getFichaRegistroService()->deleteToken($formData['id_user']);
        			//ENVIA MAIL DONDE LE INIDCA LA DOCUMENTACION QUE EL ALUMNO TIENE QUE LLEVAR A LAS OFICINAS DE CETEC
        			$mail->sendInfoMail($formData['email']);
        			$mail->sendRegistrationNotification($dataUser,$dataAdress,$billingDetails);
        			$this->flashMessenger()->setNamespace("add_correcto")->addMessage("Te hemos enviado un mail con los ultimos pasos, verifica tu bandeja de entrada");
        			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/login');
        		}
        	
        	}else{
        		$token=$this->params()->fromRoute("id",null);
        		$validateToken = $this->getFichaRegistroService()->getToken($token); 
        		if($validateToken){
        			$user = $userDetails->getRowById($validateToken[0]['id_user']);
        			$data = array("name" => $user[0]['name'],
        					"surname" => $user[0]['surname'],
        					"lastname" => $user[0]['lastname'],
        					"user_id"  => $user[0]['user_id'],
        					"email"    => $user[0]['email']
        			);

        			return new ViewModel($data);
        		}else{
        			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/login');
        		}
        	
        	}
        
        
	}

}