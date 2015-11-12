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

    public function __construct($storageEntity, $container) {
        $this->storageEntity = $storageEntity;
        $this->container = $container;
        $this->translator = $this->container->get('translator');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $template = new Template();

        $builder
                ->add('layout', 'entity', array(
                    'class' => $this->container->getParameter('kijho_mailer.layout_storage'),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                                ->orderBy('l.name', 'ASC');
                    },
                    'required' => false,
                    'empty_value' => $this->translator->trans('kijho_mailer.template.no_layout'),
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
