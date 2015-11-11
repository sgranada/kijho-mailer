<?php

namespace Kijho\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kijho\MailerBundle\Form\EmailTemplateType;
use Symfony\Component\HttpFoundation\Request;
use Kijho\MailerBundle\Util\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TemplateController extends Controller {

    /**
     * Permite visualizar el listado de todos los templates creados en el sistema
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @return type
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');

        $templates = $em->getRepository($templateStorage)->findAll();

        return $this->render('KijhoMailerBundle:Template:index.html.twig', array(
                    'templates' => $templates,
                    'menu' => 'templates'
        ));
    }

    /**
     * Permite desplegar el formulario de creacion de templates
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @return Response formulario de creacion del template
     */
    public function newAction() {

        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');
        $template = new $templateStorage;

        $form = $this->createForm(new EmailTemplateType($templateStorage, $this->container), $template);

        return $this->render('KijhoMailerBundle:Template:new.html.twig', array(
                    'template' => $template,
                    'form' => $form->createView(),
                    'menu' => 'templates'
        ));
    }

    /**
     * Permite validar y almacenar la informacion de un template
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @param Request $request datos de la solicitud
     * @return Response en caso de exito redirecciona al listado de templates, 
     * en caso de error despliega nuevamente el formulario de creacion de templates
     */
    public function saveAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');
        $template = new $templateStorage;
        $form = $this->createForm(new EmailTemplateType($templateStorage, $this->container), $template);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $template->setCreationDate(Util::getCurrentDate());
            $em->persist($template);
            $em->flush();

            $this->get('session')->getFlashBag()->add('messageSuccessTemplate', $this->get('translator')->trans('kijho_mailer.template.creation_success_message'));
            return $this->redirect($this->generateUrl('kijho_mailer_template'));
        }

        return $this->render('KijhoMailerBundle:Template:new.html.twig', array(
                    'template' => $template,
                    'form' => $form->createView(),
                    'menu' => 'templates'
        ));
    }

    /**
     * Permite desplegar el formulario de edicion de templates
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @param integer $templateId identificador del template a editar
     * @return Response formulario de edicion del template
     */
    public function editAction($templateId) {

        $em = $this->getDoctrine()->getManager();

        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');

        $template = $em->getRepository($templateStorage)->find($templateId);

        $form = $this->createForm(new EmailTemplateType($templateStorage, $this->container), $template);

        return $this->render('KijhoMailerBundle:Template:edit.html.twig', array(
                    'template' => $template,
                    'form' => $form->createView(),
                    'menu' => 'templates'
        ));
    }

    /**
     * Permite validar y almacenar los cambios en la informacion de un template
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @param Request $request datos de la solicitud
     * @param integer $templateId identificador del template a editar
     * @return Response en caso de exito redirecciona al listado de templates, 
     * en caso de error despliega nuevamente el formulario de creacion de templates
     */
    public function updateAction(Request $request, $templateId) {
        $em = $this->getDoctrine()->getManager();

        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');
        $template = $em->getRepository($templateStorage)->find($templateId);
        $form = $this->createForm(new EmailTemplateType($templateStorage, $this->container), $template);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($template);
            $em->flush();

            $this->get('session')->getFlashBag()->add('messageSuccessTemplate', $this->get('translator')->trans('kijho_mailer.template.update_success_message'));
            return $this->redirect($this->generateUrl('kijho_mailer_template'));
        }

        return $this->render('KijhoMailerBundle:Template:edit.html.twig', array(
                    'template' => $template,
                    'form' => $form->createView(),
                    'menu' => 'templates'
        ));
    }

    /**
     * Permite desplegar el preview de un template
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @param integer $templateId identificador del template
     * @return Response pagina con el preview del template
     */
    public function previewAction($templateId) {

        $em = $this->getDoctrine()->getManager();

        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');

        $template = $em->getRepository($templateStorage)->find($templateId);


        return $this->render('KijhoMailerBundle:Template:preview.html.twig', array(
                    'template' => $template,
                    'menu' => 'templates'
        ));
    }

    /**
     * Permite eliminar un template del sistema
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 11/11/2015
     * @param Request $request datos de la solicitud
     * @return Response Json con mensaje de respuesta
     */
    public function deleteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $templateId = $request->request->get('templateId');
        $templateStorage = $this->container->getParameter('kijho_mailer.template_storage');
        $template = $em->getRepository($templateStorage)->find($templateId);

        $response['result'] = '__OK__';
        $response['msg'] = '';

        try {
            $em->remove($template);
            $em->flush();
        } catch (\Exception $exc) {
            $response['result'] = '__KO__';
            $response['msg'] = 'Unknown error, try again';
        }
        return new JsonResponse($response);
    }

}
