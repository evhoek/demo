<?php

namespace AppBundle\Controller\api\PrintModels;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddController extends Controller
{
    /**
    * @Route("/api/PrintModels/Add")
    */
    public function indexAction(Request $request)
    {
        $loginUser = $this->get('security.context')->getToken()->getUser();
        
        // save new PrintModel
        $result = $this->get('app.print_model')->AddPrintModel($loginUser, $request->files->get('file'), $request->request->get('description'));
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode(array('result' => $result)));
        
        return $response;
    }
}
