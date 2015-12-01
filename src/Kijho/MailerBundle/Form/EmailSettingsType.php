<?php

namespace Kijho\MailerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Kijho\MailerBundle\Model\Settings;

class EmailSettingsType extends AbstractType {

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

        $settings = new Settings();
        $builder
                ->add('sendMode', 'choice', array('required' => true,
                    'choices' => array(Settings::SEND_MODE_INSTANTANEOUS => $this->translator->trans($settings->getSendModeDescription(Settings::SEND_MODE_INSTANTANEOUS)),
                        Settings::SEND_MODE_PERIODIC => $this->translator->trans($settings->getSendModeDescription(Settings::SEND_MODE_PERIODIC)),
                        Settings::SEND_MODE_BY_EMAIL_AMOUNT => $this->translator->trans($settings->getSendModeDescription(Settings::SEND_MODE_BY_EMAIL_AMOUNT)),),
                    'label' => $this->translator->trans('kijho_mailer.setting.send_mode'),
                    'placeholder' => $this->translator->trans('kijho_mailer.global.select'),
                    'attr' => array('class' => 'form-control')))
                ->add('limitEmailAmount', 'number', array('required' => true,
                    'label' => $this->translator->trans('kijho_mailer.setting.limit_email_amount'),
                    'attr' => array('class' => 'form-control only_numbers',
                        'maxlength' => 3)))
                ->add('intervalToSend', 'number', array('required' => true,
                    'label' => $this->translator->trans('kijho_mailer.setting.interval_to_send'),
                    'attr' => array('class' => 'form-control only_numbers',
                        'maxlength' => 3)))
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
        return 'kijho_mailerbundle_settings_type';
    }

}
