<?php
namespace Company\Controller;

use Zend\Session\Container;
use \Iofractal\Controller\BaseController;
use Company\Services\CompanyService;
use Company\Services\PreferencesService;
use Company\Services\AddressesService;
use Company\Services\UsersService;
use Company\Services\UserDetailsService;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class IndexController extends BaseController
{
	private $session;
	private $companyService;
	private $preferencesService;
	private $usersService;
	private $addressesService;
	private $userDetailsService;

	public function __construct()
	{
		$this->session = new Container('User');
	}
	protected function getCompanyService()
	{
		return $this->companyService = new CompanyService();
	}
	protected function getPreferencesService()
	{
		return $this->preferencesService = new PreferencesService();
	}
	protected function getAddressesService()
	{
		return $this->addressesService = new AddressesService();
	}
	protected function getUsersService()
	{
		return $this->usersService = new UsersService();
	}
	protected function getUserDetailsService()
	{
		return $this->userDetailsService = new UserDetailsService();
	}
	
	public function indexAction()
	{
		$data    = array(
				"userId"     => $this->session->offsetGet('userId'),
				"id_company" => $this->session->offsetGet('id_company'),
				"user_email" => $this->session->offsetGet('email'),
				"rol_name"   => $this->session->offsetGet('roleName'),
				"user_name"  => $this->session->offsetGet('name'),
				"surname"    => $this->session->offsetGet('surname'),
				"lastname"   => $this->session->offsetGet('lastname')
		);
		$getCompany = $this->getCompanyService()->getCompanyByUser($data['id_company']);
		$logo = $this->getPreferencesService()->getLogo();
		$userFavorite= $this->getUsersService()->getUserFavorite($data['id_company']);
		$data['favoriteUser'] = $userFavorite;
		$data['company'] = $getCompany;
		$data['logotipe'] = $logo;
		return new ViewModel($data);
	}
	//Ajax para actualizar el logo de la empresa
	public function updatelogoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$img = $request->getPost('image');
			$editLogo = $this->getPreferencesService()->updateImgLogo($img);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $img)));
			return $response;
		}
		exit;
	}
	//Ajax para consultar el logo de la empresa al cancelar el cambio de logo
	public function getlogoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$getLogo = $this->getPreferencesService()->getLogo();
			$logo = $getLogo[0]['value'];
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $logo)));
			return $response;
		}
		exit;
	}
	//Obtenemos la información para actualizar los datos de la empresa
	public function getinfoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$company = $request->getPost('company');
			$getCompany = $this->getCompanyService()->getCompanyByUser($company);
			$states = $this->getAddressesService()->getStates();
			$districts = $this->getAddressesService()->getDistricts($getCompany[0]['state_id']);
			$colonys = $this->getAddressesService()->getColonys($getCompany[0]['district']);
			$userFavorite= $this->getUsersService()->getUserFavorite($company);
			$jobs = $this->getUsersService()->getJobs();
			$data = array(
					'name_company' => $getCompany[0]['name_company'],
					'brand' => $getCompany[0]['brand'],
					'rfc' => $getCompany[0]['rfc'],
					'website' => $getCompany[0]['website'],
					'phone' => $getCompany[0]['phone'],
					'extension' => $getCompany[0]['ext'],
					'map' => $getCompany[0]['url_map'],
					'name_bank' => $getCompany[0]['name_bank'],
					'number_acount' => $getCompany[0]['number_acount'],
					'interbank_clabe' => $getCompany[0]['interbank_clabe'],
					'sucursal_name' => $getCompany[0]['sucursal_name'],
					'business' => $getCompany[0]['business'],
					'street' => $getCompany[0]['street'],
					'number_ext' => $getCompany[0]['number'],
					'number_int' => $getCompany[0]['interior'],
					'id_colony' => $getCompany[0]['neighborhood'],
					'colony' => $getCompany[0]['colony'],
					'postal_code' => $getCompany[0]['postalcode'],
					'id_district' => $getCompany[0]['district'],
					'district_name' => $getCompany[0]['name'],
					'id_state' => $getCompany[0]['state_id'],
					'state_name' => $getCompany[0]['state'],
					'states' => $states,
					'districts' => $districts,
					'colonys' => $colonys,
					'userFavorite' => $userFavorite,
					'jobs_users' => $jobs
			);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $data)));
			return $response;
		}
		exit;
	}
	//Ajax para obtener municipios
	public function  getdistrictsAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$state = $request->getPost('state');
			$districts = $this->getAddressesService()->getDistricts($state);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'districts' => $districts)));
			return $response;
		}
		exit();
	}
	//Ajax para obtener las colonias
	public function getcolonysAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$district = $request->getPost('district');
			$colonys = $this->getAddressesService()->getColonys($district);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'colonys' => $colonys)));
			return $response;
		}
		exit();
	}
	public function getpostalcodeAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$colony = $request->getPost('colony');
			$col = $this->getAddressesService()->getPostalCode($colony);
			$postalcode = $col[0]['postal_code'];
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'postal_code' => $postalcode)));
			return $response;
		}
		exit();
	}
	//Ajax para editar la información de la empresa
	public function updatecompanyAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$id_company = $this->session->offsetGet('id_company');
			$formData = $request->getPost();
			$getCompany = $this->getCompanyService()->updateCompany($formData,$id_company);
			$getAddressCompany = $this->getAddressesService()->updateAddressCompany($formData,$id_company);
			$getCompany = $this->getCompanyService()->getCompanyByUser($id_company);
			$userFavorite= $this->getUsersService()->getUserFavorite($id_company);
			if($userFavorite){
				$updateUserFavorite = $this->getUsersService()->editUserFavorite($userFavorite[0]['user_id'],$formData);
				$updateDetailsUser = $this->getUserDetailsService()->updateDetailsUser($updateUserFavorite[0]['user_id'],$formData);
			}
			$data = array(
					'name_company' => $getCompany[0]['name_company'],
					'brand' => $getCompany[0]['brand'],
					'rfc' => $getCompany[0]['rfc'],
					'website' => $getCompany[0]['website'],
					'phone' => $getCompany[0]['phone'],
					'extension' => $getCompany[0]['ext'],
					'map' => $getCompany[0]['url_map'],
					'name_bank' => $getCompany[0]['name_bank'],
					'number_acount' => $getCompany[0]['number_acount'],
					'interbank_clabe' => $getCompany[0]['interbank_clabe'],
					'sucursal_name' => $getCompany[0]['sucursal_name'],
					'business' => $getCompany[0]['business'],
					'street' => $getCompany[0]['street'],
					'number_ext' => $getCompany[0]['number'],
					'number_int' => $getCompany[0]['interior'],
					'id_colony' => $getCompany[0]['neighborhood'],
					'colony' => $getCompany[0]['colony'],
					'postal_code' => $getCompany[0]['postalcode'],
					'id_district' => $getCompany[0]['district'],
					'district_name' => $getCompany[0]['name'],
					'id_state' => $getCompany[0]['state_id'],
					'state_name' => $getCompany[0]['state'],
					'userFavorite' => $userFavorite
			);
			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'data' => $data)));
			return $response;
		}
		exit();
	}
}