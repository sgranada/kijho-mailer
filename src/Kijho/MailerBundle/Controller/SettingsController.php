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

        $swiftMailerSettings = $this->getSwiftMailerSettings();
        
        return $this->render('KijhoMailerBundle:Settings:edit.html.twig', array(
                    'settings' => $settings,
                    'swiftMailerSettings' => $swiftMailerSettings,
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

        $swiftMailerSettings = $this->getSwiftMailerSettings();

        return $this->render('KijhoMailerBundle:Settings:edit.html.twig', array(
                    'settings' => $settings,
                    'swiftMailerSettings' => $swiftMailerSettings,
                    'form' => $form->createView(),
                    'menu' => 'settings'
        ));
    }

    /**
     * Esta funcion permite analizar la bolsa de parametros del swiftmailer
     * y obtener los valores de dichos parametros
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 19/11/2015
     * @return array[string] arreglo con los parametros del swiftmailer
     */
    public function getSwiftMailerSettings() {

        $bag = $this->container->getParameterBag();
        
        $basicParameters = array(
            "transport.name",
            "delivery.enabled",
            "transport.smtp.encryption",
            "transport.smtp.port",
            "transport.smtp.host",
            "transport.smtp.username",
            "transport.smtp.password",
            "transport.smtp.auth_mode",
            "transport.smtp.timeout",
            "transport.smtp.source_ip",
            "memory.path",
            "spool.enabled",
            "plugin.impersonate",
            "single_address");
        
        $mailers = $this->container->get('email_manager')->getMailers();
        
        //aca recorremos los mailers y armamos el arreglo reemplazando el nombre del mailer
        $swiftParameters = array();
        foreach ($mailers as $mailer) {
            foreach ($basicParameters as $parameter){
                array_push($swiftParameters, $mailer.".".$parameter);
            }
        }
        
        $otherParameters = array(
            "swiftmailer.spool.enabled",
            "swiftmailer.delivery.enabled",
            "swiftmailer.single_address",
            "swiftmailer.mailers",
            "swiftmailer.default_mailer");
        
        $swiftParameters = array_merge($swiftParameters, $otherParameters);
        
        $mailerSettings = array();
        foreach ($swiftParameters as $swiftParameter) {
            if ($bag->has($swiftParameter)) {
                $mailerSettings[$swiftParameter] = $bag->get($swiftParameter);
            }
        }
        
        return $mailerSettings;
    }

}
