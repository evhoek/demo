<?php

namespace AppBundle\Controller\api\PrintModels;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditController extends Controller
{
    /**
     * update existing PrintModel
     * 
     * @param integer $id
     * @param Request $request[] post data
     * @param {string} Request[].description
     * @return JSON result:false on error, on success result:true
     * 
     * @Route("/api/PrintModels/Edit/{id}")
     */
    public function indexAction(Request $request, $id)
    {
        $loginUser = $this->get('security.context')->getToken()->getUser();
        
        // update PrintModel
        $result = $this->get('app.print_model')->EditPrintModel($id, $loginUser, $request->request->get('description'));
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode(array('result' => $result)));
        
        return $response;
    }
}
