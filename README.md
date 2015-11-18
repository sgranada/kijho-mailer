<html>
 <body>
<h1>Instalación</h1>


<h3>composer.json</h3>

Colocar esta linea en el archivo composer.json del proyecto:

<pre style="font-family: Courier New;">"cesargiraldonaranjo/kijho.mailer": "dev-master"</pre>


Asegurarse que la versión del bundle sendio/distribution-bundle sea 4.0:

<pre style="font-family: Courier New;">"sensio/distribution-bundle": "~4.0"</pre>

<h3>AppKernel.php</h3>

Colocar la siguiente linea en el archivo AppKernel para inicializar el Bundle:

<pre style="font-family: Courier New;">new Kijho\MailerBundle\KijhoMailerBundle()</pre>


<h3>rounting.yml</h3>

Colocar la siguiente ruta en el archivo routing.yml del proyecto:

<pre style="font-family: Courier New;">
kijho_mailer:
    resource: "@KijhoMailerBundle/Resources/config/routing.yml"
    prefix:   /{_locale}/kijhoMailer
</pre>


<h3>config.yml</h3>

Habilitar la configuración del lenguaje en el archivo config.yml colocando  el lenguaje por defecto para el vendor (en, es)

<pre style="font-family: Courier New;">
framework:
    translator:      { fallbacks: [en] }
</pre>


Kijho Mailer requiere el vendor de assets, una vez instalado colocar el nombre del bundle en los bundles que usan assets:

<pre style="font-family: Courier New;">
assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    bundles:        [FrontendBundle, MasterUnlockBackendBundle, KijhoMailerBundle]
</pre>

Poner en el archivo config.yml las configuraciones propias de almacenamiento del vendor:
<pre style="font-family: Courier New;">
kijho_mailer:
    entity_directories: ["%kernel.root_dir%/../src/MasterUnlock/BackendBundle/Entity/"]
    storage:
        layout:         "MasterUnlock\BackendBundle\Entity\EmailLayout"
        template_group: "MasterUnlock\BackendBundle\Entity\EmailTemplateGroup"
        template:       "MasterUnlock\BackendBundle\Entity\EmailTemplate"
</pre>


<h3>Creacion de Entidades</h3>

<h4>EmailLayout.php</h4>


<pre>

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
</pre>

<h4>EmailTemplateGroup.php</h4>


<pre>

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
</pre>

<h4>EmailTemplate.php</h4>

<pre>
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
</pre>

<h4>composer update</h4>
Ejecute en su proyecto el comando <pre style="font-family: Courier New;">composer update</pre> 

<h4>schema update</h4>
Ejecute en su proyecto el comando <pre style="font-family: Courier New;">php app/console d:s:u --force</pre> 

<h4>Enlace</h4>
Ahora coloque en cualquier parte de su proyecto un enlace con la ruta:
<pre style="font-family: Courier New;">{{path('kijho_mailer_homepage')}}</pre>

<script>
    $( function() { $("PRE").prettyPre(); } );
</script>

</body>
</html>