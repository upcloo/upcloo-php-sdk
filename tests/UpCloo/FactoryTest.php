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

        $this->assertInstanceOf("UpCloo_Client_UpCloo", $upcloo->getClient());
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
}
