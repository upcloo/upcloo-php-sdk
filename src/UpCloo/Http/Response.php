<?php 
class UpCloo_Http_Response
{
    private $_body;
    private $_status;
    
    public function setStatus($status)
    {
        $this->_status = $status;
    }
    
    public function getStatus()
    {
        return $this->_status;
    }
    
    public function setBody($body)
    {
        $this->_body = $body;
    }
    
    public function getBody()
    {
        return $this->_body;
    }
}