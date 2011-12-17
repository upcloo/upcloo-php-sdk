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
    
    public function testVirtualSiteKey()
    {
        $this->_instance->setCredential("username", "sitekey", "password", "one", "two", "three");
        
        $this->assertInternalType("array", $this->_instance->getVirtualSiteKeys());
        
        $this->assertEquals("3", count($this->_instance->getVirtualSiteKeys()));
        
        $keys = $this->_instance->getVirtualSiteKeys();
        
        $this->assertSame('one', $keys[0]);
        $this->assertSame('two', $keys[1]);
        $this->assertSame('three', $keys[2]);
        
        $this->_instance->setVirtualSiteKeys(array('four', 'five'));
        
        $this->assertEquals("2", count($this->_instance->getVirtualSiteKeys()));
        
        $keys = $this->_instance->getVirtualSiteKeys();
        
        $this->assertSame('four', $keys[0]);
        $this->assertSame('five', $keys[1]);
    }
    
    public function testWeirdVirtualSiteKeys()
    {
        $this->_instance->setVirtualSiteKeys(array(5 => 'four', "strange" => 'five'));
        
        $keys = array_keys($this->_instance->getVirtualSiteKeys());
        
        $this->assertEquals("2", count($keys));
        
        $this->assertEquals(0, $keys[0]);
        $this->assertEquals(1, $keys[1]);
        
        $values = $this->_instance->getVirtualSiteKeys();
        
        $this->assertEquals("four", $values[0]);
        $this->assertEquals("five", $values[1]);
    }

    /**
     * @expectedException BadFunctionCallException 
     */
    public function testClone()
    {
        $clone = clone $this->_instance;
    }
}
