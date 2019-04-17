<?php
namespace System\Form;
 
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;
use System\Services\RoleService;
use Horus\Services\JobsServices;
use Horus\Services\DepartmentsServices;
use In\Services\CompaniesServices;
use Iofractal\Services\StatesServices;
 
class UserForm extends Form
{
    public function __construct($name = null,$form = null)
     {
        parent::__construct($name);
       
       if ($form == 1){
       	$classForm = "";
       }else{
       	$classForm = "form-horizontal";
       }
       //$this->setInputFilter(new \Users\Form\UserFormValidator());
       $this->setAttribute('class', $classForm);
         
       $this->setAttributes(array(
       		'action' => "",
            'method' => 'post'
        ));
       
       
       $this->add(array(
       		'name' => 'user_id',
       		'attributes' => array(
       			'type' => 'hidden'
       		)
       ));
       
       /* Tipo de usuario */
       $this->add(array(
       		'name' => 'user_type',
       		'type' => 'radio',
       		'options' => array(
       				'value_options' => array(
       						'1' => 'Interno',
       						'2' => 'Externo',
       				),
       		),
       ));
       
      /* image user */
      $this->add(array(
       		'name' => 'file',
       		'attributes' => array(
       			'type'   => 'file',
       			'id'     => 'file',
       			'accept' => 'image/*',
       		),
       		'options' => array(
       				'label' => 'File Upload',
      	),
      ));
      
      /* Name image user*/
      $this->add(array(
      		'name' => 'photofilename',
      		'attributes' => array(
      				'type'   => 'hidden',
      				'id'     => 'photofilename',
      		)
      ));
      
      /* Value image user*/
      $this->add(array(
      		'name' => 'photofile',
      		'attributes' => array(
      				'type'   => 'hidden',
      				'id'     => 'photofile',
      		)
      ));
      
      /*
       * CAMPOS DONDE SE GUARDAN IMAGENES DE DOCUMENTOS (ENLASA)
       */
      
      // IMAGEN DE IFE
      $this->add(array(
      		'name' => 'photofile_ife',
      		'attributes' => array(
      				'type'   => 'hidden',
      				'id'     => 'photofile_ife',
      		)
      ));
      
      // IMAGEN DE LICENCIA DE CONDUCIR
      $this->add(array(
      		'name' => 'photofile_license',
      		'attributes' => array(
      				'type'   => 'hidden',
      				'id'     => 'photofile_license',
      		)
      ));
      
      // IMAGEN DE CERTIFICACION
      $this->add(array(
      		'name' => 'photofile_certification',
      		'attributes' => array(
      				'type'   => 'hidden',
      				'id'     => 'photofile_certification',
      		)
      ));
      
      /*
       * END CAMPOS DONDE SE GUARDAN IMAGENES DE DOCUMENTOS (ENLASA)
       */
      
      $this->add(array(
      		'name' => 'canlogin',
      		'type'   => 'checkbox',
      		'attributes' => array(
      				'id'     => 'canlogin',
      		)
      ));
      
      // Usuario principal
      $this->add(array(
      		'name' => 'user_principal',
      		'type'   => 'checkbox',
      		'attributes' => array(
      				'id'     => 'user_principal',
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
      				'class'       => 'form-control input-lg',
      				'placeholder' => 'RFC',
      		)
      ));
      
      /* CURP */
      $this->add(array(
      		'name' => 'curp',
      		'type' => 'Text',
      		'attributes' => array(
      				'id'    => 'curp',
      				'class' => 'form-control input-lg',
      				'placeholder' => 'CURP'
      		)
      ));
      
      /* NUMERO SS */
      $this->add(array(
      		'name' => 'number_ss',
      		'type' => 'Text',
      		'attributes' => array(
      				'id'    => 'number_ss',
      				'class' => 'form-control input-lg',
      				'placeholder' => 'Numero IMSS.'
      		)
      ));
      
      /* DESCUENTO SEGURO SOCIAL*
       */
      $this->add(array(
      		'name' => 'discount_ss',
      		'type' => 'Text',
      		'options' => array(
      				'label' => 'Descuento S.S.:',
      		),
      		'attributes' => array(
      				'id'          => 'discount_ss',
      				'class'       => 'form-control input-lg',
      				'placeholder' => 'Descuento S.S.',
      		)
      ));
      
      /** FECHA INGRESO **/
      $this->add(array(
      		'name' => 'date_admission',
      		'type' => 'Text',
      		'options' => array(
      				'label' => 'Fecha Admision:',
      		),
      		'attributes' => array(
      				'id'          => 'date_admission',
      				'class'       => 'form-control input-lg',
      				'placeholder' => '01/01/2015',
      		)
      ));
      
      /** FECHA NACIMIENTO **/
      $this->add(array(
      		'name' => 'birthday',
      		'type' => 'Text',
      		'options' => array(
      				'label' => 'Fecha nacimiento:',
      		),
      		'attributes' => array(
      				'id'          => 'birthday',
      				'class'       => 'form-control input-lg',
      		)
      ));
      
      /** SUELDO **/
      $this->add(array(
      		'name' => 'cost',
      		'type' => 'Text',
      		'options' => array(
      				'label' => 'Costo:',
      		),
      		'attributes' => array(
      				'class'       => 'form-control input-lg',
      				'placeholder' => 'Salario',
      		)
      ));
      
      /** PERIODO **/
      $this->add(array(
      		'name' => 'period',
      		'type' => 'Radio',
      		'options' => array(
      				'value_options' => array(
      						'1' => 'Acta de nacimiento',
      						'2' => 'Certificado de estudios',
      						'3' => 'Comprobante de domicilio',
      				),
      		),
      ));
      
      /** MODO DE PAGO **/
      $this->add(array(
      		'name' => 'mannerofpayment',
      		'type' => 'Radio',
      		'options' => array(
      				'value_options' => array(
      						'1' => 'Tranferencia',
      						'2' => 'Cheque',
      						//'3' => 'Honorario',
      				),
      		),
      ));
      
      /** DEPARTAMENTO **/
      $this->add(array(
      		'name' => 'id_department',
      		'type' => 'Select',
      		'options' => array (
      				'label' => 'Departamento:',
      				'empty_option' => '--selecciona--',
      				'value_options' => $this->getAllDepartments()
      		),
      		'attributes' => array(
      				'id'    =>'id_department',
      				'class' => 'form-control input-lg'
      		)
      ));
      
      /* TIPO DE CONTRATO */
      $this->add(array(
      		'name' => 'contract_type',
      		'type' => 'Select',
      		'options' => array(
      				'label'         => 'Tipo de contrato',
      				'empty_option'  => '--Seleccione--',
      				'value_options' => array("1" => "Temporal", "2" => "Permanente")
      		),
      		'attributes' => array(
      				'id'    => 'contract_type',
      				'class' => 'form-control input-lg'
      		)
      ));
      
      /* TELEFONO LOCAL */
      $this->add(array(
      		'name' => 'local_phone',
      		'type' => 'Text',
      		'attributes' => array(
      				'id'    => 'local_phone',
      				'class' => 'form-control input-lg',
      				'placeholder' => '(+52)55-555-555'
      		)
      ));
      
      /* TELEFONO CELULAR */
      $this->add(array(
      		'name' => 'cellphone',
      		'type' => 'Text',
      		'attributes' => array(
      				'id'    => 'cellphone',
      				'class' => 'form-control input-lg',
      				'placeholder' => '044-55-5555-5555'
      		)
      ));
      
      /** PUESTO **/
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
      				'class'    => 'form-control input-lg',
      		)
      ));
         
      $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Correo: ',
            ),
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control input-lg',
                //'required' => 'required',
				'placeholder' => 'Correo',
            )
        ));
         
         $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Contraseña: ',
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control input-lg',
                //'required'=>'required',
				'placeholder' => '********',
            )
        ));
          
		$this->add(array(
			'name' => 'name',
			'options' => array(
				'label' => 'Nombre:'
			),
			'attributes' => array(
				'type'     => 'text',
				'id'       => 'name',
				'class'    => 'form-control input-lg',
				//'required' => 'required',
				'placeholder' => 'Nombre',
            )
        ));
         
        $this->add(array(
            'name' => 'surname',
            'options' => array(
                'label' => 'Apellido paterno: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control input-lg',
                //'required'=>'required',
				'placeholder' => 'Apellido paterno',
            )
        ));
        
        $this->add(array(
        	'name'    => 'lastname',
        	'options' => array(
        		'label' => 'Apellido materno:'
        	),
        	'attributes' => array(
        		'type'  => 'text',
        		'class' => 'form-control input-lg',
        		//'required' => 'required',
				'placeholder' => 'Apellido materno',
        	)
        ));
        
        $this->add(array(
        		'name'    => 'user_name',
        		'options' => array(
        				'label' => 'Clave usuario:'
        		),
        		'attributes' => array(
        				'type'  => 'text',
        				'class' => 'form-control input-lg',
        				//'required' => 'required',
        				'placeholder' => 'Clave usuario',
        		)
        ));
        
        $this->add(array(
        	'name' => 'role',
        	'type' => 'Select',
        	'options' => array (
        		'label' => 'Roles Disponibles:',
        		'empty_option' => "--selecciona--",
        		'value_options' => $this->getAllRoles()
        	),
        	'attributes' => array(
        		'id'       =>'role',
        		'class' => 'form-control input-lg',
        		//'required' => 'required',
        	)
        ));
        
        // Compañias disponibles
        $this->add(array(
        		'name' => 'id_company',
        		'type' => 'Select',
        		'options' => array (
        				'label' => 'Compañias Disponibles:',
        				'empty_option'  => '--selecciona--',
        				'value_options' => $this->getAllSuppliers(),//array("0"=>"uno", "1"=>"dos","2"=>"tres")
        		),
        		'attributes' => array(
        				'id'       => 'id_company',
        				'class'    => 'form-control input-lg',
        				//'required' => 'required',
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
        				'class'       => 'form-control input-lg',
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
        				'class'       => 'form-control input-lg',
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
        				'class' => 'form-control input-lg',
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
        				'class' => 'form-control input-lg',
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
        				'class' => 'form-control input-lg',
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
        				'class'       => 'form-control input-lg',
        				'placeholder' => 'C.P.',
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
 	
        /* BOTON SUBMIT */
        $this->add(array(
            'name' => 'saveUser',
            'attributes' => array(    
            	'id'    => 'saveUser',
                'type'  => 'submit',
                'value' => 'Enviar',
                'title' => 'Guardar',
                'class' => 'btn btn-info btn-lg '
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
     
     // Obtenemos todas las companias proveedores
     public function getAllSuppliers()
     {
     	$supplierService = new CompaniesServices();
     	$suppliers       = $supplierService->fetchAll(2);
     	$result          = array();
     
     	foreach ($suppliers as $row){
     		$result[$row['id_company']] = $row['name_company'];
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
     
     // Funcion que obtiene todos los departamentos disponibles
     public function getAllDepartments()
     {
     	$departments_services = new DepartmentsServices();
     	$departments          = $departments_services->fetchAll();
     	$result               = array();
     	 
     	foreach ($departments as $dep){
     		$result[$dep['id_department']] = $dep['d_name'];
     	}
     	//echo "<pre>"; print_r($result); exit;
     	return $result;
     }
}
 
?>