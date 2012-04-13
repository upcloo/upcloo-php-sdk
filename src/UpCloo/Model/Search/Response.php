<?php 
class UpCloo_Model_Search_Response
{
    private $_count;
    private $_start;
    
    private $_docs;
    private $_facets;
    private $_ranges;
    private $_suggests;
    
    /**
     *Generate a model starting from an XML structure
     * 
     * @param SimpleXMLElement $root
     * @return UpCloo_Model_Search_Response
     */
    public static function fromResponse($root)
    {
        $model = new UpCloo_Model_Search_Response();
        
        $model->_count = 0;
        $model->_start = 0;
        $model->_docs = array();
        $model->_facets = array();
        $model->_ranges = array();
        $model->_suggests = array();
        
        if ($root != null) {
            $attr = $root->docs->attributes();
            $model->_count = (int)$attr["count"];
            $model->_start = (int)$attr["start"];
            
            $model->_docs = array();
            foreach ($root->docs->doc as $doc) {
                $attr = $doc->attributes();
            
                $m = array();
                $m["id"] = $attr["id"];
                foreach ($doc as $key => $value) {
                    $m[$key] = (string)$value;
                }
                
                $model->_docs[] = $m;
            }
            
            foreach ($root->suggestions->suggest as $suggest) {
                $attr = $suggest->attributes();
                $name = (string)$attr["name"];
                
                foreach ($suggest->proposal as $proposal) {
                    $model->_suggests[$name][] = (string)$proposal;
                }
            }
        }
        return $model;
    }
    
    public function getSuggestions()
    {
        return $this->_suggests;
    }
    
    /**
     * Get number of objects involved in
     * this search query.
     * 
     * @return int Number of objects (remotely)
     */
    public function getCount()
    {
        return $this->_count;
    }
    
    /**
     * Where you are (position)
     * 
     * @return int your index position
     */
    public function getStart()
    {
        return $this->_start;
    }
    
    /**
     * Docs found
     * 
     * @return UpCloo_Model_Base A simple data model
     */
    public function getDocs()
    {
        return $this->_docs;
    }
}