<?php 
class SearchResponseTest
    extends PHPUnit_Framework_TestCase
{
    public function testStartAndCount()
    {
        $xml = simplexml_load_file(__DIR__ . '/data/simple.xml');
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
        
        //Base information
        $this->assertSame(1521, $model->getCount());
        $this->assertSame(0, $model->getStart());
    }
    
    public function testDocsParsing()
    {
        $xml = simplexml_load_file(__DIR__ . '/data/simple.xml');
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
    
        //Base information
        $this->assertEquals("2", count($model->getDocs()));
        
        $docs = $model->getDocs();
        $this->assertEquals("A title", $docs[0]["title"]);
        $this->assertEquals("A super title", $docs[1]["title"]);
        
        $this->assertEquals("Walter Dal Mut", $docs[0]["author"]);
        $this->assertEquals("Ed eccoci qui", $docs[0]["summary"]);
        $this->assertEquals("2011-09-27T15:52:00Z", $docs[0]["publish_date"]);
        $this->assertEquals("http://domain.ltd/gagad.html", $docs[0]["url"]);
        
        $this->assertEquals("post_1", $docs[0]["id"]);
        $this->assertEquals("post_2", $docs[1]["id"]);
        
        $this->assertEquals(0, count($model->getSuggestions()));
    }
    
    public function testSuggests()
    {
        $xml = simplexml_load_file(__DIR__ . '/data/suggests.xml');
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
        
        $this->assertEquals(2, count($model->getSuggestions()));
        $mu = $model->getSuggestions();

        $keys = array_keys($mu);
        $this->assertEquals("intrel", $keys[0]);
        $this->assertEquals("ble", $keys[1]);

        $first = $mu["intrel"];
        $this->assertEquals("2", count($first));
        $this->assertEquals("intranet", $first[0]);
        $this->assertEquals("intel", $first[1]);

        $first = $mu["ble"];
        $this->assertEquals("1", count($first));
        $this->assertEquals("blue", $first[0]);
    }
}