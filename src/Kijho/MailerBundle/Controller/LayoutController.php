<?php

namespace Kijho\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LayoutController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $layouts = $em->getRepository('KijhoMailerBundle:Layout')->findAll();
        
        return $this->render('KijhoMailerBundle:Layout:index.html.twig', array('layouts' => $layouts));
    }
}
