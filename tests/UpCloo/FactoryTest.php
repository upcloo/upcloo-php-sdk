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
        $this->assertEquals("", $upcloo->getSitekey());

        $this->assertInstanceOf("UpCloo_Client_UpCloo", $upcloo->getClient());
    }

    public function testSetCredentials()
    {
        $upcloo = UpCloo_Factory::factory(
            "UpCloo",
            array(
                'sitekey' => 'sitekey',
            )
        );

        $this->assertInstanceOf("UpCloo_Manager", $upcloo);
        $this->assertEquals("sitekey", $upcloo->getSitekey());
    }
}
