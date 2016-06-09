<?php

namespace AppBundle\Controller\api\PrintModels;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OverviewController extends Controller
{
    /**
     * get all PrintModels
     * 
     * @return HTML html-table with all PrintModels, own printmodels are editable
     * 
     * @Route("/api/PrintModels/Overview")
     */
    public function indexAction(Request $request)
    {
        $loginUser = $this->get('security.context')->getToken()->getUser();
        
        // get all PrintModels
        $printModels = $this->get('app.print_model')->GetPrintModels();
        
        return $this->render('api/PrintModels/overview.html.twig', array(
            'loginUsername'    => $loginUser->getUsername(),
            'print_models'    => $printModels,
        ));
    }
}
