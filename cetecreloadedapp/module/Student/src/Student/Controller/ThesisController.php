<?php

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

class ThesisController extends AbstractActionController
{

    public function indexAction()
    {	
 
        $layout = $this->layout();
      $layout->setTemplate('/layout/classlayout.phtml');
        

        //layout que opera con el loader
        //$layout->setTemplate('/layout/layout.phtml'); 
    	
        //$this->layout('layout/classlayout');
        return new ViewModel();
    }

    public function loaderAction()
    {
/*      static::ROUTE_LOGIN
*/
        $request=$this->getRequest();
    	if($request->isPost()){
            $inf = $request->getPost();
          
            
            $trimestre = 1;

          /*  if($inf['id']==1){*/

                //return $this->redirect()->toUrl("portafolio");

                $id=$inf['id']+1;

               echo $id;

        //   exit();

/*                $data = array(
                    'idval' => $id
                );*/

                $viewModel = new ViewModel();
                $viewModel->setVariable('datos', $id);
                $viewModel->setTemplate('student/thesis/'.$trimestre.'/xx0'.$inf['id']);
                return $viewModel;



    /*        }
            else
            {
                return $this->redirect()->toUrl("portafolio2");
            }*/

           // return $this->redirect()->toUrl("portafolio");
            
         //   return $this->redirect()->toRoute("xx01");

            //exit();

        }
    }

        public function portafolioAction()
    {   
        //$this->layout('layout/layout.phtml');
        // $this->layout('');
        // $this->_helper->layout->disableLayout();
        // return new ViewModel();
    $viewModel = new ViewModel();
    $viewModel->setTerminal(true);

    return $viewModel;

    }

    // public function setEventManager(EventManagerInterface $events)
    // {
    // parent::setEventManager($events);
    // $controller = $this;
    // $events->attach('dispatch', function ($e) use ($controller) {
    //     $controller->layout('layout/empty.phtml');
    // }, 100);
    // }   

}

