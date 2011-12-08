<?php 
class ManagerTest extends PHPUnit_Framework_TestCase
{
    private $_instance;
    
    public function setUp()
    {
        $this->_instance = UpCloo_Manager::getInstance();
        
        $stub = $this->getMock("UpCloo_Client_UpCloo", array('_index'));
        
        $stub->expects($this->any())
            ->method('_index')
            ->will($this->returnValue(true));
        
        $this->_instance->setCredential("username", "sitekey", "password");
        $this->_instance->setClient($stub);
    }
    
    public function testCompleted()
    {
        $model = new UpCloo_Model_Base();
        $model["id"] = 5;
        $model["title"] = "HEllo";
        
        $this->assertTrue($this->_instance->index($model));
    }
    
    /**
     * @expectedException UpCloo_Model_Exception
     */
    public function testMissingId()
    {
        $model = new UpCloo_Model_Base();
        $model["title"] = "HEllo";
        $model["summary"] = "This is a summary";
        
        $this->_instance->index($model);
    }
}
