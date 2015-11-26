<html>
 <body>
<h1>Instalación</h1>


<h3>composer.json</h3>

Colocar esta linea en el archivo composer.json del proyecto:

<pre style="font-family: Courier New;">"cesargiraldonaranjo/kijho.mailer": "dev-master"</pre>


Asegurarse que la versión del bundle sendio/distribution-bundle sea 4.0 o superior:

<pre style="font-family: Courier New;">"sensio/distribution-bundle": "~4.0"</pre>

<h3>AppKernel.php</h3>

Colocar la siguiente linea en el archivo AppKernel para inicializar el Bundle:

<pre style="font-family: Courier New;">new Kijho\MailerBundle\KijhoMailerBundle()</pre>


<h3>routing.yml</h3>

Colocar la siguiente ruta en el archivo routing.yml del proyecto:

<pre style="font-family: Courier New;">
kijho_mailer:
    resource: "@KijhoMailerBundle/Resources/config/routing.yml"
    prefix:   /{_locale}/kijhoMailer
</pre>


<h3>config.yml</h3>

Habilitar la configuración del lenguaje en el archivo config.yml colocando  el lenguaje por defecto para el vendor. De momento los lenguajes disponibles son ingles y español (en, es)

<pre style="font-family: Courier New;">
framework:
    translator:      { fallbacks: [en] }
</pre>


Kijho Mailer requiere el vendor de assets, una vez instalado colocar el nombre del bundle en los bundles que usan assets:

<pre style="font-family: Courier New;">
assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    bundles:        [AcmeDemoBundle, KijhoMailerBundle]
</pre>

Poner en el archivo config.yml las configuraciones propias de almacenamiento del vendor:
<pre style="font-family: Courier New;">
kijho_mailer:
    entity_directories: ["%kernel.root_dir%/../src/Acme/DemoBundle/Entity/"]
    storage:
        layout:         "Acme\DemoBundle\Entity\EmailLayout"
        template_group: "Acme\DemoBundle\Entity\EmailTemplateGroup"
        template:       "Acme\DemoBundle\Entity\EmailTemplate"
        settings:       "Acme\DemoBundle\Entity\EmailSettings"
        email:          "Acme\DemoBundle\Entity\Email"
        email_event:    "Acme\DemoBundle\Entity\EmailEvent"
</pre>

Asegurarse de configurar los parametros del swiftmailer, los parametros van en el archivo parameters.yml:

<pre style="font-family: Courier New;">
swiftmailer:
    transport: "%mailer_transport%"
    host:      "%mailer_host%"
    username:  "%mailer_user%"
    password:  "%mailer_password%"
    spool:     { type: memory }
</pre>

<h3>Creacion de Entidades</h3>

Crear las siguientes entidades en el paquete "Entity" de uno de los bundles del proyecto:

<h4>EmailLayout.php</h4>


<pre>

namespace Acme\DemoBundle\Entity;

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
</pre>

<h4>EmailTemplateGroup.php</h4>


<pre>

namespace Acme\DemoBundle\Entity;

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
</pre>

<h4>EmailTemplate.php</h4>

<pre>
namespace Acme\DemoBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\EmailLayout")
     * @ORM\JoinColumn(name="temp_layout", referencedColumnName="layo_id", nullable=true)
     */
    protected $layout;
    
    /**
     * Grupo al que esta asociado el template
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\EmailTemplateGroup")
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
</pre>

<h4>EmailSettings.php</h4>

<pre>
namespace Acme\DemoBundle\Entity;

use Kijho\MailerBundle\Model\Settings as BaseSettings;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email Settings
 * @ORM\Table(name="kijho_email_settings")
 * @ORM\Entity
 */
class EmailSettings extends BaseSettings {

    /**
     * @ORM\Id
     * @ORM\Column(name="sett_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    function getId() {
        return $this->id;
    }

}
</pre>

<h4>EmailEvent.php</h4>

<pre>
namespace Acme\DemoBundle\Entity;

use Acme\DemoBundle\Model\EmailEvent as BaseEmailEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email Event
 * @ORM\Table(name="email_event")
 * @ORM\Entity
 */
class EmailEvent extends BaseEmailEvent {

    /**
     * @ORM\Id
     * @ORM\Column(name="emev_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * Template al que esta asociado el correo
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\EmailTemplate")
     * @ORM\JoinColumn(name="emev_template", referencedColumnName="temp_id")
     */
    protected $template;
    
    function getId() {
        return $this->id;
    }
    
    function getTemplate() {
        return $this->template;
    }

    function setTemplate($template = null) {
        $this->template = $template;
    }

}

</pre>

<h4>composer update</h4>
Ejecute en su proyecto el comando <pre style="font-family: Courier New;">composer update</pre> 

<h4>schema update</h4>
Ejecute en su proyecto el comando <pre style="font-family: Courier New;">php app/console d:s:u --force</pre> 

<h4>Enlace</h4>
Ahora para acceder a las funcionalidades del vendor instalado, coloque en cualquier parte de su proyecto un enlace con la ruta:
<pre style="font-family: Courier New;">{{path('kijho_mailer_homepage')}}</pre>

<script>
    $( function() { $("PRE").prettyPre(); } );
</script>

</body>
</html>