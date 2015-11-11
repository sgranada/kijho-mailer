<?php

namespace Kijho\MailerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kijho\MailerBundle\Model\Layout;

class EmailLayoutType extends AbstractType {

    protected $storageEntity;
    protected $translator;

    public function __construct($storageEntity, $translator) {
        $this->storageEntity = $storageEntity;
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('name', 'text', array('required' => true,
                    'label' => $this->translator->trans('kijho_mailer.global.name'),
                    'attr' => array('class' => 'form-control')))
                ->add('header', 'textarea', array('required' => true, 
                    'label' => $this->translator->trans('kijho_mailer.layout.header'),
                    'attr' => array('class' => 'form-control')))
                ->add('footer', 'textarea', array('required' => true, 
                    'label' => $this->translator->trans('kijho_mailer.layout.footer'),
                    'attr' => array('class' => 'form-control')))
                ->add('languageCode', 'choice', array('required' => true,
                    'choices' => array(Layout::LANG_EN => strtoupper(Layout::LANG_EN),
                        Layout::LANG_ES => strtoupper(Layout::LANG_ES)),
                    'label' => $this->translator->trans('kijho_mailer.global.language'),
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
        return 'kijho_mailerbundle_layout_type';
    }

}
