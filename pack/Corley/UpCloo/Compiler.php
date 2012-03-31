<?php 
namespace Corley\UpCloo;

class Compiler
{
    private function _addDir($phar, $path, $stripPath, $suffix = '', $prefix = '', $exclude = array())
    {
        $factory = new \File_Iterator_Factory();
        $fileIterator = $factory->getFileIterator(
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
        
        $this->_addDir(
        $phar,
            realpath(dirname(__FILE__) . '/../../../src/UpCloo'),
            realpath(dirname(__FILE__) . '/../../../src') . '/',
            'php'
        );
        
        $phar->addFile(realpath(dirname(__FILE__) . "/../../LICENSE"), "LICENSE");
        
        $phar->setStub($this->getStub());
        
        $phar->stopBuffering();
        
        $phar->compressFiles(\Phar::GZ);
        
        unset($phar);
    }
    
    protected function getStub()
    {
        return <<<EOF
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
 
require_once 'phar://upcloo-sdk.phar/UpCloo/Autoloader.php';
 
__HALT_COMPILER();
EOF;
    }
}
