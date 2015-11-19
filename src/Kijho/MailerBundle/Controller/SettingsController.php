<?php

namespace Kijho\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kijho\MailerBundle\Form\EmailSettingsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller {

    /**
     * Permite desplegar el formulario de edicion de configuraciones de emails
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 19/11/2015
     * @return Response formulario de edicion de las settings
     */
    public function editAction() {

        $em = $this->getDoctrine()->getManager();

        $settingsStorage = $this->container->getParameter('kijho_mailer.storage')['settings'];


        $settings = $em->getRepository($settingsStorage)->findAll();
        if (!$settings) {
            $settings = new $settingsStorage;
        } else {
            $settings = $settings[0];
        }

        $form = $this->createForm(new EmailSettingsType($settingsStorage, $this->get('translator')), $settings);

        return $this->render('KijhoMailerBundle:Settings:edit.html.twig', array(
                    'settings' => $settings,
                    'form' => $form->createView(),
                    'menu' => 'settings'
        ));
    }

    /**
     * Permite validar y almacenar los cambios en los settings
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 19/11/2015
     * @param Request $request datos de la solicitud
     * @return Response en caso de exito redirecciona a la homepage, 
     * en caso de error despliega nuevamente el formulario de settings
     */
    public function updateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $settingsStorage = $this->container->getParameter('kijho_mailer.storage')['settings'];

        $settings = $em->getRepository($settingsStorage)->findAll();
        if (!$settings) {
            $settings = new $settingsStorage;
        } else {
            $settings = $settings[0];
        }

        $form = $this->createForm(new EmailSettingsType($settingsStorage, $this->get('translator')), $settings);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($settings);
            $em->flush();

            $this->get('session')->getFlashBag()->add('messageHomeSuccess', $this->get('translator')->trans('kijho_mailer.setting.update_success_message'));
            return $this->redirect($this->generateUrl('kijho_mailer_homepage'));
        }

        return $this->render('KijhoMailerBundle:Settings:edit.html.twig', array(
                    'settings' => $settings,
                    'form' => $form->createView(),
                    'menu' => 'settings'
        ));
    }

}
