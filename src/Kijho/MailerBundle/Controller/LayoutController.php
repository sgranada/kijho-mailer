<?php

namespace Kijho\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LayoutController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $layoutStorage = $this->container->getParameter('kijho_mailer.layout_storage');
        var_dump($layoutStorage." este es");
        
        $layouts = $em->getRepository('MasterUnlockBackendBundle:EmailLayout')->findAll();
        
        return $this->render('KijhoMailerBundle:Layout:index.html.twig', array('layouts' => $layouts));
    }
}
