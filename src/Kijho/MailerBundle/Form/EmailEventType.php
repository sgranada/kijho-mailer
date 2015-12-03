<?php

namespace Kijho\MailerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EmailEventType extends AbstractType {

    protected $storageEntity;
    protected $container;
    protected $translator;

    public function __construct($container) {
        $this->container = $container;
        $this->storageEntity = $this->container->getParameter('kijho_mailer.storage')['email_event'];
        $this->translator = $this->container->get('translator');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('name', 'text', array('required' => true,
                    'label' => $this->translator->trans('kijho_mailer.email_event.name'),
                    'attr' => array('class' => 'form-control')))
                ->add('template', 'entity', array(
                    'class' => $this->container->getParameter('kijho_mailer.storage')['template'],
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->orderBy('t.name', 'ASC');
                    },
                    'label' => $this->translator->trans('kijho_mailer.email_event.template'),
                    'required' => true,
                    'attr' => array('class' => 'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->storageEntity
        ));
    }
    
    /**
     * @return string
     */
    public function getName() {
        return 'kijho_mailerbundle_email_event_type';
    }

}
