<?php 
class UpCloo_Zend_Controller_Plugin_UpClooRemoteImporter
    extends Zend_Controller_Plugin_Abstract
{
    private $_directMap = array(
        'id',
        'title',
        'publish_date',
        'summary',
        'content',
        'image',
        'url',
        'type'
    );
    
    private $_listMap = array(
        'authors',
        'tags',
        'categories'        
    );
    
    //TODO: missing dynamics
    
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (Zend_Registry::isRegistered(UpCloo_Manager::META_FIELDS)) {
            $response = $this->getResponse();
            
            $metafields = Zend_Registry::get(UpCloo_Manager::META_FIELDS);
            
            if (is_array($metafields)) {
                foreach ($metafields as $key => $value) {
                    if (in_array($key, $this->_directMap)) {
                        $response->appendBody($this->getFieldDefinition($key, $value));
                    } else if (in_array($key, $this->_listMap)) {
                        if (!is_array($value)) {
                            $value = array($value); 
                        }
                        $response->appendBody($this->getFieldDefinition($key, implode(",", $value)));
                    }
                }
            }
        }
    }
    
    private function getFieldDefinition($key, $value)
    {
        return sprintf(
        	"<!-- UPCLOO_POST_%s %s UPCLOO_POST_%s -->" . PHP_EOL, 
            strtoupper($key), 
            $value, 
            strtoupper($key)
        );
    }
}