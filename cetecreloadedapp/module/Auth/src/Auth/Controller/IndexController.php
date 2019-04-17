<?php
namespace Auth\Controller;

use Iofractal\Controller\BaseController;
use Zend\View\Model\ViewModel;
// Formulario de login
use Auth\Form\LoginForm;
// Validador del formulario de login
use Auth\Form\Filter\LoginFilter;
use Auth\Utility\UserPassword;
use Zend\Session\Container;
// Servicios
//use System\Services\CompanyService;
//use System\Services\PreferencesService;
use Zend\Crypt\Password\Bcrypt;

use Zend\Authentication\Result;
use Classes\Services\StudyTimeService;
use Classes\Services\LoginHistoryService;
use Exams\Services\ExamscoreServiceImpl;
use Exams\Services\ExamanswersServiceImpl;
use Auth\Utility\Mail2;
class IndexController extends BaseController
{
	
	public function indexAction()
	{
		$try     = 0;                   // Numero de intentos
		$request = $this->getRequest(); // Peticion
		$layout  = $this->layout();     // Indicamos layout
		$layout->setTemplate('layout/layoutLogin');
		$view      = new ViewModel();            // Vista
		$loginForm = new LoginForm('loginForm'); // Formulario
		$loginForm->setInputFilter(new LoginFilter());
		
		// Validamos Peticion
		if ($request->isPost()){
			
			$data = $request->getPost(); // Obtenemos los datos que vienen por post
			$loginForm->setData($data);  // Pasamos los datos al formulario
			// Validamos el formularios
			if ($loginForm->isValid()) {
				// Obtenemos datos del formulario
				$formData = $loginForm->getData();
				
				// En el caso de que la contrase�a en la db este cifrada
				// tenemos que utilizar el mismo algoritmo de cifrado
				$bcrypt = new Bcrypt(array(
						'salt' => '$2y$05$KkFmCjGPJiC1jdt.SFcJ5uDXkF1yYCQFgiQIjjT6p.z7QIHyU1elW',
						'cost' => 5
				));
				$securePass = $bcrypt->create($formData['password']);
				// Hacemos la autenticacion
				$authService = $this->getServiceLocator()->get('AuthService');
				$authService->getAdapter()
					->setIdentity($formData['email'])
					->setCredential($securePass);
				
				// Resultado de la autenticacion
				$result      = $authService->authenticate();
				// Validamos el resultado de la autenticacion
				switch ($result->getCode()) {
				
					case Result::FAILURE_IDENTITY_NOT_FOUND:
						// hacer cosas para la identidad inexistente
						// Incrementamos en uno el numero de intentos
						$try      = $formData['try'] + 1;
						
						// Validamos el numero de intentos
						if ($try > 3){
							//die("Superaste el numero de intentos");
						}
						
						// Generamos un response
						$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response" => "fail", "tries" => $try, "code" => 0, "status" => "Credenciales invalidas")));
						
						break;
				
					case Result::FAILURE_CREDENTIAL_INVALID:
						// hacer cosas para la credencial no v�lido
						
						// Incrementamos en uno el numero de intentos
						$try      = $formData['try'] + 1;
						$status   = "";
						
						// Validamos el numero de intentos
						if ($try > 3){
							$userService      = new \System\Services\UsersService();
							//$blockUserAccount = $userService->blockUserAccount($formData['email']);
							$blockUserAccount = $this->_blockUserAccount($formData['email']);
							$status = "Cuenta bloqueada";
							//die("Vamos a bloquear tu cuenta contacta al administrador");
						}
						
						// Generamos un response
						$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response" => "fail", "tries" => $try, "code" => 2, 'status' => $status)));
					
						
						break;
				
					case Result::SUCCESS:
						// hacer cosas para la autenticaci�n exitosa
						//die("hacer cosas para la autenticaci�n exitosa");
						
						// Validamos si la autenticacion fue exitosa
						if ($result->isValid()){

							// Obtemos los datos del usuario logeado
							$userDetails = $this->_getUserDetails(array(
									'email' => $formData['email']
							), array(
									'user_id',
									'id_company',
									'name',
									'surname',
									'lastname',
									'email',
									'photofile',
									'sexo',
									'trim',
							));
							
							// Datos enviados a la vista
							$dataView = array(
									'name'      => $userDetails[0]['name'] . " " . $userDetails[0]['lastname'] . " " . $userDetails[0]['surname'],
									'email'     => $userDetails[0]['email'],
									'photofile' => $userDetails[0]['photofile'],
							);
							
							// Generamos sesion al usuario autenticado
							$userSession = $this->_getUserSession($formData, $userDetails);
							$response    = $this->getResponse()->setContent(\Zend\Json\Json::encode(array(
									"response" => "ok", 
									"tries" => $try, 
									"code" => 1, 
									"status" => "Correcto", 
									"data" => $dataView)));
							
							$id_user = $userDetails[0]['user_id'];
							$timeService = new StudyTimeService();
							$timeExist = $timeService->getTimeByUser($id_user);
							if($timeExist == null){
								$userTime = array(
										'id_user' => $id_user,
										'time'       => '00:00:00'
								);
								$timeService->addTime($userTime);
							}else{
								$checkTime = explode(":",$timeExist['time']);
								if($checkTime[0] >= 80){
									$scoreService = new ExamscoreServiceImpl();
									$idExam = 0;
									if($userDetails[0]['trim'] == 1){
										$idExam = 269;
									}elseif($userDetails[0]['trim'] ==2){
										$idExam = 340;
									}elseif($userDetails[0]['trim'] == 3){
										$idExam = 341;
									}elseif($userDetails[0]['trim'] == 4){
										$idExam = 342;
									}elseif($userDetails[0]['trim'] == 5){
										$idExam = 343;
									}elseif($userDetails[0]['trim'] == 6){
										$idExam = 344;
									}
									$checkUserScore = $scoreService->getNormalScoreByUser($id_user,$idExam);
									if($checkUserScore == null){
										$mailService = new Mail2();
										$mailService->sendEvaluationInfo2($userDetails[0]['email']);
									}
								}
							}
						}
						
						break;
				
					default:
						// hacer cosas de otra falla
						$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array(
							"response" => "fail", 
							"tries" => $try, 
							"code" => 4, 
							"status" => "Error desconocido")));
						break;
				}
				
			}else{
				// Incrementamos en uno el numero de intentos
				$try      = $data['try'] + 1;
				$errors   = $loginForm->getMessages();
				$response = $this->getResponse()->setContent(\Zend\Json\Json::encode(array("response" => "error", "tries" => $try, "code" => 3, 'status' => $errors)));;
			}
			
			return $response;
			
		}
		
		// Variables que se pasan a la vista
		$view->setVariable('loginForm', $loginForm);
		$view->setVariable('try', $try);
		
		return $view;
	}

    public function logoutAction(){
        $authService = $this->getServiceLocator()->get('AuthService');
        
        $session = new Container('User');
        $initialDate = $session->offsetGet('startHour');
        if($initialDate){
        	$id_user = $session->offsetGet('userId');
        	$timeService = new StudyTimeService();
        	$loginHistoryService = new LoginHistoryService();
        	$endDate = date('Y-m-d H:i:s');
        	$date1Timestamp = strtotime($initialDate);
        	$date2Timestamp = strtotime($endDate);
        	$totalTime = array();
        	//Calculate the difference.
        	$difference = $date2Timestamp - $date1Timestamp;
        	$totalTime['hours'] = abs(floor(($difference)/3600));
        	$totalTime['min']= abs(floor(($difference-($totalTime['hours'] * 3600))/60));#floor($difference / 60);
        	$totalTime['seg']  = abs(floor(($difference%60)));
        	$sumTime = $timeService->updateGlobalTime($id_user, $totalTime);
        	$addHistory =  $loginHistoryService->addTime($id_user,$initialDate,$endDate,$totalTime);
        }
        $session->getManager()->destroy();
        
        $authService->clearIdentity();
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/login');
    }
    
    /*
     * OBTENEMOS EL DETALLE DEL USUARIO
     */
    private function _getUserDetails($where, $columns)
    {
        $userTable = $this->getServiceLocator()->get("UserTable");
        $users     = $userTable->getUsers($where, $columns);
        return $users;
    }
    
    /*
     * OBTIENE LOGO DE LA EMPRESA
     */
    private function _getLogo()
    {
    	$preferencesTable = $this->getServiceLocator()->get("PreferencesTable");
    	$logo             = $preferencesTable->getLogo();
    	return $logo;
    }
    
    /*
     * BLOQUEAR CUENTA DE USUARIO
     */
    private function _blockUserAccount($email)
    {
    	$userTable = $this->getServiceLocator()->get("UserTable");
    	$user     = $userTable->blockUserAccount($email);
    	return $user;
    }
    
    /*
     * GENERAMOS UNA SESION DE USUARIO
     */
    private function _getUserSession($formData, $userDetails)
    {
    	//$companyService    = new CompanyService();
        //$preferenceService = new PreferencesService();
        // $companyDetails = $companyModel->getCompanyByUser($userDetails[0]['id_company']);
        //$companyDetails = $companyService->getHostCompany();
        //echo "<pre>"; print_r($companyDetails); exit;
        //$logo = $preferenceService->getLogo();
        $logo = $this->_getLogo();
        
        if($userDetails[0]['role_name']=="Alumno"){
        	$loginDate = date('Y-m-d H:i:s');
        }else{
        	$loginDate = "";
        }
      
    	$session = new Container('User');
    	$session->offsetSet('email', $formData['email']);
    	$session->offsetSet('userId', $userDetails[0]['user_id']);
    	$session->offsetSet('id_company', $userDetails[0]['id_company']);
    	$session->offsetSet('roleId', $userDetails[0]['role_id']);
    	$session->offsetSet('roleName', $userDetails[0]['role_name']);
    	$session->offsetSet('name', $userDetails[0]['name']);
    	$session->offsetSet('surname', $userDetails[0]['surname']);
    	$session->offsetSet('lastname', $userDetails[0]['lastname']);
    	$session->offsetSet('brand', "");
    	$session->offsetSet('logo', $logo[0]['value']);
    	$session->offsetSet('photofile', $userDetails[0]['photofile']);
    	$session->offsetSet('name_job', $userDetails[0]['name_job']);
    	$session->offsetSet('sexo', $userDetails[0]['sexo']);
    	$session->offsetSet('trim', $userDetails[0]['trim']);
    	$session->offsetSet('startHour', $loginDate);
    	
    	//echo "<pre>"; print_r($session->offsetGet('surname')); exit;
    	return $session;
    }
}