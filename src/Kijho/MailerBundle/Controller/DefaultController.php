<?php

namespace Kijho\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homeAction()
    {
        return $this->render('KijhoMailerBundle:Default:home.html.twig', array('menu' => 'home'));
    }
}
