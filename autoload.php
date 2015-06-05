<?php
/**
 * @author Emmanuel VAÏSSE <evaisse@gmail.com>
 */

/**
 * SPL autoloader for RamlGenerator package
 * 
 * @param  string $class Class name 
 */
function ramlGeneratorLoader($class) 
{
    static $root;


    $root = $root ? $root : dirname(__FILE__);

    if (strpos($class, 'sfYaml') === 0) {
        include $root . DIRECTORY_SEPARATOR . 'yaml' . DIRECTORY_SEPARATOR . $class . '.class.php';
    }

    if (strpos($class, 'RamlGenerator') !== 0) {
        return;
    }

    $class = str_replace('_', DIRECTORY_SEPARATOR, $class);

    include $root . DIRECTORY_SEPARATOR . $class . '.php';

}

spl_autoload_register('ramlGeneratorLoader');
