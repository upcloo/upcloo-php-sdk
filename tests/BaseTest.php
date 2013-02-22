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
        $this->_instance->setCredential("sitekey");

        $this->assertSame("sitekey", $this->_instance->getSiteKey());

        $this->_instance->setSiteKey("helloS");
        $this->assertSame("helloS", $this->_instance->getSiteKey());
    }

    public function testTimeoutSetterGetter()
    {
    	$instance = UpCloo_Manager::getInstance();
    	$timeout = $instance->getClient()->getHttpClient()->getTimeout();

    	$this->assertSame($timeout, UpCloo_Http_Client::TIMEOUT);

    	$client = $instance->getClient()->getHttpClient();

    	$client->setTimeout(2);
    	$timeout = $instance->getClient()->getHttpClient()->getTimeout();

    	$this->assertEquals($timeout, 2);
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testClone()
    {
        $clone = clone $this->_instance;
    }
}
