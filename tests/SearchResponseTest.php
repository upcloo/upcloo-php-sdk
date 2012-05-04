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
    
    /**
     * @expectedException     UpCloo_Model_Exception
     * @expectedExceptionCode 1500
     */
    public function testErrorResponse()
    {
        $errorXML = simplexml_load_file(dirname(__FILE__) . '/data/error-1500.xml');
    
        $model = UpCloo_Model_Search_Response::fromResponse($errorXML);
    }
    
    public function testFacets()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . '/data/full-response.xml');
        
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
        
        $facets = $model->getFacets();
        
        $this->assertEquals("2", count($facets));
        
        $names = array_keys($facets);
        
        $this->assertEquals("author", $names[0]);
        $this->assertEquals("category", $names[1]);
        
        $authors = $facets["author"];
        $authorNames = array_keys($authors);
        
        $this->assertEquals(3, count($authorNames));
        
        $this->assertEquals("Author Ex", $authorNames[0]);
        $this->assertEquals("My Comp", $authorNames[1]);
        $this->assertEquals("Rarely Man", $authorNames[2]);
        
        $this->assertSame(5880, $authors["Author Ex"]);
        $this->assertSame(1106, $authors["My Comp"]);
        $this->assertSame(1, $authors["Rarely Man"]);
        
        $categories = $facets["category"];
        
        $categoryNames = array_keys($categories);
        
        $this->assertEquals(4, count($categoryNames));
        
        $this->assertEquals("Schede Madre e RAM", $categoryNames[0]);
        $this->assertEquals("Processori", $categoryNames[1]);
        $this->assertEquals("Schede Video", $categoryNames[2]);
        $this->assertEquals("Altro", $categoryNames[3]);
        
        $this->assertSame(7756, $categories["Schede Madre e RAM"]);
        $this->assertSame(13, $categories["Processori"]);
        $this->assertSame(8, $categories["Schede Video"]);
        $this->assertSame(2, $categories["Altro"]);
        
    }

    public function testRanges()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . '/data/full-response.xml');
        
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
        
        $ranges = $model->getRanges();
        
        $this->assertEquals("1", count($ranges));
        
        $names = array_keys($ranges);
        
        $this->assertEquals("publish_date", $names[0]);
        
        $publishDate = $ranges["publish_date"];
        $publishDateKeys = array_keys($publishDate);
        
        $this->assertEquals(10, count($publishDateKeys));
        
        $this->assertEquals("2003-01-01T00:00:00Z", $publishDateKeys[0]);
        $this->assertEquals("2004-01-01T00:00:00Z", $publishDateKeys[1]);
        $this->assertEquals("2005-01-01T00:00:00Z", $publishDateKeys[2]);
        $this->assertEquals("2006-01-01T00:00:00Z", $publishDateKeys[3]);
        $this->assertEquals("2007-01-01T00:00:00Z", $publishDateKeys[4]);
        $this->assertEquals("2008-01-01T00:00:00Z", $publishDateKeys[5]);
        $this->assertEquals("2009-01-01T00:00:00Z", $publishDateKeys[6]);
        $this->assertEquals("2010-01-01T00:00:00Z", $publishDateKeys[7]);
        $this->assertEquals("2011-01-01T00:00:00Z", $publishDateKeys[8]);
        $this->assertEquals("2012-01-01T00:00:00Z", $publishDateKeys[9]);
        
        $this->assertSame(541, $publishDate["2003-01-01T00:00:00Z"]);
        $this->assertSame(2054, $publishDate["2004-01-01T00:00:00Z"]);
        $this->assertSame(2490, $publishDate["2005-01-01T00:00:00Z"]);
        $this->assertSame(1937, $publishDate["2006-01-01T00:00:00Z"]);
        $this->assertSame(183, $publishDate["2007-01-01T00:00:00Z"]);
        $this->assertSame(182, $publishDate["2008-01-01T00:00:00Z"]);
        $this->assertSame(144, $publishDate["2009-01-01T00:00:00Z"]);
        $this->assertSame(118, $publishDate["2010-01-01T00:00:00Z"]);
        $this->assertSame(87, $publishDate["2011-01-01T00:00:00Z"]);
        $this->assertSame(20, $publishDate["2012-01-01T00:00:00Z"]);
    }
    
    public function testEmptyRanges()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . '/data/simple.xml');
        
        $model = UpCloo_Model_Search_Response::fromResponse($xml);
        
        $ranges = $model->getRanges();
        
        $this->assertInternalType("array", $ranges);
        $this->assertEquals(0, count($ranges));
    }
}