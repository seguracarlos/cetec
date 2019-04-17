<?php
namespace Horus\Controller;

use Zend\View\Model\ViewModel;
use Iofractal\Controller\BaseController;

use In\Services\ShippingServices;
use System\Services\UsersService;
use In\Services\CompaniesServices;
use Horus\Services\InventoryService;
use Enlasa\Services\AssistanceService;

class WelcomeController extends BaseController
{
	
	/*
	 * TODOS LOS VIAJES ACTIVOS
	 */
	private function getAllShippingsActive()
	{
		$serviceShipping = new ShippingServices(); // Servicio de viajes
		$shippingsActive = $serviceShipping->getAllShippingsActive(); // Todos los viajes activos
		return $shippingsActive;
	}
	
	/*
	 * EMPLEADOS MAS ACTIVOS EN VIAJES
	 */
	private function getAllUsersActive()
	{
		$servieUsers = new UsersService();
		$usersActive = $servieUsers->getAllUsersActive(1);
		return $usersActive;
	}
	
	/*
	 * CLIENTES MAS FRECUENTES CON VIAJES
	 */
	private function getAllClientsActive()
	{
		$serviceCompany = new CompaniesServices();
		$clientsActive  = $serviceCompany->getAllClientsActive(1);
		return $clientsActive;
	}
	
	/*
	 * CAMIONES EN VIAJES
	 */
	private function getAllTruckActive()
	{
		$serviceInventory = new InventoryService();
		$trucksActive     = $serviceInventory->getAllTruckActive();
		return $trucksActive;
	}
	
	/*
	 * CAMIONES DISPONIBLES
	 */
	private function getAllTruckAvailable()
	{
		$serviceInventory = new InventoryService();
		$trucksAvailable  = $serviceInventory->getTrucksAvailable(5);
		return $trucksAvailable;
	}
	
	/*
	 * OBTENER ASISTENCIA DE LOS EMPLEADOS EL DIA DE HOY
	 */
	public function getAllEmployeeAttendanceToday()
	{
		$assistanceService = new AssistanceService();
		$getAllEmployeeAttendanceToday = $assistanceService->getAllEmployeeAttendanceToday();
		//echo "<pre>"; print_r($getAllEmployeeAttendanceToday); exit;
		return $getAllEmployeeAttendanceToday;
	}
	
	/*
	 * INDEXACTION
	 */
	public function indexAction()
	{
		$dataSesion = parent::getAllDataSesionUser();
		
		$permisosPorRole = new \System\Model\RoleModel();
		//echo "<pre>"; print_r($permisosPorRole->obtenerPermisosPorRole($dataSesion['roleId']));
		//exit;
		
		// Validamos que tipo de rol tiene para mostrar el contenido
		/*if ($dataSesion['rol_name'] == "SuperUsuario" || $dataSesion['rol_name'] == "Administrador"){
			$view = array(
					"shippings_active" => array(),//$this->getAllShippingsActive(),
					"users_active"     => array(),//$this->getAllUsersActive(),
					"clients_active"   => array(),//$this->getAllClientsActive(),
					"trucks_active"    => array(),//$this->getAllTruckActive(),
					"trucks_available" => array(),//$this->getAllTruckAvailable(),
					"employee_Attendance_today" => array(),//$this->getAllEmployeeAttendanceToday(),
			);
			$viewModel = new ViewModel($view);
		}else{*/
			$viewModel = new ViewModel(array(
					"user_name" => $dataSesion['user_name'],
					"surname"   => $dataSesion['surname'],
					"lastname"  => $dataSesion['lastname'],
					"photo_file" => $dataSesion['photofile'],
					"rol_name"  => $dataSesion['rol_name'],
					"name_job"  => $dataSesion['name_job'],
					"sexo"      => $dataSesion['sexo'],
			));
// 			if($dataSesion["rol_name"] == "SuperUsuario"){
// 				$viewModel->setTemplate("horus/welcome/index.phtml");
// 			}else{
 				$viewModel->setTemplate("horus/welcome/comun.phtml");
// 			}
		//}
		
		return $viewModel;
	}
}