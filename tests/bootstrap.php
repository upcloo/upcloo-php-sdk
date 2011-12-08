<?php 

set_include_path(implode(PATH_SEPARATOR,  array(
    realpath(dirname(__FILE__) . '/../src'),
    get_include_path(),
)));

require_once 'UpCloo/Manager.php';
require_once 'UpCloo/Model/Base.php';