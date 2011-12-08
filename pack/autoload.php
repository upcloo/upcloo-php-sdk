<?php 
set_include_path(
    implode(
        DIRECTORY_SEPARATOR, 
        array(
            dirname(__FILE__),
            get_include_path()
        )
    )
);

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Zend_');
$loader->registerNamespace('UpCloo_');
