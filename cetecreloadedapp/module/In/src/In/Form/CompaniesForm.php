<?php
namespace In\Form;

use Zend\Form\Form;
use Iofractal\Services\StatesServices;
use Horus\Services\JobsServices;
use System\Services\RoleService;

class CompaniesForm extends Form
{
	
	public function __construct($name = null, $type)
	{
		
		parent::__construct($name);
		
		$this->setAttributes(array(
				'action'=>"",
				'method' => 'post'
		));
		
		/*********** VALIDACIONES DE CAMPOS REQUERIDOS ***********/
		
		/* 
		 * 1 - Cliente, 2 - Proveedor, 3 - Prospecto  
		 */
		if($type == 1){
			// Campos Informacion Comercial  
			$rfc_class           = "form-control input-lg input-required";
			$brand_class         = "form-control input-lg input-required";
			$business_class      = "form-control input-lg input-required";
			$interestingin_class = "form-control input-lg";
			// Campos Direccion
			$street_class        = "form-control input-lg input-required";
			$number_class        = "form-control input-lg input-required";
			$state_class         = "form-control input-lg input-required";
			$district_class      = "form-control input-lg input-required";
			$neighborhood_class  = "form-control input-lg input-required";
			$code_postal         = "form-control input-lg input-required";
		}elseif ($type == 2){
			// Campos Informacion Comercial
			$rfc_class           = "form-control input-lg input-required";
			$brand_class         = "form-control input-lg";
			$business_class      = "form-control input-lg";
			$interestingin_class = "form-control input-lg";
			// Campos Direccion
			$street_class        = "form-control input-lg input-required";
			$number_class        = "form-control input-lg input-required";
			$state_class         = "form-control input-lg input-required";
			$district_class      = "form-control input-lg input-required";
			$neighborhood_class  = "form-control input-lg input-required";
			$code_postal         = "form-control input-lg input-required";
		}elseif ($type == 3){
			// Campos Informacion Comercial
			$rfc_class           = "form-control input-lg";
			$brand_class         = "form-control input-lg";
			$business_class      = "form-control input-lg";
			$interestingin_class = "form-control input-lg input-required";
			// Campos Direccion
			$street_class        = "form-control input-lg";
			$number_class        = "form-control input-lg";
			$state_class         = "form-control input-lg";
			$district_class      = "form-control input-lg";
			$neighborhood_class  = "form-control input-lg";
			$code_postal         = "form-control input-lg";
		}
		
		/*********** DATOS DE GENERALES ***********/
		
		/** TIPO COMPANIA **/
		$this->add(array(
				'name' => 'type_company',
				'type' => 'Hidden',
				'attributes' => array(
						'class'    => 'form-control input-lg',
				)
		));
		
		/*
		 * TIPO DE CLIENTE
		 */
		$this->add(array(
				'name' => 'type_client',
				'type' => 'Select',
				'options' => array (
						'label' => 'Tipo de cliente:',
						'empty_option' => '--selecciona--',
						'value_options' => array(
								array('value' => '1', 'label' => 'Interno'),
						 		array('value' => '2', 'label' => 'Mayoristas'),
								array('value' => '3', 'label' => 'Representantes'),
						),
				),
				'attributes' => array(
						'id' => 'type_client',
						'class' => 'form-control input-lg',
				)
		));
		
		/** ID COMPANIA **/
		$this->add(array(
				'name' => 'id_company',
				'type' => 'Hidden',
		));
		
		/** NOMBRE COMPANIA **/
		$this->add(array(
				'name' => 'name_company',
				'type' => 'Text',
				'options' => array(
						'label' => 'Nombre o Razon social:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Nombre',
						'required'    => 'required',
				)
		));
		
		/** RFC **/
		$this->add(array(
				'name' => 'rfc',
				'type' => 'Text',
				'options' => array(
						'label' => 'RFC:',
				),
				'attributes' => array(
						'class'       => $rfc_class,
						'placeholder' => 'RFC',
				)
		));
		
		/** WEBSITE **/
		$this->add(array(
				'name' => 'website',
				'type' => 'Text',
				'options' => array(
						'label' => 'Sitio web:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => 'http://texto.com',
				)
		));
		
		/** GIRO DE COMPANIA **/
		$this->add(array(
				'name' => 'business',
				'type' => 'Text',
				'options' => array(
						'label' => 'Giro de la empresa:',
				),
				'attributes' => array(
						'class'       => $business_class,
						'placeholder' => 'Giro de la empresa',
				)
		));
		
		/** MARCA **/
		$this->add(array(
				'name' => 'brand',
				'type' => 'Text',
				'options' => array(
						'label' => 'Marca:',
				),
				'attributes' => array(
						'class'       => $brand_class,
						'placeholder' => 'Marca',
				)
		));
		
		/** INTERESADO EN **/
		$this->add(array(
				'name' => 'interestingin',
				'type' => 'Text',
				'options' => array(
						'label' => 'Interesado en:',
				),
				'attributes' => array(
						'class'       => $interestingin_class,
						'placeholder' => 'Interesado en',
				)
		));
		
		/*********** DATOS DE DIRECCION ***********/
		
		/** CALLE **/
		$this->add(array(
				'name' => 'street',
				'type' => 'Text',
				'options' => array(
						'label' => 'Calle:',
				),
				'attributes' => array(
						'class'       => $street_class,
						'placeholder' => 'Calle',
				)
		));
		
		/** NUMERO EXTERIOR **/
		$this->add(array(
				'name' => 'number',
				'type' => 'Text',
				'options' => array(
						'label' => 'Numero exterior:',
				),
				'attributes' => array(
						'class'       => $number_class,
						'placeholder' => 'Numero exterior',
				)
		));
		
		/** NUMERO INTERIOR **/
		$this->add(array(
				'name' => 'interior',
				'type' => 'Text',
				'options' => array(
						'label' => 'Numero interior:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => 'Numero interior',
				)
		));
		
		/** ESTADO **/
		$this->add(array(
				'name' => 'state_id',
				'type' => 'Select',
				'options' => array (
						'label' => 'Estado:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllStatesOfMexico(),
				),
				'attributes' => array(
						'id'    => 'state_id',
						'class' => $state_class,
				)
		));
		
		/** DELEGACION O MUNICIPIO **/
		$this->add(array(
				'name' => 'district',
				'type' => 'Select',
				'options' => array (
						'label' => 'Delegacion o municipio:',
						'empty_option' => '--selecciona--',
				),
				'attributes' => array(
						'id'    => 'district',
						'class' => $district_class,
				)
		));
		
		/** COLONIA **/
		$this->add(array(
				'name' => 'neighborhood',
				'type' => 'Select',
				'options' => array (
						'label' => 'Colonia:',
						'empty_option' => '--selecciona--',
						/*'value_options' => array(
							array('value' => 'alt', 'label' => 'Alternative'),
							array('value' => 'country', 'label' => 'Country'),
							array('value' => 'jazz', 'label' => 'Jazz'),
							array('value' => 'rock', 'label' => 'Rock'),
						),*/
				),
				'attributes' => array(
						'id' => 'neighborhood',
						'class' => $neighborhood_class,
				)
		));
		
		/** CODIGO POSTAL **/
		$this->add(array(
				'name' => 'postalcode',
				'type' => 'Text',
				'options' => array(
						'label' => 'Codigo postal:',
				),
				'attributes' => array(
						'id'          => 'postalcode',
						'class'       => $code_postal,
						'placeholder' => 'C.P.',
				)
		));
		
		/** TELEFONO **/
		$this->add(array(
				'name' => 'phone',
				'type' => 'Text',
				'options' => array(
						'label' => 'Telefono:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => '(+52) 55-555-555',
				)
		));
		
		/** EXTENSION **/
		$this->add(array(
				'name' => 'ext',
				'type' => 'Text',
				'options' => array(
						'label' => 'Extension:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => 'Ext',
				)
		));
		
		/** URL MAPA **/
		$this->add(array(
				'name' => 'url_map',
				'type' => 'Text',
				'options' => array(
						'label' => 'Extension:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => 'http://texto.com',
				)
		));
		
		/*********** DATOS DE CONTACTO ***********/
		
		/** ID CONTACTO **/
		$this->add(array(
				'name' => 'user_id',
				'type' => 'Hidden',
				'attributes' => array(
						'class'    => 'form-control input-lg',
				)
		));
		
		/** NOMBRE CONTACTO **/
		$this->add(array(
				'name' => 'name_contact',
				'type' => 'Text',
				'options' => array(
						'label' => 'Nombre:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Nombre',
				)
		));
		
		/** APELLIDO PATERNO CONTACTO **/
		$this->add(array(
				'name' => 'surname_contact',
				'type' => 'Text',
				'options' => array(
						'label' => 'Apellido paterno:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'Ap. paterno',
				)
		));
		
		/** APELLIDO MATERNO CONTACTO **/
		$this->add(array(
				'name' => 'lastname_contact',
				'type' => 'Text',
				'options' => array(
						'label' => 'Apellido materno:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => 'Ap. materno',
				)
		));
		
		/** CORREO CONTACTO **/
		$this->add(array(
				'name' => 'mail_contact',
				'type' => 'Text',
				'options' => array(
						'label' => 'E-mail:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg input-required',
						'placeholder' => 'example@example.com',
				)
		));
		
		/** TELEFONO CONTACTO **/
		$this->add(array(
				'name' => 'phone_contact',
				'type' => 'Text',
				'options' => array(
						'label' => 'Telefono local:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => '(+52) 55-555-555',
				)
		));
		
		/** TELEFONO CELULAR CONTACTO **/
		$this->add(array(
				'name' => 'cellphone_contact',
				'type' => 'Text',
				'options' => array(
						'label' => 'Movil:',
				),
				'attributes' => array(
						'class'       => 'form-control input-lg',
						'placeholder' => '044-55-5555-5555',
				)
		));
		
		/** PUESTO CONTACTO **/
		$this->add(array(
				'name' => 'id_job',
				'type' => 'Select',
				'options' => array (
						'label' => 'Puesto:',
						'empty_option'  => '--selecciona--',
						'value_options' => $this->getAllJobs(),
				),
				'attributes' => array(
						'id'       => 'id_job',
						'class'    => 'form-control input-lg input-required',
				)
		));
		
		/*********** INFORMACION BANCARIA ***********/
		
		/** NOMBRE BANCO **/
		$this->add(array(
				'name'   => 'name_bank',
				'type'   => 'Text',
				'attributes' => array(
						'id'          => 'name_bank',  
						'class'       => 'form-control input-lg',
						'placeholder' => 'Banco',
				)
		));
		
		/** NUMERO DE CUENTA **/
		$this->add(array(
				'name'   => 'number_acount',
				'type'   => 'Text',
				'attributes' => array(
						'id'          => 'number_acount',
						'class'       => 'form-control input-lg',
						'placeholder' => 'N. Cuenta',
				)
		));
		
		/** CLABE **/
		$this->add(array(
				'name'   => 'interbank_clabe',
				'type'   => 'Text',
				'attributes' => array(
						'id'          => 'interbank_clabe',
						'class'       => 'form-control input-lg',
						'placeholder' => 'CLABE',
				)
		));
		
		/** SUCURSAL **/
		$this->add(array(
				'name'   => 'sucursal_name',
				'type'   => 'Text',
				'attributes' => array(
						'id'          => 'sucursal_name',
						'class'       => 'form-control input-lg',
						'placeholder' => 'Sucursal',
				)
		));
		
		/*********** DATOS DE SEGURIDAD ***********/
		
		/** PUEDE INICIAR SESION **/
		$this->add(array(
				'name' => 'canlogin',
				'type'   => 'Checkbox',
				'attributes' => array(
						'id'     => 'canlogin',
				)
		));
		
		$this->add(array(
				'name' => 'select-role',
				'type' => 'Select',
				'options' => array (
						'label' => 'Roles Disponibles:',
						'empty_option' => "--selecciona--",
						'value_options' => $this->getAllRoles()
				),
				'attributes' => array(
						'id'       =>'select-role',
						'class' => 'form-control input-lg',
						'disabled' => true,
				)
		));
		
		/** CLAVE DE USUARIO **/
		$this->add(array(
				'name'   => 'user_name',
				'type'   => 'Text',
				'attributes' => array(
        				'class'       => 'form-control input-lg',
        				//'required'    => 'required',
        				'placeholder' => 'Clave usuario',
        		)
		));
		
		/** CONTRASENA **/
		$this->add(array(
				'name'   => 'password',
				'attributes' => array(
						'type'        => 'Password',
						'class'       => 'form-control input-lg',
						//'required'    => 'required',
						'placeholder' => '********',
				)
		));
		
		/*********** BOTON GUARDAR ***********/
		
		/** BOTON SUBMIT **/
		$this->add(array(
				'name' => 'submitbutton',
				'type' => 'Submit',
				'attributes' => array(
						'value' => 'Guardar',
						'id'    => 'submitbutton',
						'class' => 'btn btn-info btn-lg btn-block',
				),
		));
		
	}
	
	// Funcion que trae todos los estados de Mexico disponibles
	public function getAllStatesOfMexico()
	{
		$statesServices = new StatesServices();
		$statesOfMexico = $statesServices->fetchAll();
		$result      = array();
	
		foreach ($statesOfMexico as $s_m){
			//$result[] = ['attributes'=> ['data-est'=>$s_m['id']], 'value' => $s_m['id'], 'label' => $s_m['state'] ];
			$result[$s_m['id']] = $s_m['state'];
		}
	
		return $result;
	}
	
	// Funcion que trae todos los puestos disponibles
	public function getAllJobs()
	{
		$jobsServices = new JobsServices();
		$jobs         = $jobsServices->fetchAll();
		$result       = array();
	
		foreach ($jobs as $j){
			//$result[] = ['attributes'=> ['data-est'=>$s_m['id']], 'value' => $s_m['id'], 'label' => $s_m['state'] ];
			$result[$j['id']] = $j['name_job'];
		}
	
		return $result;
	}
	
	//Funcion que trae todos los roles disponibles
	public function getAllRoles()
	{
		$roleService = new RoleService();
		$roles       = $roleService->fetchAll();
		$result      = array();
		//echo "<pre>"; print_r($roles); exit;
		foreach ($roles as $rol){
			$result[] = ['attributes'=> ['data-user'=>$rol['type_user']], 'value' => $rol['rid'], 'label' => $rol['role_name'] ];
			//$result[$rol['rid']] = $rol['role_name'];
		}
	
		return $result;
	}
}