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
        $manager = UpCloo_Manager::getInstance();
        if ($manager->index($options)) {
            ($this->_getLog()) ?
                $this->_getLog()
                    ->debug("Content indexed for UpCloo. ID: {$options["id"]}") : '';
        } else {
            ($this->_getLog()) ?
                $this->_getLog()
                    ->debug("Unable to index this content to UpCloo. ID: {$options["id"]}") : '';
        }
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
            ($this->_getLog()) ? 
                $this->_getLog()->debug("UpCloo Action Helper called without useful information. Skip this action") :
                "";
            return; //silent exit
        }
        
        $this->upclooFlow($options);
    } 
    
    /**
     * Retrive the default logger
     * 
     * @return Zend_Log|boolean A valid log instance or null if missing
     */
    private function _getLog()
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        if ($bootstrap instanceof Zend_Application_Bootstrap_BootstrapAbstract) {
            $log = $bootstrap->hasResource("log");

            if ($log instanceof Zend_Log) {
                return $bootstrap->getResource("log");
            } else {
                return false;
            }
        } 
        
        return false;
    }
}