<?php 
/**
 * 
 * Search Model Tests
 *
 * @author Walter Dal Mut
 * @package 
 * @license MIT
 *
 * Copyright (C) 2012 Corley S.R.L.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class SearchModelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $manager = UpCloo_Manager::getInstance();
        $manager->setCredential("username", "my-sitekey", "password");
    }
    
    public function testBaseQuery()
    {
        $manager = UpCloo_Manager::getInstance();
        $search = $manager->search()->query("To a spectacular man...");
        
        $xml = simplexml_load_string((string)$search);
        
        $this->assertEquals("To a spectacular man..." ,(string)$xml->search->q);
        $attrs = $xml->search->attributes();
        $this->assertEquals("my-sitekey", (string)$attrs["sitekey"]);
        $this->assertEquals("password", (string)$attrs["password"]);
    }
    
    public function testFacetQueries()
    {
        $manager = UpCloo_Manager::getInstance();
        $search = $manager->search()
            ->query("To a spectacular man...")
            ->facet("category")
            ->facet("tags")
        ;
        
        $xml = simplexml_load_string((string)$search);
        $facets = $xml->search->facet;
        
        $this->assertEquals("To a spectacular man..." ,(string)$xml->search->q);
        $this->assertEquals("2", count($facets));
        $this->assertEquals("category", $facets[0]);
        $this->assertEquals("tags", $facets[1]);
    }
    
    public function testFilterByQueries()
    {
        $manager = UpCloo_Manager::getInstance();
        $search = $manager->search()
            ->query("To a spectacular man...")
            ->filterBy("category", "Web")
            ->filterBy("tags", "Commedy")
        ;
        
        $xml = simplexml_load_string((string)$search);
        $filters = $xml->search->filter;
        
        $this->assertEquals("To a spectacular man..." ,(string)$xml->search->q);
        $this->assertEquals("2", count($filters));
        $fattr = $filters[0]->attributes();
        $this->assertEquals("category", $fattr["by"]);
        $fattr = $filters[1]->attributes();
        $this->assertEquals("tags", $fattr["by"]);
        
        $this->assertEquals("Web", (string)$filters[0]);
        $this->assertEquals("Commedy", (string)$filters[1]);
    }
}