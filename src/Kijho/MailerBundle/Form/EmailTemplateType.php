<?php

namespace Kijho\MailerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kijho\MailerBundle\Model\Template;
use Doctrine\ORM\EntityRepository;

class EmailTemplateType extends AbstractType {

    protected $storageEntity;
    protected $container;
    protected $translator;
    protected $entityNames;

    public function __construct($storageEntity, $container, $entities = array()) {
        $this->storageEntity = $storageEntity;
        $this->container = $container;
        $this->translator = $this->container->get('translator');

        //incluimos en el formulario las entidades que se identificaron en el proyecto
        if (!empty($entities)) {
            $this->entityNames = array();
            foreach ($entities as $entity) {
                $this->entityNames[strtolower($entity->getShortName())] = $entity->getShortName();
            }
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $template = new Template();

        $mailers = $this->container->get('email_manager')->getMailers();

        $defaultMailer = $this->container->getParameter('swiftmailer.default_mailer');

        $builder
                ->add('layout', 'entity', array(
                    'class' => $this->container->getParameter('kijho_mailer.storage')['layout'],
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                                ->orderBy('l.name', 'ASC');
                    },
                    'label' => $this->translator->trans('kijho_mailer.template.layout'),
                    'required' => false,
                    'empty_value' => $this->translator->trans('kijho_mailer.template.no_layout'),
                    'attr' => array('class' => 'form-control')))
                ->add('group', 'entity', array(
                    'class' => $this->container->getParameter('kijho_mailer.storage')['template_group'],
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                                ->orderBy('g.name', 'ASC');
                    },
                    'label' => $this->translator->trans('kijho_mailer.template.group'),
                    'required' => false,
                    'empty_value' => $this->translator->trans('kijho_mailer.template.no_group'),
                    'attr' => array('class' => 'form-control')))
                ->add('name', 'text', array('required' => true,
                    'label' => $this->translator->trans('kijho_mailer.template.name'),
                    'attr' => array('class' => 'form-control')))
                ->add('fromName', 'text', array('required' => false,
                    'label' => $this->translator->trans('kijho_mailer.template.from_name'),
                    'attr' => array('class' => 'form-control')))
                ->add('fromMail', 'email', array('required' => false,
                    'label' => $this->translator->trans('kijho_mailer.template.from_mail'),
                    'attr' => array('class' => 'form-control',
                        'placeholder' => $this->translator->trans('kijho_mailer.global.email_example'))))
                ->add('copyTo', 'email', array('required' => false,
                    'label' => $this->translator->trans('kijho_mailer.template.copy_to'),
                    'attr' => array('class' => 'form-control',
                        'placeholder' => $this->translator->trans('kijho_mailer.global.email_example'))))
                ->add('subject', 'text', array('required' => false,
                    'label' => $this->translator->trans('kijho_mailer.template.subject'),
                    'attr' => array('class' => 'form-control')))
                ->add('contentMessage', 'textarea', array('required' => false,
                    'label' => $this->translator->trans('kijho_mailer.layout.content'),
                    'attr' => array('class' => 'form-control')))
                ->add('status', 'choice', array('required' => true,
                    'choices' => array(Template::STATUS_ENABLED => $this->translator->trans($template->getStatusDescription(Template::STATUS_ENABLED)),
                        Template::STATUS_DISABLED => $this->translator->trans($template->getStatusDescription(Template::STATUS_DISABLED))),
                    'label' => $this->translator->trans('kijho_mailer.template.status'),
                    'attr' => array('class' => 'form-control')))
                ->add('mailerSettings', 'choice', array('required' => true,
                    'choices' => $mailers,
                    'preferred_choices' => array($defaultMailer),
                    'label' => $this->translator->trans('kijho_mailer.template.status'),
                    'attr' => array('class' => 'form-control')))
                ->add('entityNames', 'choice', array('required' => false,
                    'mapped' => false,
                    'choices' => $this->entityNames,
                    'label' => $this->translator->trans('kijho_mailer.template.select_entity'),
                    'empty_value' => $this->translator->trans('kijho_mailer.global.select'),
                    'attr' => array('class' => 'form-control')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->storageEntity
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'kijho_mailerbundle_template_type';
    }

}
