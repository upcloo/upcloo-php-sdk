<?php
class UpCloo_Client_UpClooTest extends PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        $response = new UpCloo_Http_Response();
        $response->setStatus(200);
        $response->setBody(file_get_contents(__DIR__ . '/../../data/valid-json.json'));

        $clientMock = $this->getMock("UpCloo_Http_Client", array("request"));
        $clientMock->expects($this->any())
            ->method("request")
            ->will($this->returnValue($response));

        $this->object = new UpCloo_Client_UpCloo($clientMock);
    }

    public function testValidRegion()
    {
        $this->object->setSitekey("it-walter");
        $this->assertEquals("it", $this->object->getRegion());

        $this->object->setSitekey("en-xx00XXxxx");
        $this->assertEquals("en", $this->object->getRegion());

        $this->object->setSitekey("     en-xx00XXxxx   ");
        $this->assertEquals("en", $this->object->getRegion());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testInvalidSitekeys()
    {
        $this->object->setSitekey("en!alksa");
    }

    public function testMissingDashRegion()
    {
        $this->object->setSitekey("walter");
        $this->assertEquals("walter", $this->object->getRegion());
        $this->assertEquals("walter", $this->object->getSitekey());
    }

    public function testValidGeneralResponse()
    {
        $this->object->setSitekey("it-xx00XXxxx");
        $results = $this->object->get("http://localhost");
        $this->assertCount(3, $results);
        $this->assertEquals("http://it.o.upcloo.com/it-xx00XXxxx/aHR0cDovL2xvY2FsaG9zdA==", $this->object->getHttpClient()->getUri());

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/../../data/valid-json.json', json_encode($results));
    }
}
