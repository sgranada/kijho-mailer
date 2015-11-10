<?php

namespace Kijho\MailerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kijho\MailerBundle\Entity\EmailLayout;

class EmailLayoutType extends AbstractType {

    protected $storageEntity;
    
    public function __construct($storageEntity) {
        $this->storageEntity = $storageEntity;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('name', 'text', array('required' => true, 'label' => 'Name',
                    'attr' => array('class' => 'form-control')))
                ->add('header', 'textarea', array('required' => true, 'label' => 'Layout Header',
                    'attr' => array('class' => 'form-control')))
                ->add('footer', 'textarea', array('required' => true, 'label' => 'Layout Footer',
                    'attr' => array('class' => 'form-control')))
                ->add('languageCode', 'choice', array('required' => true,
                    'choices' => array(EmailLayout::LANG_EN => strtoupper(EmailLayout::LANG_EN),
                        EmailLayout::LANG_ES => strtoupper(EmailLayout::LANG_ES)),
                    'label' => 'Language',
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
        return 'kijho_mailerbundle_email_layout_type';
    }

}
