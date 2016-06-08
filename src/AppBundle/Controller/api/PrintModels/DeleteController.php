<?php

namespace AppBundle\Controller\api\PrintModels;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteController extends Controller
{
    /**
     * @Route("/api/PrintModels/Delete/{id}")
     */
    public function indexAction(Request $request, $id)
    {
        $loginUser = $this->get('security.context')->getToken()->getUser();
        
        // delete PrintModel
        $result = $this->get('app.print_model')->DeletePrintModel($id, $loginUser);
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode(array('result' => $result)));
        
        return $response;
    }
}
