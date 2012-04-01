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
     * @var UpCloo_Http_Client
     */
    protected $_client;
    
    /**
     * The username
     * 
     * @var string The username
     */
    private $_username;
    
    /**
     * The sitekey
     * 
     * @var string The sitekey
     */
    private $_sitekey;
    
    /**
     * Client constructor
     * 
     * Create the default client.
     * 
     */
    public function __construct($client = false)
    {
        if (!$client) {
            $this->_client = new UpCloo_Http_Client();
        }
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
        $this->_client->setUri(sprintf(UpCloo_Manager::UPDATE_END_POINT, $this->getUsername()));
        
        return $this->_index($model);
    }
    
    
    protected function _index($model)
    {
        $this->_client->setRawData($model->asXml());
        
        $response = $this->_client->request(UpCloo_Http_Client::POST);
        
        return ($response->getStatus() == 200);
    }
    
    /**
     * Execute a search query
     *
     * @param UpCloo_Model_Search $searchQuery
     * 
     * @return UpCloo_Model_Search_Response The raw xml parsed
     */
    public function search(UpCloo_Model_Search $searchQuery)
    {
        $this->_client->setUri(UpCloo_Manager::SEARCH_ENDPOINT);
    
        $this->_client->setRawData($searchQuery->asXml());
    
        $xml = @simplexml_load_string($this->_client->request(UpCloo_Http_Client::POST));
        
        //Modelize response
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
    
        return $model;
    }
    
    /**
     * (non-PHPdoc)
     * @see UpCloo_Client_ClientInterface::get()
     */
    public function get($id, $vsitekey = false)
    {
        $model = new UpCloo_Model_Base();
        
        $uri = '';
        if (!$vsitekey) {
            $uri = sprintf(UpCloo_Manager::REPOSITORY, $this->getSiteKey());
        } else {
            $uri = sprintf(UpCloo_Manager::REPOSITORY, $this->getSiteKey());
            $uri .= "/%s";
            $uri = sprintf($uri, $vsitekey);
        }
        $uri .= "/{$id}.xml";
        $this->_client->setUri($uri);
        
        $xml = $this->_getFromRepository($uri);
        
        $elements = @simplexml_load_string($xml);
        
        $results = array();
        if ($elements && $elements->doc) {
            foreach ($elements->doc as $element) {
                $model = new UpCloo_Model_Base();
                $model["title"] = (string)$element->title;
                $model["url"] = (string)$element->url;
                $model["description"] = (string)$element->description;
                $model["type"] = (string)$element->type;
                $model["image"] = (string)$element->image;
                
                //TODO: working on categories and tags
                
                $results[] = $model;
            }
        } 
        
        return $results;
    }
    
    /**
     * Retrive from repo
     * 
     * @param string $uri
     * 
     * @return array
     */
    protected function _getFromRepository()
    {
        $response = $this->_client->request("get");
        
        if ($response->getStatus() == 200) {
            return $response->getBody();
        } else {
            return array();
        }
    }
    
    /**
     * Set the username
     * 
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }
    
    /**
     * Retrive the username
     * 
     * @return string The username
     */
    public function getUsername()
    {
        return $this->_username;
    }
    
    /**
     * Set the site key
     * 
     * @param string $sitekey
     */
    public function setSiteKey($sitekey)
    {
        $this->_sitekey = $sitekey;
    }
    
    /**
     * 
     * Retrive the site key
     * 
     * @return string The site key
     */
    public function getSiteKey()
    {
        return $this->_sitekey;
    }
    
    /**
     * Retrive the uri of latest request
     * 
     * @return string;
     */
    public function getUri()
    {
        return $this->_client->getUri(true);
    }
}