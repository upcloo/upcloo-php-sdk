<?php 
/**
 *
 * The UpCloo HTTP Client
 *
 * @author Walter Dal Mut
 * @package UpCloo_Client
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
class UpCloo_Client_UpCloo implements UpCloo_Client_ClientInterface
{
    /**
     * The http client
     * 
     * @var Zend_Http_Client
     */
    private $_client;
    
    public function __construct()
    {
        $this->_client = new Zend_Http_Client();
    }
    
    /**
     * (non-PHPdoc)
     * @see UpCloo_Client_ClientInterface::index()
     */
    public function index(UpCloo_Model_Base $model)
    {
        if (!$model["id"]) {
            throw new UpCloo_Model_Exception("You must provide the content id");
        }
        
        return $this->_index($model);
    }
    
    protected function _index($model)
    {
        $this->_client->setUri(sprintf(UpCloo_Manager::UPDATE_END_POINT, $this->getUsername()));
        $this->_client->setRawData($model->asXml());
        
        $response = $this->_client->request("put");
        
        return ($response->getStatus() == 200);
    }
    
    /**
     * (non-PHPdoc)
     * @see UpCloo_Client_ClientInterface::get()
     */
    public function get($id, $vsitekey = false)
    {
        $model = new UpCloo_Model_Base();
        
        return $model;
    }
    
    protected function _getFromRepository(Zend_Uri_Http $uri)
    {
        
    }
    
    public function setUsername($username)
    {
        $this->_username = $username;
    }
    
    public function getUsername()
    {
        return $this->_username;
    }
}