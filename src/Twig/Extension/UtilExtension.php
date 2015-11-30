<?php
namespace Twig\Extension;

use \Twig_Filter_Function;
use \Twig_Filter_Method;

class UtilExtension extends \Twig_Extension
{
    /**
     * Retorna las funciones registradas como extenciones
     * @return array
     */
    public function getFunctions() {
        return array(
            'file_exists' => new \Twig_Function_Function('file_exists')
        );
    }


    public function getName() {
        return 'twig_extension';
    }
}