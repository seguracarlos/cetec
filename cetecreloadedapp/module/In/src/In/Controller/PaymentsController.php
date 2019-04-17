<?php


namespace In;

use BaseController;
use PaymentsServiceImpl;
use BsProyectos;
use Zend_Validate_NotEmpty;
use Zend_Validate_Int;


include_once APPLICATION_PATH . '/controllers/BaseController.php';
require APPLICATION_PATH . '/services/impl/PaymentsServiceImpl.php';
class PaymentsController extends BaseController
{
    function init() {
    	BaseController::init();
	}
	
    // El index es por defecto el listado
    public function indexAction()
    {
    	
    	//linea para tronar el recurso
    	echo $this->baseUrl();
    	
	 $pay = new PaymentsServiceImpl();
	 $payment = $pay->getInvoi();
	 $this->view->payments =$payment;
    }
    public function crearAction()
    {
    	
    }
    public function eliminarAction()
    {
		$bsProyectos = new BsProyectos();
		$bsProyectos->eliminar( $this->_request->getParam('ID') );
    }
    public function modificarAction()
    {
		$bsProyectos = new BsProyectos();
		$this->view->mensajes = $this->_helper->_flashMessenger->getMessages();
		$this->view->proyectos = $bsProyectos->buscarPorID( $this->_request->getParam('ID') );
    }
    public function guardarAction()
    {
		// Si llega un ID por POST o GET, se guarda en esta variable.
		// Sino, se le asigna false (para determinar más adelante si se trata
		// de un alta o una modificación)
		$ID = $this->_request->getParam('ID',false);
		// Pasa el ID a la pantalla de guardar para saber si se trata de un alta
		// o una modificación
		$this->view->ID = $ID;
		
		// Preparación de los datos para la validación
		$lsnombre_proyecto = $this->_request->getParam('nombre_proyecto');
		$lsfecha_inicio = $this->_request->getParam('fecha_inicio');
		$lsfecha_terminacion = $this->_request->getParam('fecha_terminacion');
		$lsporcentaje = $this->_request->getParam('porcentaje');
		$lsstatus = $this->_request->getParam('status');
	
		// Objetos para las validaciones:
		// Para validar no vacíos
		$validator			= new Zend_Validate_NotEmpty();
		// Para validar números enteros
		$validatorInt		= new Zend_Validate_Int();
		// Para validar tres caracteres (para usar con el CountryCode)
		//$validatorLength	= new Zend_Validate_StringLength(3, 3);
		
		// Contador de errores
		$iContador = 0; 
		

		// Si no hubo errores de validación:
		if ($iContador == 0) {
			$bsProyectos = new BsProyectos();
			// Guarda en la tabla los datos ingresados (y después muestra la pantalla 'guardar')
			if ($ID) {
				$bsProyectos->modificar($ID, $lsnombre_proyecto, $lsfecha_inicio, $lsfecha_terminacion, $lsporcentaje, $lsstatus);
			} else {
				$bsProyectos->crear($lsnombre_proyecto, $lsfecha_inicio, $lsfecha_terminacion, $lsporcentaje, $lsstatus);
			}
			
		// Si hubo algún error de validación:
		} else {
			// Se crea un objeto destinado a redireccionar
			$this->_redirector = $this->_helper->getHelper('Redirector');
			// Si por POST o GET vino un ID, es porque se trata de una modificación, y sino, es un alta 
			if($ID) {
				// Si era una modificación, vuelvo al formulario de modificación para mostrar los errores
				// de validación y le paso el ID de nuevo
				$this->_redirector->gotoUrl('/proyectos/modificar/ID/' . $ID);
			} else {
				// Si era un alta, vuelvo al formulario de alta para mostrar los errores de validación
				// y para que se reingresen los datos
				$this->_redirector->gotoUrl('/proyectos/crear');
			}
		}
    }
    
	public function addAction(){}
}
