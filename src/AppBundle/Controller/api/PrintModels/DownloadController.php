<?php

namespace AppBundle\Controller\api\PrintModels;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DownloadController extends Controller
{
    /**
     * @Route("/api/PrintModels/Download/{id}")
     */
    public function indexAction(Request $request, $id)
    {
        // get PrintModel
        $printModel = $this->get('app.print_model')->GetPrintModel($id);
        
        // PrintModel not found 
        if (!$printModel)
            throw new HttpException(400);
        
        // get the file from upload folder
        $response = new Response();
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $printModel->getFilename());
        $response->setContent(file_get_contents($this->get('app.print_model')->GetAbsoluteFileName($printModel)));
        
        return $response;
    }
}
