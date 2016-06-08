<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $loginUser = $this->get('security.context')->getToken()->getUser();
        
        return $this->render('default/index.html.twig', array(
            'loginUsername' => $loginUser->getUsername(),
            'maxFileSize'    => ini_get('upload_max_filesize'),
        ));
    }
}
