<?php 
class ManagerTest extends PHPUnit_Framework_TestCase
{
    private $_instance;
    
    /**
     * SetUp
     * 
     * The first 4 index method goes on, next one fails
     * 
     */
    public function setUp()
    {
        $this->_instance = UpCloo_Manager::getInstance();
        
        $stub = $this->getMock("UpCloo_Client_UpCloo", array('_index', '_getFromRepository'));
        
        $stub->expects($this->any())
            ->method('_index')
            ->will($this->onConsecutiveCalls(true, true, true, true, false));
        
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
, "<docs/>", <<<MAO
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
MAO
, "<docs/>", ""
));
        
        $this->_instance->setCredential("username", "sitekey", "password");
        $this->_instance->setClient($stub);
    }
    
    public function testCompleted()
    {
        $model = new UpCloo_Model_Base();
        $model["id"] = 5;
        $model["title"] = "HEllo";
        
        $this->assertTrue($this->_instance->index($model));
        
        $this->assertEquals("http://username.update.upcloo.com", $this->_instance->getClient()->getUri());
        
        $model = array();
        $model["id"] = 15;
        $model["title"] = "Hello, world";
        
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
    
    /**
    * @expectedException UpCloo_Model_Exception
    */
    public function testArrayMissingId()
    {
        $model = array();
        $model["title"] = "HEllo";
        $model["summary"] = "This is a summary";
    
        $this->_instance->index($model);
    }
    
    public function testGetFromRepository()
    {
        $results = $this->_instance->get("15");
        $this->assertInternalType("array", $results);
        $this->assertEquals(2, count($results));
        $uri = $this->_instance->getClient()->getUri();
        $this->assertEquals("http://repository.upcloo.com/sitekey/15.xml", $uri);
        
        $this->assertInstanceOf("UpCloo_Model_Base", $results[0]);
        $this->assertEquals("Walter", $results[0]["title"]);

        $this->assertInstanceOf("UpCloo_Model_Base", $results[1]);
        $this->assertEquals("walter test", $results[1]["title"]);
        
        $results = $this->_instance->get("1204");
        $this->assertInternalType("array", $results);
        $this->assertEquals(0, count($results));
        
        $results = $this->_instance->get("15", "vsite");
        $uri = $this->_instance->getClient()->getUri();
        $this->assertEquals("http://repository.upcloo.com/sitekey/vsite/15.xml", $uri);
        
        $this->assertInternalType("array", $results);
        $this->assertEquals(2, count($results));
        
        $results = $this->_instance->get("1204", "vsite");
        $this->assertInternalType("array", $results);
        $this->assertEquals(0, count($results));
    }
    
    public function testNotIndexed()
    {
        $stub = $this->getMock("UpCloo_Client_UpCloo", array('_index'));
        
        $stub->expects($this->any())
            ->method('_index')
            ->will($this->onConsecutiveCalls(false));
            
        $this->_instance->setCredential("username", "sitekey", "password");
        $this->_instance->setClient($stub);
        
    	$model = new UpCloo_Model_Base();
        $model["id"] = 5;
        $model["title"] = "HEllo";
        
        $this->assertFalse($this->_instance->index($model));
    }
    
    public function testEmptyResponse()
    {
    	$stub = $this->getMock("UpCloo_Client_UpCloo", array('_getFromRepository'));
        
        $stub->expects($this->any())
            ->method('_getFromRepository')
            ->will($this->onConsecutiveCalls(""));
            
        $this->_instance->setCredential("username", "sitekey", "password");
        $this->_instance->setClient($stub);
        
    	$results = $this->_instance->get("15");
        $this->assertInternalType("array", $results);
        $this->assertEquals(0, count($results));
    }
}
