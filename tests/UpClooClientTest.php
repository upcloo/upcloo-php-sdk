<?php 
class UpClooClientTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * SetUp
     *
     * The first 4 index method goes on, next one fails
     *
     */
    public function testSimpleSearch()
    {
        $manager = UpCloo_Manager::getInstance();
        $manager->setCredential("username", "sitekey", "password");
    
        $stub = $this->getMock("UpCloo_Client_UpCloo", array('search'));
        
        $stub->expects($this->any())
            ->method('search')
            ->will(
                $this->returnValue(
                    simplexml_load_string(
                        file_get_contents(__DIR__ . "/data/simple.xml")
                    )
                )
            );
        
        $manager->setClient($stub);
        
        $searchQuery = $manager->search()->query("example");
        
        $xml = $manager->get($searchQuery);
        $this->assertEquals("2", count($xml->docs->doc));
        
        $this->markTestIncomplete("Modelize the search response");
    }
}