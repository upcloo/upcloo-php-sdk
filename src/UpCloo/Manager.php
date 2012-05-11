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
    /**
     * The update end point
     * 
     * @var string
     */
    const UPDATE_END_POINT = 'http://%s.update.upcloo.com';
    /**
     * The repository
     * 
     * @var string
     */
    const REPOSITORY = 'http://repository.upcloo.com/%s';
    
    /**
     * Search Endpoint
     * 
     * @var string
     */
    const SEARCH_ENDPOINT = "http://search.upcloo.com/search/rest";
    
    /**
     * Internal Storage name
     * 
     * @var string
     */
    const STORAGE_NAME = "UpClooStatus";
    
    /**
     * The single instance
     * 
     * @var UpCloo_Manager
     */
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
    
    /**
     * @var UpCloo_Client_UpCloo
     */
    protected $_client;
    
    /**
     * A list of virtual sitekeys
     * 
     * @var array
     */
    private $_virtualSitekeys = false;
    
    /**
     * 
     * @var PDO
     */
    private $_storage;
    
    /**
     * Constructor is protected for singleton pattern
     * 
     */
    protected function __construct() {}
    
    /**
     * Clone is not supported 
     * 
     * @throws BadFunctionCallException
     */
    public function __clone(){
        throw new BadFunctionCallException("Clone operation is not supported");
    }
    
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
    
    public function setSearchClient($client)
    {
        $this->_searchClient = $client;
    }
    
    /**
     * Set up the client
     * 
     * @param UpCloo_Client_ClientInterface $client
     */
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
        $this->setUsername($username);
        $this->setSiteKey($sitekey);
        $this->setPassword($password);
        
        return $this;
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
        
        $status = $this->getClient()->index($model);
        
        if ($this->_storage && $status) {
            $this->_register($model["id"]);
        }
        
        return $status;
    }
    
    /**
     * Start a new search query
     * 
     * @return UpCloo_Model_Search Search query
     */
    public function search()
    {
        return new UpCloo_Model_Search();
    }
    
    /**
     * Use local storage for using index method
     * 
     * Activating local storage allow the continuous indexing
     * without problems.
     * 
     * @param string $path
     * @throws Exception In case of storage creation
     */
    public function useStorage($path)
    {
        $create = false;
        if (!$path || !is_string($path) || empty($path)) {
            throw new Exception("You must se a valid path");
        } else {
            if (!file_exists($path)) {
                touch($path);
                $create = true;
            }
        }
        
        if (!$this->_storage) {
            $this->_storage = new PDO("sqlite://" . $path);
            if ($create) {
                $this->_createStorage;
            }
        }
    }
    
    /**
     * Create a valid storage.
     */
    private function _createStorage()
    {
        $query = "CREATE TABLE IF NOT EXISTS " . STORAGE_NAME . " (content_id INTEGER PRIMARY KEY AUTOINCREMENT)";
        $this->_storage->exec($query);
    }
    
    /**
     * Record that id into the storage
     * 
     * @param string $id
     */
    private function _register($id)
    {
        $query = "INSERT INTO " . STORAGE_NAME . " (content_id) VALUES (?)";
        $cmd = $this->_storage->prepare($query);
        $cmd->execute(array($id));
    }
    
    /**
     * Get a content relation or execute a search 
     * query
     * 
     * In case of search you have to pass a valid 
     * <code>UpCloo_Model_Search</code> and the
     * <code>UpCloo_Model_Search_Response</code> will
     * be returned.
     * 
     * @param string|UpCloo_Model_Search $id
     * @param string $virtualSiteKey
     * 
     * @return array|UpCloo_Model_Search_Response The list of results 
     */
    public function get($id, $virtualSiteKey = false)
    {
        $this->_client->setSiteKey($this->getSiteKey());
        
        if ($id instanceof UpCloo_Model_Search) {
            return $this->_client->search($id);
        } else {
            return $this->_client->get($id, $virtualSiteKey);
        }
    }
}