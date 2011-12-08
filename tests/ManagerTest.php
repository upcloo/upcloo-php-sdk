<?php 
class ManagerTest extends PHPUnit_Framework_TestCase
{
    private $_instance;
    
    public function setUp()
    {
        $this->_instance = UpCloo_Manager::getInstance();
        
        $stub = $this->getMock("UpCloo_Client_UpCloo", array('_index', '_getFromRepository'));
        
        $stub->expects($this->any())
            ->method('_index')
            ->will($this->returnValue(true));
        
        $stub->expects($this->any())
            ->method('_getFromRepository')
            ->will($this->onConsecutiveCalls(<<<EOF
<?xml version="1.0" encoding="utf-8"?>
<docs>
	<doc id="post_326" uid="bf34KScns_post_326_it_1" pubDate="2011-10-25T16:10:03Z">
		<title>Walter</title>
		<url>http://wp.local/?p=326</url>
		<description>questo è un test</description>
		<type>post</type>
		<categories>
			<category>Uncategorized</category>
		</categories>
	</doc>
	<doc id="post_351" uid="bf34KScns_post_351_it_1" pubDate="2011-10-25T19:42:02Z">
		<title>walter test</title>
		<url>http://wp.local/?p=351</url>
		<description />
		<type>post</type>
		<categories>
			<category>Uncategorized</category>
		</categories>
	</doc>
</docs>
EOF
, "<docs/>"));
        
        $this->_instance->setCredential("username", "sitekey", "password");
        $this->_instance->setClient($stub);
    }
    
    public function testCompleted()
    {
        $model = new UpCloo_Model_Base();
        $model["id"] = 5;
        $model["title"] = "HEllo";
        
        $this->assertTrue($this->_instance->index($model));
        
        $this->assertEquals("username", $this->_instance->getClient()->getUsername());
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
    
    public function testGetFromRepository()
    {
        $results = $this->_instance->get("15");
        $this->assertInternalType("array", $results);
        $this->assertEquals(2, count($results));
        
        $this->assertInstanceOf("UpCloo_Model_Base", $results[0]);
        $this->assertEquals("Walter", $results[0]["title"]);

        $this->assertInstanceOf("UpCloo_Model_Base", $results[1]);
        $this->assertEquals("walter test", $results[1]["title"]);
        
        $results = $this->_instance->get("1204");
        $this->assertInternalType("array", $results);
        $this->assertEquals(0, count($results));
    }
}
