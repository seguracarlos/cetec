<?php

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ExamsController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

