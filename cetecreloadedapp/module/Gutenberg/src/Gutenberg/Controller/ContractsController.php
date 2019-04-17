<?php


namespace Gutenberg;

use Zend_Controller_Action;
use clsTinyButStrong;
use clsTbsZip;



require 'tbs_us/tbs_class.php';
// require_once 'tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php';
require_once 'tbszip_2.15/tbszip.php';

class ContractsController extends Zend_Controller_Action
{

	var $yourname=null;
	var $mydata = array();
	var $parafra="hell";
	
	
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
    	$file_name = APPLICATION_PATH . '/documents/contractHorus.odt';
    	
    	$TBS = new clsTinyButStrong;
    	$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN, 'noerr');    
    	
    	$yourname = "Jesus Cardozo de Garcia";
    	
    	$TBS->VarRef['businessname'] = "IOFRACTAL, CONSULTORES EN DESARROLLO DE SISTEMAS S.A. DE C.V.";
    	
    	$TBS->VarRef['yourname'] = $yourname;
    	
    	$parafra="asasfasdf";
    	$TBS->NoErr;
    	

$mydata[] = array('rank'=> 'A', 'firstname'=>'Vane' , 'name'=>'Hill'      , 'number'=>'1523d', 'score'=>200, 'email_1'=>'sh@tbs.com',  'email_2'=>'sandra@tbs.com',  'email_3'=>'s.hill@tbs.com');
$mydata[] = array('rank'=> 'A', 'firstname'=>'Roger'  , 'name'=>'Smith'     , 'number'=>'1234f', 'score'=>800, 'email_1'=>'rs@tbs.com',  'email_2'=>'robert@tbs.com',  'email_3'=>'r.smith@tbs.com' );
$mydata[] = array('rank'=> 'B', 'firstname'=>'William', 'name'=>'Mac Dowell', 'number'=>'5491y', 'score'=>130, 'email_1'=>'wmc@tbs.com', 'email_2'=>'william@tbs.com', 'email_3'=>'w.m.dowell@tbs.com' );    	
	    	
    	$TBS->LoadTemplate($file_name); // Load the archive 'document.odt'.
    	
    	$TBS->MergeBlock('a,b', $mydata);
    	
    	$TBS->Show(OPENTBS_DOWNLOAD, 'DEFAULT.odt');
    	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
    }

    public function goodieAction()
    {
    	 
    	$file_name = APPLICATION_PATH . '/documents/goodie.html';
    	 
    	$TBS = new clsTinyButStrong;
    	//$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN, 'noerr');
    	 
    	$yourname = "Título de trabajo";
    	 
    	$TBS->VarRef['businessname'] = "IOFRACTAL, CONSULTORES EN DESARROLLO DE SISTEMAS S.A. DE C.V.";
    	 
    	$TBS->VarRef['goodie'] = $yourname;
    	 
    	
    	$this->parafra="erick";
    	
    	$TBS->VarRef['para'] = $this->parafra;
    	$TBS->NoErr;
        
    	$TBS->LoadTemplate($file_name); // Load the archive 'document.odt'.
    	 
    	//$TBS->MergeBlock('a,b');
    	
//     	$fil = $TBS->MergeBlock('goodie');
    	
    	$salida=$TBS->ShowIOF(true,'IOF');
    	
     	echo "amigos " . $salida;
     	
     	//pack images y otros archivos
    	 
     	$arc = $this->createFile($salida);
     	
     	$this->zipBook($arc);
     	
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(TRUE);
    }    
    
    function createFile($source){
    	$dire = APPLICATION_PATH .'/documents/publications/';
    	$crear = fopen($dire.'source.html',"w");
    	$grabar = fwrite($crear,$source);
    	fclose($crear);
    	return $crear;
    }
    
    function zipBook($arc){
    	
    	$zip = new clsTbsZip();
    	$zip->CreateNew();
    	
    	$zip->FileAdd("newsource.html", APPLICATION_PATH .'/documents/publications/source.html', TBSZIP_FILE, false);
    	
    	$zip->Flush($Render=TBSZIP_DOWNLOAD, $File='down2.zip'); //, $ContentType='application/octet-stream'
    	$zip->Close();
    	
    }

}

