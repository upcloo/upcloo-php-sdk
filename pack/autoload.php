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

function upcloo_autoload($className) {
    require_once str_replace("_", "/", $className).".php";
}
spl_autoload_register("upcloo_autoload");