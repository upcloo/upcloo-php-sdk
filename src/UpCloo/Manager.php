<?php
/**
 * 
 * The UpCloo base simple manager for baseline PHP application
 * 
 * @author Walter Dal Mut
 * @package UpCloo
 * @license MIT
 *
 * Copyright (C) 2011 Walter Dal Mut, Gabriele Mittica
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class UpCloo_Manager
{
    private static $_instance = false;
    
    /**
     * The username
     * 
     * @var string
     */
    private $_username;
    
    /**
     * The sitekey
     * 
     * @var string
     */
    private $_sitekey;
    
    /**
     * The password
     * 
     * @var string
     */
    private $_password;
    
    protected $_client;
    
    /**
     * A list of virtual sitekeys
     * 
     * @var array
     */
    private $_virtualSitekeys = false;
    
    const UPDATE_END_POINT = 'http://%s.update.upcloo.com';
    const REPOSITORY = 'http://repository.upcloo.com/%s';
    
    protected function __construct() {}
    
    /**
     * Retrive the instance
     * 
     * @return UpCloo_Manager The instance
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
            
            //Default set the client
            self::$_instance->setClient(
                new UpCloo_Client_UpCloo()
            );
        }
        
        return self::$_instance;
    }
    
    public function setClient($client)
    {
        $this->_client = $client;
    }
    
    /**
     * Retrive the Client
     * 
     * @return UpCloo_Client_UpCloo
     */
    public function getClient()
    {
        return $this->_client;
    }
    
    /**
     * Set credentials
     * 
     * @param string $username
     * @param string $sitekey
     * @param string $password
     */
    public function setCredential($username, $sitekey, $password)
    {
        $args = func_get_args();

        $this->setUsername($username);
        $this->setSiteKey($sitekey);
        $this->setPassword($password);
        
        array_shift($args);
        array_shift($args);
        array_shift($args); //remove first three elements
        
        if (count($args)) {
            $this->setVirtualSiteKeys($args);
        }
        
        return $this;
    }
    
    /**
     * Set virtual site keys
     * 
     * @param array $keys
     * @return UpCloo_Manager
     */
    public function setVirtualSiteKeys(array $keys)
    {
        $this->_virtualSitekeys = $keys;
        
        return $this;
    }
    
    /**
     * Get the sitekeys list 
     * 
     * @return array The list of virtual site keys
     */
    public function getVirtualSiteKeys()
    {
        return $this->_virtualSitekeys;
    }
    
    /**
     * Set the Username 
     *
     * @param string $username
     * @return UpCloo_Manager
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        
        return $this;
    }
    
    /**
     * Retrive the actual username
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }
    
    /**
     * Set the virtual site key
     * 
     * @param string $sitekey
     * @return UpCloo_Manager
     */
    public function setSiteKey($sitekey)
    {
        $this->_sitekey = $sitekey;
        
        return $this;
    }
    
    /**
     * Retrive the actual sitekey
     * 
     * @return string
     */
    public function getSiteKey()
    {
        return $this->_sitekey;
    }
    
    /**
     * Set the password
     * 
     * @param string $password
     * @return UpCloo_Manager
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        
        return $this;
    }
    
    /**
     * Retrive the actual password
     * 
     * @return string The password
     */
    public function getPassword()
    {
        return $this->_password;
    }
    
    /**
     * Index new contents or update
     * 
     * @param array|UpCloo_Model_Base $model
     *
     * @return boolean The result of operation.
     * 
     * @throws UpCloo_Model_Exception In case of errors
     */
    public function index($model)
    {
        if (is_array($model)) {
            $model = UpCloo_Model_Base::fromArray($model);
        }
        
        $model["password"] = $this->getPassword();
        $model["sitekey"] = $this->getSiteKey();
        
        $this->getClient()->setUsername($this->getUsername());
        
        return $this->getClient()->index($model);
    }
    

    /**
     * 
     * 
     * @param string $id
     * @param string $virtualSiteKey
     * 
     * @return array The list of results 
     */
    public function get($id, $virtualSiteKey = false)
    {
        $this->_client->setSiteKey($this->getSiteKey());
        
        return $this->_client->get($id, $virtualSiteKey);
    }
}