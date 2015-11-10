<?php

namespace Kijho\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kijho\MailerBundle\Entity\EmailLayout;
use Kijho\MailerBundle\Form\EmailLayoutType;
use Symfony\Component\HttpFoundation\Request;
use Kijho\MailerBundle\Util\Util;
use Symfony\Component\HttpFoundation\Response;

class LayoutController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $layoutStorage = $this->container->getParameter('kijho_mailer.layout_storage');

        $layouts = $em->getRepository($layoutStorage)->findAll();

        return $this->render('KijhoMailerBundle:Layout:index.html.twig', array(
                    'layouts' => $layouts,
                    'menu' => 'layouts'
        ));
    }

    /**
     * Permite desplegar el formulario de creacion de layouts
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 10/11/2015
     * @return Response formulario de creacion del layout
     */
    public function newAction() {

        $layoutStorage = $this->container->getParameter('kijho_mailer.layout_storage');
        $layout = new $layoutStorage;

        $form = $this->createForm(new EmailLayoutType($layoutStorage), $layout);

        return $this->render('KijhoMailerBundle:Layout:new.html.twig', array(
                    'layout' => $layout,
                    'form' => $form->createView(),
                    'menu' => 'layouts'
        ));
    }

    /**
     * Permite validar y almacenar la informacion de un layout
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 10/11/2015
     * @param Request $request datos de la solicitud
     * @return Response en caso de exito redirecciona al listado de layouts, 
     * en caso de error despliega nuevamente el formulario de creacion de layouts
     */
    public function saveAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $layoutStorage = $this->container->getParameter('kijho_mailer.layout_storage');
        $layout = new $layoutStorage;
        $form = $this->createForm(new EmailLayoutType(), $layout);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $layout->setCreationDate(Util::getCurrentDate());
            $em->persist($layout);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('messageSuccessLayout', 'Layout created successfully');
            return $this->redirect($this->generateUrl('kijho_mailer_layout'));
        }

        return $this->render('KijhoMailerBundle:Layout:new.html.twig', array(
                    'layout' => $layout,
                    'form' => $form->createView(),
                    'menu' => 'layouts'
        ));
    }

}
