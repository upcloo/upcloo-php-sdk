<?php 
namespace Corley\UpCloo;

class Compiler
{
    private function _addDir($phar, $path, $stripPath, $suffix = '', $prefix = '', $exclude = array())
    {
        $fileIterator = \File_Iterator_Factory::getFileIterator(
            $path,
            $suffix, 
            $prefix, 
            $exclude
        );
        
        foreach ($fileIterator as $file) {
            if ($file->isFile()) {
                $path = str_replace($stripPath, "", $file->getPath());

                $phar->addFile($file, $path . "/" . $file->getFilename());
            }
        }
    }

    public function compile($pharFile = 'upcloo-sdk.phar')
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }
        
        $phar = new \Phar($pharFile);
        $phar->setSignatureAlgorithm(\Phar::SHA1);
        
        $phar->startBuffering();
        
        $phar->addFile("autoload.php");
        
        $this->_addDir(
            $phar, 
            dirname(__FILE__), 
            realpath(dirname(__FILE__)."/../../") . "/",
        	"php"
        );

        //Loader
        $phar->addFile(dirname(__FILE__) . '/../../../vendor/zend/Zend/Loader.php', 'Zend/Loader.php');
        $this->_addDir(
            $phar,
            realpath(dirname(__FILE__) . '/../../../vendor/zend/Zend/Loader'),
            realpath(dirname(__FILE__) . '/../../../vendor/zend') . '/',
            'php'
        );
        
        //Http
        $this->_addDir(
            $phar,
            realpath(dirname(__FILE__) . '/../../../vendor/zend/Zend/Http'),
            realpath(dirname(__FILE__) . '/../../../vendor/zend') . '/',
            'php'
        );
        
        //Uri
        $this->_addDir(
            $phar,
            realpath(dirname(__FILE__) . '/../../../vendor/zend/Zend/Uri'),
            realpath(dirname(__FILE__) . '/../../../vendor/zend') . '/',
            'php'
        );
        
        //Validator
        $this->_addDir(
            $phar,
            realpath(dirname(__FILE__) . '/../../../vendor/zend/Zend/Validate'),
            realpath(dirname(__FILE__) . '/../../../vendor/zend') . '/',
            'php'
        );
        
        $phar->addFile(realpath(dirname(__FILE__) . "/../../LICENSE"));
        
        $phar->setStub($this->getStub());
        
        $phar->stopBuffering();
        
//         $phar->compressFiles(\Phar::GZ);
        
        unset($phar);
    }
    
    protected function getStub()
    {
        return <<<'EOF'
    <?php
    /*
     * This file is part of the UpCloo sdk.
     *
     * (c) Walter Dal Mut <walter.dalmut@gmail.com>
     *
     * This source file is subject to the MIT license that is bundled
     * with this source code in the file LICENSE.
     */
    
    Phar::mapPhar('upcloo-sdk.phar');
    
    require_once 'phar://upcloo-sdk.phar/autoload.php';
    
    __HALT_COMPILER();
EOF;
    }
}