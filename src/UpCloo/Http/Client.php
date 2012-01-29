<?php 
class UpCloo_Http_Client
{
    const PUT = 'put';
    const GET = 'get';
    const POST = 'post';
    
    private $_uri;
    private $_rawData;
    
    public function __construct($uri = false)
    {
        if ($uri) {
            $this->setUri($uri);
        }
    }
    
    public function setUri($uri)
    {
        $this->_uri = $uri;
    }
    
    public function getUri()
    {
        return $this->_uri;
    }
    
    public function setRawData($rawData)
    {
        $this->_rawData = $rawData;
    }
    
    public function getRawData()
    {
        return $this->_rawData;
    }
    
    /**
     * Request for a page
     * 
     * @param string $method
     * 
     * @return UpCloo_Http_Response
     */
    public function request($method = null)
    {
        $response = new UpCloo_Http_Response();
        
        if (!$this->getUri()) {
            throw new UpCloo_Http_Exception('No valid URI has been passed to the client');
        }
        
        $uri = $this->getUri();
        
        if ($method == self::PUT || $method == self::POST) {
            /* raw post on curl module */
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL,            $uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST,           1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,     $this->getRawData());
            curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
            curl_setopt($ch, CURLOPT_USERAGENT,      UPCLOO_USER_AGENT);
            
            $result=curl_exec ($ch);
            $headers = curl_getinfo($ch);
            curl_close($ch);
            
            $status = $headers["http_code"];
            
            $response->setBody($result);
            $response->setStatus($status);
        } else {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            
            $result=curl_exec ($ch);
            $headers = curl_getinfo($ch);
            curl_close($ch);
            
            $status = $headers["http_code"];
            
            $response->setBody($result);
            $response->setStatus($status);
        }
        
        return $response;
    }
}