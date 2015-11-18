# kijho-mailer

Instalación


Colocar esta linea en el archivo composer.json del proyecto:

"cesargiraldonaranjo/kijho.mailer": "dev-master"


Asegurarse que la versión del bundle sendio/distribution-bundle sea 4.0:

"sensio/distribution-bundle": "~4.0"


Colocar la siguiente linea en el archivo AppKernel para inicialiar el Bundle:

new Kijho\MailerBundle\KijhoMailerBundle()


Habilitar la configuración del lenguaje en el archivo config.yml colocando  el lenguaje por defecto para el vendor (en, es)

framework:
    translator:      { fallbacks: [en] }


Colocar la siguiente ruta en el archivo routing.yml del proyecto:

kijho_mailer:
    resource: "@KijhoMailerBundle/Resources/config/routing.yml"
    prefix:   /{_locale}/kijhoMailer


Kijho Mailer requiere el vendor de assets, una vez instalado colocar el bundle en los bundles que usan assets:

# Assetic Configuration
assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    bundles:        [FrontendBundle, MasterUnlockBackendBundle, KijhoMailerBundle]


Poner en el arvhico config.yml las configuraciones propias de almacenamiento del vendor
kijho_mailer:
    entity_directories: ["%kernel.root_dir%/../src/MasterUnlock/BackendBundle/Entity/"]
    storage:
        layout:         "MasterUnlock\BackendBundle\Entity\EmailLayout"
        template_group: "MasterUnlock\BackendBundle\Entity\EmailTemplateGroup"
        template:       "MasterUnlock\BackendBundle\Entity\EmailTemplate"


Crear las entidades EmailLayout, EmailTemplateGroup y EmailTemplate en el paquete de entidades del proyecto así:

<?php

namespace MasterUnlock\BackendBundle\Entity;

use Kijho\MailerBundle\Model\Layout as BaseLayout;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email Layout
 * @ORM\Table(name="kijho_email_layout")
 * @ORM\Entity
 */
class EmailLayout extends BaseLayout {

    /**
     * @ORM\Id
     * @ORM\Column(name="layo_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    function getId() {
        return $this->id;
    }
}


<?php

namespace MasterUnlock\BackendBundle\Entity;

use Kijho\MailerBundle\Model\TemplateGroup as BaseTemplateGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email Template Group
 * @ORM\Table(name="kijho_email_template_group")
 * @ORM\Entity
 */
class EmailTemplateGroup extends BaseTemplateGroup {

    /**
     * @ORM\Id
     * @ORM\Column(name="tgro_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    function getId() {
        return $this->id;
    }
}

<?php
namespace MasterUnlock\BackendBundle\Entity;

use Kijho\MailerBundle\Model\Template as BaseTemplate;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email Template
 * @ORM\Table(name="kijho_email_template")
 * @ORM\Entity
 */
class EmailTemplate extends BaseTemplate {

    /**
     * @ORM\Id
     * @ORM\Column(name="temp_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * Layout al que esta asociado el template
     * @ORM\ManyToOne(targetEntity="MasterUnlock\BackendBundle\Entity\EmailLayout")
     * @ORM\JoinColumn(name="temp_layout", referencedColumnName="layo_id", nullable=true)
     */
    protected $layout;
    
    /**
     * Grupo al que esta asociado el template
     * @ORM\ManyToOne(targetEntity="MasterUnlock\BackendBundle\Entity\EmailTemplateGroup")
     * @ORM\JoinColumn(name="temp_group", referencedColumnName="tgro_id", nullable=true)
     */
    protected $group;
    
    function getId() {
        return $this->id;
    }
    
    function getLayout() {
        return $this->layout;
    }

    function setLayout(EmailLayout $layout = null) {
        $this->layout = $layout;
    }
    
    function getGroup() {
        return $this->group;
    }

    function setGroup(EmailTemplateGroup $group = null) {
        $this->group = $group;
    }
}

Run composer update in your project

Update your the database schema


Ahora ponga en cualquier parte de su proyecto un enlace como el siguiente:

<a href=“{{path(‘kijho_mailer_homepage’)}}”>Email Settings</a>

