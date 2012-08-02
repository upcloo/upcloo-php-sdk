<?php 
class UpCloo_FactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        
    }
    
    public function testFactoryBase()
    {
        $upcloo = UpCloo_Factory::factory();
        
        $this->assertInstanceOf("UpCloo_Manager", $upcloo);
        $this->assertEquals("corley", $upcloo->getUsername());
        $this->assertEquals("", $upcloo->getSitekey());
        $this->assertEquals("", $upcloo->getPassword());
        $this->assertNull($upcloo->getStorage());
        
        $this->assertInstanceOf("UpCloo_Client_UpCloo", $upcloo->getClient());
    }
    
    public function testDifferentInternalClient()
    {
        $upcloo = UpCloo_Factory::factory("UpClooMock");
        
        $this->assertInstanceOf("UpCloo_Manager", $upcloo);
        
        $this->assertInstanceOf("UpCloo_Client_UpClooMock", $upcloo->getClient());
    }
    
    public function testSetCredentials()
    {
        $upcloo = UpCloo_Factory::factory(
            "UpCloo", 
            array(
                'username' => 'walter',
                'sitekey' => 'sitekey',
                'password' => 'pwd'       
            )
        );
        
        $this->assertInstanceOf("UpCloo_Manager", $upcloo);
        $this->assertEquals("walter", $upcloo->getUsername());
        $this->assertEquals("sitekey", $upcloo->getSitekey());
        $this->assertEquals("pwd", $upcloo->getPassword());
    }
    
    public function testUsingStorage()
    {
        $storage = new PDO("sqlite::memory:");
        $upcloo = UpCloo_Factory::factory(
            "UpCloo",
            array(
                "storage" => $storage
            )
        );
        
        $this->assertInstanceOf("UpCloo_Manager", $upcloo);
        $this->assertInstanceOf("PDO", $upcloo->getStorage());
        
        $this->assertSame($storage, $upcloo->getStorage());
    }
}