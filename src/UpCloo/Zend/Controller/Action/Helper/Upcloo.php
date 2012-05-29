<?php 
class UpCloo_Zend_Controller_Action_Helper_Upcloo
extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct()
    {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }
    
    /**
     * Create all UpCloo flow
     * 
     * @param array $options
     */
    public function upclooFlow($options) 
    {
        // Engage the registry for UpCloo Meta Field show
        Zend_Registry::set(UpCloo_Manager::META_FIELDS, $options);
        
        //TODO: call the index method.
    }
    
    /**
     * Strategy pattern: call helper as broker method
     *
     * @param  string $name
     * @param  array $options
     * @return void
     */
    public function direct($options = null)
    {
        if ($options === null || !is_array($options)) {
            return; //silent exit
        }
        
        $this->upclooFlow($options);
    } 
}