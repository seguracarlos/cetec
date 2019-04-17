<?php
namespace Horus\Form;
 
use Zend\Form\Form;
use Horus\Services\TypesServices;
use Horus\Services\InventoryArticlesServices;
use Horus\Services\InventoryStateServices;
use Horus\Services\DepartmentsServices;
use System\Services\UsersService;
 
class InventoryForm extends Form
{
    public function __construct($name = null)
    {
		parent::__construct($name);
         
       //$this->setInputFilter(new \Horus\Form\InventoryFormValidator());
         
       $this->setAttributes(array(
       		'action'=>"",
            'method' => 'post'
        ));
       
       $this->add(array(
       		'name' => 'id_inventories',
       		'attributes' => array(
       			'type' => 'hidden'
       		)
       ));
       
       /** TIPO DE UNIDAD SECA/REFRIGERADA **/
       $this->add(array(
       		'name' => 'chilled_dry',
       		'type' => 'radio',
       		'options' => array(
       				'value_options' => array(
       						'1' => 'Seca',
       						'2' => 'Refrigerada',
       				),
       		),
       ));
        
       	/*No serie*/
		$this->add(array(
            'name' => 'serialnumber',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control input-lg'
            )
        ));
        
        /*Monto*/
        $this->add(array(
        		'name' => 'amount',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /*Marca*/
        $this->add(array(
        		'name' => 'brand',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /*Modelo*/
        $this->add(array(
        		'name' => 'model',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /*Identificador del articulo (Placa)*/
        $this->add(array(
        		'name' => 'id_product',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /*Capacidad*/
        $this->add(array(
        		'name' => 'capacity',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /*Estado*/
        $this->add(array(
        	'name' => 'state',
        	'type' => 'Select',
        	'options' => array (
        		'label' => 'Estados Disponibles:',
        		'empty_option' => '--selecciona--',
        		'value_options' => $this->getAllStates()
        	),
        	'attributes' => array(
        		'id'       =>'state',
        		'class' => 'form-control input-lg'
        	)
        ));
        
        /*Descripcion*/
        $this->add(array(
        		'name'      => 'description',
        		'attributes'=> array(
        				'type'  => 'textarea',
        				'rows'  => 5,
        				'class' => 'form-control input-lg',
        		)
        ));
        
        /*Tipos de equipo*/
        $this->add(array(
        		'name' => 'types_id_types',
        		'type' => 'Select',
        		'options' => array (
        				'label' => '* Tipo:',
        				'empty_option' => '--selecciona--',
        				'value_options' => $this->getAllTypes()
        		),
        		'attributes' => array(
        				'id'       => 'types_id_types',
        				'class'    => 'form-control input-lg',
        				'required' => 'required'
        		)
        ));
        
        /*Articulos disponibles*/
        /*$this->add(array(
        	'name' => 'article',
        	'type' => 'Select',
        	'options' => array (
        		'label' => 'Articulo:',
        		'empty_option' => '--selecciona--',
        		'value_options' => $this->getAllInventoryArticles()
        			//[
        				//	['attributes'=>['data-key'=>'value'],'value'=>'myValue','label'=>'myLabel'],
        				//	['attributes'=>['data-key'=>'value'],'value'=>'myValue','label'=>'myLabel2']
        			//]
        	),
        	'attributes' => array(
        		'id'    =>'article',
        		'class' => 'form-control input-lg'
        	)
        ));*/
        $this->add(array(
        		'name' => 'article',
        		'attributes' => array(
        				'type'  => 'text',
        				'id'    =>'article',
        				'class' => 'form-control input-lg',
        				'required' => 'required'
        		)
        ));
        
        /* Asignado a */
        $this->add(array(
        		'name' => 'id_employee',
        		'type' => 'Select',
        		'options' => array (
        				'label' => 'Asignado a:',
        				'empty_option' => '--selecciona--',
        				'value_options' => $this->getAllEmployees()
        		),
        		'attributes' => array(
        				'id'    =>'id_employee',
        				'class' => 'form-control input-lg'
        		)
        ));
        
        /* Departamento */
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
 
        $this->add(array(
            'name' => 'saveInventory',
            'attributes' => array(    
            	'id'    => 'saveInventory',
                'type'  => 'submit',
                'value' => 'Guardar',
                'title' => 'Guardar',
                'class' => 'btn btn-info btn-lg btn-block'
            ),
        ));
     }
     
     // Funcion que trae todos los tipos disponibles
     public function getAllTypes()
     {
     	$typeService = new TypesServices();
     	$roles       = $typeService->fetchAll();
     	$result      = array();
     	foreach ($roles as $rol){
     		$result[$rol['id_types']] = $rol['description'];
     	}
     	return $result;
     }
     
     // Funcion que trae todos los articulos disponibles
     public function getAllInventoryArticles()
     {
     	$inventoryArticlesService = new InventoryArticlesServices();
     	$inventory_articles       = $inventoryArticlesService->fetchAll();
     	$result      = array();
     	
     	foreach ($inventory_articles as $i_a){
     		$result[] = ['attributes'=> ['data-art'=>$i_a['id_types']], 'value' => $i_a['id'], 'label' => $i_a['name_article'] ];
     	}
     	
     	return $result;
     }
     
     // Funcion que trae todos los estados disponibles
     public function getAllStates()
     {
     	$inventoryStateService = new InventoryStateServices();
     	$roles       = $inventoryStateService->fetchAll();
     	$result      = array();
     	foreach ($roles as $rol){
     		$result[$rol['id_state_Inventory']] = $rol['conditions'];
     	}
     	return $result;
     }
     
     // Funcion que obtiene todos los empleados disponibles
     public function getAllEmployees()
     {
     	$users_services = new UsersService();
     	$employess      = $users_services->fetchAll(1);
     	$result         = array();
     	
     	foreach ($employess as $emp){
     		$result[$emp['user_id']] = $emp['name']." ".$emp['surname']." ".$emp['lastname']; 
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
     
     	return $result;
     }
}
 
?>