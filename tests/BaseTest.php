<?php 
class BaseTest extends PHPUnit_Framework_TestCase
{
    private $_instance;
    
    public function setUp()
    {
        $this->_instance = UpCloo_Manager::getInstance();
    }
    
    public function testEasy()
    {
        $this->assertEquals("a", "a");
    }

    public function testGetInstance()
    {
        $instance = UpCloo_Manager::getInstance();
        
        $this->assertInstanceOf("UpCloo_Manager", $instance);
        
        //test the singleton
        $instance2 = UpCloo_Manager::getInstance();
        $this->assertSame($instance, $instance2);
    }
    
    public function testSettersGetters()
    {
        $this->_instance->setCredential("username", "sitekey", "password");
        
        $this->assertSame("username", $this->_instance->getUsername());
        $this->assertSame("password", $this->_instance->getPassword());
        $this->assertSame("sitekey", $this->_instance->getSiteKey());
        
        $this->_instance->setUsername("hello");
        $this->assertSame("hello", $this->_instance->getUsername());
        
        $this->_instance->setPassword("helloPWD");
        $this->assertSame("helloPWD", $this->_instance->getPassword());
        
        $this->_instance->setSiteKey("helloS");
        $this->assertSame("helloS", $this->_instance->getSiteKey());
    }
    
    /**
     * @expectedException BadFunctionCallException 
     */
    public function testClone()
    {
        $clone = clone $this->_instance;
    }
}
